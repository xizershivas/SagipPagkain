namespace SagipPagkain.API.DTOs;

public record LoginDto(string username, string password);

public record RegisterDto(
    string username,
    string fullName,
    string? contact,
    string? email,
    string password,
    string confirmPassword,
    string accountType,
    string? address,
    double? latitude,
    double? longitude,
    double? salary
);

public record AuthResponseDto(
    int userId,
    string username,
    string fullName,
    string token,
    string role
);
