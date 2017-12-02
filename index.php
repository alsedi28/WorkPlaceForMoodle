<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../config.php');

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nirtest/index.php');

$PAGE->requires->css('/nirtest/css/menu_kaf.css');
$PAGE->requires->css('/nirtest/css/modal_dialog.css');
$PAGE->requires->css('/nirtest/css/tabs.css');
$PAGE->requires->css('/nirtest/css/main.css');
$PAGE->requires->css('/nirtest/material/jquery.filer.css');
$PAGE->requires->css('/nirtest/material/jquery.filer-dragdropbox-theme.css');
$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/material/jquery.filer.min.js', true);
$PAGE->requires->js('/nirtest/js/main.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$previewnode = $PAGE->navigation->add("НИР", new moodle_url('/nir/index.php'), navigation_node::TYPE_CONTAINER);
$previewnode->make_active();


$ADMIN = 2; // kaf id hardcode

echo $OUTPUT->header();

if($USER->profile['isTeacher'] === "666"){
    
    
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        
        $sql_student = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id, mdl_user_info_data.data FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=".$student_id." AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid='3'";
        $rs_student = $DB->get_record_sql($sql_student);
            
        
        if(isset($_GET["id"])){
            echo "<h1>Научно-исследовательская работа</h1>";
            $work_id = (int) $_GET["id"];
            
            $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_user.id=mdl_nir.teacher_id AND mdl_nir.id=".$work_id;
            $rs = $DB->get_records_sql($sql_work);

            if(count($rs) == 0){
                echo "404 NOT FOUND";
            }
            else{
                $sql_file_type_z = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.date, mdl_nir_files.is_new, mdl_nir_files.is_sign_kaf, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='Z' AND mdl_user.id=mdl_nir_files.user_id AND mdl_nir_files.is_sign_teacher=1";
                $file_type_z = $DB->get_record_sql($sql_file_type_z);
                $sql_messages_type_z = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='Z' ORDER BY mdl_nir_messages.date";
                $messages_type_z = $DB->get_records_sql($sql_messages_type_z);
                
                $sql_file_type_o = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.date, mdl_nir_files.is_new, mdl_nir_files.is_sign_kaf, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='O' AND mdl_user.id=mdl_nir_files.user_id AND mdl_nir_files.is_sign_teacher=1";
                $file_type_o = $DB->get_record_sql($sql_file_type_o);
                $sql_messages_type_o = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='O' ORDER BY mdl_nir_messages.date";
                $messages_type_o = $DB->get_records_sql($sql_messages_type_o);
        
        
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Научный руководитель: </span>";
                echo $rs[$work_id]->lastname." ".$rs[$work_id]->firstname."</p>";
                
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Студент: </span>";
                echo $rs_student->lastname." ".$rs_student->firstname."</p>";
                
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
                echo "</span>".$rs_student->data."</p>";
                echo "<br/>";
                
                
                echo "<div class='tabs'>";
                    echo "<ul class='tab-links'>";
                        echo "<li class='active'><a href='#tab1'>Задание на НИР</a></li>";
                        echo "<li><a href='#tab2'>Отчет</a></li>";
                    echo "</ul>";
         
                    echo "<div class='tab-content'>";
                        echo "<div id='tab1' class='tab active'>";
                            echo "<div id='content'>";
                                echo "<div class='block_files_kaf'>";
                                if($file_type_z){
                                    echo "<a href='".$file_type_z->filename."' target='_blank' class='a_file_block a_file_block_kaf'>";
                                        echo "<div class='block_file_prev_kaf'>";
                                            echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file_type_z->date."</p>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file_type_z->lastname." ".$file_type_z->firstname."</p>";
                                            echo "<input type='hidden' name='file_id' id='file_id' value='".$file_type_z->id."'>";
                                        echo "</div>";
                                    echo "</a>";
                                }
                                else{
                                    echo "<p class='work_not_load'>Работа еще не была загружена.</p>";
                                }
                                    echo "<div style='clear:both;'></div>";
                                echo "</div>";
                                
                                if($file_type_z){
                                    echo "<div class='block_files_sign_kaf'>";
                                        echo "<div class='sign_kaf_button";
                                        if($file_type_z->is_sign_kaf == 1){
                                            echo " sign_kaf_button_not_active";
                                        }
                                        echo "' >Подписать</div>";
                                        echo "<div class='cancel_kaf_button' >Отклонить</div>";
                                    echo "</div>";
                                    echo "<br/>";
                                }
                                
                                echo "<input type='hidden' name='h_work' id='h_work' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type' value='Z'>";
                                
                                if(count($messages_type_z) == 0 && $rs[$work_id]->is_closed == 1){
                                }
                                else
                                {
                                    echo "<div class='messages' >";
                                    
                                    foreach ($messages_type_z as $mz){
                                        echo "<div class='message'>";
                                            echo "<div class='header_message'>";
                                                echo "<p class='header_message_name'>Кафедра</p>";
                                                echo "<p class='header_message_date'>".$mz->date."</p>";
                                                echo "<div style='clear:both;'></div>";
                                            echo "</div>";
                                            echo "<p class='message_text'>".$mz->text."</p>";
                                        echo "</div>";
                                    }
                                    
                                    if($rs[$work_id]->is_closed != 1){
                                        echo "<div class='textar_message_new'>";
                                            echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab1'></textarea>";
                                            echo "<button class='send_message_button' id='send_message_tab1'>";
                                                echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                            echo "</button>";
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                
                            echo "</div>";
                        echo "</div>";
                 
                        echo "<div id='tab2' class='tab'>";
                            echo "<div id='content'>";
                                echo "<div class='block_files_kaf'>";
                                
                                if($file_type_o){
                                    echo "<a href='".$file_type_o->filename."' target='_blank' class='a_file_block a_file_block_kaf'>";
                                        echo "<div class='block_file_prev_kaf'>";
                                            echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file_type_o->date."</p>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file_type_o->lastname." ".$file_type_o->firstname."</p>";
                                            echo "<input type='hidden' name='file_id' id='file_id' value='".$file_type_o->id."'>";
                                        echo "</div>";
                                    echo "</a>";
                                }   
                                else{
                                    echo "<p class='work_not_load'>Работа еще не была загружена.</p>";
                                }
                                    echo "<div style='clear:both;'></div>";
                                echo "</div>";
                                
                                if($file_type_o){
                                    echo "<div class='block_files_sign_kaf'>";
                                        echo "<div class='sign_kaf_button";
                                        if($file_type_o->is_sign_kaf == 1){
                                            echo " sign_kaf_button_not_active";
                                        }
                                        echo "' >Подписать</div>";
                                        echo "<div class='cancel_kaf_button' >Отклонить</div>";
                                    echo "</div>";
                                    echo "<br/>";
                                }
                                
                                echo "<input type='hidden' name='h_work' id='h_work_2' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type_2' value='O'>";
                                
                                echo "<input type='hidden' name='h_work' id='h_work_3' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type_3' value='O'>";
                                
                                if($rs[$work_id]->review != "" && $rs[$work_id]->mark != null){
                                    echo "<div id='review_block_header'>";
                                        echo "<p class='review_header_title'>Отзыв научного руководителя</p>";
                                    echo "</div>";
                                    echo "<div id='review_block' style='height: auto' >";
                                        echo "<p class='ex_review_title'>Отзыв</p>";
                                        echo "<p class= 'ex_review_text'>".$rs[$work_id]->review."</p>";
                                        echo "<p class='ex_mark'>Оценка (по 5-ти балльной шкале): <span>".$rs[$work_id]->mark."</span></p>";
                                    echo "</div>";   
                                }
                                
                                if(count($messages_type_o) == 0 && $rs[$work_id]->is_closed == 1){
                                }
                                else
                                {
                                    echo "<div class='messages' >";
                                        foreach ($messages_type_o as $mo){
                                            echo "<div class='message'>";
                                                echo "<div class='header_message'>";
                                                    echo "<p class='header_message_name'>Кафедра</p>";
                                                    echo "<p class='header_message_date'>".$mo->date."</p>";
                                                    echo "<div style='clear:both;'></div>";
                                                echo "</div>";
                                                echo "<p class='message_text'>".$mo->text."</p>";
                                            echo "</div>";
                                        }
                                        
                                        if($rs[$work_id]->is_closed != 1){
                                            echo "<div class='textar_message_new'>";
                                                echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab2'></textarea>";
                                                echo "<button class='send_message_button' id='send_message_tab2'>";
                                                    echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                                echo "</button>";
                                            echo "</div>";
                                        }
                                    echo "</div>";
                                }
                            echo "</div>";
                        echo "</div>";
                 
                    echo "</div>";
                echo "</div>";
            }
            
        }
        else{
            echo "<h1>Научно-исследовательские работы</h1>";
            
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Студент: </span>";
            echo $rs_student->lastname." ".$rs_student->firstname."</p>";
            
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
            echo "</span>".$rs_student->data."</p>";
            echo "<br/>";
            
            $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND  mdl_user.id=mdl_nir.teacher_id";
            $works = $DB->get_records_sql($sql_works);
        
            foreach ($works as $wk){
                echo "<a href='/nir/index.php?std=".$student_id."&id=".$wk->id."'><div class='work_block";
                if($wk->is_closed == 1)
                {
                    echo " work_block_closed";
                }
                echo "'>";
                echo "<p class='work_title'><span class='work_title_title'>Научный руководитель: </span>".$wk->lastname." ".$wk->firstname."</p>";
                echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
                echo "</div></a>";
            }
        }
    }
    else{
        echo "<h1>Научно-исследовательская работа</h1>";
        
        $sql_groups = "SELECT DISTINCT data FROM mdl_user_info_data WHERE fieldid=3  AND data!='' ORDER BY data";
        $rs = $DB->get_records_sql($sql_groups);
            
        echo "<div id='cssmenu'>";
        echo "<ul>";
        
        foreach($rs as $grp){
            echo "<li class='has-sub'><a href='#'><span>".$grp->data."</span></a>";
            echo "<ul>";
            
            $sql_users_groups = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=mdl_user_info_data.userid AND mdl_user_info_data.data='".$grp->data."'";
            $users_group = $DB->get_records_sql($sql_users_groups);
            
            foreach($users_group as $u){
                $sql_users_count_files = "SELECT COUNT(mdl_nir_files.id) as count FROM mdl_nir, mdl_nir_files WHERE mdl_nir.user_id=".$u->id." AND mdl_nir_files.nir_id=mdl_nir.id AND mdl_nir_files.is_sign_teacher=1 AND mdl_nir_files.is_sign_kaf=0";
                $count = $DB->get_record_sql($sql_users_count_files);
                echo "<li><a href='/nir/index.php?std=".$u->id."'><span>".$u->lastname." ".$u->firstname."</span>";
                if($count->count > 0){
                    echo "<div class='sign_files_kaf_icon'><img title='Добавлен новый документ' src='img/report-3-xxl.png' height='25px'/></div>";
                }
                echo "</a></li>";
            }
            
            echo "</ul>";
            echo "</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    }

}
else if(isset($_GET["id"])){
    $work_id = (int) $_GET["id"];
    
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM mdl_nir, mdl_user, mdl_user_info_data WHERE mdl_nir.user_id=".$student_id." AND mdl_nir.teacher_id=".$USER->id."  AND mdl_user.id=mdl_nir.user_id AND mdl_nir.id=".$work_id." AND mdl_user_info_data.userid=".$student_id." AND mdl_user_info_data.fieldid=3";
        $rs = $DB->get_records_sql($sql_work);
    }
    else{
        $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id AND mdl_nir.id=".$work_id;
        $rs = $DB->get_records_sql($sql_work);
    }

    if(count($rs) == 0){
        echo "404 NOT FOUND";
    }
    else{
        require_once($CFG->dirroot.'/nir/ajax_remove_file.php');
        require_once($CFG->dirroot.'/nir/ajax_upload_file.php');
        
        $sql_files_type_z = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='Z' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_z = $DB->get_records_sql($sql_files_type_z);
        $sql_messages_type_z = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='Z' ORDER BY mdl_nir_messages.date";
        $messages_type_z = $DB->get_records_sql($sql_messages_type_z);
        
        $sql_files_type_o = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='O' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_o = $DB->get_records_sql($sql_files_type_o);
        $sql_messages_type_o = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='O' ORDER BY mdl_nir_messages.date";
        $messages_type_o = $DB->get_records_sql($sql_messages_type_o);
        
        $sql_files_type_p = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='P' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_p = $DB->get_records_sql($sql_files_type_p);
        $sql_messages_type_p = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='P' ORDER BY mdl_nir_messages.date";
        $messages_type_p = $DB->get_records_sql($sql_messages_type_p);

        echo "<h1>Научно-исследовательская работа</h1>";
        echo "<p class='single_work_title'><span class='single_work_title_title'>Описание: </span>".$rs[$work_id]->title."</p>";
        echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>";
        
        if(isset($_GET["std"])){
            echo "Студент: ";
        }
        else{
            echo "Научный руководитель: ";
        }
        
        echo "</span>".$rs[$work_id]->lastname." ".$rs[$work_id]->firstname."</p>";
        
        if(isset($_GET["std"])){
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
            echo "</span>".$rs[$work_id]->data."</p>";
        }
        
        echo "<div class='tabs'>";
            echo "<ul class='tab-links'>";
                echo "<li class='active'><a href='#tab1'>Задание на НИР</a></li>";
                echo "<li><a href='#tab2'>Отчет</a></li>";
                echo "<li><a href='#tab3'>Презентация</a></li>";
            echo "</ul>";
 
            echo "<div class='tab-content'>";
                echo "<div id='tab1' class='tab active'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $i = 1;
                            $total = count($files_type_z);
                            $flag = true;
                            
                            foreach ($files_type_z as $file){
                                
                                    echo "<div class='block_file_prev'";
                                    if($rs[$work_id]->is_closed == 0 && (($total == $i || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  || ($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666")){
                                        echo " style='height:340px'";
                                    }
                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Задание ".$i."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                        if($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666"){
                                            echo "<br/>";
                                            echo "<br/>";
                                            echo "<p class='file_date'>Файл подписан научным руководителем. Ожидает подтверждения от кафедры.</p>";
                                        }
                                    echo "</a>";
                                    if($rs[$work_id]->is_closed == 0 && ($total == $i || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  
                                    {
                                        if($total != $i)
                                            $flag = false;
                                            
                                        echo "<div class='block_files_sign_teacher'>";
                                            echo "<div class='sign_button_teacher";
                                            if($file->is_sign_teacher == 1){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Подписать</div>";
                                            echo "<div class='cancel_button_teacher";
                                            if($file->is_sign_teacher == 0){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Отклонить</div>";
                                        echo "</div>";
                                    }
                                    
                                    echo "</div>";
                                
                                $i++;
                            }
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input2'>";
                        }
                        
                        echo "<input type='hidden' name='h_work' id='h_work' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type' value='Z'>";
                        
                        if(count($messages_type_z) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                            
                            foreach ($messages_type_z as $mz){
                                echo "<div class='message'>";
                                    echo "<div class='header_message";
                                    if($mz->user_id == $ADMIN){
                                        echo " header_message_kaf";
                                    }
                                    echo "'>";
                                    if($mz->user_id == $ADMIN){
                                        echo "<p class='header_message_name'>Кафедра</p>";
                                    }
                                    else{
                                        echo "<p class='header_message_name'>".$mz->lastname." ".$mz->firstname."</p>";
                                    }
                                        echo "<p class='header_message_date'>".$mz->date."</p>";
                                        echo "<div style='clear:both;'></div>";
                                    echo "</div>";
                                    echo "<p class='message_text'>".$mz->text."</p>";
                                echo "</div>";
                            }
                            
                            if($rs[$work_id]->is_closed != 1){
                                echo "<div class='textar_message_new'>";
                                    echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab1'></textarea>";
                                    echo "<button class='send_message_button' id='send_message_tab1'>";
                                        echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                    echo "</button>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        
                    echo "</div>";
                echo "</div>";
         
                echo "<div id='tab2' class='tab'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $m = 1;
                            $total_o = count($files_type_o);
                            $flag = true;
                            
                            foreach ($files_type_o as $file){
                                
                                    echo "<div class='block_file_prev'";
                                    if($rs[$work_id]->is_closed == 0 && (($total_o == $m || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  || ($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666")){
                                        echo " style='height:340px'";
                                    }
                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Отчет ".$m."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                        if($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666"){
                                            echo "<br/>";
                                            echo "<br/>";
                                            echo "<p class='file_date'>Файл подписан научным руководителем. Ожидает подтверждения от кафедры.</p>";
                                        }
                                    echo "</a>";
                                    if($rs[$work_id]->is_closed == 0 && ($total_o == $m || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  
                                    {
                                        if($total_o != $m)
                                            $flag = false;
                                            
                                        echo "<div class='block_files_sign_teacher'>";
                                            echo "<div class='sign_button_teacher";
                                            if($file->is_sign_teacher == 1 || $rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' ";
                                            if($rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                                echo "title='Необходимо добавить отзыв'";
                                            }
                                            echo ">Подписать</div>";
                                            echo "<div class='cancel_button_teacher";
                                            if($file->is_sign_teacher == 0){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Отклонить</div>";
                                        echo "</div>";
                                    }
                                    
                                    echo "</div>";
                                
                                $m++;
                            }
                            
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input1'>";
                        }
                        echo "<input type='hidden' name='h_work' id='h_work_2' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type_2' value='O'>";
                        
                        if($USER->profile['isTeacher'] === "1"){
                            echo "<div id='review_block_header'>";
                                echo "<p class='review_header_title'>Отзыв научного руководителя</p>";
                            echo "</div>";
                            echo "<div id='review_block'";
                             if($rs[$work_id]->review != "" && $rs[$work_id]->mark != ""){
                                echo " style='height: auto'";
                            }
                            echo ">";
                            
                            if($rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                echo "<p class='review_title'>Отзыв</p>";
                                echo "<textarea placeholder='Введите отзыв...' rows='4' name='review' required style='resize: none;' id='review_area'></textarea>";
                                echo "<br/>";
                                echo "<span class='mark_title'>Оценка (по 5-ти балльной шкале)</span>";
                                echo "<input type='number' min='1' max='5' size='3' value='4' id='mark_input'/>";
                                echo "<br/>";
                                echo "<p id='send_review'>Отправить</p>";
                            }
                            else{
                                echo "<p class='ex_review_title'>Отзыв</p>";
                                echo "<p class= 'ex_review_text'>".$rs[$work_id]->review."</p>";
                                echo "<p class='ex_mark'>Оценка (по 5-ти балльной шкале): <span>".$rs[$work_id]->mark."</span></p>";
                            }
                            
                            echo "</div>";
                        }
                        
                        if(count($messages_type_o) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                                foreach ($messages_type_o as $mo){
                                    echo "<div class='message'>";
                                    echo "<div class='header_message";
                                    if($mo->user_id == $ADMIN){
                                        echo " header_message_kaf";
                                    }
                                    echo "'>";
                                            if($mo->user_id == $ADMIN){
                                                echo "<p class='header_message_name'>Кафедра</p>";
                                            }
                                            else{
                                                echo "<p class='header_message_name'>".$mo->lastname." ".$mo->firstname."</p>";
                                            }
                                            echo "<p class='header_message_date'>".$mo->date."</p>";
                                            echo "<div style='clear:both;'></div>";
                                        echo "</div>";
                                        echo "<p class='message_text'>".$mo->text."</p>";
                                    echo "</div>";
                                }
                                
                                if($rs[$work_id]->is_closed != 1){
                                    echo "<div class='textar_message_new'>";
                                        echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab2'></textarea>";
                                        echo "<button class='send_message_button' id='send_message_tab2'>";
                                            echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                        echo "</button>";
                                    echo "</div>";
                                }
                            echo "</div>";
                        }
                    echo "</div>";
                echo "</div>";
         
                echo "<div id='tab3' class='tab'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $n = 1;
                            $total_p = count($files_type_p);
                            $flag = true;
                            
                            foreach ($files_type_p as $file){
                                
                                    echo "<div class='block_file_prev'";

                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Презентация ".$n."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                    echo "</a>";
                                    
                                    echo "</div>";
                                
                                $n++;
                            }
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input3'>"; 
                        }
                        
                        echo "<input type='hidden' name='h_work' id='h_work_3' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type_3' value='P'>";
                        
                        if(count($messages_type_p) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                                foreach ($messages_type_p as $mp){
                                    echo "<div class='message'>";
                                        echo "<div class='header_message'>";
                                            echo "<p class='header_message_name'>".$mp->lastname." ".$mp->firstname."</p>";
                                            echo "<p class='header_message_date'>".$mp->date."</p>";
                                            echo "<div style='clear:both;'></div>";
                                        echo "</div>";
                                        echo "<p class='message_text'>".$mp->text."</p>";
                                    echo "</div>";
                                }
                                
                                if($rs[$work_id]->is_closed != 1){
                                    echo "<div class='textar_message_new'>";
                                        echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab3'></textarea>";
                                        echo "<button class='send_message_button' id='send_message_tab3'>";
                                            echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                        echo "</button>";
                                    echo "</div>";
                                }
                            echo "</div>";
                        }
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        
        if ($USER->profile['isTeacher'] === "1" && $rs[$work_id]->is_closed == 0){
            echo "<p class='finish_work_button'>Завершить работу</p>";   
            echo "<input type='hidden' name='work_f' id='work_f' value='".$work_id."'>";
        }
    }
}
else if($USER->profile['isTeacher'] === "1"){
    //Доступ в дополнительным полям, в данном случае к группе
    //echo $USER->profile['isTeacher']; 
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        
        $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname, mdl_user.id as student_id FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_nir.teacher_id=".$USER->id." AND  mdl_user.id=mdl_nir.user_id";
        $works = $DB->get_records_sql($sql_works);
        
        echo "<h1>Научно-исследовательские работы</h1>";
    
        foreach ($works as $wk){
            $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
            $count_new_file = $DB->get_record_sql($sql_new_files_amount);
            
            echo "<a href='/nir/index.php?std=".$wk->student_id."&id=".$wk->id."'><div class='work_block";
            if($wk->is_closed == 1)
            {
                echo " work_block_closed";
            }
            echo "'>";
            echo "<p class='work_title'><span class='work_title_title'>Студент: </span>".$wk->lastname." ".$wk->firstname."</p>";
            echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
            if ($count_new_file->count > 0){
                $title_file_m=" новых файлов";
                if($count_new_file->count==1){
                    $title_file_m=" новый файл";
                }
                else if($count_new_file->count>1 && $count_new_file->count<5){
                    $title_file_m=" новых файла";
                }
                echo "<p class='new_file_message'>Добавлено ".$count_new_file->count.$title_file_m."</p>";
            }
            echo "</div></a>";
        }
    
    }
    else{
        $sql_users_of_teacher = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM mdl_nir, mdl_user, mdl_user_info_data WHERE mdl_nir.teacher_id=".$USER->id." AND mdl_user.id=mdl_nir.user_id AND mdl_user_info_data.userid=mdl_nir.user_id AND mdl_user_info_data.fieldid=3";
        $users_of_teacher = $DB->get_records_sql($sql_users_of_teacher); 
        
        echo "<h1>Студенты</h1>";
        
        foreach ($users_of_teacher as $us){
            $sql_count_n_f = "SELECT COUNT(*) as count FROM mdl_nir_files, mdl_nir WHERE mdl_nir_files.user_id=".$us->id." AND mdl_nir.teacher_id=".$USER->id." AND mdl_nir_files.is_new=1 AND mdl_nir_files.nir_id=mdl_nir.id";
            $count_n_f = $DB->get_record_sql($sql_count_n_f);
            echo "<a href='index.php?std=".$us->id."'><div class='users_list_el'>";
            echo "<span style='float: left'>".$us->lastname." ".$us->firstname."</span>";
            echo $us->data;
            if($count_n_f->count > 0){
                echo " <img title='Добавлен новый документ' src='img/report-3-xxl.png' height='25px'/>";
            }
            echo "</div></a>";
        }
    }
}
else{
    $sql = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM mdl_user, mdl_user_info_data WHERE mdl_user.deleted=0 AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid=2 AND mdl_user_info_data.data=1";
    $rs = $DB->get_records_sql($sql);
    
    $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id";
    $works = $DB->get_records_sql($sql_works);
    
    
    echo "<h1>Научно-исследовательские работы</h1>";
    
    $count_open_works = 0;
    
    foreach ($works as $wk){
        $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
        $count_new_file = $DB->get_record_sql($sql_new_files_amount);
        
        echo "<a href='/nir/index.php?id=".$wk->id."'><div class='work_block";
        if($wk->is_closed == 1)
        {
            echo " work_block_closed";
        }
        else{
            $count_open_works++;
        }
        echo "'>";
        echo "<p class='work_title'><span class='work_title_title'>Научный руководитель: </span>".$wk->lastname." ".$wk->firstname."</p>";
        echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
        if ($count_new_file->count > 0){
            $title_file_m=" новых файлов";
            if($count_new_file->count==1){
                $title_file_m=" новый файл";
            }
            else if($count_new_file->count>1 && $count_new_file->count<5){
                $title_file_m=" новых файла";
            }
            echo "<p class='new_file_message'>Добавлено ".$count_new_file->count.$title_file_m."</p>";
        }
        echo "</div></a>";
    }
    
    echo "<div style='clear:both;'></div>";
    echo "</br>";
    
    if($count_open_works === 0)
    {
        // NEW Modal window for create NIR
        echo "<a href='#win1'><div id='button_create_nir'>Создать НИР</div></a>";
        
        echo "<a href='#x' class='overlay' id='win1'></a>";
        echo "<div class='popup'>";
            echo "<div>";
        		echo "<h2 style='text-align:center'>Создание НИР</h2>";
        		echo "<form id='form_create_nir' method='post' action='create_work.php'>";
                	echo "<p id='modal_d_teacher'>Выберите научного руководителя:</p>";
                    echo "<p><select name='teacher' required>";
                    	       foreach ($rs as $teacher){
                    	           echo "<option value='".$teacher->id."'>".$teacher->lastname." ".$teacher->firstname."</option>";
                    	       }
                    echo "</select></p>";
                    echo "<p id='modal_d_title'>Введите название научно-исследовательской работы:</p>";
                    echo "<textarea rows='3' cols='55' name='title' required style='resize: none;'></textarea>";
                    echo "<input type='hidden' name='user' value='".$USER->id."'>";
                    echo "<br/>";
                    echo "<input type='submit' value='Создать' id='submit_modal_form'>";
                echo "</form>";
        	echo "</div>";
        echo "</div>";
        // end
    }

}
echo $OUTPUT->footer();
?>
<script>

	// kaf navigate
	$('#cssmenu > ul > li ul').each(function(index, e){
        var count = $(e).find('li').length;
        var content = '<span class=\"cnt\">' + count + '</span>';
        $(e).closest('li').children('a').append(content);
    });
    $('#cssmenu ul ul li:odd').addClass('odd');
    $('#cssmenu ul ul li:even').addClass('even');
    $('#cssmenu > ul > li > a').click(function() {
        $('#cssmenu li').removeClass('act');
        $(this).closest('li').addClass('act');   
        var checkElement = $(this).next();
        if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('act');
            checkElement.slideUp('normal');
        }
        if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#cssmenu ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }
        if($(this).closest('li').find('ul').children().length == 0) {
            return true;
        } else {
            return false;
        }
    });
    
    
</script>
