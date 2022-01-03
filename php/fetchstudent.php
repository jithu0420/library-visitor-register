<?php
include 'config_database.php';

// get the admno,time,date parameter from URL
$admission_no = $_REQUEST['admno'];
$time = $_REQUEST['time'];
$date = $_REQUEST['date'];

$sql = "select name from students where admission_no='$admission_no'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

if($row == null){
    $no_record = array("error"=>"No such record with admission no ".$admission_no);
    $noRecObj = json_encode($no_record);
    echo $noRecObj;
}
else{
    //before inserting or updating record in register table
    //checking if record is present
    $chk_rec = "select r_id from register where admission_no='$admission_no' and exit_time IS NULL and time_spent IS NULL";
    $chk_result = mysqli_query($conn,$chk_rec);
    $chk_row = mysqli_fetch_array($chk_result);
    
    if($chk_row==null){
        //this block will run since no records were found where exit_time & time_spent were null with $admission_no
        //create new record
        $ins_sql = "insert into register (admission_no, date, entry_time) values ('$admission_no','$date','$time')";
        $ins_result = mysqli_query($conn,$ins_sql);

        if($ins_result==1){
            $arr = array("name"=>$row["name"],"entry_time"=>$time);
            $myJSON = json_encode($arr);

            echo $myJSON;
        }
        else{
            $arr = array("error"=>"Could not insert record ! ".mysqli_error($conn));
            $myJSON = json_encode($arr);

            echo $myJSON;
        }
    }
    else{
        //this block will run since the student has entered entry time
        $register_id = $chk_row["r_id"];

        $time_sql = "select entry_time from register where r_id='$register_id'";
        $time_result = mysqli_query($conn,$time_sql);
        $time_row = mysqli_fetch_array($time_result);
        $entry_time = $time_row["entry_time"];

        //finding time spent in minutes
        $minutes = (strtotime($time)-strtotime($entry_time)) / 60;

        if($minutes<=1){
            $arr = array("error"=>"Please wait atleast 1 minute before exiting ");
            $myJSON = json_encode($arr);

            echo $myJSON;
        }
        else{
            //converting minutes into hh:mm format
            $converted_time = date('H:i',mktime(0,$minutes));

            $upd_sql = "update register set exit_time='$time', time_spent='$converted_time hr' where r_id='$register_id'";
            $upd_result = mysqli_query($conn,$upd_sql);

            if($upd_result==1){
                $sel_sql = "select * from register where r_id='$register_id'";
                $sel_result = mysqli_query($conn,$sel_sql);
            
                $sel_row = mysqli_fetch_array($sel_result);
            
                $arr = array("name"=>$row["name"],"entry_time"=>$sel_row["entry_time"],"exit_time"=>$sel_row["exit_time"]);
                $myJSON = json_encode($arr);

                echo $myJSON;
            }
            else{
                $arr = array("error"=>"Could not update record ! ".mysqli_error($conn));
                $myJSON = json_encode($arr);

                echo $myJSON;
            }
        }
        
    }
}

mysqli_close($conn);
?>