<?php
require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$review = isset($_POST['review']) ? $_POST['review'] : "";
$mark = isset($_POST['mark']) ? (int)$_POST['mark'] : 0;

if($review === "" || $mark === 0 || !($mark >= 1 && $mark <= 5)){
    echo "Error";
}

$sql_work = "SELECT id, teacher_id FROM mdl_nir WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

if($rs && $USER->profile['isTeacher'] === "1" && $rs->teacher_id == $USER->id){
    $update_record = new stdClass();
    $update_record->id=$id;
    $update_record->review=$review;
    $update_record->mark=$mark;
   
    $DB->update_record('nir',$update_record); 

    echo "Ok";
}
else{
    echo "Error";
}
?>