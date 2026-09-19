<?php include("lib/header.php"); ?>

<!DOCTYPE html>
<html>
<head>
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
    <?php
    include("lib/common.php");

    if(isset($_GET['name'])) {
        $manufacturer_name = mysqli_real_escape_string($db, $_GET['name']);


        $sqlApplianceAll = "SELECT app.ApplianceType, IFNULL(a.CountAppliance,0) as CountAppliance
                    FROM (
                        SELECT 'Water Heater' AS ApplianceType
                        UNION SELECT 'Air Conditioner'
                        UNION SELECT 'Heater'
                        UNION SELECT 'Heat Pump'
                    ) AS app
                    LEFT JOIN (
                        SELECT ManufacturerName, ApplianceType, COUNT(*) as CountAppliance
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
                        WHERE A.ManufacturerName = '$manufacturer_name'
                        GROUP BY ApplianceType
                        ORDER BY COUNT(*) ASC
                    ) AS a ON app.ApplianceType = a.ApplianceType
        ";
                    
        $result = mysqli_query($db, $sqlApplianceAll);
        //echo "<h2>Appliance Count Report for $manufacturer_name</h2>";

        if (mysqli_num_rows($result) > 0) {
            // Display the report for the selected manufacturer
            echo "<h2>Appliance Count Report for $manufacturer_name</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<td>Appliance Type</td>";
            echo "<td>Count</td>";
            echo "</tr>";
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";       
                echo "<td>" . $row["ApplianceType"] . "</td>";
                echo "<td>" . $row["CountAppliance"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "There is no data available";
        }
        
        mysqli_free_result($result);
    }
    ?>

    <br>
    <br>
    <a href="top25_man.php">Back to Top 25 Manufacturer Report</a>
    <br>
    <br>
    <a href="View_Reports.php">Return to the View Reports</a>
    <br>
    <br>
    <a href="Main_Menu.php">Return to the main menu</a>

</div>
</html>