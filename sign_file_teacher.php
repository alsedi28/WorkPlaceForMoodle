<?php
require_once(dirname(__FILE__) . '/../config.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$date = isset($_POST['date']) ? $_POST['date'] : "";

if($id === 0 || $date === ""){
    echo "Error";
    exit();
}

$sql_work = "SELECT nir_id, type FROM mdl_nir_files WHERE id=".$id;
$rs = $DB->get_record_sql($sql_work);

if($rs && $USER->profile['isTeacher'] === "1"){
    $update_record = new stdClass();
    $update_record->id=$id;
    $update_record->is_sign_teacher=1;
   
    $DB->update_record('nir_files',$update_record);

    $user_id = $USER->id;
        
    $record = new stdClass();
    $record->user_id = $user_id;
    $record->nir_id = $rs->nir_id;
    $record->nir_type = $rs->type;
    $record->text = "Документ одобрен и подписан научным руководителем.";
    
    $DB->insert_record('nir_messages', $record, false);

    if($date !== "0")
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.date > '".$date."' AND mdl_nir_messages.nir_id=".($rs->nir_id)." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".($rs->type)."'";
    else
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".($rs->nir_id)." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".($rs->type)."'";

    $messages = $DB->get_records_sql($sql_messages);

    foreach ($messages as $m){
        echo "<div class='message'>";
        echo "<div class='header_message";
        if($m->id == $ADMIN){
            echo " header_message_kaf";
        }
        echo "'>";
        if($m->id == $ADMIN)
            echo "<p class='header_message_name'>Кафедра</p>";
        else
            echo "<p class='header_message_name'>".$m->lastname." ".$m->firstname."</p>";
        echo "<p class='header_message_date'>".$m->date."</p>";
        echo "<div style='clear:both;'></div>";
        echo "</div>";
        echo "<p class='message_text'>".$m->text."</p>";
        echo "</div>";
    }
}
else{
    echo "Error";
}
?>