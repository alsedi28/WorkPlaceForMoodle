<?php

require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$sql_work = "SELECT nir_id, type FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

if($rs && $USER->profile['isTeacher'] === "1"){
    $update_record = new stdClass();
    $update_record->id=$id;
    $update_record->is_sign_teacher=1;
   
    $DB->update_record('nir_files',$update_record); 
    
    $user = $USER->id;
        
    $record = new stdClass();
    $record->user_id = (int)$user;
    $record->nir_id = (int)$rs->nir_id;
    $record->nir_type = $rs->type;
    $record->text = "Документ одобрен и подписан научным руководителем.";
    
    $DB->insert_record('nir_messages', $record, false);

    echo "Ok";
}
else{
    echo "Error";
}
?>