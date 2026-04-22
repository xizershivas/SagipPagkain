using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;
using SagipPagkain.API.Models;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class VolunteersController(AppDbContext db, IWebHostEnvironment env) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> GetAll()
    {
        var volunteers = await db.Volunteers.Where(v => v.ysnActive).ToListAsync();
        return Ok(volunteers);
    }

    [HttpGet("{id}")]
    public async Task<IActionResult> GetById(int id)
    {
        var volunteer = await db.Volunteers.FindAsync(id);
        if (volunteer == null) return NotFound();
        return Ok(volunteer);
    }

    [HttpPost]
    public async Task<IActionResult> Create([FromForm] Volunteer dto, IFormFile? signature)
    {
        string? signPath = null;
        if (signature != null)
        {
            var uploadsDir = Path.Combine(env.ContentRootPath, "uploads", "volunteer");
            Directory.CreateDirectory(uploadsDir);
            var fileName = $"temp_{Path.GetFileName(signature.FileName)}_{DateTime.Now:yyyyMMdd}{Path.GetExtension(signature.FileName)}";
            var filePath = Path.Combine(uploadsDir, fileName);
            using var stream = new FileStream(filePath, FileMode.Create);
            await signature.CopyToAsync(stream);
            signPath = $"/uploads/volunteer/{fileName}";
        }

        dto.strSignFilePath = signPath;
        db.Volunteers.Add(dto);
        await db.SaveChangesAsync();

        if (signPath != null)
        {
            var newFileName = $"{dto.intVolunteerId}_{Path.GetFileName(signature!.FileName)}_{DateTime.Now:yyyyMMdd}{Path.GetExtension(signature.FileName)}";
            var oldPath = Path.Combine(env.ContentRootPath, "uploads", "volunteer", Path.GetFileName(signPath));
            var newPath = Path.Combine(env.ContentRootPath, "uploads", "volunteer", newFileName);
            if (System.IO.File.Exists(oldPath)) System.IO.File.Move(oldPath, newPath);
            dto.strSignFilePath = $"/uploads/volunteer/{newFileName}";
            await db.SaveChangesAsync();
        }

        return StatusCode(201, new { message = "Volunteer added successfully" });
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> Update(int id, [FromForm] Volunteer dto, IFormFile? signature)
    {
        var volunteer = await db.Volunteers.FindAsync(id);
        if (volunteer == null) return NotFound();

        if (signature != null)
        {
            var uploadsDir = Path.Combine(env.ContentRootPath, "uploads", "volunteer");
            Directory.CreateDirectory(uploadsDir);

            var oldFiles = Directory.GetFiles(uploadsDir, $"{id}_*");
            foreach (var f in oldFiles) System.IO.File.Delete(f);

            var fileName = $"{id}_{Path.GetFileName(signature.FileName)}_{DateTime.Now:yyyyMMdd}{Path.GetExtension(signature.FileName)}";
            var filePath = Path.Combine(uploadsDir, fileName);
            using var stream = new FileStream(filePath, FileMode.Create);
            await signature.CopyToAsync(stream);
            volunteer.strSignFilePath = $"/uploads/volunteer/{fileName}";
        }

        volunteer.strFirstName = dto.strFirstName;
        volunteer.strLastName = dto.strLastName;
        volunteer.strGender = dto.strGender;
        volunteer.dtmDateOfBirth = dto.dtmDateOfBirth;
        volunteer.strStreet = dto.strStreet;
        volunteer.strAddress = dto.strAddress;
        volunteer.strCity = dto.strCity;
        volunteer.strRegion = dto.strRegion;
        volunteer.strZipCode = dto.strZipCode;
        volunteer.strCountry = dto.strCountry;
        volunteer.strContact = dto.strContact;
        volunteer.strEmail = dto.strEmail;

        await db.SaveChangesAsync();
        return Ok(new { message = "Volunteer successfully updated" });
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> Delete(int id)
    {
        var volunteer = await db.Volunteers.FindAsync(id);
        if (volunteer == null) return NotFound();
        volunteer.ysnActive = false;
        await db.SaveChangesAsync();
        return Ok(new { message = "Volunteer was deleted successfully" });
    }
}
