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
public class BeneficiaryController(AppDbContext db, IWebHostEnvironment env) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> GetAll()
    {
        var beneficiaries = await db.Beneficiaries
            .Include(b => b.User)
            .Select(b => new BeneficiaryDto
            {
                intBeneficiaryId = b.intBeneficiaryId,
                intUserId = b.intUserId,
                strName = b.strName,
                strEmail = b.strEmail,
                strContact = b.strContact,
                strAddress = b.strAddress,
                dblLatitude = b.dblLatitude,
                dblLongitude = b.dblLongitude,
                dblSalary = b.dblSalary,
                strDocument = b.strDocument,
                ysnActive = b.User!.ysnActive
            })
            .ToListAsync();
        return Ok(beneficiaries);
    }

    [HttpGet("{userId}/profile")]
    public async Task<IActionResult> GetProfile(int userId)
    {
        var beneficiary = await db.Beneficiaries
            .Include(b => b.User)
            .FirstOrDefaultAsync(b => b.intUserId == userId);
        if (beneficiary == null) return NotFound();

        return Ok(new BeneficiaryDto
        {
            intBeneficiaryId = beneficiary.intBeneficiaryId,
            intUserId = beneficiary.intUserId,
            strName = beneficiary.strName,
            strEmail = beneficiary.strEmail,
            strContact = beneficiary.strContact,
            strAddress = beneficiary.strAddress,
            dblLatitude = beneficiary.dblLatitude,
            dblLongitude = beneficiary.dblLongitude,
            dblSalary = beneficiary.dblSalary,
            strDocument = beneficiary.strDocument,
            ysnActive = beneficiary.User!.ysnActive
        });
    }

    [HttpPut("{id}/activate")]
    [Authorize(Roles = "Admin")]
    public async Task<IActionResult> Activate(int id)
    {
        var beneficiary = await db.Beneficiaries.FindAsync(id);
        if (beneficiary == null) return NotFound();
        var user = await db.Users.FindAsync(beneficiary.intUserId);
        if (user == null) return NotFound();
        user.ysnActive = true;
        await db.SaveChangesAsync();
        return Ok(new { message = "Beneficiary activated" });
    }

    [HttpDelete("{id}")]
    [Authorize(Roles = "Admin")]
    public async Task<IActionResult> Delete(int id)
    {
        var beneficiary = await db.Beneficiaries.FindAsync(id);
        if (beneficiary == null) return NotFound();
        var user = await db.Users.FindAsync(beneficiary.intUserId);
        if (user != null) user.ysnActive = false;
        await db.SaveChangesAsync();
        return Ok(new { message = "Beneficiary deactivated" });
    }

    [HttpGet("requests")]
    public async Task<IActionResult> GetRequests([FromQuery] int? userId, [FromQuery] int? foodBankUserId)
    {
        var query = db.BeneficiaryRequests
            .Include(r => r.Beneficiary)
            .Include(r => r.Purpose)
            .Include(r => r.FoodBankDetail)
            .Include(r => r.RequestDetails).ThenInclude(rd => rd.Item)
            .Where(r => r.ysnActive)
            .AsQueryable();

        if (userId.HasValue)
        {
            var beneficiary = await db.Beneficiaries.FirstOrDefaultAsync(b => b.intUserId == userId.Value);
            if (beneficiary != null)
                query = query.Where(r => r.intBeneficiaryId == beneficiary.intBeneficiaryId);
        }

        if (foodBankUserId.HasValue)
        {
            var user = await db.Users.FindAsync(foodBankUserId.Value);
            if (user?.intFoodBankId != null)
            {
                var detailIds = await db.FoodBankDetails
                    .Where(fb => fb.intFoodBankId == user.intFoodBankId)
                    .Select(fb => fb.intFoodBankDetailId)
                    .ToListAsync();
                query = query.Where(r => detailIds.Contains(r.intFoodBankDetailId));
            }
        }

        var requests = await query.OrderByDescending(r => r.dtmCreatedAt).Select(r => new BeneficiaryRequestDto
        {
            intBeneficiaryRequestId = r.intBeneficiaryRequestId,
            intBeneficiaryId = r.intBeneficiaryId,
            strBeneficiaryName = r.Beneficiary!.strName,
            strRequestNo = r.strRequestNo,
            strRequestType = r.strRequestType,
            strUrgencyLevel = r.strUrgencyLevel,
            dtmPickupDate = r.dtmPickupDate,
            strDocument = r.strDocument,
            intPurposeId = r.intPurposeId,
            strPurpose = r.Purpose!.strPurpose,
            intFoodBankDetailId = r.intFoodBankDetailId,
            strFoodBankName = r.FoodBankDetail!.strFoodBankName,
            strStatus = r.strStatus,
            dtmCreatedAt = r.dtmCreatedAt,
            ItemNames = r.RequestDetails.Select(rd => rd.Item!.strItem).ToList()
        }).ToListAsync();

        return Ok(requests);
    }

    [HttpPost("requests")]
    public async Task<IActionResult> SubmitRequest([FromForm] BeneficiaryRequestCreateDto dto, IFormFile? document)
    {
        string? docPath = null;
        if (document != null)
        {
            var uploadsDir = Path.Combine(env.ContentRootPath, "uploads", "documents");
            Directory.CreateDirectory(uploadsDir);
            var fileName = $"{dto.beneficiaryId}_{Path.GetFileName(document.FileName)}_{DateTime.Now:yyyyMMdd}{Path.GetExtension(document.FileName)}";
            var filePath = Path.Combine(uploadsDir, fileName);
            using var stream = new FileStream(filePath, FileMode.Create);
            await document.CopyToAsync(stream);
            docPath = $"/uploads/documents/{fileName}";
        }

        using var transaction = await db.Database.BeginTransactionAsync();
        try
        {
            var request = new BeneficiaryRequest
            {
                intBeneficiaryId = dto.beneficiaryId,
                strRequestType = dto.requestType,
                strUrgencyLevel = dto.urgencyLevel,
                dtmPickupDate = dto.pickupDate,
                strDocument = docPath ?? dto.document,
                intPurposeId = dto.purposeId,
                intFoodBankDetailId = dto.foodbankId
            };
            db.BeneficiaryRequests.Add(request);
            await db.SaveChangesAsync();

            request.strRequestNo = $"RQ-{request.intBeneficiaryRequestId}";

            foreach (var itemId in dto.itemsNeeded)
            {
                db.BeneficiaryRequestDetails.Add(new BeneficiaryRequestDetail
                {
                    intBeneficiaryRequestId = request.intBeneficiaryRequestId,
                    intItemId = itemId
                });
            }

            db.Notifications.Add(new Notification
            {
                intSourceId = request.intBeneficiaryRequestId,
                strSourceTable = "tblbeneficiaryrequest"
            });

            await db.SaveChangesAsync();
            await transaction.CommitAsync();

            return StatusCode(201, new { message = "Request submitted successfully." });
        }
        catch
        {
            await transaction.RollbackAsync();
            return StatusCode(500, new { message = "Failed to submit request" });
        }
    }

    [HttpPut("requests/{id}/status")]
    public async Task<IActionResult> UpdateRequestStatus(int id, [FromBody] string status)
    {
        var request = await db.BeneficiaryRequests.FindAsync(id);
        if (request == null) return NotFound();
        request.strStatus = status;
        await db.SaveChangesAsync();
        return Ok(new { message = "Status updated" });
    }

    [HttpDelete("requests/{id}")]
    public async Task<IActionResult> DeleteRequest(int id)
    {
        var request = await db.BeneficiaryRequests.FindAsync(id);
        if (request == null) return NotFound();
        request.ysnActive = false;
        await db.SaveChangesAsync();
        return Ok(new { message = "Request deleted" });
    }
}
