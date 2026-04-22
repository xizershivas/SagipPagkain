using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;
using SagipPagkain.API.DTOs;
using SagipPagkain.API.Models;
using SagipPagkain.API.Services;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
public class AuthController(AppDbContext db, TokenService tokenService) : ControllerBase
{
    [HttpPost("login")]
    public async Task<IActionResult> Login([FromBody] LoginDto dto)
    {
        var user = await db.Users.FirstOrDefaultAsync(u => u.strUsername == dto.username && u.ysnActive);
        if (user == null) return Unauthorized(new { message = "Invalid Username/Password" });

        if (!BCrypt.Net.BCrypt.Verify(dto.password, user.strPassword))
            return Unauthorized(new { message = "Invalid Username/Password" });

        var role = user.ysnAdmin ? "Admin"
            : user.ysnDonor ? "Donor"
            : user.ysnFoodBank ? "FoodBank"
            : "Beneficiary";

        var token = tokenService.CreateToken(user);
        return Ok(new AuthResponseDto(user.intUserId, user.strUsername, user.strFullName, token, role));
    }

    [HttpPost("register")]
    public async Task<IActionResult> Register([FromForm] RegisterDto dto, IFormFile? uploadDocu)
    {
        if (await db.Users.AnyAsync(u => u.strUsername == dto.username))
            return BadRequest(new { message = "The Username already exists, please choose a different Username" });

        if (dto.password != dto.confirmPassword)
            return BadRequest(new { message = "Passwords do not match" });

        if (dto.password.Length < 8)
            return BadRequest(new { message = "Password must be 8 characters long" });

        var hashedPassword = BCrypt.Net.BCrypt.HashPassword(dto.password);

        int? foodBankId = null;
        if (dto.accountType == "donor" && dto.address != null)
        {
            var allFoodBanks = await db.FoodBanks.ToListAsync();
            var match = allFoodBanks.FirstOrDefault(fb =>
                dto.address.Contains(fb.strMunicipality, StringComparison.OrdinalIgnoreCase));
            foodBankId = match?.intFoodBankId;
        }

        var user = new User
        {
            strUsername = dto.username,
            strFullName = dto.fullName,
            strContact = dto.contact,
            strEmail = dto.email,
            strPassword = hashedPassword,
            strAddress = dto.address,
            ysnDonor = dto.accountType == "donor",
            ysnFoodBank = dto.accountType == "foodbank",
            ysnBeneficiary = dto.accountType == "beneficiary",
            ysnActive = dto.accountType != "beneficiary",
            intFoodBankId = dto.accountType == "donor" ? foodBankId : null
        };

        db.Users.Add(user);
        await db.SaveChangesAsync();

        if (dto.accountType == "beneficiary")
        {
            string? docPath = null;
            if (uploadDocu != null)
            {
                var uploadsDir = Path.Combine("uploads", "documents");
                Directory.CreateDirectory(uploadsDir);
                var fileName = $"{user.intUserId}_{Path.GetFileName(uploadDocu.FileName)}";
                var filePath = Path.Combine(uploadsDir, fileName);
                using var stream = new FileStream(filePath, FileMode.Create);
                await uploadDocu.CopyToAsync(stream);
                docPath = filePath;
            }

            var beneficiary = new Beneficiary
            {
                intUserId = user.intUserId,
                strName = dto.fullName,
                strEmail = dto.email,
                strContact = dto.contact,
                strAddress = dto.address,
                dblLatitude = dto.latitude,
                dblLongitude = dto.longitude,
                dblSalary = dto.salary,
                strDocument = docPath
            };
            db.Beneficiaries.Add(beneficiary);
            await db.SaveChangesAsync();
        }

        return StatusCode(201, new { message = "Registration successful" });
    }
}
