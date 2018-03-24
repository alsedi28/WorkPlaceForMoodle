<?php
// The number of lines in front of config file determine the // hierarchy of files.
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');
require_once('class.datagateway.php');
require_once('class.render.php');
require_once('class.cssbuilder.php');

CssBuilder::build(array("menu_kaf", "modal_dialog", "tabs", "main", "switch_control"));

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nirtest/index.php');

$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/js/accordionList.js', true);
$PAGE->requires->js('/nirtest/js/resource.js', true);
$PAGE->requires->js('/nirtest/js/indexPage.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$previewnode = $PAGE->navigation->add("НИР", new moodle_url('/nirtest/index.php'), navigation_node::TYPE_CONTAINER);
$previewnode->make_active();

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    $node_students_list = $previewnode->add("Настройки", new moodle_url('/nirtest/settings.php'));
    $node_students_list->make_inactive();
}

echo $OUTPUT->header();

$content = '';

// Page kafedra
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA) {
    $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

    $all_groups = DataGateway::get_groups();
    $groups = Helper::get_divided_groups_by_type($all_groups);

    $content .= Render::render_filter_block();

    $content .= Render::render_list_of_groups_and_students($groups["active"]);

    $content .= Render::render_list_of_groups_and_students($groups["not_active"], false);
}
else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER) { // Page for teacher
    // List of teacher's students
    $users_of_teacher = DataGateway::get_students_by_teacher($USER->id);

    $content .= html_writer::tag('h1', 'Студенты');

    $all_groups = DataGateway::get_groups();
    $groups = Helper::get_divided_groups_by_type($all_groups);

    $active_groups = array_map("Helper::get_group_name", $groups["active"]);
    $not_active_groups = array_map("Helper::get_group_name", $groups["not_active"]);

    $content .= Render::render_filter_block();

    $active_users = array();
    $not_active_users = array();

    foreach ($users_of_teacher as $us) {
        if (in_array($us->data, $active_groups))
            array_push($active_users, $us);
        else
            array_push($not_active_users, $us);
    }

    $content .= Render::render_list_of_students($active_users, $USER->id);
    $content .= Render::render_list_of_students($not_active_users, $USER->id, false);
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

        if($wk->is_closed == 0 && !$work_plan) {
            $count_files_in_nir = DataGateway::get_number_files_by_nir($wk->id);

            if ($count_files_in_nir->count == 0) {
                $content .= html_writer::start_tag('div', array('class' => 'button_delete_work', 'title' => 'Удалить'));
                $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'value' => $wk->id));
                $content .= html_writer::end_tag('div');
            }
        }
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
