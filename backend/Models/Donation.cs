using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tbldonationmanagement")]
public class Donation
{
    [Key]
    public int intDonationId { get; set; }
    public int intUserId { get; set; }
    public DateTime dtmDate { get; set; }
    public string? strDescription { get; set; }
    public int intFoodBankDetailId { get; set; }
    public string? strDocFilePath { get; set; }
    public int intPurposeId { get; set; }
    public DateTime dtmExpirationDate { get; set; }
    public bool ysnActive { get; set; } = true;
    public string strStatus { get; set; } = "Pending";

    public User? User { get; set; }
    public FoodBankDetail? FoodBankDetail { get; set; }
    public Purpose? Purpose { get; set; }
    public Inventory? Inventory { get; set; }
}
