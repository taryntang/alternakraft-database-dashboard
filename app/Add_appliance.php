<?php 
include('../lib/common.php');

if (!isset($_SESSION['Email'])) {
    header('Location: Enter_household.php');
    exit();
}

$Email = $_SESSION['Email'];
$errors = [];
if (isset($_POST['nextButton'])) {
    $ModelName=$_POST["ModelName"];
    $ApplianceType=$_POST["ApplianceType"];
    $BTURating=$_POST["BTURating"];
    $ManufacturerName=$_POST["Manufacturer"];
    $Air_handler_types_arry=array();
 
    if (isset($_POST["ApplianceType"])){
        $query="SELECT MAX(ApplianceId)AS max_id  FROM Appliance WHERE Email= '$Email'" ;
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) > 0) { 
            $row_arry = mysqli_fetch_assoc($result);
            if(isset($_SESSION['delete_ApplianceID'])){ 
            if ($row_arry['max_id']>$_SESSION['delete_ApplianceID']){
                $index = $row_arry['max_id'];
            }else{
                $index = $_SESSION['delete_ApplianceID'];
                unset($_SESSION['delete_ApplianceID']);
            }
            }else{
                $index = $row_arry['max_id'];
            }
        }else{
            $index=0; 
        }
        if ($ApplianceType=="Air Handler"){ 
            $index++;
            $Name=mysqli_real_escape_string($db,$_POST["ApplianceType"]);
            $ApplianceId=$index;
            $ManufacturerName=mysqli_real_escape_string($db,$_POST["Manufacturer"]);
            $BTURating=mysqli_real_escape_string($db,$_POST["BTURating"]);
            $ModelName=mysqli_real_escape_string($db,$_POST["ModelName"]);
            $sql=  "INSERT INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) 
            VALUES ('$ApplianceId','$Name','{$_SESSION['Email']}', '$ModelName', '$BTURating', '$ManufacturerName')";
            $Result=mysqli_query($db,$sql);
        


            if (isset ($_POST['Airconditioner'])){ 
                $Air_handler_types_arry[] = $_POST['Airconditioner'];
                $AirHandlerId=$index;
                if(!isset($_POST['EnergyEfficiencyRatio'])){
                    $errors['EnergyEfficiencyRatio']  = "Please enter Energy Efficiency Ratio.";
                }else{
                $EnergyEfficiencyRatio=mysqli_real_escape_string($db,$_POST['EnergyEfficiencyRatio']);
                $sql=  "INSERT INTO Airconditioner (AirHandlerId,Email,EnergyEfficiencyRatio) 
                VALUES ('$AirHandlerId','$Email','$EnergyEfficiencyRatio')";
                $Result=mysqli_query($db,$sql);
                }
            }
                
            
            if (isset ($_POST['Heater'])){
                $Air_handler_types_arry[] = $_POST['Heater'];
                $AirHandlerId=$index;
                if(empty($_POST['HeaterEnergySource'])){
                    $errors['HeaterEnergySource']  = "Please enter Heater Energy Source.";	
                }else{
                $HeaterEnergySource=mysqli_real_escape_string($db,$_POST['HeaterEnergySource']);
                $sql=  "INSERT INTO Heater (AirHandlerId,Email,HeaterEnergySource) 
                VALUES ('$AirHandlerId','$Email','$HeaterEnergySource')";
                $Result=mysqli_query($db,$sql);
                }
            }
            
            if (isset ($_POST['Heatpump'])){
                $Air_handler_types_arry[] = $_POST['Heatpump'];
                $AirHandlerId=$index;
                $SeasonalEnergyEfficiencyRating=mysqli_real_escape_string($db,$_POST['SeasonalEnergyEfficiencyRating']);
                $HeatingSeasonalPerformanceFactor=mysqli_real_escape_string($db,$_POST['HeatingSeasonalPerformanceFactor']);
                $sql=  "INSERT INTO HeatPump (AirHandlerId,Email,SeasonalEnergyEfficiencyRating,HeatingSeasonalPerformanceFactor) 
                VALUES ('$AirHandlerId','$Email','$SeasonalEnergyEfficiencyRating','$HeatingSeasonalPerformanceFactor')";
                $Result=mysqli_query($db,$sql);

            }
            $Air_handler_types_string = implode(",", $Air_handler_types_arry);
            $HeatingCoolingMethod=$Air_handler_types_string;
            $sql1=  "INSERT INTO AirHandler (ApplianceId,Email,HeatingCoolingMethod) 
            VALUES ('$ApplianceId','$Email','$HeatingCoolingMethod')";
            $Result1=mysqli_query($db,$sql1);
            if ($Result1) {
                header('Location: View_appliance.php');
            }
            
        }
        if($ApplianceType=="Water Heater"){
            $index++;
            $Name=mysqli_real_escape_string($db,$_POST["ApplianceType"]);
            $ApplianceId=$index;
            $ManufacturerName=mysqli_real_escape_string($db,$_POST["Manufacturer"]);
            $BTURating=mysqli_real_escape_string($db,$_POST["BTURating"]);
            $ModelName=mysqli_real_escape_string($db,$_POST["ModelName"]);
            $sql=  "INSERT INTO Appliance (ApplianceId, Name, Email, ModelName, BTURating, ManufacturerName) 
            VALUES ('$ApplianceId','$Name','{$_SESSION['Email']}', '$ModelName', '$BTURating', '$ManufacturerName')";
            $Result=mysqli_query($db,$sql);

            $Capacity=mysqli_real_escape_string($db,$_POST["Capacity"]);
            $Temperature=mysqli_real_escape_string($db,$_POST["Temperature"]);
            $EnergySource=mysqli_real_escape_string($db,$_POST["EnergySource"]);
            $sql1=  "INSERT INTO WaterHeater(ApplianceId,Email,Capacity,Temperature,EnergySource) 
            VALUES ('$ApplianceId','{$_SESSION['Email']}','$Capacity','$Temperature','$EnergySource')";
            $Result1=mysqli_query($db,$sql1);
            if ($Result1) {
                header('Location: View_appliance.php');
            }
        }

    }
}
?>
        

