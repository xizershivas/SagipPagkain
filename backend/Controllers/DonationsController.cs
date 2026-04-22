using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;
using SagipPagkain.API.DTOs;
using SagipPagkain.API.Models;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class DonationsController(AppDbContext db, IWebHostEnvironment env) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> GetAll([FromQuery] int? userId, [FromQuery] int? foodBankUserId)
    {
        var query = db.Donations
            .Include(d => d.User)
            .Include(d => d.FoodBankDetail)
            .Include(d => d.Purpose)
            .Include(d => d.Inventory).ThenInclude(i => i!.Item)
            .Include(d => d.Inventory).ThenInclude(i => i!.Unit)
            .Include(d => d.Inventory).ThenInclude(i => i!.Category)
            .Where(d => d.ysnActive)
            .AsQueryable();

        if (userId.HasValue)
            query = query.Where(d => d.intUserId == userId.Value);

        if (foodBankUserId.HasValue)
        {
            var user = await db.Users.FindAsync(foodBankUserId.Value);
            if (user?.intFoodBankId != null)
            {
                var detailIds = await db.FoodBankDetails
                    .Where(fb => fb.intFoodBankId == user.intFoodBankId)
                    .Select(fb => fb.intFoodBankDetailId)
                    .ToListAsync();
                query = query.Where(d => detailIds.Contains(d.intFoodBankDetailId));
            }
        }

        var donations = await query.OrderByDescending(d => d.dtmDate).Select(d => new DonationDto
        {
            intDonationId = d.intDonationId,
            intUserId = d.intUserId,
            strDonorName = d.User!.strFullName,
            dtmDate = d.dtmDate,
            strDescription = d.strDescription,
            intFoodBankDetailId = d.intFoodBankDetailId,
            strFoodBankName = d.FoodBankDetail!.strFoodBankName,
            strDocFilePath = d.strDocFilePath,
            intPurposeId = d.intPurposeId,
            strPurpose = d.Purpose!.strPurpose,
            dtmExpirationDate = d.dtmExpirationDate,
            strStatus = d.strStatus,
            ysnActive = d.ysnActive,
            intItemId = d.Inventory != null ? d.Inventory.intItemId : null,
            strItem = d.Inventory != null ? d.Inventory.Item!.strItem : null,
            intQuantity = d.Inventory != null ? d.Inventory.intQuantity : null,
            strUnit = d.Inventory != null ? d.Inventory.Unit!.strUnit : null,
            strCategory = d.Inventory != null ? d.Inventory.Category!.strCategory : null
        }).ToListAsync();

        return Ok(donations);
    }

    [HttpGet("{id}")]
    public async Task<IActionResult> GetById(int id)
    {
        var donation = await db.Donations
            .Include(d => d.User)
            .Include(d => d.FoodBankDetail)
            .Include(d => d.Purpose)
            .Include(d => d.Inventory).ThenInclude(i => i!.Item)
            .Include(d => d.Inventory).ThenInclude(i => i!.Unit)
            .Include(d => d.Inventory).ThenInclude(i => i!.Category)
            .FirstOrDefaultAsync(d => d.intDonationId == id);

        if (donation == null) return NotFound();

        return Ok(new DonationDto
        {
            intDonationId = donation.intDonationId,
            intUserId = donation.intUserId,
            strDonorName = donation.User!.strFullName,
            dtmDate = donation.dtmDate,
            strDescription = donation.strDescription,
            intFoodBankDetailId = donation.intFoodBankDetailId,
            strFoodBankName = donation.FoodBankDetail!.strFoodBankName,
            strDocFilePath = donation.strDocFilePath,
            intPurposeId = donation.intPurposeId,
            strPurpose = donation.Purpose!.strPurpose,
            dtmExpirationDate = donation.dtmExpirationDate,
            strStatus = donation.strStatus,
            ysnActive = donation.ysnActive,
            intItemId = donation.Inventory?.intItemId,
            strItem = donation.Inventory?.Item?.strItem,
            intQuantity = donation.Inventory?.intQuantity,
            strUnit = donation.Inventory?.Unit?.strUnit,
            strCategory = donation.Inventory?.Category?.strCategory
        });
    }

    [HttpPost]
    public async Task<IActionResult> Create([FromForm] DonationCreateDto dto, IFormFile? verification)
    {
        string? docFilePath = null;
        if (verification != null)
        {
            var uploadsDir = Path.Combine(env.ContentRootPath, "uploads", "donor");
            Directory.CreateDirectory(uploadsDir);
            var fileName = $"{dto.userId}_{Path.GetFileName(verification.FileName)}_{DateTime.Now:yyyyMMddHHmmss}{Path.GetExtension(verification.FileName)}";
            var filePath = Path.Combine(uploadsDir, fileName);
            using var stream = new FileStream(filePath, FileMode.Create);
            await verification.CopyToAsync(stream);
            docFilePath = $"/uploads/donor/{fileName}";
        }

        using var transaction = await db.Database.BeginTransactionAsync();
        try
        {
            var donation = new Donation
            {
                intUserId = dto.userId,
                dtmDate = dto.date,
                strDescription = dto.description,
                intFoodBankDetailId = dto.foodBankDetailId,
                strDocFilePath = docFilePath ?? dto.docFilePath,
                intPurposeId = dto.purposeId,
                dtmExpirationDate = dto.expirationDate
            };
            db.Donations.Add(donation);
            await db.SaveChangesAsync();

            var inventory = new Inventory
            {
                intDonationId = donation.intDonationId,
                intFoodBankDetailId = dto.foodBankDetailId,
                intItemId = dto.itemId,
                intCategoryId = dto.categoryId,
                intUnitId = dto.unitId,
                intQuantity = dto.quantity,
                dtmExpirationDate = dto.expirationDate
            };
            db.Inventories.Add(inventory);

            db.Notifications.Add(new Notification
            {
                intSourceId = donation.intDonationId,
                strSourceTable = "tblinventory"
            });

            await db.SaveChangesAsync();
            await transaction.CommitAsync();

            return StatusCode(201, new { message = "Donation submitted successfully" });
        }
        catch
        {
            await transaction.RollbackAsync();
            return StatusCode(500, new { message = "Failed to submit donation" });
        }
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> Update(int id, [FromBody] DonationUpdateDto dto)
    {
        var donation = await db.Donations.FindAsync(id);
        if (donation == null) return NotFound();

        donation.strDescription = dto.description;
        donation.intFoodBankDetailId = dto.foodBankDetailId;
        donation.intPurposeId = dto.purposeId;
        donation.dtmExpirationDate = dto.expirationDate;
        donation.ysnActive = dto.isActive;

        await db.SaveChangesAsync();
        return Ok(new { message = "Donation updated successfully" });
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> Archive(int id)
    {
        var donation = await db.Donations.FindAsync(id);
        if (donation == null) return NotFound();
        donation.ysnActive = false;
        await db.SaveChangesAsync();
        return Ok(new { message = "Donation archived" });
    }

    [HttpGet("stats/{userId}")]
    public async Task<IActionResult> GetStats(int userId)
    {
        var user = await db.Users.FindAsync(userId);
        if (user == null) return NotFound();

        var baseQuery = db.Donations.Where(d => d.ysnActive);

        if (user.ysnAdmin)
        {
            var monthlyData = await baseQuery
                .GroupBy(d => new { d.dtmDate.Year, d.dtmDate.Month })
                .Select(g => new { g.Key.Year, g.Key.Month, Count = g.Count() })
                .OrderBy(g => g.Year).ThenBy(g => g.Month)
                .ToListAsync();

            var purposeData = await baseQuery
                .Include(d => d.Purpose)
                .GroupBy(d => d.Purpose!.strPurpose)
                .Select(g => new { Purpose = g.Key, Count = g.Count() })
                .ToListAsync();

            return Ok(new { monthlyData, purposeData, totalDonations = await baseQuery.CountAsync() });
        }
        else if (user.ysnDonor)
        {
            var myDonations = await baseQuery.Where(d => d.intUserId == userId).CountAsync();
            return Ok(new { totalDonations = myDonations });
        }

        return Ok(new { totalDonations = 0 });
    }
}
