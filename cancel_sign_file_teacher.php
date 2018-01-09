<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/helpers.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$date = isset($_POST['date']) ? $_POST['date'] : "";

if($id === 0 || $date === ""){
    echo "Error";
    exit();
}

$sql_work = "SELECT nir_id, type, is_sign_teacher FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

if($rs && $USER->profile['isTeacher'] === "1"){
    
    if($rs->is_sign_teacher == 1){
        $update_record = new stdClass();
        $update_record->id=$id;
        $update_record->is_sign_teacher=0;
        
        $DB->update_record('nir_files',$update_record);
        
        $message = "Подпись научного руководителя отменена.";
        
        $user_id = $USER->id;
        
        $record = new stdClass();
        $record->user_id = $user_id;
        $record->nir_id = $rs->nir_id;
        $record->nir_type = $rs->type;
        $record->text = $message;
        
        $DB->insert_record('nir_messages', $record, false);

        $last_date = NULL;
        if($date !== "0")
            $last_date = $date;

        echo get_messages($rs->nir_id, $rs->type, $last_date);

        exit();
    }
    
    echo "Error";
}
else{
    echo "Error";
}
?>