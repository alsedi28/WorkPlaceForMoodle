<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../config.php');

global $USER;

$ADMIN = 2;

$nir = isset($_POST['nir']) ? $_POST['nir'] : 0;
$type = isset($_POST['type']) ? $_POST['type'] : "";
$text = isset($_POST['text']) ? $_POST['text'] : "";
$date = isset($_POST['date']) ? $_POST['date'] : "0";
$user = $USER->id;

$is_kaf = false;
if($USER->profile['isTeacher'] === "666")
    $is_kaf = true;

$sql_work = "SELECT id, user_id, teacher_id FROM mdl_nir WHERE id=".$nir;
$rs = $DB->get_records_sql($sql_work);

if(count($rs) == 0 || ($rs[$nir]->user_id != $user && $rs[$nir]->teacher_id != $user && !$is_kaf)){
    exit;
}
    
$record = new stdClass();
$record->user_id = (int)$user;
$record->nir_id = (int)$nir;
$record->nir_type = $type;
$record->text = htmlspecialchars($text);

$DB->insert_record('nir_messages', $record, false);

if($is_kaf){
    if ($date !== "0"){
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.date > '".$date."' AND mdl_nir_messages.nir_id=".$nir." AND mdl_nir_messages.user_id=".$user." AND mdl_nir_messages.nir_type='".$type."'";
    }
    else{
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$nir." AND mdl_nir_messages.user_id=".$user." AND mdl_nir_messages.nir_type='".$type."'";
    }
}
else{
    if ($date !== "0"){
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.date > '".$date."' AND mdl_nir_messages.nir_id=".$nir." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".$type."'";
    }
    else{
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$nir." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".$type."'";
    }
}
$messages = $DB->get_records_sql($sql_messages);

foreach ($messages as $m){
    echo "<div class='message'>";
        echo "<div class='header_message";
        if(!$is_kaf && $m->id == $ADMIN){
            echo " header_message_kaf";
        }
        echo "'>";
        if($is_kaf || $m->id == $ADMIN)
            echo "<p class='header_message_name'>Кафедра</p>";
        else
            echo "<p class='header_message_name'>".$m->lastname." ".$m->firstname."</p>";
            echo "<p class='header_message_date'>".$m->date."</p>";
            echo "<div style='clear:both;'></div>";
        echo "</div>";
        echo "<p class='message_text'>".$m->text."</p>";
    echo "</div>";
}

?>