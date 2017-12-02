<?php

require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$sql_work = "SELECT * FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_records_sql($sql_work);

if(count($rs) !== 0 && $rs[$id]->user_id !== $USER->id){
    $update_record = new stdClass();
    $update_record->id=$id;
    $update_record->is_new=0;
   
    $DB->update_record('nir_files',$update_record); 
    echo "Ok";
}
else{
    echo "Error";
}
?>