<?php include("../lib/header.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Heating/cooling method report</title>
    <style>
        a, h2, h3 {
            margin-left: 10px;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin: 0 auto;
            margin-left: 10px;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            margin-left: 10px;
            font-size: 16px;
        }
    </style>

</head>
<div id="main_container">
    <h2>Heating/cooling method details by household type</h2>
    <?php
    include("../lib/common.php");

    $sql = "SELECT 
            HouseType, 
            COUNT(ac.AirHandlerId) as 'Count of AC',
            ROUND(AVG(a.BTURating)) as 'AVG BTU Rating for AC',
            ROUND(AVG(ac.EnergyEfficiencyRatio),1) as 'Average EER',
            COUNT(h.AirHandlerId) as 'Count of Heater',
            ROUND(AVG(aHT.BTURating)) as 'AVG BTU Rating for Heater',
            (SELECT HeaterEnergySource
             FROM heater
             GROUP BY HeaterEnergySource
             ORDER BY COUNT(*) DESC 
             LIMIT 1
            ) as 'Most Common Energy Source',
            COUNT(hp.AirHandlerId) as 'Count of Heat Pump',
            ROUND(AVG(aHP.BTURating)) as 'AVG BTU Rating for Heat Pump',
            ROUND(AVG(hp.SeasonalEnergyEfficiencyRating),1) as 'Average SEER',
            ROUND(AVG(hp.HeatingSeasonalPerformanceFactor),1) as 'Average HSPF'
            FROM household 
            JOIN appliance a on a.Email = household.Email
            JOIN airhandler ah on a.Email = ah.Email and a.ApplianceId = ah.ApplianceId
            LEFT JOIN airconditioner ac on ac.AirHandlerId = ah.ApplianceId and ac.Email = ah.Email
            LEFT JOIN heatpump hp on hp.AirHandlerId = ah.ApplianceId and hp.Email = ah.Email
            LEFT JOIN heater h on h.AirHandlerId = ah.ApplianceId and h.Email = ah.Email
            LEFT JOIN appliance aHT on aHT.ApplianceId = h.AirHandlerId and aHT.Email = h.Email
            LEFT JOIN appliance aHP on aHP.ApplianceId = hp.AirHandlerId and aHP.Email = hp.Email
            WHERE a.Name = 'air_handler'
            GROUP BY HouseType;";
    $result = mysqli_query($db, $sql);


    if(!$result){
        echo "<p>Query is not working</p>";
    }else if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<td>House Type</td>";
        echo "<td>Count of air conditioners</td>";
        echo "<td>Average air conditioner BTUs</td>";
        echo "<td>Average EER for air conditioner</td>";
        echo "<td>Count of heater</td>";
        echo "<td>Average heater BTUs</td>";
        echo "<td>Most common energy source for heater</td>";
        echo "<td>Count of heat pump</td>";
        echo "<td>Average heat pump BTUs</td>";
        echo "<td>Average SEER for heat pump</td>";
        echo "<td>Average HSPF for heat pump</td>";
        echo "</tr>";
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>" . $row["HouseType"] . "</td>";
            echo "<td>" . $row["Count of AC"] . "</td>";
            echo "<td>" . $row["AVG BTU Rating for AC"] . "</td>";
            echo "<td>" . $row["Average EER"] . "</td>";
            echo "<td>" . $row["Count of Heater"] . "</td>";
            echo "<td>" . $row["AVG BTU Rating for Heater"] . "</td>";
            echo "<td>" . $row["Most Common Energy Source"] . "</td>";
            echo "<td>" . $row["Count of Heat Pump"] . "</td>";
            echo "<td>" . $row["AVG BTU Rating for Heat Pump"] . "</td>";
            echo "<td>" . $row["Average SEER"] . "</td>";
            echo "<td>" . $row["Average HSPF"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "There is no data available";
    }

    ?>

    <br>
    <br>
    <a href="View_Reports.php">Return to the View Reports</a>

    <br>
    <br>
    <a href="Main_Menu.php">Return to the main menu</a>
</div>
</html>

