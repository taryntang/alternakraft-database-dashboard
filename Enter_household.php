<?php 
include('lib/common.php');
$errors=array('Email'=>"",'PostalCode'=>"",'heatCheckbox'=>'','coolCheckbox'=>"");


if (isset($_POST['nextButton'])) {
            $Email =$_POST['Email'];
            $PostalCode =$_POST['PostalCode'];  
            $HeatTem=$_POST['HeatTem'];
            $CoolTem = $_POST['CoolTem'];
            $UtilityType_array=array();
            if ( !empty($Email)){
                $query="SELECT Email FROM HouseHold WHERE Email= '$Email'";
                $result = mysqli_query($db, $query);
                if (mysqli_num_rows($result) > 0) {
                        $errors['Email']  = "This email address is already in use. Please try again.";
                    } else { 
                        $_SESSION['Email'] = $_POST['Email'];
                        $Email =mysqli_real_escape_string($db, $_POST['Email']);
                    }
            }else{
                $errors['Email']  = "Email is required";
            }

            if ( !empty($PostalCode)){
                $query="SELECT PostalCode FROM Location WHERE PostalCode= '$PostalCode'";
                $result = mysqli_query($db, $query);
                if (mysqli_num_rows($result) >0) {
                    $PostalCode = mysqli_real_escape_string($db,$_POST['PostalCode']);  
                }else{
                        $errors['PostalCode']="Please enter a valid postal
                        code."; 
                    }
            }else{
                $errors['PostalCode']  = "Postal Code  is required";
            }
            if (empty($_POST['HeatTem'])) {
                if ($_POST['heating'] !== 'NULL'){
                $errors['heatCheckbox'] = 'Please select "No Heat" checkbox or put in a temperature.';
                } else {
                $HeatTem = NULL;
                }
            }else{
                $HeatTem=mysqli_real_escape_string($db, $_POST['HeatTem']);
            }
        
            if (empty($_POST['CoolTem'])) {
                if ($_POST['cooling'] !== 'NULL'){
                $errors['coolCheckbox'] = 'Please select "No Cooling" checkbox or put in a temperature.';
                } else {
                    $CoolTem = NULL;
                }
            }else{    
                $CoolTem = mysqli_real_escape_string($db, $_POST['CoolTem']);
            }
            if (isset ($_POST['Electric'])){
                $UtilityType_array[] = $_POST['Electric'];
            }
            if (isset ($_POST['Gas'])){
                $UtilityType_array[] = $_POST['Gas'];
            }
            if (isset ($_POST['Steam'])){
                $UtilityType_array[] = $_POST['Steam'];
            }
            if (isset($_POST['FuelOil'])){
                $UtilityType_array[] = $_POST['FuelOil'];
            }
            $UtilityType_string = implode(",", $UtilityType_array);
            
                         

    if (array_filter($errors)){
        //echo 'erros in the form';
    }else{
        $HouseType =mysqli_real_escape_string($db, $_POST['HouseType']);
        $HouseSize = mysqli_real_escape_string($db,$_POST['HouseSize']);
        $UtilityType =mysqli_real_escape_string($db,$UtilityType_string);
        
        try{
            $sql="INSERT INTO HouseHold (Email,PostalCode,HouseSize, HouseType,CoolingThermostatTemp, HeatingThermostatTemp) 
            VALUES ('$Email', '$PostalCode', '$HouseSize', '$HouseType', " . ($CoolTem !== NULL ? "'$CoolTem'" : "NULL") . ", " . ($HeatTem !== NULL ? "'$HeatTem'" : "NULL") . ")";
            }catch (mysqli_sql_exception $Email) {
                $errors['emailErr'] = "This email address is already in use. Please try again.";
            }
            $sql1 = "INSERT INTO UtilityType (Email, UtilityType) VALUES ('$Email', '$UtilityType')";
        //Save  to db
        $Result=mysqli_query($db,$sql);
        $Result1 = mysqli_query($db, $sql1);
    
        // echo 'form is valid';
        if ($Result && $Result1) { 
        header('Location: Add_appliance.php');
        }
    
    }
}
?>
        

<?php include("lib/header.php"); ?>

<body>

    <div class="mainContainer">

        <div class="column">
            <form action="Enter_household.php" method="POST">
                <h2>Enter household info</h2>
                <input type="email" name="Email" placeholder="Email" required><br>
                <div class="error"><?php echo $errors['Email'];?></div><br>

                <input type="number" name="PostalCode" placeholder="Postal Code" required><br>
                <div class="error"><?php echo $errors['PostalCode'];?></div>
                <br>
                <label for="HouseType">Home Type:</label>
                <select id="home" name="HouseType">
                    <option value="">--Select --</option>
                    <option value="House">House</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Townhome">Townhome</option>
                    <option value="Condominium">Condominium</option>
                    <option value="mobilehome">Mobilehome</option>
                </select>
                <br>
                <label for="HouseSize">Square footage:</label>
                <input type="number" name="HouseSize" min="1" max="10000000000" placeholder="HouseSize"
                    required>
                <div>
                <br>

                    <label for="HeatTem"> Thermostat setting for heating:</label>
                    <input type="number" name="HeatTem" min="1" max="10000000000" placeholder="heating">
                    <label for="heting"> No heat</label>
                    <input type="checkbox" id="heating" name="heating" value="NULL">
                    <div class="error"><?php echo $errors['heatCheckbox'];?></div><br>
                </div>
                <br>
                <div>
                    <label for="CoolTem"> Thermostat setting for heating:</label>
                    <input type="number" name="CoolTem" min="1" max="10000000000" placeholder="cooling">
                    <label for="cooling"> No cooling </label>
                    <input type="checkbox" id="cooling" name="cooling" value="NULL">
                    <div class="error"><?php echo $errors['coolCheckbox'];?></div><br>
                </div>
                <br>
                <fieldset>
                    <legend>Public utilities:
                        (If none,leave unchecked)
                    </legend>
                    <div>
                        <input type="checkbox" id="Electric" name="Electric" value="electric" />
                        <label for="Electric">Electric</label>
                    </div>
                    <div>
                        <input type="checkbox" id="Gas" name="Gas" value="gas" />
                        <label for="Gas">Gas</label>
                    </div>
                    <div>
                        <input type="checkbox" id="Steam" name="Steam" value="steam" />
                        <label for="Steam">Steam</label>
                    </div>
                    <div>
                        <input type="checkbox" id="FuelOil" name="FuelOil" value="fuel oil" />
                        <label for="FuelOil">Fuel Oil</label>
                    </div>
                </fieldset>

                <input type="submit" name="nextButton" value="Next" class="right">
            </form>
        </div>
     
    </div>

</body>

</html>