<?php include("../lib/header.php"); ?>
<style>
fieldset{
    width: 130px;
}
input.right {
margin-left: 300px;  
}
 
</style>

<body>

    <div class="mainContainer">
        <div class="column">
            <form id="Addappliance" action="Add_appliance.php" method="POST">
                <h2> Appliances</h2>
                <span>Please provide the details for the appliance.</span>
                <br>
                <label for="ApplianceType"> Appliance Type: </label>
                <select id="ApplianceType" name="ApplianceType" required>  
                    <option value="">--Select--</option>
                    <option value="Air Handler">Air handler</option>
                    <option value="Water Heater">Water heater</option>   
                </select>
                <br>
                <label for="Manufacturer">Manufacturer:</label>
                <select name="Manufacturer" id="Manufacturer"required>
                    <option value="">--Select --</option>
                    <?php
                        $query = "SELECT Name FROM Manufacturer ORDER BY Name ASC";
                        $result = mysqli_query($db, $query);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo '<option value="' . $row['Name'] . '">' . $row['Name'] . '</option>';
                        }
                    ?>
                </select>
                <br>
                <div>
                    <label for="ModelName">Model Name:</label>
                    <input id = "ModelName" type="text" name="ModelName" placeholder="Model Name" size="30" required>
                </div>
                <br> 
                    <div>   
                        <label for="BTURating">BTU Rating:</label>
                        <input type="number" name="BTURating" placeholder="BTU Rating" min="1" max="10000000000" required>
                    </div>
                    <div id="waterHeaterFields" style="display:none;">
                        <div>
                            <hr>
                        </div>
                        <div >
                            <label for="Temperature">Temperature: </label>
                            <input type="number" name="Temperature" min="1" required >
                        </div>
                        
                        <br>
                        <div >
                            <label for="Capacity">Capacity(gallons): </label>
                            <input type="number" name="Capacity" step="0.01" required >
                        </div>
                        <br>
                        <div>
                            <label for="EnergySource"> Energy Source : </label>
                            <select id="EnergySource" name="EnergySource" required>  
                                <option value="">--Select--</option>
                                <option value="electric">Electric</option>
                                <option value="gas">Gas</option>  
                                <option value="thermosolar">Thermosolar</option>  
                                <option value="heat pump">heat pump</option>   
                                </select>
                        </div>
                        <input type="submit" name="nextButton" value="Next" class="right" >
                    </div> 
            
                    <br>
                    <div id="airHandlerFields" style="display:none;">
                        <div>
                            <hr>
                        </div>
                        <div>
                            <fieldset>
                                <div>  
                                    <input type="checkbox" id="Air conditioner" name="Airconditioner" value="Air conditioner"/>
                                    <label for="Air conditioner"> Air conditioner </label> 
                                    
                                </div>

                                <div>
                                    <input type="checkbox" id="Heater" name="Heater" value="Heater"/>
                                    <label for="Heater">Heater</label>
                                    
                                </div>

                                <div>
                                    <input type="checkbox" id="Heat pump" name="Heatpump" value="Heat pump"/>
                                    <label for="Heat pump">Heat pump</label>
                
                                </div>

                            </fieldset>
                        </div>                           
                        <br>

                        <div>
                            <label for="EnergyEfficiencyRatio">Energy efficiency ratio: </label>
                            <input type="number" name="EnergyEfficiencyRatio" min="0" max="10000000000">
                            <div class="error"><?php echo $errors['EnergyEfficiencyRatio'];?></div><br>
                            <br>  
                        </div>  
                        
                        <div>
                            <label for="SeasonalEnergyEfficiencyRating">Seasonal Energy Efficiency Rating: </label>
                            <input type="number" name="SeasonalEnergyEfficiencyRating" min="0" max="10000000000" >  
                        </div>
                            <br> 
                        <div> 
                            <label for="HeatingSeasonalPerformanceFactor">Heating Seasonal Performance Factor: </label>
                            <input type="number" name="HeatingSeasonalPerformanceFactor" min="0" max="10000000000">
                        </div>

                        <br>
                        <div>
                          <label for="HeaterEnergySource"> Energy Source : </label>
                            <select name="HeaterEnergySource">  
                                <option value="">--Select--</option>
                                <option value="electric">Electric</option>
                                <option value="gas">Gas</option>  
                                <option value="fuel oil">Fuel Oil</option>    
                            </select>
                            <div class="error"><?php echo $errors['HeaterEnergySource'];?></div><br>
                        </div>
                        <input type="submit" name="nextButton" value="Next" class="right" >
                        </div>
                    </div>
                
                
            </form>
                

        </div>
     
    </div>
    <script>
                document.getElementById("ApplianceType").addEventListener("change", function() {
                    let applianceType = this.value;
                    let waterHeaterFields = document.getElementById("waterHeaterFields");
                    let airHandlerFields = document.getElementById("airHandlerFields");
                    const temperatureInput = document.querySelector('input[name="Temperature"]');
                    const CapacityInput = document.querySelector('input[name="Capacity"]');
                    const energySourceSelect = document.querySelector('select[name="EnergySource"]')
                    

                    

                    if (applianceType === "Water Heater") {
                        waterHeaterFields.style.display = "block";
                        airHandlerFields.style.display = "none";
                        temperatureInput.setAttribute('required', '');
                        CapacityInput.setAttribute('required', '');
                        energySourceSelect.setAttribute('required', '');

  
                    } else if (applianceType === "Air Handler") {
                        waterHeaterFields.style.display = "none";
                        airHandlerFields.style.display = "block";
                        temperatureInput.removeAttribute('required');
                        CapacityInput.removeAttribute('required');
                        energySourceSelect.removeAttribute('required');
                    } else {
                        waterHeaterFields.style.display = "none";
                        airHandlerFields.style.display = "none";
  
                    }
                });
                document.addEventListener('DOMContentLoaded', function() {
                const applianceTypeSelect = document.getElementById("ApplianceType");
                const airConditionerCheckbox = document.getElementById('Air conditioner');
                const heaterCheckbox = document.getElementById('Heater');
                const heatPumpCheckbox = document.getElementById('Heat pump');
                const EERInput = document.querySelector('input[name="EnergyEfficiencyRatio"]');
                const SEERInput = document.querySelector('input[name="SeasonalEnergyEfficiencyRating"]');
                const HSPFInput = document.querySelector('input[name="HeatingSeasonalPerformanceFactor"]');
                const HeaterEnergySourceSelect= document.querySelector('select[name="HeaterEnergySource"]');
                var form = document.getElementById('Addappliance');
                
                
                function updateFieldsRequired() {
                    EERInput.required = airConditionerCheckbox.checked;
                    SEERInput.required=heatPumpCheckbox.checked;
                    HSPFInput.required=heatPumpCheckbox.checked;
                    HeaterEnergySourceSelect.required=heaterCheckbox.checked;
                }

                function validateForm(e) {
        
                if (applianceTypeSelect.value=="Air Handler"&&!airConditionerCheckbox.checked && !heaterCheckbox.checked && !heatPumpCheckbox.checked) {
                alert("Please check at least one checkbox.");
                 e.preventDefault()
                }
            }
        
                airConditionerCheckbox.addEventListener('change', updateFieldsRequired);
                heaterCheckbox.addEventListener('change', updateFieldsRequired);
                heatPumpCheckbox.addEventListener('change', updateFieldsRequired);
                updateFieldsRequired();

                form.addEventListener('submit', validateForm);

     updateFieldsRequired();
});

</script>

</body>

</html>


