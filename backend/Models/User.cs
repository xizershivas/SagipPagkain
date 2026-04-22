using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tbluser")]
public class User
{
    [Key]
    public int intUserId { get; set; }
    public string strUsername { get; set; } = string.Empty;
    public string strFullName { get; set; } = string.Empty;
    public string? strAddress { get; set; }
    public string? strContact { get; set; }
    public string? strEmail { get; set; }
    public string strPassword { get; set; } = string.Empty;
    public bool ysnActive { get; set; } = true;
    public bool ysnAdmin { get; set; } = false;
    public bool ysnDonor { get; set; } = false;
    public bool ysnFoodBank { get; set; } = false;
    public bool ysnBeneficiary { get; set; } = false;
    public int? intFoodBankId { get; set; }

    public FoodBank? FoodBank { get; set; }
    public Beneficiary? Beneficiary { get; set; }
}
