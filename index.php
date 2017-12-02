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

$content = '';

// Page kafedra
if($USER->profile['isTeacher'] === "666"){
    
    // Page kafedra select student
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        
        $sql_student = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id, mdl_user_info_data.data FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=".$student_id." AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid='3'";
        $rs_student = $DB->get_record_sql($sql_student);
            
        // Student's work
        if(isset($_GET["id"])){
            $work_id = (int) $_GET["id"];

            $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_user.id=mdl_nir.teacher_id AND mdl_nir.id=".$work_id;
            $rs = $DB->get_records_sql($sql_work);

            $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

            if(count($rs) == 0){
                $content .= html_writer::tag('h3', '404 NOT FOUND');
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

                $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
                $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
                $content .= $rs[$work_id]->lastname." ".$rs[$work_id]->firstname;
                $content .= html_writer::end_tag('p');

                $content .= render_student_info($rs_student);

                $content .= html_writer::start_tag('div', array('class' => 'tabs'));

                $content .= html_writer::start_tag('ul', array('class' => 'tab-links'));
                $content .= html_writer::start_tag('li', array('class' => 'active'));
                $content .= html_writer::tag('a', 'Задание на НИР', array('href' => '#tab1'));
                $content .= html_writer::end_tag('li');
                $content .= html_writer::start_tag('li');
                $content .= html_writer::tag('a', 'Отчет', array('href' => '#tab2'));
                $content .= html_writer::end_tag('li');
                $content .= html_writer::end_tag('ul');

                $content .= html_writer::start_tag('div', array('class' => 'tab-content'));
                $content .= html_writer::start_tag('div', array('class' => 'tab active', 'id' => 'tab1'));

                $content .= render_kafedra_tab($file_type_z, $messages_type_z, $work_id, $rs);

                $content .= html_writer::end_tag('div');
                $content .= html_writer::end_tag('div');

                $content .= html_writer::start_tag('div', array('class' => 'tab', 'id' => 'tab2'));

                $content .= render_kafedra_tab($file_type_o, $messages_type_o, $work_id, $rs, true);

                $content .= html_writer::end_tag('div');
                $content .= html_writer::end_tag('div');

                $content .= html_writer::end_tag('div');
            }
            
        }
        else{ // List of student's works
            $content .= html_writer::tag('h1', 'Научно-исследовательские работы');

            $content .= render_student_info($rs_student);
            
            $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND  mdl_user.id=mdl_nir.teacher_id";
            $works = $DB->get_records_sql($sql_works);
        
            foreach ($works as $wk){
                $url = "/nir/index.php?std=".$student_id."&id=".$wk->id;

                $content .= html_writer::start_tag('a', array('href' => $url));
                $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

                $content .= render_header_work_block($wk);

                $content .= html_writer::end_tag('div');
                $content .= html_writer::end_tag('a');
            }
        }
    }
    else{ // Main page kafedra with list of students
        $content .= html_writer::tag('h1', 'Научно-исследовательская работа');
        
        $sql_groups = "SELECT DISTINCT data FROM mdl_user_info_data WHERE fieldid=3  AND data!='' ORDER BY data";
        $rs = $DB->get_records_sql($sql_groups);

        $content .= html_writer::start_tag('div', array('id' => 'cssmenu'));
        $content .= html_writer::start_tag('ul');

        foreach($rs as $grp){
            $content .= html_writer::start_tag('li', array('class' => 'has-sub'));
            $content .= html_writer::start_tag('a', array('href' => '#'));
            $content .= html_writer::tag('span', $grp->data);
            $content .= html_writer::end_tag('a');

            $content .= html_writer::start_tag('ul');

            $sql_users_groups = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=mdl_user_info_data.userid AND mdl_user_info_data.data='".$grp->data."'";
            $users_group = $DB->get_records_sql($sql_users_groups);
            
            foreach($users_group as $u){
                $sql_users_count_files = "SELECT COUNT(mdl_nir_files.id) as count FROM mdl_nir, mdl_nir_files WHERE mdl_nir.user_id=".$u->id." AND mdl_nir_files.nir_id=mdl_nir.id AND mdl_nir_files.is_sign_teacher=1 AND mdl_nir_files.is_sign_kaf=0";
                $count = $DB->get_record_sql($sql_users_count_files);

                $url = "/nir/index.php?std=".$u->id;

                $content .= html_writer::start_tag('li');
                $content .= html_writer::start_tag('a', array('href' => $url));
                $content .= html_writer::tag('span', $u->lastname." ".$u->firstname);

                if($count->count > 0){
                    $content .= html_writer::start_tag('div', array('class' => 'sign_files_kaf_icon'));
                    $content .= html_writer::empty_tag('img', array('src' => 'img/report-3-xxl.png', 'height' => '25px', 'title' => 'Добавлен новый документ'));
                    $content .= html_writer::end_tag('div');
                }
                $content .= html_writer::end_tag('a');
                $content .= html_writer::end_tag('li');
            }

            $content .= html_writer::end_tag('ul');
            $content .= html_writer::end_tag('li');
        }

        $content .= html_writer::end_tag('ul');
        $content .= html_writer::end_tag('div');
    }

}
else if(isset($_GET["id"])){ // Page of work for teacher and student
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
        $content .= html_writer::tag('h3', '404 NOT FOUND');
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

        $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

        $content .= html_writer::start_tag('p', array('class' => 'single_work_title'));
        $content .= html_writer::tag('span', 'Описание: ', array('class' => 'single_work_title_title'));
        $content .= $rs[$work_id]->title;
        $content .= html_writer::end_tag('p');

        if(isset($_GET["std"])){
            $content .= render_student_info($rs[$work_id]);
        }
        else{
            $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
            $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
            $content .= $rs[$work_id]->lastname." ".$rs[$work_id]->firstname;
            $content .= html_writer::end_tag('p');
        }

        $content .= html_writer::start_tag('div', array('class' => 'tabs'));

        $content .= html_writer::start_tag('ul', array('class' => 'tab-links'));
        $content .= html_writer::start_tag('li', array('class' => 'active'));
        $content .= html_writer::tag('a', 'Задание на НИР', array('href' => '#tab1'));
        $content .= html_writer::end_tag('li');
        $content .= html_writer::start_tag('li');
        $content .= html_writer::tag('a', 'Отчет', array('href' => '#tab2'));
        $content .= html_writer::end_tag('li');
        $content .= html_writer::start_tag('li');
        $content .= html_writer::tag('a', 'Презентация', array('href' => '#tab3'));
        $content .= html_writer::end_tag('li');
        $content .= html_writer::end_tag('ul');

        $content .= html_writer::start_tag('div', array('class' => 'tab-content'));

        $content .= render_tab($files_type_z, $messages_type_z, $rs, $USER, $work_id, array ('tab_id' => 'tab1', 'tab_number' => 1, 'image_path' => 'img/Filetype-Docs-icon.png',
            'file_type_name' => 'Задание', 'filer_input_id' => 'filer_input2', 'work_input_id' => 'h_work', 'work_input_type' => 'h_work_type', 'work_type' => 'Z',
            'message_textarea_id' => 'message_textarea_tab1', 'send_message_id' => 'send_message_tab1'));

        $content .= render_tab($files_type_o, $messages_type_o, $rs, $USER, $work_id, array ('tab_id' => 'tab2', 'tab_number' => 2, 'image_path' => 'img/Filetype-Docs-icon.png',
            'file_type_name' => 'Отчет', 'filer_input_id' => 'filer_input1', 'work_input_id' => 'h_work_2', 'work_input_type' => 'h_work_type_2', 'work_type' => 'O',
            'message_textarea_id' => 'message_textarea_tab2', 'send_message_id' => 'send_message_tab2'));

        $content .= render_tab($files_type_p, $messages_type_p, $rs, $USER, $work_id, array ('tab_id' => 'tab3', 'tab_number' => 3, 'image_path' => 'img/Filetype-Docs-icon.png',
            'file_type_name' => 'Презентация', 'filer_input_id' => 'filer_input3', 'work_input_id' => 'h_work_3', 'work_input_type' => 'h_work_type_3', 'work_type' => 'P',
            'message_textarea_id' => 'message_textarea_tab3', 'send_message_id' => 'send_message_tab3'));


        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        
        if ($USER->profile['isTeacher'] === "1" && $rs[$work_id]->is_closed == 0){
            $content .= html_writer::tag('p', 'Завершить работу', array('class' => 'finish_work_button'));
            $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_f', 'id' => 'work_f', 'value' => $work_id));
        }
    }
}
else if($USER->profile['isTeacher'] === "1"){ // Main page for teacher
    //Доступ в дополнительным полям, в данном случае к группе
    //echo $USER->profile['isTeacher']; 
    if(isset($_GET["std"])){ // List of student's works for the current teacher
        $student_id = (int) $_GET["std"];
        
        $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname, mdl_user.id as student_id FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_nir.teacher_id=".$USER->id." AND  mdl_user.id=mdl_nir.user_id";
        $works = $DB->get_records_sql($sql_works);

        $content .= html_writer::tag('h1', 'Научно-исследовательские работы');
    
        foreach ($works as $wk){
            $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
            $count_new_file = $DB->get_record_sql($sql_new_files_amount);

            $url = '/nir/index.php?std='.$wk->student_id.'&id='.$wk->id;

            $content .= html_writer::start_tag('a', array('href' => $url));
            $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

            $content .= render_header_work_block($wk, true);
            $content .= render_work_block_title_new_files($count_new_file);

            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('a');
        }
    
    }
    else{ // List of teacher's students
        $sql_users_of_teacher = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM mdl_nir, mdl_user, mdl_user_info_data WHERE mdl_nir.teacher_id=".$USER->id." AND mdl_user.id=mdl_nir.user_id AND mdl_user_info_data.userid=mdl_nir.user_id AND mdl_user_info_data.fieldid=3";
        $users_of_teacher = $DB->get_records_sql($sql_users_of_teacher); 

        $content .= html_writer::tag('h1', 'Студенты');
        
        foreach ($users_of_teacher as $us){
            $sql_count_n_f = "SELECT COUNT(*) as count FROM mdl_nir_files, mdl_nir WHERE mdl_nir_files.user_id=".$us->id." AND mdl_nir.teacher_id=".$USER->id." AND mdl_nir_files.is_new=1 AND mdl_nir_files.nir_id=mdl_nir.id";
            $count_n_f = $DB->get_record_sql($sql_count_n_f);

            $url = '/nir/index.php?std='.$us->id;

            $content .= html_writer::start_tag('a', array('href' => $url));
            $content .= html_writer::start_tag('div', array('class' => 'users_list_el'));
            $content .= html_writer::tag('span', $us->lastname." ".$us->firstname, array('style' => 'float:left'));
            $content .= $us->data;

            if($count_n_f->count > 0){
                $content .= html_writer::empty_tag('img', array('src' => 'img/report-3-xxl.png', 'height' => '25px', 'title' => 'Добавлен новый документ'));
            }

            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('a');
        }
    }
}
else{ // Main page for student with list of his works
    $sql = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM mdl_user, mdl_user_info_data WHERE mdl_user.deleted=0 AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid=2 AND mdl_user_info_data.data=1";
    $rs = $DB->get_records_sql($sql);
    
    $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id";
    $works = $DB->get_records_sql($sql_works);

    $content .= html_writer::tag('h1', 'Научно-исследовательские работы');
    
    $count_open_works = 0;
    
    foreach ($works as $wk){
        $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
        $count_new_file = $DB->get_record_sql($sql_new_files_amount);

        $url = '/nir/index.php?id='.$wk->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

        if($wk->is_closed != 1)
            $count_open_works++;

        $content .= render_header_work_block($wk);
        $content .= render_work_block_title_new_files($count_new_file);

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }

    $content .= html_writer::empty_tag('div', array('style' => 'clear:both;'));
    $content .= html_writer::tag('br');
    
    if($count_open_works === 0)
    {
        // Modal window for create work
        $content = render_modal_dialog_create_work($rs, $USER->id);
    }
}

