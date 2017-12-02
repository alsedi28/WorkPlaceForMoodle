<?php

require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$sql_work = "SELECT nir_id, type, is_sign_kaf FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

$message = "";
$result = "Ok cancel sign document";

if($rs && $USER->profile['isTeacher'] === "666"){
    
    if($rs->is_sign_kaf == 1){
        $update_record = new stdClass();
        $update_record->id=$id;
        $update_record->is_sign_kaf=0;
        
        $DB->update_record('nir_files',$update_record);
        
        $message = "Подпись отменена.";
    }
    else{
        
        $update_record = new stdClass();
        $update_record->id=$id;
        $update_record->is_sign_teacher=0;
        
        $DB->update_record('nir_files',$update_record);
        
        $message = "Документ отклонён.";
        
        $result = "Ok cancel document";
    }
    
    $user = $USER->id;
        
    $record = new stdClass();
    $record->user_id = (int)$user;
    $record->nir_id = (int)$rs->nir_id;
    $record->nir_type = $rs->type;
    $record->text = $message;
    
    $DB->insert_record('nir_messages', $record, false);

    echo $result;
}
else{
    echo "Error";
}
?>