<?php include("lib/header.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Off-the-grid household dashboard</title>
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
	<h2>View Off-the-grid Household Dashboard</h2>
    <?php
    include("lib/common.php");
	
	echo "The number of off-the-grid household by state:" . "<br/>";
	
	$offTheGridQuery = "
	SELECT l.State, COUNT(*) AS CountOffTheGridHousehold
	FROM household h 
		LEFT JOIN utilitytype AS u on u.Email = h.Email
		JOIN location as l on l.PostalCode = h. PostalCode
	WHERE h.Email NOT IN (SELECT Email from utilitytype) 
	GROUP BY l.State
    ORDER BY CountOffTheGridHousehold DESC , l.State ASC Limit 1;
	";
	
	$result = mysqli_query($db, $offTheGridQuery);


    if(!$result){
        echo "<p>Query is not working</p>";
    }else if (mysqli_num_rows($result) > 0) {      
		echo "<table>";
        echo "<tr>";
        echo "<td>State</td>";
        echo "<td>Count of Off-the-grid Household</td>";
        echo "</tr>";
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>" . $row["State"] . "</td>";
            echo "<td>" . $row["CountOffTheGridHousehold"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "There is no data available";
    }	

		
	
	echo "<br/>";
	echo "Average battery storage capacity and percentage of each power generation type:";
	$offTheGridQuery = "
	SELECT SUM(p.StorageKWh) AS storageCapacity, 
	h.Email as email, 
	GROUP_CONCAT(DISTINCT p.PowerGenerationType) AS types
	FROM household AS h
		JOIN powergenerator AS p on p.Email = h.Email
		LEFT JOIN utilitytype AS u on u.Email = h.Email
	WHERE h.Email NOT IN (SELECT Email from utilitytype) 
	GROUP BY email;
	";

	$result = mysqli_query($db, $offTheGridQuery);
	

    if(!$result){
        echo "<p>Query is not working</p>";
    }else if (mysqli_num_rows($result) > 0) {      
		$countSolarElectric = 0;
		$countWind = 0;
		$countMixed = 0;
		$countHouseholdWithBatteryStorage = 0;
		$TotalBatteryStorage = 0;
		while($row = mysqli_fetch_assoc($result)){
            $storageCapacity = $row['storageCapacity'];
			if ($storageCapacity != NULL) {
				$countHouseholdWithBatteryStorage++;
				$TotalBatteryStorage += $storageCapacity;
			}
			
			$types = $row['types'];
			if ($types == "solar-electric") {
				$countSolarElectric++;
			} else if ($types == "wind") {
				$countWind++;
			} else {
				$countMixed++;
			}
        }
		$averStorageCapacity = round($TotalBatteryStorage / $countHouseholdWithBatteryStorage);
		$percentSolarElectric = round(100 * $countSolarElectric / ($countSolarElectric + $countWind + $countMixed), 1);
		$percentWind = round(100 * $countWind / ($countSolarElectric + $countWind + $countMixed), 1);
		$percentMixed = round(100 * $countMixed / ($countSolarElectric + $countWind + $countMixed), 1);
		echo "<table>";
        echo "<tr>";
		echo "<td>Average battery storage capacity</td>";
        echo "<td>Percentage of solar-electric</td>";
		echo "<td>Percentage of wind</td>";
		echo "<td>Percentage of mixed</td>";
        echo "</tr>";
        
		echo "<tr>";
		echo "<td>". $averStorageCapacity. "</td>";
		echo "<td>". $percentSolarElectric. "%</td>";
        echo "<td>". $percentWind. "%</td>";
        echo "<td>". $percentMixed. "%</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "There is no data available";
    }

	echo "<br/>";
	echo "Average water heater capacity for off-the-grid and on the grid households:";
	$offTheGridQuery = "
	SELECT
		ROUND(AVG(CASE WHEN h.Email NOT IN (SELECT Email from utilitytype) THEN w.Capacity END), 1) AS avgCapacityForOffTheGrid,
		ROUND(AVG(CASE WHEN h.Email IN (SELECT Email from utilitytype) THEN w.Capacity END), 1) AS avgCapacityForOnTheGrid
	FROM household AS h
		JOIN waterheater AS w on w.Email = h.Email
		LEFT JOIN utilitytype AS u on u.Email = h.Email;
	";
	
	$result = mysqli_query($db, $offTheGridQuery);

    if(!$result){
        echo "<p>Query is not working</p>";
    }else if (mysqli_num_rows($result) > 0) {      
		$row = mysqli_fetch_assoc($result);
		echo "<table>";
        echo "<tr>";
        echo "<td>Average Capacity of water heater of the off-the-grid household</td>";
        echo "<td>Average Capacity of water heater of the on-the-grid household</td>";
        echo "</tr>";
        
		echo "<tr>";
        echo "<td>". $row["avgCapacityForOffTheGrid"]. "</td>";
        echo "<td>". $row["avgCapacityForOnTheGrid"]. "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "There is no data available";
    }

	echo "<br/>";
	echo "Minimum, average, and maximum BTU rating by appliance type:";
	$offTheGridQuery = "
	SELECT 
		a.Name as applianceType,
		ROUND(MIN(a.BTURating)) as minBTURating,
		ROUND(AVG(a.BTURating)) as avgBTURating,
		ROUND(MAX(a.BTURating)) as maxBTURating
	FROM household AS h
		JOIN appliance AS a on a.Email = h.Email
		LEFT JOIN utilitytype AS u on u.Email = h.Email
	WHERE h.Email NOT IN (SELECT Email from utilitytype) 
	GROUP BY a.Name;	
	";
	
	$result = mysqli_query($db, $offTheGridQuery);

    if(!$result){
        echo "<p>Query is not working</p>";
    }else if (mysqli_num_rows($result) > 0) {      
		echo "<table>";
        echo "<tr>";
		echo "<td>Appliance Type</td>";
        echo "<td>Minimum BTU Rating</td>";
		echo "<td>Average BTU Rating</td>";
		echo "<td>Maximum BTU Rating</td>";
        echo "</tr>";
        
		while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
			echo "<td>". $row["applianceType"]. "</td>";
			echo "<td>". $row["minBTURating"]. "</td>";
			echo "<td>". $row["avgBTURating"]. "</td>";
			echo "<td>". $row["maxBTURating"]. "</td>";
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