echo $content;
echo $OUTPUT->footer();

function render_modal_dialog_create_work($teachers, $user_id){
    $dialog = '';
    $dialog .= html_writer::start_tag('a', array('href' => '#win1'));
    $dialog .= html_writer::tag('div', 'Создать НИР', array('id' => 'button_create_nir'));
    $dialog .= html_writer::end_tag('a');

    $dialog .= html_writer::tag('a', array('href' => '#x', 'class' => 'overlay', 'id' => 'win1'));

    $dialog .= html_writer::start_tag('div', array('class' => 'popup'));
    $dialog .= html_writer::start_tag('div');
    $dialog .= html_writer::tag('h2', 'Создание НИР', array('style' => 'text-align:center'));

    $dialog .= html_writer::start_tag('form', array('id' => 'form_create_nir', 'method' => 'post', 'action' => 'create_work.php'));
    $dialog .= html_writer::tag('p', 'Выберите научного руководителя:', array('id' => 'modal_d_teacher'));

    $dialog .= html_writer::start_tag('p');
    $dialog .= html_writer::start_tag('select', array('name' => 'teacher', 'required' => true));

    foreach ($teachers as $teacher) {
        $dialog .= html_writer::tag('option', $teacher->lastname . " " . $teacher->firstname, array('value' => $teacher->id));
    }

    $dialog .= html_writer::end_tag('select');
    $dialog .= html_writer::end_tag('p');

    $dialog .= html_writer::tag('p', 'Введите название научно-исследовательской работы:', array('id' => 'modal_d_title'));
    $dialog .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'cols', 'required' => true, 'style' => 'resize: none;', 'cols' => '55'));
    $dialog .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'user', 'value' => $user_id));
    $dialog .= html_writer::tag('br');
    $dialog .= html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'submit_modal_form', 'value' => 'Создать'));

    $dialog .= html_writer::end_tag('form');
    $dialog .= html_writer::end_tag('div');
    $dialog .= html_writer::end_tag('div');

    return $dialog;
}

