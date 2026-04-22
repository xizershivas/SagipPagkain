using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblvolunteer")]
public class Volunteer
{
    [Key]
    public int intVolunteerId { get; set; }
    public string strFirstName { get; set; } = string.Empty;
    public string strLastName { get; set; } = string.Empty;
    public string? strGender { get; set; }
    public DateTime? dtmDateOfBirth { get; set; }
    public string? strStreet { get; set; }
    public string? strAddress { get; set; }
    public string? strCity { get; set; }
    public string? strRegion { get; set; }
    public string? strZipCode { get; set; }
    public string? strCountry { get; set; }
    public string? strContact { get; set; }
    public string? strEmail { get; set; }
    public string? strSignFilePath { get; set; }
    public bool ysnActive { get; set; } = true;
}
