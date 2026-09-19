import csv


def readData(file):
    with open(file, 'r', encoding='utf-8', newline='') as file:
        # reader = csv.DictReader(file, delimiter='\t', fieldnames=None, restkey='_duplicate')
        # data = []
        # for row in reader:
        #     data.append(row)
        # return data
        reader = csv.reader(file, delimiter='\t')
        header = next(reader) # Read the header row
        # Create unique names for duplicate columns
        unique_header = []
        count = {}
        data = []
        for column_name in header:
            if column_name not in count:
                count[column_name] = 0
                unique_header.append(column_name)
            else:
                count[column_name] += 1
                unique_header.append(f"{column_name}_{count[column_name]}")
        # Read the data rows and create dictionaries
        for row in reader:
            data_dict = {unique_header[i]: row[i] for i in range(len(row))}
            data.append(data_dict)
        return data

def writeSQL(text, output):
    with open(output, 'w') as file:
        file.write(text)


def manufacturerSQL(data):
    sql = "INSERT IGNORE INTO Manufacturer (Name) VALUES "
    for row in data:
        sql += "('{}'),\n".format(row['manufacturer_name'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "manufacturer.sql")
    return sql


def householdSQL(data):
    sql = "INSERT IGNORE INTO HouseHold (Email, PostalCode, HouseSize, HouseType, CoolingThermostatTemp, HeatingThermostatTemp) VALUES "
    # email	household_type	footage	heating_temp	cooling_temp	postal_code	utilities
    for row in data:
        if row['heating_temp'] == "":
            row['heating_temp'] = "NULL"
        if row['cooling_temp'] == "":
            row['cooling_temp'] = "NULL"
        sql += "('{}','{}', {},'{}',{},{}),\n".format(row['email'], row['postal_code'], row['footage'],
                                                      row['household_type'], row['cooling_temp'],
                                                      row['heating_temp'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "household.sql")
    return sql


def UtilityTypeSQL(data):
    sql = "INSERT IGNORE INTO UtilityType (Email, UtilityType) values "
    for row in data:
        utilities = row['utilities'].split(",")
        for item in utilities:
            if item != "":
                sql += "('{}','{}'),\n".format(row['email'], item)
    sql = sql[:-2] + ";"
    writeSQL(sql, "UtilityType.sql")
    return sql


def PowerGeneratorSQL(data):
    sql = "INSERT IGNORE INTO PowerGenerator (Email, PowerGeneratorId, PowerGenerationType, AverageKWH, StorageKWh) values "
    # household_email	power_number	energy_source	kilowatt_hours	battery
    for row in data:
        if row['battery'] == "":
            sql += "('{}',{}, '{}', {}, {}),\n" \
                .format(row['household_email'], row['power_number'], row['energy_source'], row['kilowatt_hours'],
                        "NULL")
        else:
            sql += "('{}',{}, '{}', {},{}),\n" \
                .format(row['household_email'], row['power_number'], row['energy_source'], row['kilowatt_hours'],
                        row['battery'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "PowerGenerator.sql")
    return sql


# household_email	appliance_number	manufacturer_name	model	appliance_type	air_handler_types	eer	energy_source	hspf	seer	energy_source	capacity	temperature	btu
def ApplianceSQL(data):
    sql = "INSERT IGNORE INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) values "
    for row in data:
        # if row['appliance_type'] == 'water_heater':
        #     sql += "INSERT INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) values ({},'{}', '{}', '{}','{}','{}');\n"\
        #         .format(row['appliance_number'], row['appliance_type'], row['household_email'], row['model'], row['btu'], row['manufacturer_name'])
        # else:
        #     airHandler = row['air_handler_types'].split(",")
        #     for item in airHandler:
        #         sql += "INSERT INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) values ({},'{}', '{}', '{}','{}','{}');\n" \
        #             .format(row['appliance_number'], item, row['household_email'], row['model'], row['btu'], row['manufacturer_name'])
        if row['model'] == "":
            sql += "({},'{}', '{}',{}, {},'{}'),\n" \
                .format(row['appliance_number'], row['appliance_type'], row['household_email'], "NULL", row['btu'],
                        row['manufacturer_name'])
        else:
            sql += "({},'{}', '{}', '{}',{},'{}'),\n" \
                .format(row['appliance_number'], row['appliance_type'], row['household_email'], row['model'],
                        row['btu'],
                        row['manufacturer_name'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "Appliance.sql")
    return sql


def WaterHeaterSQL(data):
    sql = "INSERT IGNORE INTO WaterHeater (ApplianceId, Email, Capacity, Temperature, EnergySource) values "
    for row in data:
        if row['appliance_type'] == 'water_heater':
            if row['temperature'] == "":
                sql += "({},'{}', {},{},'{}'),\n" \
                    .format(row['appliance_number'], row['household_email'], row['capacity'], "NULL",
                            row['energy_source_1'])
            else:
                sql += "({},'{}', {}, {},'{}'),\n" \
                    .format(row['appliance_number'], row['household_email'], row['capacity'], row['temperature'],
                            row['energy_source_1'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "WaterHeater.sql")
    return sql


def AirHandlerSQL(data):
    sql = "INSERT IGNORE INTO AirHandler (ApplianceId, Email, HeatingCoolingMethod) values "
    for row in data:
        if row['appliance_type'] == 'air_handler':
            # airHandler = row['air_handler_types'].split(",")
            # for item in airHandler:
            sql += "({},'{}','{}'),\n" \
                .format(row['appliance_number'], row['household_email'], row['air_handler_types'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "AirHandler.sql")
    return sql


def AirConditionerSQL(data):
    sql = "INSERT IGNORE INTO AirConditioner (AirHandlerId, Email, EnergyEfficiencyRatio) values "
    for row in data:
        if "air_conditioner" in row['air_handler_types']:
            sql += "({},'{}',{}),\n" \
                .format(row['appliance_number'], row['household_email'], row['eer'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "AirConditioner.sql")
    return sql


def HeatPumpSQL(data):
    sql = "INSERT IGNORE INTO HeatPump (AirHandlerId, Email, SeasonalEnergyEfficiencyRating, HeatingSeasonalPerformanceFactor) values "
    for row in data:
        if "heatpump" in row['air_handler_types']:
            sql += "({},'{}',{},{}),\n" \
                .format(row['appliance_number'], row['household_email'], row['seer'], row['hspf'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "HeatPump.sql")
    return sql


def HeaterSQL(data):
    sql = "INSERT IGNORE INTO Heater (AirHandlerId, Email, HeaterEnergySource) values "
    for row in data:
        if "heater" in row['air_handler_types']:
            sql += "({},'{}','{}'),\n" \
                .format(row['appliance_number'], row['household_email'], row['energy_source'])
    sql = sql[:-2] + ";"
    writeSQL(sql, "Heater.sql")
    return sql


def readCSV(text):
    with open(text, 'r') as file:
        reader = csv.reader(file)
        data = []
        for row in reader:
            data.append(row)
        return data


def postalCodeSQL(data):
    sql = "INSERT IGNORE INTO Location (PostalCode, State, City, Longitude, Latitude) values "
    for row in data:
        sql += "('{}', '{}',\"{}\",'{}','{}'),\n".format(row[0], row[1], row[2], row[3], row[4])
    sql = sql[:-2] + ";"
    writeSQL(sql, "postalCode.sql")
    return sql


def outputDemoDataSQL():
    postalCode = readCSV("postal_codes2.csv")
    # email	household_type	footage	heating_temp	cooling_temp	postal_code	utilities
    household = readData("Household.tsv")
    # manufacturer_name
    manufacturer = readData("Manufacturer.tsv")
    # household_email	power_number	energy_source	kilowatt_hours	battery
    power = readData("Power.tsv")
    # household_email	appliance_number	manufacturer_name	model	appliance_type	air_handler_types	eer	energy_source	hspf	seer	energy_source	capacity	temperature	btu
    appliance = readData("Appliance.tsv")

    postalCodeSQL(postalCode)

    dataSQL = ""
    dataSQL += manufacturerSQL(manufacturer)
    # INSERT INTO HouseHold (Email, PostalCode, HouseSize, HouseType, CoolingThermostatTemp, HeatingThermostatTemp) values
    dataSQL += householdSQL(household)
    # INSERT INTO UtilityType (Email, UtilityType) values
    dataSQL += UtilityTypeSQL(household)
    # INSERT INTO PowerGenerator (Email, PowerGeneratorId, PowerGenerationType, AverageKWH, StorageKWh) values
    dataSQL += PowerGeneratorSQL(power)
    # INSERT INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) values
    dataSQL += ApplianceSQL(appliance)
    # INSERT INTO WaterHeater (ApplianceId, Email, Capacity, Temperature, EnergySource) values
    dataSQL += WaterHeaterSQL(appliance)
    # INSERT INTO AirHandler (ApplianceId, Email, HeatingCoolingMethod) values
    dataSQL += AirHandlerSQL(appliance)
    # INSERT INTO AirConditioner (ApplianceId, Email, EnergyEfficiencyRatio) values
    dataSQL += AirConditionerSQL(appliance)
    # INSERT INTO Heater (ApplianceId, Email, HeaterEnergySource) values
    dataSQL += HeaterSQL(appliance)
    # INSERT INTO HeatPump (ApplianceId, Email, SeasonalEnergyEfficiencyRating, HeatingSeasonalPerformanceFactor) values
    dataSQL += HeatPumpSQL(appliance)

    return dataSQL


writeSQL(outputDemoDataSQL(), 'demoData.sql')
# writeSQL(ApplianceSQL(readData("Appliance.tsv")))
