-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_sp23_team052`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sp23_team052 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_sp23_team052;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_sp23_team052`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables 
CREATE TABLE HouseHold (
    Email varchar(250) NOT NULL, 
    PostalCode varchar(50) NOT NULL, 
    HouseSize INT NOT NULL, 
    HouseType varchar(50) NOT NULL, 
    CoolingThermostatTemp int DEFAULT NULL, 
    HeatingThermostatTemp int DEFAULT NULL, 
    PRIMARY KEY (Email), 
    UNIQUE KEY Email (Email)
);

CREATE TABLE UtilityType(
    Email varchar(250) NOT NULL,
    UtilityType varchar(50) NOT NULL,
    PRIMARY KEY (Email, UtilityType)
);

CREATE TABLE Location (
    PostalCode varchar(50) NOT NULL, 
    State varchar (50) NOT NULL,
    City varchar(50) NOT NULL, 
    Longitude DECIMAL NOT NULL,
    Latitude DECIMAL NOT NULL, 
    PRIMARY KEY(PostalCode), 
    unique key PostalCode(PostalCode)
);

CREATE TABLE PowerGenerator(
    Email varchar(250) NOT NULL,
    PowerGeneratorId int(16) unsigned NOT NULL,
	PowerGenerationType  varchar(50) NOT NULL, 
    AverageKWH int NOT NULL, 
    StorageKWh int DEFAULT NULL,
    PRIMARY KEY(PowerGeneratorId, Email)
);

CREATE TABLE Appliance(
    ApplianceId int(16) unsigned NOT NULL,
    Name varchar(250) NOT NULL, 
    Email varchar(250) NOT NULL,
    ModelName varchar(250) DEFAULT NULL, 
    BTURating int(16) NOT NULL,
    ManufacturerName varchar(250) NOT NULL, 
	primary key (ApplianceId, Email)
);

CREATE TABLE Manufacturer(
    Name varchar(250) NOT NULL,
    primary key (Name)
);

CREATE TABLE WaterHeater(
    ApplianceId int(16) unsigned not null,
    Email varchar(250) NOT NULL,
    Capacity DOUBLE Precision NOT NULL, 
    Temperature int DEFAULT NULL, 
    EnergySource varchar(50) NOT NULL, 
    primary key (ApplianceId, Email)
);

CREATE TABLE AirHandler(
    ApplianceId int(16) unsigned not null,
    Email varchar(250) NOT NULL,
    HeatingCoolingMethod varchar(50) NOT NULL, 
    primary key (ApplianceId, Email)
);

CREATE TABLE AirConditioner(
    AirHandlerId int(16) unsigned not null,
    Email varchar(250) NOT NULL,
    EnergyEfficiencyRatio DOUBLE Precision NOT NULL, 
	primary key (AirHandlerId, Email)
);

CREATE TABLE Heater(
    AirHandlerId int(16) unsigned not null,
    Email varchar(250) NOT NULL,
    HeaterEnergySource varchar(50) NOT NULL, 
    primary key (AirHandlerId, Email)
);

CREATE TABLE HeatPump(
    AirHandlerId int(16) unsigned not null,
    Email varchar(250) NOT NULL,
    SeasonalEnergyEfficiencyRating DOUBLE Precision NOT NULL, 
    HeatingSeasonalPerformanceFactor DOUBLE Precision NOT NULL, 
	primary key (AirHandlerId, Email)
);

-- Constraints Foreign Keys FK_ChildTable_childColumn_ParentTable_parentColumn

Alter table UtilityType
add constraint fk_HouseHold_Email_UtilityType_Email foreign key(Email) references HouseHold (Email);

alter table HouseHold 
add constraint fk_Location_PostalCode_HouseHold_PostalCode foreign key(PostalCode) references Location(PostalCode); 

alter table PowerGenerator
add constraint fk_HouseHold_Email_PowerGenerator_Email foreign key(Email) references HouseHold (Email);

alter table Appliance
add constraint fk_HouseHold_Email_Appliance_Email foreign key(Email) references HouseHold(Email); 

alter table Appliance
add constraint fk_Manufacturer_ManufacturerName_Appliance_ManufacturerName foreign key(ManufacturerName) references Manufacturer(Name); 

alter table WaterHeater
add constraint fk_Appliance_ApplianceId_Email_WaterHeater_ApplianceId_Email foreign key(ApplianceId, Email) references Appliance (ApplianceId, Email);

alter table AirHandler
add constraint fk_Appliance_ApplianceId_Email_AirHandler_ApplianceId_Email foreign key(ApplianceId, Email) references Appliance (ApplianceId, Email);

alter table AirConditioner
add constraint fk_Appliance_ApplianceId_Email_AirConditioner_AirHandlerId_Email foreign key(AirHandlerId, Email) references Appliance (ApplianceId, Email);

alter table Heater
add constraint fk_Appliance_ApplianceId_Email_Heater_AirHandlerId_Email foreign key(AirHandlerId, Email) references Appliance (ApplianceId, Email);

alter table HeatPump
add constraint fk_Appliance_ApplianceId_Email_HeatPump_AirHandlerId_Email foreign key(AirHandlerId, Email) references Appliance (ApplianceId, Email);