function render_work_block_title_new_files($count_new_file){
    if ($count_new_file->count > 0){
        $title_file_m=" новых файлов";
        if($count_new_file->count==1){
            $title_file_m=" новый файл";
        }
        else if($count_new_file->count>1 && $count_new_file->count<5){
            $title_file_m=" новых файла";
        }

        return html_writer::tag('p', 'Добавлено '.$count_new_file->count.$title_file_m, array('class' => 'new_file_message'));
    }
}

function render_header_work_block($work, $is_student = false){
    $header = '';
    $header .= html_writer::start_tag('p', array('class' => 'work_title'));
    $header .= html_writer::tag('span', $is_student ? 'Студент: ' : 'Научный руководитель: ', array('class' => 'work_title_title'));
    $header .= $work->lastname." ".$work->firstname;
    $header .= html_writer::end_tag('p');

    $header .= html_writer::start_tag('p', array('class' => 'work_teacher'));
    $header .= html_writer::tag('span', 'Описание: ', array('class' => 'work_teacher_title'));
    $header .= $work->title;
    $header .= html_writer::end_tag('p');

    return $header;
}

function render_student_info($student){
    $header = '';
    $header .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
    $header .= html_writer::tag('span', 'Студент: ', array('class' => 'single_work_teacher_title'));
    $header .= $student->lastname." ".$student->firstname;
    $header .= html_writer::end_tag('p');

    $header .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
    $header .= html_writer::tag('span', 'Группа: ', array('class' => 'single_work_teacher_title'));
    $header .= $student->data;
    $header .= html_writer::end_tag('p');

    return $header;
}

