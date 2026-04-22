namespace SagipPagkain.API.DTOs;

public record BeneficiaryRequestCreateDto(
    int beneficiaryId,
    string requestType,
    List<int> itemsNeeded,
    string urgencyLevel,
    DateTime pickupDate,
    string? document,
    int purposeId,
    int foodbankId
);

public class BeneficiaryRequestDto
{
    public int intBeneficiaryRequestId { get; set; }
    public int intBeneficiaryId { get; set; }
    public string strBeneficiaryName { get; set; } = string.Empty;
    public string? strRequestNo { get; set; }
    public string strRequestType { get; set; } = string.Empty;
    public string strUrgencyLevel { get; set; } = string.Empty;
    public DateTime dtmPickupDate { get; set; }
    public string? strDocument { get; set; }
    public int intPurposeId { get; set; }
    public string strPurpose { get; set; } = string.Empty;
    public int intFoodBankDetailId { get; set; }
    public string strFoodBankName { get; set; } = string.Empty;
    public string strStatus { get; set; } = string.Empty;
    public DateTime dtmCreatedAt { get; set; }
    public List<string> ItemNames { get; set; } = [];
}

public class BeneficiaryDto
{
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
    public bool ysnActive { get; set; }
}
