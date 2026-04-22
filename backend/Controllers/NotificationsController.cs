using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Data;

namespace SagipPagkain.API.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class NotificationsController(AppDbContext db) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> GetUnseen()
    {
        var notifications = await db.Notifications
            .Where(n => !n.ysnSeen)
            .OrderByDescending(n => n.dtmCreatedAt)
            .Take(20)
            .ToListAsync();
        return Ok(notifications);
    }

    [HttpPut("{id}/seen")]
    public async Task<IActionResult> MarkSeen(int id)
    {
        var notification = await db.Notifications.FindAsync(id);
        if (notification == null) return NotFound();
        notification.ysnSeen = true;
        await db.SaveChangesAsync();
        return Ok();
    }

    [HttpPut("mark-all-seen")]
    public async Task<IActionResult> MarkAllSeen()
    {
        var unseen = await db.Notifications.Where(n => !n.ysnSeen).ToListAsync();
        unseen.ForEach(n => n.ysnSeen = true);
        await db.SaveChangesAsync();
        return Ok(new { message = $"{unseen.Count} notifications marked as seen" });
    }
}
