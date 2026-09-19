<?php include("lib/header.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Top 25 popular manufacturers</title>
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
            font-size: 16px;
        }
    </style>

</head>
<div id="main_container">
    <h2>Top 25 popular manufacturers</h2>
    <?php
    include("lib/common.php");


    $sql = "SELECT ManufacturerName, COUNT(*) as CountAppliance
    FROM Appliance a
    LEFT JOIN (
               SELECT AirHandlerId as ID, Email as em, 'Air Conditioner' as ApplianceType, 'air_handler' as AppName from AirConditioner
               UNION
               SELECT AirHandlerId as ID, Email as em, 'Heater' as ApplianceType, 'air_handler' as AppName from Heater
               UNION
               SELECT AirHandlerId as ID, Email as em, 'Heat Pump' as ApplianceType, 'air_handler' as AppName from HeatPump
               UNION
               SELECT ApplianceId as ID, Email as em, 'Water Heater' as ApplianceType, 'water_heater' as AppName from WaterHeater
               ) s
    ON a.ApplianceId = s.ID AND a.Email = s.em AND a.Name = s.AppName
    GROUP BY ManufacturerName
    ORDER BY COUNT(*) desc
    LIMIT 25;
    ";

    $result = mysqli_query($db, $sql);


    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<td>Manufacturer Name</td>";
        echo "<td>Count of appliances</td>";
        echo "</tr>";
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td><a href='top25_man_drill_down.php?name=" . $row["ManufacturerName"] . "'>" . $row["ManufacturerName"] . "</a></td>";
            echo "<td>" . $row["CountAppliance"] . "</td>";
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

