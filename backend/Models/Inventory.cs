using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblinventory")]
public class Inventory
{
    [Key]
    public int intInventoryId { get; set; }
    public int intDonationId { get; set; }
    public int intFoodBankDetailId { get; set; }
    public int intItemId { get; set; }
    public int intCategoryId { get; set; }
    public int intUnitId { get; set; }
    public int intQuantity { get; set; }
    public DateTime dtmExpirationDate { get; set; }
    public bool ysnActive { get; set; } = true;

    public Donation? Donation { get; set; }
    public FoodBankDetail? FoodBankDetail { get; set; }
    public Item? Item { get; set; }
    public Category? Category { get; set; }
    public Unit? Unit { get; set; }
}
