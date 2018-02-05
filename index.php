<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once('class.config.php');
require_once('class.datagateway.php');

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
$PAGE->requires->css('/nirtest/work_plan_form.css');
$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/material/jquery.filer.min.js', true);
$PAGE->requires->js('/nirtest/js/MessageArea.js', true);
$PAGE->requires->js('/nirtest/js/resource.js', true);
$PAGE->requires->js('/nirtest/js/main.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$previewnode = $PAGE->navigation->add("НИР", new moodle_url('/nir/index.php'), navigation_node::TYPE_CONTAINER);
$previewnode->make_active();

echo $OUTPUT->header();

$content = '';

// Page kafedra
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    
    // Page kafedra select student
    if(isset($_GET["std"])){

        if(!intval($_GET['std'])){
            echo html_writer::tag('h3', '404 NOT FOUND');
            echo $OUTPUT->footer();
            exit();
        }

        $student_id = $_GET["std"];

        $student_info = DataGateway::get_student_info($student_id);

        if(!$student_info){
            echo html_writer::tag('h3', '404 NOT FOUND');
            echo $OUTPUT->footer();
            exit();
        }
            
        // Student's work
        if(isset($_GET["id"])){

            if(!intval($_GET['id'])){
                echo html_writer::tag('h3', '404 NOT FOUND');
                echo $OUTPUT->footer();
                exit();
            }

            $work_id = $_GET["id"];

            $work = DataGateway::get_nir_by_student($student_id, $work_id);

            if(!$work){
                echo html_writer::tag('h3', '404 NOT FOUND');
                echo $OUTPUT->footer();
                exit();
            }

            $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

            $messages_type_z = DataGateway::get_comments_for_kafedra($work_id, $USER->id, 'Z');

            $file_type_o = DataGateway::get_file_for_kafedra($work_id, 'O');
            $messages_type_o = DataGateway::get_comments_for_kafedra($work_id, $USER->id, 'O');

            $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
            $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
            $content .= $work->lastname." ".$work->firstname;
            $content .= html_writer::end_tag('p');

            $content .= render_student_info($student_info);

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

            $content .= render_kafedra_tab_work_plan($messages_type_z, $work->is_closed, $work_id);

            $content .= html_writer::end_tag('div');

            $content .= html_writer::start_tag('div', array('class' => 'tab', 'id' => 'tab2'));

            $content .= render_kafedra_tab_report($file_type_o, $messages_type_o, $work, $work_id); // tab2

            $content .= html_writer::end_tag('div');

            $content .= html_writer::end_tag('div');
        }
        else{ // List of student's works
            $content .= html_writer::tag('h1', 'Научно-исследовательские работы');

            $content .= render_student_info($student_info);

            $works = DataGateway::get_list_nir_by_student($student_id);
        
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

        $groups = DataGateway::get_groups();

        $content .= html_writer::start_tag('div', array('id' => 'cssmenu'));
        $content .= html_writer::start_tag('ul');

        foreach($groups as $grp){
            $content .= html_writer::start_tag('li', array('class' => 'has-sub'));
            $content .= html_writer::start_tag('a', array('href' => '#'));
            $content .= html_writer::tag('span', $grp->data);
            $content .= html_writer::end_tag('a');

            $content .= html_writer::start_tag('ul');

            $users_group = DataGateway::get_students_by_group($grp->data);
            
            foreach($users_group as $u){
                $count = DataGateway::get_number_files_student_signed_teacher($u->id);

                $sql_work_plan = "SELECT mdl_nir_work_plans.id FROM {nir}, {nir_work_plans} WHERE mdl_nir.user_id = ? AND mdl_nir.is_closed = 0 
                                    AND mdl_nir_work_plans.nir_id = mdl_nir.id AND mdl_nir_work_plans.is_sign_user = 1 AND mdl_nir_work_plans.is_sign_teacher = 1 
                                    AND mdl_nir_work_plans.is_sign_kaf = 0";
                $work_plan = $DB->get_record_sql($sql_work_plan, array($u->id));

                $url = "/nir/index.php?std=".$u->id;

                $content .= html_writer::start_tag('li');
                $content .= html_writer::start_tag('a', array('href' => $url));
                $content .= html_writer::tag('span', $u->lastname." ".$u->firstname);

                if($count->count > 0 || $work_plan){
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

    if(!intval($_GET['id'])){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $work_id = $_GET["id"];
    
    if(isset($_GET["std"])){
        if(!intval($_GET['std'])){
            echo html_writer::tag('h3', '404 NOT FOUND');
            echo $OUTPUT->footer();
            exit();
        }

        $student_id = $_GET["std"];

        $work = DataGateway::get_nir_by_student_and_teacher($student_id, $USER->id, $work_id);
    }
    else{
        $work = DataGateway::get_nir_by_student($USER->id, $work_id);
    }

    if(!$work){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $messages_type_z = DataGateway::get_comments_limit($work_id, 'Z');

    $files_type_o = DataGateway::get_files_by_type($USER->id, $work_id, 'O');
    $messages_type_o = DataGateway::get_comments_limit($work_id, 'O');

    $files_type_o = DataGateway::get_files_by_type($USER->id, $work_id, 'P');
    $messages_type_p = DataGateway::get_comments_limit($work_id, 'P');

    $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

    $content .= html_writer::start_tag('p', array('class' => 'single_work_title'));
    $content .= html_writer::tag('span', 'Описание: ', array('class' => 'single_work_title_title'));
    $content .= $work->title;
    $content .= html_writer::end_tag('p');

    if(isset($_GET["std"])){
        $content .= render_student_info($work);
    }
    else{
        $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
        $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
        $content .= $work->lastname." ".$work->firstname;
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

    $content .= render_tab(null, $messages_type_z, $work, $USER, $work_id, array ('tab_id' => 'tab1', 'tab_number' => 1, 'image_path' => 'img/Filetype-Docs-icon.png',
        'file_type_name' => 'Задание', 'filer_input_id' => 'filer_input2', 'work_input_id' => 'h_work', 'work_input_type' => 'h_work_type', 'work_type' => 'Z',
        'message_textarea_id' => 'message_textarea_tab1', 'send_message_id' => 'send_message_tab1'));

    $content .= render_tab($files_type_o, $messages_type_o, $work, $USER, $work_id, array ('tab_id' => 'tab2', 'tab_number' => 2, 'image_path' => 'img/Filetype-Docs-icon.png',
        'file_type_name' => 'Отчет', 'filer_input_id' => 'filer_input1', 'work_input_id' => 'h_work_2', 'work_input_type' => 'h_work_type_2', 'work_type' => 'O',
        'message_textarea_id' => 'message_textarea_tab2', 'send_message_id' => 'send_message_tab2'));

    $content .= render_tab($files_type_p, $messages_type_p, $work, $USER, $work_id, array ('tab_id' => 'tab3', 'tab_number' => 3, 'image_path' => 'img/Filetype-Docs-icon.png',
        'file_type_name' => 'Презентация', 'filer_input_id' => 'filer_input3', 'work_input_id' => 'h_work_3', 'work_input_type' => 'h_work_type_3', 'work_type' => 'P',
        'message_textarea_id' => 'message_textarea_tab3', 'send_message_id' => 'send_message_tab3'));


    $content .= html_writer::end_tag('div');
    $content .= html_writer::end_tag('div');

    if ($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER && $work->is_closed == 0){
        $content .= html_writer::tag('p', 'Завершить работу', array('class' => 'finish_work_button'));
        $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_f', 'id' => 'work_f', 'value' => $work_id));
    }
}
else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){ // Main page for teacher
    //Доступ к дополнительным полям, в данном случае к группе
    //echo $USER->profile[Config::FIELD_USER_TYPE_NAME];
    if(isset($_GET["std"])){ // List of student's works for the current teacher

        if(!intval($_GET['std'])){
            echo html_writer::tag('h3', '404 NOT FOUND');
            echo $OUTPUT->footer();
            exit();
        }

        $student_id = $_GET["std"];

        $works = DataGateway::get_list_nir_by_student_and_teacher($student_id, $USER->id);

        $content .= html_writer::tag('h1', 'Научно-исследовательские работы');
    
        foreach ($works as $wk){
            $count_new_file = DataGateway::get_number_new_files_uploaded_user_by_nir($wk->id, $USER->id);

            $work_plan = DataGateway::get_work_plan_by_nir($wk->id);
            $work_plan_exist = ($work_plan->is_sign_user == 1 && $work_plan->is_sign_teacher == 0 && $work_plan->is_sign_kaf == 0) ? true : false;

            $url = '/nir/index.php?std='.$wk->student_id.'&id='.$wk->id;

            $content .= html_writer::start_tag('a', array('href' => $url));
            $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

            $content .= render_header_work_block($wk, true);
            $content .= render_work_block_title_new_files($count_new_file, $work_plan_exist);

            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('a');
        }
    
    }
    else{ // List of teacher's students
        $users_of_teacher = DataGateway::get_students_by_teacher($USER->id);

        $content .= html_writer::tag('h1', 'Студенты');
        
        foreach ($users_of_teacher as $us){
            $count_n_f = DataGateway::get_number_new_files_uploaded_student($us->id, $USER->id);

            $sql_work_plan = "SELECT mdl_nir_work_plans.id FROM {nir}, {nir_work_plans} WHERE mdl_nir.user_id = ? AND mdl_nir.is_closed = 0 AND mdl_nir.teacher_id = ? 
                                AND mdl_nir_work_plans.nir_id = mdl_nir.id AND mdl_nir_work_plans.is_sign_user = 1 AND mdl_nir_work_plans.is_sign_teacher = 0 AND 
                                mdl_nir_work_plans.is_sign_kaf = 0";
            $work_plan = $DB->get_record_sql($sql_work_plan, array($us->id, $USER->id));

            $url = '/nir/index.php?std='.$us->id;

            $content .= html_writer::start_tag('a', array('href' => $url));
            $content .= html_writer::start_tag('div', array('class' => 'users_list_el'));
            $content .= html_writer::tag('span', $us->lastname." ".$us->firstname, array('style' => 'float:left'));
            $content .= $us->data;

            if($count_n_f->count > 0 || $work_plan){
                $content .= html_writer::empty_tag('img', array('src' => 'img/report-3-xxl.png', 'height' => '25px', 'title' => 'Добавлен новый документ'));
            }

            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('a');
        }
    }
}
else{ // Main page for student with list of his works
    $works = DataGateway::get_list_nir_by_student($USER->id);

    $content .= html_writer::tag('h1', 'Научно-исследовательские работы');
    
    $count_open_works = 0;
    
    foreach ($works as $wk){
        $count_new_file = DataGateway::get_number_new_files_uploaded_user_by_nir($USER->id, $wk->id);

        $work_plan = DataGateway::get_work_plan_by_nir($wk->id);
        $work_plan_exist = ($work_plan->is_sign_user == 0 && $work_plan->is_sign_teacher == 0 && $work_plan->is_sign_kaf == 0) ? true : false;

        $url = '/nir/index.php?id='.$wk->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

        if($wk->is_closed != 1)
            $count_open_works++;

        $content .= render_header_work_block($wk);
        $content .= render_work_block_title_new_files($count_new_file, $work_plan_exist);

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }

    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::tag('br');
    
    if($count_open_works === 0)
    {
        $teachers = DataGateway::get_teachers();
        // Modal window for create work
        $content .= render_modal_dialog_create_work($teachers, $USER->id);
    }
}

echo $content;
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