function render_kafedra_tab($file, $messages, $result, $work_id, $need_review = false){
    $tab_content = '';
    $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
    $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_kaf'));

    if($file){
        $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block a_file_block_kaf', 'target' => '_blank', 'href' => $file->filename));
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev_kaf'));

        $tab_content .= html_writer::empty_tag('img', array('src' => 'img/Filetype-Docs-icon.png', 'height' => '110px', 'class' => 'img_files_prev'));
        $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
        $tab_content .= html_writer::tag('span', 'Дата загрузки: ', array('style' => 'font-weight: bold'));
        $tab_content .= $file->date;
        $tab_content .= html_writer::end_tag('p');

        $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
        $tab_content .= html_writer::tag('span', 'Добавил: ', array('style' => 'font-weight: bold'));
        $tab_content .= $file->lastname." ".$file->firstname;
        $tab_content .= html_writer::end_tag('p');

        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'file_id', 'id' => 'file_id', 'value' => $file->id));

        $tab_content .= html_writer::end_tag('div');
        $tab_content .= html_writer::end_tag('a');
    }
    else{
        $tab_content .= html_writer::tag('p', 'Работа еще не была загружена.', array('class' => 'work_not_load'));
    }

    $tab_content .= html_writer::empty_tag('div', array('style' => 'clear:both;'));
    $tab_content .= html_writer::end_tag('div');

    if($file){
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_kaf'));
        $tab_content .= html_writer::tag('div', 'Подписать', array('class' => $file->is_sign_kaf == 1 ? 'sign_kaf_button sign_kaf_button_not_active' : 'sign_kaf_button'));
        $tab_content .= html_writer::tag('div', 'Отклонить', array('class' => 'cancel_kaf_button'));
        $tab_content .= html_writer::end_tag('div');
        $tab_content .= html_writer::tag('br');
    }

    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => $need_review ? 'h_work_2' : 'h_work', 'value' => $work_id));
    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => $need_review ? 'h_work_type_2' : 'h_work_type', 'value' => $need_review ? 'O' : 'Z'));

    if($need_review){ //need delete (failed js when try get *_3)
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => 'h_work_3', 'value' => $work_id));
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => 'h_work_type_3', 'value' => 'O'));
    }

    if($result[$work_id]->review != "" && $result[$work_id]->mark != null){
        $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
        $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
        $tab_content .= html_writer::end_tag('div');

        $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => 'height: auto'));
        $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'ex_review_title'));
        $tab_content .= html_writer::tag('p', $result[$work_id]->review, array('class' => 'ex_review_text'));
        $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
        $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
        $tab_content .= html_writer::tag('span', $result[$work_id]->mark);
        $tab_content .= html_writer::end_tag('div');
    }

    if(!(count($messages) == 0 && $result[$work_id]->is_closed == 1)){
        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => 'header_message'));
            $tab_content .= html_writer::tag('p', 'Кафедра', array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::empty_tag('div', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result[$work_id]->is_closed != 1){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => $need_review ? 'message_textarea_tab2' : 'message_textarea_tab1', 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => $need_review ? 'send_message_tab2' : 'send_message_tab1'));
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');
    }

    $tab_content .= html_writer::end_tag('div');
    return $tab_content;
}

