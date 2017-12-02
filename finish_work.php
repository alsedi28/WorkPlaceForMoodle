<?php

require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$user = $USER->id;
$sql_work = "SELECT id FROM mdl_nir WHERE id=".$id." AND teacher_id=".$user;
$rs = $DB->get_record_sql($sql_work);

if($rs && $USER->profile['isTeacher'] === "1"){
    $update_record = new stdClass();
    $update_record->id=$id;
    $update_record->is_closed=1;
   
    $DB->update_record('nir',$update_record); 

    echo "Ok";
}
else{
    echo "Error";
}
?>