<?php 
if (!isset($_SESSION)) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("lib/common.php");
require_once("assets/includes/classes/powerGenerator.php");
$monthlykWhEmpty = false;
$storagekWhEmpty = false;
$errors = [];
$power = new powerGenerator($db);

if (!isset($_SESSION['Email'])) {
    header("Location: Enter_household.php");
    exit();
}

$email = $_SESSION['Email'];
$utility = $power->getUtility($email);
$emailExists = $power->getEmail($email);
$hasError = false;
// if ($emailExists && $emailExists->num_rows > 0) {
//     header("Location: powergenerator.php");
//     exit();
// } 

if(isset($_POST["addButton"])){
    $email=$_SESSION['Email'];
    $monthlykWh=mysqli_real_escape_string($db,$_POST['monthlykWh']);
    $storagekWh=mysqli_real_escape_string($db,$_POST['storagekWh']);
    $powerType=mysqli_real_escape_string($db,$_POST['power']);
 
    $success = $power->register($powerType, $monthlykWh, $storagekWh, $email);
    $errors = $power->getErrors();
    
    if (empty($errors)) {
        header("Location: powerview.php");
        exit();
    } 
}

    
if (isset($_POST["skipButton"])) {
    if ($utility && $utility->num_rows > 0 && $emailExists && $emailExists->num_rows > 0) {
        header("Location: powerview.php");
        exit();
    } else {
        $hasError = true;
    }
}
if ($hasError) {
    $error_message = "you must have at least one utility to skip";
    header("Location: powergenerator.php?error_message=$error_message");
    exit;
}


?>
<!DOCTYPE html>
<html>

<head>


    <link rel="stylesheet" type="text/css" href="style/style.css" />

</head>

<body>
    <div>
        <img src="images/Alternakraft.png" title="logo" alt="site logo" />
    </div>
    <div class="mainContainer">
        <div class="column" style="width: 30%;">
            <form method="POST">
                <label for="powerType">Add power generation</label>
                <span style="font-size: 14px;">Please provide power generation details.</span><br>

                <label for="powerType">Type:</label>
                <select id="power" name="power">
                    <option value="solar-electric">solar-electric</option>
                    <option value="wind">wind</option>
                </select>

                <label for="monthlykWh">Monthly kWh:</label>

                <input type="number" id="monthlykWh" name="monthlykWh" min="0" placeholder="monthlykWh"
                    <?php if ($monthlykWhEmpty) echo ' class="error"'; ?>>
                <?php
    foreach ($errors as $error) {
        if (strpos($error, 'Monthly kWh') !== false) {
            echo '<span class="error-message">' . $error . '</span>';
            break;
        }
    }
?>

                <label for="storagekWh">Storage
                    kWh:</label>

                <input type="number" id="storagekWh" name="storagekWh" min="0" placeholder="storagekWh"
                    <?php if ($storagekWhEmpty) echo ' class="error"'; ?>>
                <?php
    foreach ($errors as $error) {
        if (strpos($error, 'Storage kWh') !== false) {
            echo '<span class="error-message">' . $error . '</span>';
            break;
        }
    }
?>
                <!-- -->
                <?php
if (isset($_GET['error_message'])) {
    echo '<p class="error-message">' . $_GET['error_message'] . '</p>';
}
?>

                <input type="submit" name="skipButton" value="Skip">
                <input type="submit" name="addButton" value="add">
            </form>

        </div>
        <!-- <div class="column" style="width: 10%; text-align: right;">
            <input type="submit" name="previousButton" value="Previous">
        </div>
        <div class="column" style="width: 10%; text-align: right;">
            <input type="submit" name="nextButton" value="Next">
        </div> -->
    </div>

</body>

</html>