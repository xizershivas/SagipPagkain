# Sagip Pagkain - React + C# API

## Prerequisites
- .NET 10 SDK
- Node.js 18+
- SQL Server (localhost\MSSQLSERVER01, sa/Password1)

## Setup (First Time Only)
Run the database script:
```
sqlcmd -S "localhost\MSSQLSERVER01" -U sa -P "Password1" -No -i backend\database_setup.sql
```

## Running the App

### Terminal 1 — C# API (port 5000)
```
cd backend
dotnet run
```

### Terminal 2 — React Frontend (port 5173)
```
cd frontend
npm run dev
```

Open: http://localhost:5173

## Test Accounts (password: Admin@123)

| Role       | Username          |
|------------|-------------------|
| Admin      | admin             |
| Donor      | donor1            |
| Donor      | donor2            |
| Food Bank  | foodbank_qc       |
| Food Bank  | foodbank_manila   |
| Beneficiary| beneficiary1      |
| Beneficiary| beneficiary2      |

## API Docs (Swagger)
http://localhost:5000/swagger
