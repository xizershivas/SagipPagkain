using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
public class LookupController(AppDbContext db) : ControllerBase
{
    [HttpGet("items")]
    public async Task<IActionResult> GetItems()
    {
        var items = await db.Items
            .Include(i => i.Category)
            .Include(i => i.Unit)
            .Where(i => i.ysnActive)
            .Select(i => new {
                i.intItemId,
                i.strItem,
                i.intCategoryId,
                strCategory = i.Category!.strCategory,
                i.intUnitId,
                strUnit = i.Unit!.strUnit
            })
            .ToListAsync();
        return Ok(items);
    }

    [HttpGet("categories")]
    public async Task<IActionResult> GetCategories()
    {
        var categories = await db.Categories.Where(c => c.ysnActive).ToListAsync();
        return Ok(categories);
    }

    [HttpGet("units")]
    public async Task<IActionResult> GetUnits()
    {
        var units = await db.Units.Where(u => u.ysnActive).ToListAsync();
        return Ok(units);
    }

    [HttpGet("purposes")]
    public async Task<IActionResult> GetPurposes()
    {
        var purposes = await db.Purposes.Where(p => p.ysnActive).ToListAsync();
        return Ok(purposes);
    }

    [HttpGet("foodbanks")]
    public async Task<IActionResult> GetFoodBanks()
    {
        var foodBanks = await db.FoodBankDetails
            .Where(fb => fb.ysnActive)
            .Select(fb => new {
                fb.intFoodBankDetailId,
                fb.strFoodBankName,
                fb.strAddress,
                fb.dblLatitude,
                fb.dblLongitude,
                fb.intFoodBankId
            })
            .ToListAsync();
        return Ok(foodBanks);
    }

    [HttpGet("foodbanks/user/{userId}")]
    public async Task<IActionResult> GetFoodBankByUser(int userId)
    {
        var user = await db.Users.FindAsync(userId);
        if (user == null) return NotFound();

        var details = await db.FoodBankDetails
            .Where(fb => fb.intFoodBankId == user.intFoodBankId && fb.ysnActive)
            .ToListAsync();
        return Ok(details);
    }

    [HttpGet("item/{itemId}/recommended-foodbank/{userId}")]
    public async Task<IActionResult> GetRecommendedFoodBank(int itemId, int userId)
    {
        var user = await db.Users.FindAsync(userId);
        if (user == null) return NotFound();

        var result = await db.FoodBankDetails
            .Where(fb => fb.intFoodBankId == user.intFoodBankId && fb.ysnActive)
            .Select(fb => new {
                fb.intFoodBankDetailId,
                fb.strFoodBankName,
                quantity = db.Inventories
                    .Where(i => i.intFoodBankDetailId == fb.intFoodBankDetailId && i.intItemId == itemId)
                    .Sum(i => (int?)i.intQuantity) ?? 0
            })
            .OrderBy(fb => fb.quantity)
            .FirstOrDefaultAsync();

        return Ok(result);
    }
}