function render_tab($files, $messages, $result, $user, $work_id, $options){
    $ADMIN = 2;
    $tab_content = '';
    $tab_content .= html_writer::start_tag('div', array('class' => 'tab active', 'id' => $options["tab_id"]));//tab1 tab2

    $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
    $tab_content .= html_writer::start_tag('div', array('class' => 'block_files'));

    $i = 1;
    $total = count($files);
    $flag = true;

    foreach ($files as $file){
        $height_block = '';
        if($options["tab_number"] !== 3 && $result[$work_id]->is_closed == 0 && (($total == $i || $file->is_sign_teacher == 1) && $user->profile['isTeacher'] === "1" && $flag)  || ($file->is_sign_teacher == 1 && $user->profile['isTeacher'] !== "1" && $user->profile['isTeacher'] !== "666")){
            $height_block = 'height:340px';
        }
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev', 'style' => $height_block));
        $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block', 'target' => '_blank', 'href' => $file->filename));
        $tab_content .= html_writer::empty_tag('img', array('src' => $options["image_path"], 'height' => '110px', 'class' => 'img_files_prev'));//img/Filetype-Docs-icon.png
        $tab_content .= html_writer::tag('p', $options["file_type_name"].' '.$i, array('class' => 'file_name'));//Задание Отчет Презентация

        $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
        $tab_content .= html_writer::tag('span', 'Дата загрузки: ', array('style' => 'font-weight: bold'));
        $tab_content .= $file->date;
        $tab_content .= html_writer::end_tag('p');

        $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
        $tab_content .= html_writer::tag('span', 'Добавил: ', array('style' => 'font-weight: bold'));
        $tab_content .= $file->lastname." ".$file->firstname;
        $tab_content .= html_writer::end_tag('p');

        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'file_id', 'id' => 'file_id', 'value' => $file->id));

        if($file->is_new != 0 && $file->user_id != $user->id){
            $tab_content .= html_writer::empty_tag('img', array('src' => 'img/new.gif', 'height' => '30px', 'class' => 'img_new'));
        }

        if($options["tab_number"] !== 3 && $file->is_sign_teacher == 1 && $user->profile['isTeacher'] !== "1" && $user->profile['isTeacher'] !== "666"){
            $tab_content .= html_writer::tag('br');
            $tab_content .= html_writer::tag('br');
            $tab_content .= html_writer::tag('p', 'Файл подписан научным руководителем. Ожидает подтверждения от кафедры.', array('class' => 'file_date'));
        }

        $tab_content .= html_writer::end_tag('a');

        if($options["tab_number"] !== 3 && $result[$work_id]->is_closed == 0 && ($total == $i || $file->is_sign_teacher == 1) && $user->profile['isTeacher'] === "1" && $flag)
        {
            if($total != $i)
                $flag = false;

            $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_teacher'));
            $tab_content .= html_writer::tag('div', 'Подписать', array('class' => ($file->is_sign_teacher == 1 || ($options["tab_number"] === 2 && ($result[$work_id]->review == "" || $result[$work_id]->mark == "")))? 'sign_button_teacher sign_teacher_button_not_active' : 'sign_button_teacher'));
            $tab_content .= html_writer::tag('div', 'Отклонить', array('class' => $file->is_sign_teacher == 0 ? 'cancel_button_teacher sign_teacher_button_not_active' : 'cancel_button_teacher'));
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');

        $i++;
    }

    $tab_content .= html_writer::empty_tag('div', array('style' => 'clear:both;'));
    $tab_content .= html_writer::end_tag('div');

    if($result[$work_id]->is_closed != 1){
        $tab_content .= html_writer::empty_tag('input', array('type' => 'file', 'name' => 'files[]', 'id' => $options["filer_input_id"]));//filer_input2 filer_input1 filer_input3
    }

    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => $options["work_input_id"], 'value' => $work_id)); // h_work h_work_2 h_work_3
    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => $options["work_input_type"], 'value' => $options["work_type"]));// h_work_type h_work_type_2 h_work_type_3 Z O P

    if($options["tab_number"] === 2){
        if($user->profile['isTeacher'] === "1"){
            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
            $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
            $tab_content .= html_writer::end_tag('div');

            $height_review_block = '';
            if($result[$work_id]->review != "" && $result[$work_id]->mark != ""){
                $height_review_block = 'height:auto';
            }
            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => $height_review_block));

            if($result[$work_id]->review == "" || $result[$work_id]->mark == ""){
                $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'review_title'));
                $tab_content .= html_writer::tag('textarea', '', array('rows' => '4', 'name' => 'review', 'id' => 'review_area', 'placeholder' => 'Введите отзыв...', 'style' => 'resize: none;', 'required' => true));
                $tab_content .= html_writer::tag('br');
                $tab_content .= html_writer::tag('span', 'Оценка (по 5-ти балльной шкале)', array('class' => 'mark_title'));
                $tab_content .= html_writer::empty_tag('input', array('type' => 'number', 'value' => '4', 'min' => '1', 'max' => '5', 'size' => '3', 'id' => 'mark_input'));
                $tab_content .= html_writer::tag('br');
                $tab_content .= html_writer::tag('p', 'Отправить', array('id' => 'send_review'));
            }
            else{
                $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'ex_review_title'));
                $tab_content .= html_writer::tag('p', $result[$work_id]->review, array('class' => 'ex_review_text'));

                $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
                $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
                $tab_content .= html_writer::tag('span', $result[$work_id]->mark);
                $tab_content .= html_writer::end_tag('p');
            }

            $tab_content .= html_writer::end_tag('div');
        }
    }

    if(!(count($messages) == 0 && $result[$work_id]->is_closed == 1)){
        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => $mz->user_id == $ADMIN ? 'header_message header_message_kaf' :'header_message'));
            $tab_content .= html_writer::tag('p', $mz->user_id == $ADMIN ? 'Кафедра' : $mz->lastname." ".$mz->firstname, array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::empty_tag('div', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result[$work_id]->is_closed != 1){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => $options["message_textarea_id"], 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));//message_textarea_tab1 message_textarea_tab2 message_textarea_tab3
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => $options["send_message_id"]));//send_message_tab1 send_message_tab2 send_message_tab3
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');
    }

    $tab_content .= html_writer::end_tag('div');
    $tab_content .= html_writer::end_tag('div');

    return $tab_content;
}
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

