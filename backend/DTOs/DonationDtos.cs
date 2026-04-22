namespace SagipPagkain.API.DTOs;

public record DonationCreateDto(
    int userId,
    DateTime date,
    string? description,
    int foodBankDetailId,
    int itemId,
    int quantity,
    int categoryId,
    int unitId,
    int purposeId,
    DateTime expirationDate,
    string? docFilePath
);

public record DonationUpdateDto(
    int donationId,
    string? description,
    int foodBankDetailId,
    int purposeId,
    DateTime expirationDate,
    bool isActive
);

public class DonationDto
{
    public int intDonationId { get; set; }
    public int intUserId { get; set; }
    public string strDonorName { get; set; } = string.Empty;
    public DateTime dtmDate { get; set; }
    public string? strDescription { get; set; }
    public int intFoodBankDetailId { get; set; }
    public string strFoodBankName { get; set; } = string.Empty;
    public string? strDocFilePath { get; set; }
    public int intPurposeId { get; set; }
    public string strPurpose { get; set; } = string.Empty;
    public DateTime dtmExpirationDate { get; set; }
    public string strStatus { get; set; } = string.Empty;
    public bool ysnActive { get; set; }
    public int? intItemId { get; set; }
    public string? strItem { get; set; }
    public int? intQuantity { get; set; }
    public string? strUnit { get; set; }
    public string? strCategory { get; set; }
}
