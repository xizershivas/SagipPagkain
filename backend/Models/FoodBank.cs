using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblfoodbank")]
public class FoodBank
{
    [Key]
    public int intFoodBankId { get; set; }
    public string strFoodBankName { get; set; } = string.Empty;
    public string strMunicipality { get; set; } = string.Empty;
    public string? strAddress { get; set; }
    public string? strContact { get; set; }
    public string? strEmail { get; set; }
    public bool ysnActive { get; set; } = true;

    public ICollection<FoodBankDetail> FoodBankDetails { get; set; } = [];
    public ICollection<User> Users { get; set; } = [];
}

[Table("tblfoodbankdetail")]
public class FoodBankDetail
{
    [Key]
    public int intFoodBankDetailId { get; set; }
    public int intFoodBankId { get; set; }
    public string strFoodBankName { get; set; } = string.Empty;
    public string? strAddress { get; set; }
    public string? strContact { get; set; }
    public double? dblLatitude { get; set; }
    public double? dblLongitude { get; set; }
    public bool ysnActive { get; set; } = true;

    public FoodBank? FoodBank { get; set; }
    public ICollection<Donation> Donations { get; set; } = [];
    public ICollection<Inventory> Inventories { get; set; } = [];
}
