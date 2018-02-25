<?php
// The number of lines in front of config file determine the // hierarchy of files.
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');
require_once('class.datagateway.php');
require_once('class.render.php');
require_once('class.cssbuilder.php');

CssBuilder::build(array("menu_kaf", "modal_dialog", "tabs", "main"));

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nirtest/index.php');

$PAGE->requires->css('/nirtest/material/jquery.filer.css');
$PAGE->requires->css('/nirtest/material/jquery.filer-dragdropbox-theme.css');
$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/material/jquery.filer.min.js', true);
$PAGE->requires->js('/nirtest/js/MessageArea.js', true);
$PAGE->requires->js('/nirtest/js/resource.js', true);
$PAGE->requires->js('/nirtest/js/main.js', true);
$PAGE->requires->js('/nirtest/js/accordionList.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$previewnode = $PAGE->navigation->add("НИР", new moodle_url('/nirtest/index.php'), navigation_node::TYPE_CONTAINER);
$previewnode->make_active();

echo $OUTPUT->header();

$content = '';

// Page kafedra
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA) {
    $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

    $groups = DataGateway::get_groups();

    $content .= html_writer::start_tag('div', array('id' => 'cssmenu'));
    $content .= html_writer::start_tag('ul');

    foreach ($groups as $grp) {
        $content .= html_writer::start_tag('li', array('class' => 'has-sub'));
        $content .= html_writer::start_tag('a', array('href' => '#'));
        $content .= html_writer::tag('span', $grp->data);
        $content .= html_writer::end_tag('a');

        $content .= html_writer::start_tag('ul');

        $users_group = DataGateway::get_students_by_group($grp->data);

        foreach ($users_group as $u) {
            $count = DataGateway::get_number_files_student_signed_teacher($u->id);
            $work_plan = DataGateway::get_work_plan_by_student($u->id);

            $url = "/nirtest/works.php?std=" . $u->id;

            $content .= html_writer::start_tag('li');
            $content .= html_writer::start_tag('a', array('href' => $url));
            $content .= html_writer::tag('span', $u->lastname . " " . $u->firstname);

            if ($count->count > 0 || ($work_plan->is_sign_user == 1 && $work_plan->is_sign_teacher == 1 && $work_plan->is_sign_kaf == 0)) {
                $content .= html_writer::start_tag('div', array('class' => 'sign_files_kaf_icon'));
                $content .= html_writer::empty_tag('img', array('src' => 'img/report.png', 'height' => '25px', 'title' => 'Добавлен новый документ'));
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
else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER) { // Page for teacher
    // List of teacher's students
    $users_of_teacher = DataGateway::get_students_by_teacher($USER->id);

    $content .= html_writer::tag('h1', 'Студенты');

    foreach ($users_of_teacher as $us) {
        $count_n_f = DataGateway::get_number_new_files_uploaded_student($us->id, $USER->id);
        $work_plan = DataGateway::get_work_plan_by_student_and_teacher($us->id, $USER->id);

        $url = '/nirtest/works.php?std=' . $us->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => 'users_list_el'));
        $content .= html_writer::tag('span', $us->lastname . " " . $us->firstname, array('style' => 'float:left'));
        $content .= $us->data;

        if ($count_n_f->count > 0 || ($work_plan->is_sign_user == 1 && $work_plan->is_sign_teacher == 0 && $work_plan->is_sign_kaf == 0)) {
            $content .= html_writer::empty_tag('img', array('src' => 'img/report.png', 'height' => '25px', 'title' => 'Добавлен новый документ'));
        }

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }
}
else{ // Page for student with list of his works
    $works = DataGateway::get_list_nir_by_student($USER->id);

    $content .= html_writer::tag('h1', 'Научно-исследовательские работы');

    $count_open_works = 0;

    foreach ($works as $wk){
        $count_new_file = DataGateway::get_number_new_files_uploaded_user_by_nir($USER->id, $wk->id);

        $work_plan = DataGateway::get_work_plan_by_nir($wk->id);
        $work_plan_exist = ($work_plan && $work_plan->is_sign_user == 0 && $work_plan->is_sign_teacher == 0 && $work_plan->is_sign_kaf == 0) ? true : false;

        $url = '/nirtest/work.php?id='.$wk->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

        $content .= Render::render_header_work_block($wk);

        if($wk->is_closed == 0){
            $content .= Render::render_work_block_title_new_files($count_new_file, $work_plan_exist);
            $count_open_works++;
        }

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }

    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::tag('br');

    if($count_open_works === 0)
    {
        $teachers = DataGateway::get_teachers();
        // Modal window for create work
        $content .= Render::render_modal_dialog_create_work($teachers, $USER->id);
    }
}

echo $content;
echo $OUTPUT->footer();
?>
