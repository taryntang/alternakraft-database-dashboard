<?php include("../lib/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Water Heater Reports</title>
    <style>
        a, h1, h2, h3, label {
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
            margin-left: 10px;
        }

    </style>

</head>
<div id="main_container">
    <h1>Water heater statistics by state</h1>
    <div>
        <?php
        include("../lib/common.php");

        $sql = "SELECT
                location.State,
                IFNULL(ROUND (AVG (WaterHeater.Capacity)),0) AS avgCapacity,
                IFNULL(ROUND (AVG (CASE WHEN appliance.name = 'water_heater' THEN appliance.BTURating END)),0) AS avgBTU,
                CASE WHEN AVG (waterheater.Temperature) = 0 THEN '' ELSE ROUND (AVG (waterheater.Temperature), 1) END AS avgTemperature,
                COUNT(CASE WHEN waterheater.Temperature IS NOT NULL
                THEN waterheater.ApplianceId END) AS countTemp,
                COUNT(CASE WHEN waterheater.Temperature IS NULL THEN waterheater.ApplianceId END) AS countNoTemp
                FROM location
                LEFT JOIN household ON household.PostalCode= location.PostalCode
                Left join appliance on household.Email = appliance.Email
                LEFT JOIN waterheater ON appliance.Email= waterheater.Email AND appliance.ApplianceId = waterheater.ApplianceId
                GROUP BY
                location.State
                ORDER BY
                location.State ASC;";
        $result = mysqli_query($db, $sql);

        if (!$result) {
            echo "<p>Query is not working</p>";
        } else if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<td>State</td>";
            echo "<td>Average water heater capacity</td>";
            echo "<td>Average water heater BTUs</td>";
            echo "<td>Average temperature setting</td>";
            echo "<td>Count of water heaters where temperature setting provided</td>";
            echo "<td>Count of water heaters where no temperature setting provided</td>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["State"] . "</td>";
                echo "<td>" . $row["avgCapacity"] . "</td>";
                echo "<td>" . $row["avgBTU"] . "</td>";
                echo "<td>" . $row["avgTemperature"] . "</td>";
                echo "<td>" . $row["countTemp"] . "</td>";
                echo "<td>" . $row["countNoTemp"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data returned</p>";
        }

        ?>

    </div>
    <br>
    <br>

    <form method="post">
        <?php
        //                        include("../lib/common.php");
        echo "<label>Choose a state you would like to view its drill down report: </label>";
        $sql = "
                    SELECT state 
                    FROM location
                    GROUP BY state
                    ORDER BY state ASC;";
        $states = mysqli_query($db, $sql);
        $selected_option = "";
        if (!$result) {
            echo "<p>Query is not working</p>";
        } else if (mysqli_num_rows($states) > 0) {

            $options = "<option></option>";
            while ($row = mysqli_fetch_assoc($states)) {
                $options .= "<option>" . $row["state"] . "</option>";
            }
            $dropdown_html = "<select name='my_dropdown'>" . $options . "</select>";
            echo $dropdown_html;
            echo "<input type='submit' name='submit' value='Submit' />";
            $selected_option = $_POST["my_dropdown"];


        } else {
            echo "No states were retrieved";
        }

        $sql = "
        SELECT wh.EnergySource,
        ROUND(MIN(wh.Capacity)) AS minCapacity, 
        ROUND(AVG(wh.Capacity)) AS avgCapacity,
        ROUND(MAX(wh.Capacity)) AS maxCapacity,
        ROUND(MIN(wh.Temperature),1) AS minTemperature,
        CASE WHEN AVG(wh.Temperature) = 0 THEN '' ELSE ROUND(AVG(wh.Temperature),1) END AS avgTemperature,
        ROUND(MAX(wh.Temperature),1) AS maxTemperature
        FROM location
        LEFT JOIN household h ON location.PostalCode = h.PostalCode
        JOIN appliance a ON h.Email = a.Email
        JOIN waterheater wh ON a.Email = wh.Email and a.ApplianceId = wh.ApplianceId
        WHERE location.State = '" . $selected_option . "' GROUP BY wh.EnergySource ORDER BY wh.EnergySource ASC;";
        $result = mysqli_query($db, $sql);

        $energySource = "SELECT DISTINCT EnergySource FROM waterheater ORDER BY EnergySource ASC;";
        $energySourceResult = mysqli_query($db, $energySource);
        if ($selected_option == "") {
            echo "<p>Please select a state</p>";
        } else {
            if (!$energySourceResult) {
                echo "<p>Query is not working</p>";
            } else if (mysqli_num_rows($energySourceResult) > 0) {
                echo "<h3>" . $selected_option . "</h3>";
                echo "<table>";
                echo "<tr>";
                echo "<td>Energy source</td>";
                echo "<td>Minimum water heater capacity</td>";
                echo "<td>Average water heater capacity</td>";
                echo "<td>Maximum water heater capacity</td>";
                echo "<td>Minimum temperature setting</td>";
                echo "<td>Average temperature setting</td>";
                echo "<td>Maximum temperature setting</td>";
                echo "</tr>";
                if(!$result){
                    $row['EnergySource'] = "";
                } else {
                    $row = mysqli_fetch_assoc($result);
                }
                while ($energySourceRow = mysqli_fetch_assoc($energySourceResult)) {
                    echo "<tr>";
                    echo "<td>" . $energySourceRow["EnergySource"] . "</td>";
                    if ($row['EnergySource'] != $energySourceRow['EnergySource']) {
                        echo "<td>0</td>";
                        echo "<td>0</td>";
                        echo "<td>0</td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                    } else {
                        echo "<td>" . $row["minCapacity"] . "</td>";
                        echo "<td>" . $row["avgCapacity"] . "</td>";
                        echo "<td>" . $row["maxCapacity"] . "</td>";
                        echo "<td>" . $row["minTemperature"] . "</td>";
                        echo "<td>" . $row["avgTemperature"] . "</td>";
                        echo "<td>" . $row["maxTemperature"] . "</td>";
                        $row = mysqli_fetch_assoc($result);
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }

        ?>

    </form>

    <br>
    <br>
    <a href="View_Reports.php">Return to the View Reports</a>

    <br>
    <br>
    <a href="Main_Menu.php">Return to the main menu</a>
</div>
</html>
