<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("lib/common.php");
require_once("assets/includes/classes/powerview.php");
if(!isset($_SESSION['Email'])){
    header("Location:powergenerator.php");
    exit();
}
$email=$_SESSION['Email'];
$powerView=new powerView($db,$email);
// echo $powerView->powerViewPage(null);
$entities_html = $powerView->getEntity($email);


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
        <div class="column">
            <!-- <div class="column" style="width: 10%; text-align: right;">
            <input type="submit" name="previousButton" value="Previous">
    </div>
    <div class="column" style="width: 10%; text-align: right;">
        <input type="submit" name="nextButton" value="Next">
    </div> -->

            <h2>Power Generation</h2>
            <p>You have added these to your household:</p>

            <?php echo $entities_html; ?>
            <!-- display the entities in HTML -->





        </div>
    </div>
</body>

</html>