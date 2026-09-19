<?php include("lib/header.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Search Manufacturer/Model</title>
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
        .highlight {
            background-color: lightgreen;
        }
    </style>

</head>
<div id="main_container">
    <h2>View & Search Manufacturer/Model</h2>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="text" name="search_parameter" placeholder="Enter search parameter" value="<?php echo isset($_GET['search_parameter']) ? $_GET['search_parameter'] : '' ?>">
        <input type="submit" value="Search">
    </form>

    <?php
    include("lib/common.php");

    if(isset($_GET['search_parameter'])) {
        $parameter = mysqli_real_escape_string($db, $_GET['search_parameter']);

        $sql = "SELECT DISTINCT ManufacturerName AS Manufacturer, ModelName AS Model
            FROM `Appliance`
            WHERE LOWER(ManufacturerName) LIKE LOWER('%$parameter%')
            OR LOWER(ModelName) LIKE LOWER('%$parameter%')
            ORDER BY ManufacturerName ASC, ModelName ASC;
        ";

        $result = mysqli_query($db, $sql);


        if (mysqli_num_rows($result) > 0) {
            echo "<h2>Search Results for: " . $parameter . "</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<td>ManufacturerName </td>";
            echo "<td>Model Name</td>";
            echo "</tr>";
            while($row = mysqli_fetch_assoc($result)){
                
                $manufacturer = $row["Manufacturer"];
                $model = $row["Model"];
                $manufacturer = str_ireplace($parameter, "<span class='highlight'>$parameter</span>", $manufacturer);
                $model = str_ireplace($parameter, "<span class='highlight'>$parameter</span>", $model);
                echo "<tr>";
                echo "<td>" . $manufacturer . "</td>";
                echo "<td>" . $model . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "There is no data available";
        }
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

