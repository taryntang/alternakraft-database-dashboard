
<?php 
include("lib/common.php");

$ID = mysqli_real_escape_string($db,$_GET['delete_ApplianceID']);
$_SESSION['delete_ApplianceID'] = $_GET['delete_ApplianceID'];
$email = mysqli_real_escape_string($db,$_GET['Email']);
$sql6="SELECT HeatingCoolingMethod FROM AirHandler WHERE ApplianceId = '$ID' AND Email = '$email'";
$result6 = mysqli_query($db, $sql6);
$myArray=mysqli_fetch_array($result6, MYSQLI_ASSOC);
foreach ($myArray as $value){
    if ($value=="Air conditioner"){$sql2="DELETE FROM AirConditioner WHERE AirHandlerId = '$ID' AND Email = '$email'";
        $result2 = mysqli_query($db, $sql2);
    }
    if ($value=="Heater"){
        $sql3="DELETE FROM Heater WHERE AirHandlerId = '$ID' AND Email = '$email'";
        $result3 = mysqli_query($db, $sql3);
    }
    if ($value=="HeatPump"){
        $sql4="DELETE FROM HeatPump WHERE AirHandlerId = '$ID' AND Email = '$email'";
        $result4 = mysqli_query($db, $sql4);
    }
}
$sql="DELETE FROM AirHandler WHERE ApplianceId = '$ID' AND Email = '$email'";
$result = mysqli_query($db, $sql);
$sql5="DELETE FROM WaterHeater WHERE ApplianceId = '$ID' AND Email = '$email'";
$result5 = mysqli_query($db, $sql5);
$sql1="DELETE FROM Appliance WHERE ApplianceId = '$ID' AND Email = '$email'";
$result1 = mysqli_query($db, $sql1);


if ($result1) {
    header('Location: View_appliance.php');
}

?>




