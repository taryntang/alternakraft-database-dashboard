<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
class powerView{
    private $con,$email;
    public function __construct($db,$email){
        $this->con=$db;
        $this->email=$email;
    }
    // public function powerViewPage($entity){
    //     if ($entity==null){
    //         $entity=$this->getEntity();
    //     }
    // }
    // public function createPower($result){
    //     if($entity==null){
    //         $entity=$this->getEntity();
    //     }
    // }
    public function getEntity($email) {
        if(isset($_POST['delete_button'])) {
            $id = $_POST['delete_id'];
            $_SESSION['deleted_power_id']=$id;
            $query = "DELETE FROM powerGenerator WHERE PowerGeneratorId='$id' AND email='$email'";
            mysqli_query($this->con, $query);
            var_dump($_SESSION['deleted_power_id']);
            var_dump($_SESSION['index']);
       
        }else {
            $_SESSION['deleted_power_id'] = 0;
        }
        $query = "SELECT * FROM powerGenerator WHERE email='$email'";
        $result = mysqli_query($this->con, $query);
        $html = '<table class="table">';
        $count = 0;
        $html .= '<tr class="entity-row"><th>Num</th><th>Power Generation Type</th><th>Average</th><th>Storage</th><th>Delete</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            $count++;
            $id=$row["PowerGeneratorId"];
            $num = $count;
            $average = $row["AverageKWH"];
            $StorageKWh = $row["StorageKWh"];
            $PowerGenerationType = $row["PowerGenerationType"];

    $html .= '<tr id="row_' . $id .  '">';
        $html .= '<td>' . $id . '</td>';
        $html .= '<td>' . $PowerGenerationType . '</td>';
        $html .= '<td>' . $average . '</td>';
        $html .= '<td>' . $StorageKWh . '</td>';
        $html .= '<td>
            <form method="post" action="">
                <input type="hidden" name="delete_id" value="' . $id . '">
                <button type="submit" name="delete_button">Delete</button>
            </form>
        </td>';
        $html .= '</tr>';

    }

    $html .= '<tr>
        <td colspan="5" class="table-footer">';
            $html .= '<div style="float: right;">';
                $html .= '<a href="powergenerator.php" class="button">+ Add More Power</a>';
                $html .= '<form action="submission_complete.php" method="GET">';
                    $html .= '<input type="submit" name="finishButton" value="Finish" class="button">';
                    $html .= '</div>';
            $html .= '</form>';
            $html .= '</td>
    </tr>';


    $html .= '</table>';




return $html;

}

}?>