using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class InventoryController(AppDbContext db) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> GetAll(
        [FromQuery] int? userId,
        [FromQuery] string? filter,
        [FromQuery] string? search,
        [FromQuery] int page = 1,
        [FromQuery] int limit = 10)
    {
        var query = db.Inventories
            .Include(i => i.FoodBankDetail).ThenInclude(fb => fb!.FoodBank)
            .Include(i => i.Item)
            .Include(i => i.Category)
            .Include(i => i.Unit)
            .Where(i => i.intQuantity > 0 && i.ysnActive)
            .AsQueryable();

        if (userId.HasValue)
        {
            var user = await db.Users.FindAsync(userId.Value);
            if (user?.ysnFoodBank == true && user.intFoodBankId.HasValue)
            {
                query = query.Where(i => i.FoodBankDetail!.intFoodBankId == user.intFoodBankId);
            }
        }

        if (!string.IsNullOrEmpty(search) && !string.IsNullOrEmpty(filter))
        {
            query = filter switch
            {
                "strItem" => query.Where(i => i.Item!.strItem.StartsWith(search)),
                "strUnit" => query.Where(i => i.Unit!.strUnit.StartsWith(search)),
                "strFoodBankName" => query.Where(i => i.FoodBankDetail!.strFoodBankName.StartsWith(search)),
                _ => query.Where(i => i.Category!.strCategory.StartsWith(search))
            };
        }

        var total = await query.CountAsync();
        var offset = (page - 1) * limit;

        var data = await query
            .OrderBy(i => i.intInventoryId)
            .Skip(offset)
            .Take(limit)
            .Select(i => new
            {
                i.intInventoryId,
                i.intQuantity,
                i.intFoodBankDetailId,
                strFoodBankName = i.FoodBankDetail!.strFoodBankName,
                i.intItemId,
                strItem = i.Item!.strItem,
                i.intCategoryId,
                strCategory = i.Category!.strCategory,
                i.intUnitId,
                strUnit = i.Unit!.strUnit,
                dtmExpirationDate = i.dtmExpirationDate.ToString("MMMM dd, yyyy")
            })
            .ToListAsync();

        return Ok(new { data, totalRecords = total, page, limit });
    }

    [HttpPut("{id}/transfer")]
    public async Task<IActionResult> Transfer(int id, [FromBody] int quantity)
    {
        var inventory = await db.Inventories.FindAsync(id);
        if (inventory == null) return NotFound();
        if (inventory.intQuantity < quantity) return BadRequest(new { message = "Insufficient quantity" });

        inventory.intQuantity -= quantity;
        await db.SaveChangesAsync();
        return Ok(new { message = "Transfer successful" });
    }
}
