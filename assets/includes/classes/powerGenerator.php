<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['index']=0;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class powerGenerator{
    private $con;
    public $errorArray = array();
    public $monthlykWhEmpty ;
    public $storagekWhEmpty ;
    public $unitiltyEmpty ;

    
    public function __construct($db){
        $this->con=$db;  
        $this->monthlykWhEmpty = false;
        $this->storagekWhEmpty = false;
        $this->unitiltyEmpty=false;
    
      
    }
    public function validateStorageKWh($storagekWh){
        if(empty($storagekWh)){
            $this->storagekWhEmpty = true; 
            $this->errorArray[] = "Storage kWh can't be null";
        }
    }
    public function validateMonthKWh($monthlykWh) {
        if (empty($monthlykWh)) {
            $this->monthlykWhEmpty = true;
            $this->errorArray[] = "Monthly kWh can't be null";
        }
    }
    public function getErrors(){
        return $this->errorArray;
    }
    public function register($powerType, $monthlykWh, $storagekWh,$email){
        $this->errorArray = array(); // reset errors
        $this->validateMonthKWh($monthlykWh);
        $this->validateStorageKWh($storagekWh);
        $query1="SELECT MAX(PowerGeneratorId) AS max_id FROM PowerGenerator WHERE email= '$email'" ;
        $result1 = mysqli_query($this->con, $query1);
        $deletedPowerId = $_SESSION['deleted_power_id'] ?? 0;
       
        if (mysqli_num_rows($result1) > 0) { 
          
            $row_arry = mysqli_fetch_assoc($result1);
            if ($row_arry['max_id'] > $deletedPowerId){
                $_SESSION['index'] =  (int) $row_arry['max_id'];
                
            }else{
                $_SESSION['index'] = (int) $deletedPowerId;
                unset($_SESSION['deleted_power_id']);
            }
        } else {
            $_SESSION['index'] = $_SESSION['index'] ?? 0;
        }
        if (empty($this->errorArray)) {
            return $this->insertPowerDetails($powerType, $monthlykWh, $storagekWh, $email);
        }
        
        return false;
    }
    public function insertPowerDetails($powerType, $monthlykWh, $storagekWh,$email) {
        $sessionindex = ((int) ($_SESSION['index'] ?? 0)) + 1;
        $PowerGeneratorId = $sessionindex;
        $query = "INSERT INTO PowerGenerator (Email,PowerGeneratorId,PowerGenerationType,AverageKWH,StorageKWh) VALUES('$email','$PowerGeneratorId','$powerType','$monthlykWh','$storagekWh')";
    
        return mysqli_query($this->con,$query);
    }
    public function getUtility($email){
        $this->errorArray = array();
        $query="SELECT * FROM UtilityType WHERE Email='$email'";
        return mysqli_query($this->con,$query);
    }
    public function getEmail($email){
        $query="SELECT * FROM HouseHold WHERE Email='$email'";
        return mysqli_query($this->con,$query);
    }
}
?>