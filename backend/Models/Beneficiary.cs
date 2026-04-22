using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblbeneficiary")]
public class Beneficiary
{
    [Key]
    public int intBeneficiaryId { get; set; }
    public int intUserId { get; set; }
    public string strName { get; set; } = string.Empty;
    public string? strEmail { get; set; }
    public string? strContact { get; set; }
    public string? strAddress { get; set; }
    public double? dblLatitude { get; set; }
    public double? dblLongitude { get; set; }
    public double? dblSalary { get; set; }
    public string? strDocument { get; set; }
    public bool ysnActive { get; set; } = true;

    public User? User { get; set; }
    public ICollection<BeneficiaryRequest> BeneficiaryRequests { get; set; } = [];
}

[Table("tblbeneficiaryrequest")]
public class BeneficiaryRequest
{
    [Key]
    public int intBeneficiaryRequestId { get; set; }
    public int intBeneficiaryId { get; set; }
    public string? strRequestNo { get; set; }
    public string strRequestType { get; set; } = string.Empty;
    public string strUrgencyLevel { get; set; } = string.Empty;
    public DateTime dtmPickupDate { get; set; }
    public string? strDocument { get; set; }
    public int intPurposeId { get; set; }
    public int intFoodBankDetailId { get; set; }
    public string strStatus { get; set; } = "Pending";
    public DateTime dtmCreatedAt { get; set; } = DateTime.Now;
    public bool ysnActive { get; set; } = true;

    public Beneficiary? Beneficiary { get; set; }
    public Purpose? Purpose { get; set; }
    public FoodBankDetail? FoodBankDetail { get; set; }
    public ICollection<BeneficiaryRequestDetail> RequestDetails { get; set; } = [];
}

[Table("tblbeneficiaryrequestdetail")]
public class BeneficiaryRequestDetail
{
    [Key]
    public int intBeneficiaryRequestDetailId { get; set; }
    public int intBeneficiaryRequestId { get; set; }
    public int intItemId { get; set; }

    public BeneficiaryRequest? BeneficiaryRequest { get; set; }
    public Item? Item { get; set; }
}
