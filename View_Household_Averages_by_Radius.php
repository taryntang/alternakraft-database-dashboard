<?php include("lib/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>View Household Average by Radius</title>
    <style>
        a, h1, h2, h3, label{
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
    <h1>View Household Average by Radius</h1> <br>
    <h3> Please input the postal code and search radius:</h3>
	<form name="searchform" action="View_Household_Averages_by_Radius.php" method="POST">
		
		<div>   
            <label for="postal_code">Postal Code: </label>
            <input type="text" name="postal_code" size="30" required>
        </div> <br>
		
		<div> 
			<label for="search_radius"> Search Radius: </label>
			<select id="radius" name="radius" required>  
				<option value="0">0</option>
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="100">100</option>
				<option value="250">250</option> 
            </select> 
		</div> <br>
		        
		<div>
            <input type="submit" name="search" value="search" class='left'> 
        </div> <br> <br>
		
		<?php

			include('lib/common.php');   

			/* if form was submitted, then execute query to search for households */
			if (isset($_POST['search'])) {
				$postal_code = $_POST['postal_code']; 
				$radius = $_POST['radius'];
				if (!empty($postal_code)){
					$query = "SELECT PostalCode, Longitude AS lon1, Latitude AS lat1 FROM Location WHERE Location.PostalCode= '$postal_code'";
					$result = mysqli_query($db, $query);
					if (mysqli_num_rows($result) >0) {
						$row = mysqli_fetch_assoc($result);
						$lon1 = $row['lon1'];
						$lat1 = $row['lat1'];
						$sql = "";
						if ($radius == 0) {
							$sql = "
							SELECT
							COUNT(DISTINCT h.Email) AS countOfHousehold,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'apartment' THEN h.Email END)) AS countOfApartment,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'condominium' THEN h.Email END)) AS countOfCondominium,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'house' THEN h.Email END)) AS countOfHouse,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'mobile home' THEN h.Email END)) AS countOfMobileHome,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'townhome' THEN h.Email END)) AS countOfTownhome,
							ROUND(AVG(h.HouseSize)) AS averageHouseSize,
							ROUND(AVG(h.HeatingThermostatTemp)) as averageHeatingTemp,
							ROUND(AVG(h.CoolingThermostatTemp)) as averageCoolingTemp,
							GROUP_CONCAT(DISTINCT u.UtilityType) AS utilityTypes,
							COUNT(DISTINCT u.Email) AS countOfOffTheGrid,
							COUNT(DISTINCT p.Email) as countOfHouseholdWithPowerGeneration,
							(
								SELECT pg.PowerGenerationType 
								FROM powergenerator AS pg
								GROUP BY pg.PowerGenerationType 
								ORDER BY COUNT(*) DESC 
								LIMIT 1
							) AS mostCommonGenerationMethod,
							ROUND(AVG(p.AverageKWH)) AS averageOfPowerGeneration,
							COUNT(DISTINCT (CASE WHEN p.StorageKWh IS NOT NULL THEN p.Email END)) AS countOfHouseholdWithBatteryStorage	
						FROM household AS h
							LEFT JOIN utilitytype AS u on h.Email = u.Email
							LEFT JOIN powergenerator AS p on h.Email = p.Email
							JOIN location AS lc on h.PostalCode = lc.PostalCode
						WHERE
							h.PostalCode = $postal_code
						";
						} else {
							$sql = "
						SELECT
							COUNT(DISTINCT h.Email) AS countOfHousehold,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'apartment' THEN h.Email END)) AS countOfApartment,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'condominium' THEN h.Email END)) AS countOfCondominium,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'house' THEN h.Email END)) AS countOfHouse,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'mobile home' THEN h.Email END)) AS countOfMobileHome,
							COUNT(DISTINCT (CASE WHEN h.HouseType = 'townhome' THEN h.Email END)) AS countOfTownhome,
							ROUND(AVG(h.HouseSize)) AS averageHouseSize,
							ROUND(AVG(h.HeatingThermostatTemp)) as averageHeatingTemp,
							ROUND(AVG(h.CoolingThermostatTemp)) as averageCoolingTemp,
							GROUP_CONCAT(DISTINCT u.UtilityType) AS utilityTypes,
							COUNT(DISTINCT u.Email) AS countOfOffTheGrid,
							COUNT(DISTINCT p.Email) as countOfHouseholdWithPowerGeneration,
							(
								SELECT pg.PowerGenerationType 
								FROM powergenerator AS pg
								GROUP BY pg.PowerGenerationType 
								ORDER BY COUNT(*) DESC 
								LIMIT 1
							) AS mostCommonGenerationMethod,
							ROUND(AVG(p.AverageKWH)) AS averageOfPowerGeneration,
							COUNT(DISTINCT (CASE WHEN p.StorageKWh IS NOT NULL THEN p.Email END)) AS countOfHouseholdWithBatteryStorage	
						FROM household AS h
							LEFT JOIN utilitytype AS u on h.Email = u.Email
							LEFT JOIN powergenerator AS p on h.Email = p.Email
							JOIN location AS lc on h.PostalCode = lc.PostalCode
						WHERE
							(3958.75 * 2 * ATAN2(SQRT(POWER(SIN((RADIANS(lc.Latitude) - RADIANS($lat1)) / 2), 2) 
													+ COS(RADIANS($lat1)) 
													* COS(RADIANS(lc.Latitude)) 
													* POWER(SIN((RADIANS(lc.Longitude) - RADIANS($lon1))/ 2), 2)), 
												SQRT(1 - (POWER(SIN((RADIANS(lc.Latitude) - RADIANS($lat1)) / 2), 2) 
														+ COS(RADIANS($lat1)) 
														* COS(RADIANS(lc.Latitude)) 
														* POWER(SIN((RADIANS(lc.Longitude) - RADIANS($lon1))/ 2), 2))))) 
							
							< $radius
						";
						}
						
						$sql_result = mysqli_query($db, $sql);
						if(!$sql_result){
							echo "<p>Query is not working</p>";
						} else if (mysqli_num_rows($sql_result) > 0) {
				
							echo "<table>";
							echo "<tr>";
							echo "<td>Postal code</td>";
							echo "<td>Search radius</td>";
							echo "<td>Count of households</td>";
							echo "<td>Count of households with apartment </td>";
							echo "<td>Count of households with condominium</td>";
							echo "<td>Count of households with house</td>";
							echo "<td>Count of households with mobile home</td>";
							echo "<td>Count of households with townhome</td>";
							echo "<td>Average house size</td>";
							echo "<td>Average heating temperature</td>";
							echo "<td>Average cooling temperature</td>";
							echo "<td>Public utilities</td>";
							echo "<td>Count of off-the-grid households</td>";
							echo "<td>Count of households with power generator</td>";
							echo "<td>Most common generation method</td>";
							echo "<td>Average monthly power generation</td>";
							echo "<td>Count of households with battery stoarge</td>";
							echo "</tr>";
							while ($row = mysqli_fetch_assoc($sql_result)) {
                                $Utility ="";
                                $count = 0;
                                $typeUtility = ['electric','gas','steam','fuel oil'];
                                for ($i = 0; $i < count($typeUtility); $i++){
                                    if(strpos( $row['utilityTypes'], $typeUtility[$i] )){
                                        if($count > 0){
                                            $Utility .=",";
                                        }
                                        $Utility .= $typeUtility[$i];
                                        $count++;
                                    }
                                }

								echo "<tr>";
								echo "<td>". $postal_code. "</td>";
								echo "<td>". $radius. "</td>";
								echo "<td>". $row['countOfHousehold']. "</td>";
								echo "<td>". $row['countOfApartment']. "</td>";	
								echo "<td>". $row['countOfCondominium']. "</td>";	
								echo "<td>". $row['countOfHouse']. "</td>";	
								echo "<td>". $row['countOfMobileHome']. "</td>";	
								echo "<td>". $row['countOfTownhome']. "</td>";		
								echo "<td>". $row['averageHouseSize']. "</td>";	
								echo "<td>". $row['averageHeatingTemp']. "</td>";	
								echo "<td>". $row['averageCoolingTemp']. "</td>";	
								echo "<td>". $Utility. "</td>";
								echo "<td>". $row['countOfOffTheGrid']. "</td>";	
								echo "<td>". $row['countOfHouseholdWithPowerGeneration']. "</td>";	
								echo "<td>". $row['mostCommonGenerationMethod']. "</td>";	
								echo "<td>". $row['averageOfPowerGeneration']." </td>";	
								echo "<td>". $row['countOfHouseholdWithBatteryStorage']. "</td>";	
								echo "</tr>";
							}
							echo "</table>";
						} else {
							echo "<p>No data returned for selected state: " . $selected_option."</p>";
						}
						
					}else{
						echo "Please enter a valid postal code."; 
					}
				}else{
					echo "Postal Code  is required.";
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

