<?php

require_once(dirname(__FILE__) . '/../config.php');
header('Content-type: application/json');

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$date = isset($_POST['date']) ? $_POST['date'] : "";

if($id === 0 || $date === ""){
    echo json_encode(array('status' => "Error"));
    exit();
}

$sql_work = "SELECT nir_id, type, is_sign_kaf FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

$message = "";
$status = "Ok cancel sign document";

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

        $status = "Ok cancel document";
    }
    
    $user_id = $USER->id;
        
    $record = new stdClass();
    $record->user_id = $user_id;
    $record->nir_id = $rs->nir_id;
    $record->nir_type = $rs->type;
    $record->text = $message;
    
    $DB->insert_record('nir_messages', $record, false);

    if ($date !== "0"){
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.date > '".$date."' AND mdl_nir_messages.nir_id=".($rs->nir_id)." AND mdl_nir_messages.user_id=".$user_id." AND mdl_nir_messages.nir_type='".($rs->type)."'";
    }
    else{
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".($rs->nir_id)." AND mdl_nir_messages.user_id=".$user_id." AND mdl_nir_messages.nir_type='".($rs->type)."'";
    }

    $messages = $DB->get_records_sql($sql_messages);

    $result = "";
    foreach ($messages as $m){
        $result .= "<div class='message'>";
        $result .= "<div class='header_message'>";
        $result .= "<p class='header_message_name'>Кафедра</p>";
        $result .= "<p class='header_message_date'>".$m->date."</p>";
        $result .= "<div style='clear:both;'></div>";
        $result .= "</div>";
        $result .= "<p class='message_text'>".$m->text."</p>";
        $result .= "</div>";
    }

    echo json_encode(array('status' => $status, 'data' => $result));
}
else{
    echo json_encode(array('status' => "Error"));
}
?>