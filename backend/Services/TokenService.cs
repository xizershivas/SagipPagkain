using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using Microsoft.IdentityModel.Tokens;
using SagipPagkain.API.Models;

namespace SagipPagkain.API.Services;

public class TokenService(IConfiguration config)
{
    public string CreateToken(User user)
    {
        var role = user.ysnAdmin ? "Admin"
            : user.ysnDonor ? "Donor"
            : user.ysnFoodBank ? "FoodBank"
            : "Beneficiary";

        var claims = new List<Claim>
        {
            new(ClaimTypes.NameIdentifier, user.intUserId.ToString()),
            new(ClaimTypes.Name, user.strUsername),
            new(ClaimTypes.Role, role)
        };

        var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(
            config["Jwt:Key"] ?? throw new InvalidOperationException("JWT Key not configured")));
        var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256Signature);

        var tokenDescriptor = new SecurityTokenDescriptor
        {
            Subject = new ClaimsIdentity(claims),
            Expires = DateTime.UtcNow.AddDays(7),
            SigningCredentials = creds
        };

        var tokenHandler = new JwtSecurityTokenHandler();
        var token = tokenHandler.CreateToken(tokenDescriptor);
        return tokenHandler.WriteToken(token);
    }
}
