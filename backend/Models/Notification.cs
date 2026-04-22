using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblnotification")]
public class Notification
{
    [Key]
    public int intNotificationId { get; set; }
    public int intSourceId { get; set; }
    public string strSourceTable { get; set; } = string.Empty;
    public bool ysnSeen { get; set; } = false;
    public DateTime dtmCreatedAt { get; set; } = DateTime.Now;
}
