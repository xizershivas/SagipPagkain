-- ==========================================================
-- SagipPagkain Database Setup for SQL Server
-- Server: localhost\MSSQLSERVER01  User: sa  Password: Password1
-- ==========================================================

USE master;
GO

IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'SagipPagkainDB')
BEGIN
    CREATE DATABASE SagipPagkainDB;
END
GO

USE SagipPagkainDB;
GO

-- ===========================
-- LOOKUP TABLES
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblcategory' AND xtype='U')
CREATE TABLE tblcategory (
    intCategoryId INT IDENTITY(1,1) PRIMARY KEY,
    strCategory NVARCHAR(100) NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblunit' AND xtype='U')
CREATE TABLE tblunit (
    intUnitId INT IDENTITY(1,1) PRIMARY KEY,
    strUnit NVARCHAR(50) NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblitem' AND xtype='U')
CREATE TABLE tblitem (
    intItemId INT IDENTITY(1,1) PRIMARY KEY,
    intCategoryId INT NOT NULL,
    intUnitId INT NOT NULL,
    strItem NVARCHAR(100) NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1,
    FOREIGN KEY (intCategoryId) REFERENCES tblcategory(intCategoryId),
    FOREIGN KEY (intUnitId) REFERENCES tblunit(intUnitId)
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblpurpose' AND xtype='U')
CREATE TABLE tblpurpose (
    intPurposeId INT IDENTITY(1,1) PRIMARY KEY,
    strPurpose NVARCHAR(100) NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1
);

-- ===========================
-- FOOD BANK TABLES
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblfoodbank' AND xtype='U')
CREATE TABLE tblfoodbank (
    intFoodBankId INT IDENTITY(1,1) PRIMARY KEY,
    strFoodBankName NVARCHAR(150) NOT NULL,
    strMunicipality NVARCHAR(100) NOT NULL,
    strAddress NVARCHAR(255),
    strContact NVARCHAR(50),
    strEmail NVARCHAR(150),
    ysnActive BIT NOT NULL DEFAULT 1
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblfoodbankdetail' AND xtype='U')
CREATE TABLE tblfoodbankdetail (
    intFoodBankDetailId INT IDENTITY(1,1) PRIMARY KEY,
    intFoodBankId INT NOT NULL,
    strFoodBankName NVARCHAR(150) NOT NULL,
    strAddress NVARCHAR(255),
    strContact NVARCHAR(50),
    dblLatitude FLOAT,
    dblLongitude FLOAT,
    ysnActive BIT NOT NULL DEFAULT 1,
    FOREIGN KEY (intFoodBankId) REFERENCES tblfoodbank(intFoodBankId)
);

-- ===========================
-- USER TABLE
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tbluser' AND xtype='U')
CREATE TABLE tbluser (
    intUserId INT IDENTITY(1,1) PRIMARY KEY,
    strUsername NVARCHAR(100) NOT NULL UNIQUE,
    strFullName NVARCHAR(200) NOT NULL,
    strAddress NVARCHAR(255),
    strContact NVARCHAR(50),
    strEmail NVARCHAR(150),
    strPassword NVARCHAR(255) NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1,
    ysnAdmin BIT NOT NULL DEFAULT 0,
    ysnDonor BIT NOT NULL DEFAULT 0,
    ysnFoodBank BIT NOT NULL DEFAULT 0,
    ysnBeneficiary BIT NOT NULL DEFAULT 0,
    intFoodBankId INT,
    FOREIGN KEY (intFoodBankId) REFERENCES tblfoodbank(intFoodBankId)
);

-- ===========================
-- DONATION & INVENTORY
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tbldonationmanagement' AND xtype='U')
CREATE TABLE tbldonationmanagement (
    intDonationId INT IDENTITY(1,1) PRIMARY KEY,
    intUserId INT NOT NULL,
    dtmDate DATETIME NOT NULL DEFAULT GETDATE(),
    strDescription NVARCHAR(500),
    intFoodBankDetailId INT NOT NULL,
    strDocFilePath NVARCHAR(500),
    intPurposeId INT NOT NULL,
    dtmExpirationDate DATETIME NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1,
    strStatus NVARCHAR(50) NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (intUserId) REFERENCES tbluser(intUserId),
    FOREIGN KEY (intFoodBankDetailId) REFERENCES tblfoodbankdetail(intFoodBankDetailId),
    FOREIGN KEY (intPurposeId) REFERENCES tblpurpose(intPurposeId)
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblinventory' AND xtype='U')
CREATE TABLE tblinventory (
    intInventoryId INT IDENTITY(1,1) PRIMARY KEY,
    intDonationId INT NOT NULL UNIQUE,
    intFoodBankDetailId INT NOT NULL,
    intItemId INT NOT NULL,
    intCategoryId INT NOT NULL,
    intUnitId INT NOT NULL,
    intQuantity INT NOT NULL DEFAULT 0,
    dtmExpirationDate DATETIME NOT NULL,
    ysnActive BIT NOT NULL DEFAULT 1,
    FOREIGN KEY (intDonationId) REFERENCES tbldonationmanagement(intDonationId),
    FOREIGN KEY (intFoodBankDetailId) REFERENCES tblfoodbankdetail(intFoodBankDetailId),
    FOREIGN KEY (intItemId) REFERENCES tblitem(intItemId),
    FOREIGN KEY (intCategoryId) REFERENCES tblcategory(intCategoryId),
    FOREIGN KEY (intUnitId) REFERENCES tblunit(intUnitId)
);

-- ===========================
-- BENEFICIARY TABLES
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblbeneficiary' AND xtype='U')
CREATE TABLE tblbeneficiary (
    intBeneficiaryId INT IDENTITY(1,1) PRIMARY KEY,
    intUserId INT NOT NULL UNIQUE,
    strName NVARCHAR(200) NOT NULL,
    strEmail NVARCHAR(150),
    strContact NVARCHAR(50),
    strAddress NVARCHAR(255),
    dblLatitude FLOAT,
    dblLongitude FLOAT,
    dblSalary FLOAT,
    strDocument NVARCHAR(500),
    ysnActive BIT NOT NULL DEFAULT 1,
    FOREIGN KEY (intUserId) REFERENCES tbluser(intUserId)
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblbeneficiaryrequest' AND xtype='U')
CREATE TABLE tblbeneficiaryrequest (
    intBeneficiaryRequestId INT IDENTITY(1,1) PRIMARY KEY,
    intBeneficiaryId INT NOT NULL,
    strRequestNo NVARCHAR(50),
    strRequestType NVARCHAR(50) NOT NULL,
    strUrgencyLevel NVARCHAR(50) NOT NULL,
    dtmPickupDate DATETIME NOT NULL,
    strDocument NVARCHAR(500),
    intPurposeId INT NOT NULL,
    intFoodBankDetailId INT NOT NULL,
    strStatus NVARCHAR(50) NOT NULL DEFAULT 'Pending',
    dtmCreatedAt DATETIME NOT NULL DEFAULT GETDATE(),
    ysnActive BIT NOT NULL DEFAULT 1,
    FOREIGN KEY (intBeneficiaryId) REFERENCES tblbeneficiary(intBeneficiaryId),
    FOREIGN KEY (intPurposeId) REFERENCES tblpurpose(intPurposeId),
    FOREIGN KEY (intFoodBankDetailId) REFERENCES tblfoodbankdetail(intFoodBankDetailId)
);

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblbeneficiaryrequestdetail' AND xtype='U')
CREATE TABLE tblbeneficiaryrequestdetail (
    intBeneficiaryRequestDetailId INT IDENTITY(1,1) PRIMARY KEY,
    intBeneficiaryRequestId INT NOT NULL,
    intItemId INT NOT NULL,
    FOREIGN KEY (intBeneficiaryRequestId) REFERENCES tblbeneficiaryrequest(intBeneficiaryRequestId),
    FOREIGN KEY (intItemId) REFERENCES tblitem(intItemId)
);

-- ===========================
-- VOLUNTEER TABLE
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblvolunteer' AND xtype='U')
CREATE TABLE tblvolunteer (
    intVolunteerId INT IDENTITY(1,1) PRIMARY KEY,
    strFirstName NVARCHAR(100) NOT NULL,
    strLastName NVARCHAR(100) NOT NULL,
    strGender NVARCHAR(20),
    dtmDateOfBirth DATE,
    strStreet NVARCHAR(200),
    strAddress NVARCHAR(255),
    strCity NVARCHAR(100),
    strRegion NVARCHAR(100),
    strZipCode NVARCHAR(20),
    strCountry NVARCHAR(100),
    strContact NVARCHAR(50),
    strEmail NVARCHAR(150),
    strSignFilePath NVARCHAR(500),
    ysnActive BIT NOT NULL DEFAULT 1
);

-- ===========================
-- NOTIFICATION TABLE
-- ===========================

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tblnotification' AND xtype='U')
CREATE TABLE tblnotification (
    intNotificationId INT IDENTITY(1,1) PRIMARY KEY,
    intSourceId INT NOT NULL,
    strSourceTable NVARCHAR(100) NOT NULL,
    ysnSeen BIT NOT NULL DEFAULT 0,
    dtmCreatedAt DATETIME NOT NULL DEFAULT GETDATE()
);

GO

-- ==========================================================
-- SEED DATA
-- ==========================================================

-- Categories
IF NOT EXISTS (SELECT 1 FROM tblcategory)
BEGIN
    INSERT INTO tblcategory (strCategory) VALUES
    ('Vegetables'),
    ('Fruits'),
    ('Grains & Cereals'),
    ('Dairy'),
    ('Meat & Poultry'),
    ('Fish & Seafood'),
    ('Canned Goods'),
    ('Bread & Bakery'),
    ('Beverages'),
    ('Condiments & Sauces');
END

-- Units
IF NOT EXISTS (SELECT 1 FROM tblunit)
BEGIN
    INSERT INTO tblunit (strUnit) VALUES
    ('kg'),
    ('grams'),
    ('pcs'),
    ('liters'),
    ('ml'),
    ('packs'),
    ('cans'),
    ('boxes'),
    ('bottles'),
    ('bags');
END

-- Items
IF NOT EXISTS (SELECT 1 FROM tblitem)
BEGIN
    INSERT INTO tblitem (intCategoryId, intUnitId, strItem) VALUES
    (1, 1, 'Kangkong'),
    (1, 1, 'Kamote Tops'),
    (1, 3, 'Eggplant'),
    (1, 3, 'Tomato'),
    (1, 3, 'Onion'),
    (2, 1, 'Banana'),
    (2, 1, 'Mango'),
    (2, 3, 'Papaya'),
    (3, 1, 'Rice'),
    (3, 6, 'Instant Noodles'),
    (3, 8, 'Oats'),
    (4, 4, 'Fresh Milk'),
    (4, 9, 'Evaporated Milk'),
    (5, 1, 'Chicken'),
    (5, 1, 'Pork'),
    (6, 1, 'Tilapia'),
    (6, 1, 'Bangus'),
    (7, 7, 'Corned Beef'),
    (7, 7, 'Sardines'),
    (8, 6, 'Pandesal'),
    (8, 8, 'Sliced Bread');
END

-- Purposes
IF NOT EXISTS (SELECT 1 FROM tblpurpose)
BEGIN
    INSERT INTO tblpurpose (strPurpose) VALUES
    ('Emergency Relief'),
    ('Regular Donation'),
    ('Surplus Food'),
    ('Community Event'),
    ('Special Needs');
END

-- Food Banks
IF NOT EXISTS (SELECT 1 FROM tblfoodbank)
BEGIN
    INSERT INTO tblfoodbank (strFoodBankName, strMunicipality, strAddress, strContact, strEmail) VALUES
    ('Quezon City Food Bank', 'Quezon City', '123 Commonwealth Ave, Quezon City', '02-8123-4567', 'qcfoodbank@sagippagkain.org'),
    ('Manila Food Bank', 'Manila', '456 Taft Ave, Manila', '02-8234-5678', 'manilafoodbank@sagippagkain.org'),
    ('Marikina Food Bank', 'Marikina', '789 Shoe Ave, Marikina', '02-8345-6789', 'marikinafoodbank@sagippagkain.org'),
    ('Pasig Food Bank', 'Pasig', '321 Ortigas Ave, Pasig', '02-8456-7890', 'pasigfoodbank@sagippagkain.org'),
    ('Caloocan Food Bank', 'Caloocan', '654 10th Ave, Caloocan', '02-8567-8901', 'caloocanfoodbank@sagippagkain.org');
END

-- Food Bank Details
IF NOT EXISTS (SELECT 1 FROM tblfoodbankdetail)
BEGIN
    INSERT INTO tblfoodbankdetail (intFoodBankId, strFoodBankName, strAddress, strContact, dblLatitude, dblLongitude) VALUES
    (1, 'QC Food Bank - Main Branch', 'Commonwealth Ave, QC', '02-8123-4567', 14.6760, 121.0437),
    (1, 'QC Food Bank - North Branch', 'Batasan Hills, QC', '02-8123-9999', 14.7011, 121.0909),
    (2, 'Manila Food Bank - Taft', 'Taft Ave, Manila', '02-8234-5678', 14.5648, 120.9940),
    (3, 'Marikina Food Bank - Main', 'J.P. Rizal St, Marikina', '02-8345-6789', 14.6507, 121.1029),
    (4, 'Pasig Food Bank - Main', 'Ortigas Ave, Pasig', '02-8456-7890', 14.5764, 121.0851),
    (5, 'Caloocan Food Bank - Main', '10th Ave, Caloocan', '02-8567-8901', 14.6499, 120.9840);
END

-- Users (passwords are BCrypt hashed version of 'Admin@123')
IF NOT EXISTS (SELECT 1 FROM tbluser)
BEGIN
    -- Admin
    INSERT INTO tbluser (strUsername, strFullName, strAddress, strContact, strEmail, strPassword, ysnActive, ysnAdmin, intFoodBankId)
    VALUES ('admin', 'System Administrator', 'Sagip Pagkain HQ, Manila', '09171234567', 'admin@sagippagkain.org',
            '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, NULL);

    -- Donor Users (linked to food banks)
    INSERT INTO tbluser (strUsername, strFullName, strAddress, strContact, strEmail, strPassword, ysnActive, ysnDonor, intFoodBankId)
    VALUES
    ('donor1', 'Maria Santos', '10 Sampaguita St, Quezon City', '09182345678', 'maria.santos@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 1),
    ('donor2', 'Jose Cruz', '25 Rosal Ave, Manila', '09193456789', 'jose.cruz@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 2),
    ('donor3', 'Ana Reyes', '5 Kamuning Rd, Quezon City', '09204567890', 'ana.reyes@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 1),
    ('donor4', 'Roberto Lim', '88 Shoe Ave, Marikina', '09215678901', 'roberto.lim@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 3);

    -- Food Bank Users
    INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, ysnActive, ysnFoodBank, intFoodBankId)
    VALUES
    ('foodbank_qc', 'QC Food Bank Manager', '02-8123-4567', 'qcmanager@sagippagkain.org',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 1),
    ('foodbank_manila', 'Manila Food Bank Manager', '02-8234-5678', 'manilamanager@sagippagkain.org',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 2),
    ('foodbank_marikina', 'Marikina Food Bank Manager', '02-8345-6789', 'marikinamanager@sagippagkain.org',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1, 3);

    -- Beneficiary Users (not yet active - needs admin approval)
    INSERT INTO tbluser (strUsername, strFullName, strContact, strEmail, strPassword, ysnActive, ysnBeneficiary)
    VALUES
    ('beneficiary1', 'Pedro Dela Cruz', '09226789012', 'pedro.delacruz@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1),
    ('beneficiary2', 'Luisa Mendoza', '09237890123', 'luisa.mendoza@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1),
    ('beneficiary3', 'Carlos Bautista', '09248901234', 'carlos.bautista@email.com',
     '$2a$11$rBV2JDeWW3.vKmBMW.QvQOqHQGd5RJIHNmVfGMaENxiSt9P1.Q3Oy', 1, 1);
END

-- Beneficiary profiles
IF NOT EXISTS (SELECT 1 FROM tblbeneficiary)
BEGIN
    DECLARE @bUser1 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'beneficiary1');
    DECLARE @bUser2 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'beneficiary2');
    DECLARE @bUser3 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'beneficiary3');

    INSERT INTO tblbeneficiary (intUserId, strName, strEmail, strContact, strAddress, dblLatitude, dblLongitude, dblSalary)
    VALUES
    (@bUser1, 'Pedro Dela Cruz', 'pedro.delacruz@email.com', '09226789012', '15 Maligaya St, Quezon City', 14.6523, 121.0325, 8000),
    (@bUser2, 'Luisa Mendoza', 'luisa.mendoza@email.com', '09237890123', '32 Pag-asa Village, Manila', 14.5820, 120.9820, 6500),
    (@bUser3, 'Carlos Bautista', 'carlos.bautista@email.com', '09248901234', '7 Bagong Silangan, Marikina', 14.6601, 121.0950, 5000);
END

-- Donations
IF NOT EXISTS (SELECT 1 FROM tbldonationmanagement)
BEGIN
    DECLARE @donor1 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'donor1');
    DECLARE @donor2 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'donor2');
    DECLARE @donor3 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'donor3');
    DECLARE @donor4 INT = (SELECT intUserId FROM tbluser WHERE strUsername = 'donor4');

    INSERT INTO tbldonationmanagement (intUserId, dtmDate, strDescription, intFoodBankDetailId, intPurposeId, dtmExpirationDate, strStatus)
    VALUES
    (@donor1, DATEADD(day, -30, GETDATE()), 'Monthly rice donation', 1, 2, DATEADD(month, 6, GETDATE()), 'Completed'),
    (@donor1, DATEADD(day, -20, GETDATE()), 'Emergency canned goods', 1, 1, DATEADD(month, 12, GETDATE()), 'Completed'),
    (@donor2, DATEADD(day, -25, GETDATE()), 'Vegetable surplus', 3, 3, DATEADD(day, 7, GETDATE()), 'Pending'),
    (@donor3, DATEADD(day, -15, GETDATE()), 'Bread and bakery items', 1, 2, DATEADD(day, 3, GETDATE()), 'Completed'),
    (@donor3, DATEADD(day, -10, GETDATE()), 'Canned sardines donation', 2, 1, DATEADD(month, 18, GETDATE()), 'Pending'),
    (@donor4, DATEADD(day, -5, GETDATE()), 'Protein donation - chicken', 4, 2, DATEADD(day, 5, GETDATE()), 'Completed'),
    (@donor1, DATEADD(day, -3, GETDATE()), 'Fruits from harvest', 1, 3, DATEADD(day, 10, GETDATE()), 'Pending'),
    (@donor2, DATEADD(day, -1, GETDATE()), 'Milk and dairy', 3, 2, DATEADD(month, 2, GETDATE()), 'Pending');
END

-- Inventory
IF NOT EXISTS (SELECT 1 FROM tblinventory)
BEGIN
    DECLARE @d1 INT = (SELECT TOP 1 intDonationId FROM tbldonationmanagement ORDER BY intDonationId);
    DECLARE @d2 INT = @d1 + 1;
    DECLARE @d3 INT = @d1 + 2;
    DECLARE @d4 INT = @d1 + 3;
    DECLARE @d5 INT = @d1 + 4;
    DECLARE @d6 INT = @d1 + 5;
    DECLARE @d7 INT = @d1 + 6;
    DECLARE @d8 INT = @d1 + 7;

    INSERT INTO tblinventory (intDonationId, intFoodBankDetailId, intItemId, intCategoryId, intUnitId, intQuantity, dtmExpirationDate)
    VALUES
    (@d1, 1, 9, 3, 1, 50, DATEADD(month, 6, GETDATE())),   -- Rice 50kg
    (@d2, 1, 19, 7, 7, 30, DATEADD(month, 12, GETDATE())), -- Sardines 30 cans
    (@d3, 3, 1, 1, 1, 20, DATEADD(day, 7, GETDATE())),     -- Kangkong 20kg
    (@d4, 1, 20, 8, 6, 100, DATEADD(day, 3, GETDATE())),   -- Pandesal 100 packs
    (@d5, 2, 18, 7, 7, 24, DATEADD(month, 18, GETDATE())), -- Corned Beef 24 cans
    (@d6, 4, 14, 5, 1, 15, DATEADD(day, 5, GETDATE())),    -- Chicken 15kg
    (@d7, 1, 7, 2, 1, 25, DATEADD(day, 10, GETDATE())),    -- Mango 25kg
    (@d8, 3, 12, 4, 4, 10, DATEADD(month, 2, GETDATE()));  -- Fresh Milk 10L
END

-- Beneficiary Requests
IF NOT EXISTS (SELECT 1 FROM tblbeneficiaryrequest)
BEGIN
    DECLARE @bene1 INT = (SELECT TOP 1 intBeneficiaryId FROM tblbeneficiary ORDER BY intBeneficiaryId);
    DECLARE @bene2 INT = @bene1 + 1;
    DECLARE @bene3 INT = @bene1 + 2;

    INSERT INTO tblbeneficiaryrequest (intBeneficiaryId, strRequestType, strUrgencyLevel, dtmPickupDate, intPurposeId, intFoodBankDetailId, strStatus)
    VALUES
    (@bene1, 'Emergency', 'High', DATEADD(day, 2, GETDATE()), 1, 1, 'Pending'),
    (@bene1, 'Regular', 'Low', DATEADD(day, 7, GETDATE()), 2, 1, 'Approved'),
    (@bene2, 'Special Needs', 'Medium', DATEADD(day, 3, GETDATE()), 5, 3, 'Pending'),
    (@bene3, 'Regular', 'Low', DATEADD(day, 5, GETDATE()), 2, 4, 'Completed');

    UPDATE tblbeneficiaryrequest SET strRequestNo = 'RQ-' + CAST(intBeneficiaryRequestId AS NVARCHAR(20));

    -- Request Details
    DECLARE @req1 INT = (SELECT TOP 1 intBeneficiaryRequestId FROM tblbeneficiaryrequest ORDER BY intBeneficiaryRequestId);
    DECLARE @req2 INT = @req1 + 1;
    DECLARE @req3 INT = @req1 + 2;
    DECLARE @req4 INT = @req1 + 3;

    INSERT INTO tblbeneficiaryrequestdetail (intBeneficiaryRequestId, intItemId) VALUES
    (@req1, 9), (@req1, 19), (@req1, 1),
    (@req2, 9), (@req2, 10),
    (@req3, 12), (@req3, 14),
    (@req4, 9), (@req4, 18);
END

-- Volunteers
IF NOT EXISTS (SELECT 1 FROM tblvolunteer)
BEGIN
    INSERT INTO tblvolunteer (strFirstName, strLastName, strGender, dtmDateOfBirth, strStreet, strAddress, strCity, strRegion, strZipCode, strCountry, strContact, strEmail)
    VALUES
    ('Juan', 'Dela Vega', 'Male', '1990-05-15', '123 Mabini St', 'Sampaloc, Manila', 'Manila', 'Metro Manila', '1008', 'Philippines', '09111111111', 'juan.delavega@email.com'),
    ('Anna', 'Garcia', 'Female', '1995-08-22', '45 Rosal Ave', 'Marikina Heights', 'Marikina', 'Metro Manila', '1800', 'Philippines', '09222222222', 'anna.garcia@email.com'),
    ('Michael', 'Torres', 'Male', '1988-03-10', '78 Quezon Blvd', 'Diliman', 'Quezon City', 'Metro Manila', '1101', 'Philippines', '09333333333', 'michael.torres@email.com'),
    ('Sarah', 'Villanueva', 'Female', '1993-12-05', '12 Ortigas Ave', 'Pasig City', 'Pasig', 'Metro Manila', '1600', 'Philippines', '09444444444', 'sarah.villanueva@email.com'),
    ('Diego', 'Padilla', 'Male', '1985-07-30', '55 10th Ave', 'Caloocan City', 'Caloocan', 'Metro Manila', '1400', 'Philippines', '09555555555', 'diego.padilla@email.com');
END

PRINT 'SagipPagkainDB setup completed successfully!';
PRINT 'Default password for all accounts: Admin@123';
GO
