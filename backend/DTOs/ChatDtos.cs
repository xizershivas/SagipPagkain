namespace SagipPagkain.API.DTOs;

public record ChatMessageDto(string message, List<ChatHistoryItem>? history);
public record ChatHistoryItem(string role, string content);
