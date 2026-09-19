<?php 
include('lib/common.php');
if (isset($_POST['nextButton'])) {
    $sql = "SELECT *
    FROM Appliance
    WHERE Email = '{$_SESSION['Email']}' ";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0){
        header('Location: powergenerator.php');
}else{
    $errors['EmptyAppliance']  = " Please add at least one appliance.";
}
}
?>
<?php include("lib/header.php"); ?>
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
        .right {  
        margin-bottom: 10;    
        margin-left: 500px;  
        }
        .rightbutton {  
        margin-bottom: 10;    
        margin-left: 520px;  
        }
        .nextbutton form input[type="submit"] {
        background-color: #4285f4;
        color: #fff;
        height: 36px;
        width: 90px;
        border: none;
        border-radius: 3px;
        font-weight: 500;
        margin-top: 10px;
        margin-bottom: 20px;
        font-size: 16px;
    }

    </style>

</head>
<div id="main_container">
    <div class="nextbutton">
    <h1>Appliances</h1>
    <span>You have added the following appliances to your household.</span>
    <br>
    <br>
        <?php
            $sql = "SELECT ApplianceID,Name, ManufacturerName, ModelName,Email
            FROM Appliance
            WHERE Email = '{$_SESSION['Email']}' ";
            $result = mysqli_query($db, $sql);


            if (mysqli_num_rows($result) > 0){
                echo "<table>";
                echo "<tr>";
                echo "<td>Appliance ID</td>";
                echo "<td>Name</td>";
                echo "<td> Manufacturer Name</td>";
                echo "<td>Model Name</td>";
                echo"<td></td>";
                echo "</tr>";
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>" . $row["ApplianceID"] . "</td>";
                echo "<td>" . $row["Name"] . "</td>";
                echo "<td>" . $row["ManufacturerName"] . "</td>";
                echo "<td>" . $row["ModelName"] . "</td>";
                echo "<td> <a href='Delete_appliance.php?delete_ApplianceID=".
                urlencode($row['ApplianceID']). "&Email=".urlencode($row['Email']). "'>delete</a> </td>";
            }
            echo "</table>";

            } else {
                echo "There is no data available";
                echo"<br>";
            }
        ?>
        <br>
        <br>
        <a href="Add_appliance.php"class='right'>+Add Another Appliance</a>
        <br>
            <form action="View_appliance.php" method="POST">
                <input type="submit" name="nextButton" value="Next" class="rightbutton" >
            
            </form>     
        </div>
        </div>
    
</div>
</html>

