<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');

class DataGateway
{
    /*
    table: mdl_user
    fields: ***
    */
    public static function get_user($user_id){
        global $DB;

        $user = $DB->get_record('user', array('id' => $user_id), '*', MUST_EXIST);

        return $user;
    }

    /*
    table: mdl_nir
    fields: id, user_id, teacher_id, title, is_closed, mark, review
    */
    public static function get_nir_by_id($nir_id){
        global $DB;

        $sql_nir = "SELECT * FROM {nir} WHERE id = ?";
        $nir = $DB->get_record_sql($sql_nir, array($nir_id));

        return $nir;
    }

    /*
    table: mdl_nir
    fields: id
    */
    public static function get_nir_by_user($user_id, $nir_id, $only_active = true){
        global $DB;

        $sql_nir = "SELECT mdl_nir.id FROM {nir} WHERE (mdl_nir.user_id = ? OR mdl_nir.teacher_id = ?) AND mdl_nir.id = ?";

        if($only_active)
            $sql_nir .= " AND mdl_nir.is_closed = 0";

        $nir = $DB->get_record_sql($sql_nir, array($user_id, $user_id, $nir_id));

        return $nir;
    }

    /*
    table: mdl_nir, mdl_user
    fields: nir_id, title, is_closed, mark, review, teacher_firstname, teacher_lastname, teacher_email, final_mark, status_completion, final_comment
    */
    public static function get_nir_by_student($student_id, $nir_id){
        global $DB;

        $sql_nir = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname,  
                            mdl_user.lastname, mdl_user.email, mdl_nir.final_mark, mdl_nir.status_completion, mdl_nir.final_comment  
                            FROM {nir}, {user} WHERE mdl_nir.user_id = ? AND mdl_user.id = mdl_nir.teacher_id AND mdl_nir.id = ?";
        $nir = $DB->get_record_sql($sql_nir, array($student_id, $nir_id));

        return $nir;
    }

    /*
    table: mdl_nir, mdl_user
    fields: nir_id, title, is_closed, mark, review, student_firstname, student_lastname
    */
    public static function get_nir_by_student_and_teacher($student_id, $teacher_id, $nir_id){
        global $DB;

        $sql_nir = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data 
                      FROM {nir}, {user}, {user_info_data} WHERE mdl_nir.user_id = ? AND mdl_nir.teacher_id = ?  AND 
                      mdl_user.id = mdl_nir.user_id AND mdl_nir.id = ? AND mdl_user_info_data.userid = ? AND mdl_user_info_data.fieldid = ?";

        $nir = $DB->get_record_sql($sql_nir, array($student_id, $teacher_id, $nir_id, $student_id, Config::FIELD_GROUP_ID));

        return $nir;
    }

    /*
    table: mdl_nir, mdl_user
    fields: nir_id, title, is_closed, teacher_firstname, teacher_lastname
    */
    public static function get_list_nir_by_student($student_id){
        global $DB;

        $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM {nir}, {user} 
                            WHERE mdl_nir.user_id = ? AND  mdl_user.id = mdl_nir.teacher_id";
        $works = $DB->get_records_sql($sql_works, array($student_id));

        return $works;
    }

    /*
    table: mdl_nir, mdl_user
    fields: nir_id, title, is_closed, student_firstname, student_lastname, student_id
    */
    public static function get_list_nir_by_student_and_teacher($student_id, $teacher_id){
        global $DB;

        $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname, mdl_user.id as student_id 
                        FROM {nir}, {user} WHERE mdl_nir.user_id = ? AND mdl_nir.teacher_id = ? AND  mdl_user.id = mdl_nir.user_id";
        $works = $DB->get_records_sql($sql_works, array($student_id, $teacher_id));

        return $works;
    }

    /*
    table: mdl_nir_work_plans
    fields: ***
    */
    public static function get_work_plan_by_nir($nir_id){
        global $DB;

        $sql_work_plan = "SELECT * FROM {nir_work_plans} WHERE mdl_nir_work_plans.nir_id = ?";
        $work_plan = $DB->get_record_sql($sql_work_plan, array($nir_id));

        return $work_plan;
    }

    /*
    table: mdl_nir, mdl_nir_work_plans
    fields: ***
    */
    public static function get_work_plan_by_nir_and_user($nir_id, $user_id, $only_active = true){
        global $DB;

        $sql_work_plan = "SELECT mdl_nir_work_plans.id, mdl_nir_work_plans.is_sign_user, mdl_nir_work_plans.is_sign_teacher, mdl_nir_work_plans.is_sign_kaf, 
                            mdl_nir_work_plans.theme, mdl_nir_work_plans.goal, mdl_nir.teacher_id 
                            FROM {nir}, {nir_work_plans} WHERE (mdl_nir.user_id = ? OR mdl_nir.teacher_id = ?) AND mdl_nir.id = ?  
                            AND mdl_nir_work_plans.nir_id = mdl_nir.id";

        if($only_active)
            $sql_work_plan .= " AND mdl_nir.is_closed = 0";

        $work_plan = $DB->get_record_sql($sql_work_plan, array($user_id, $user_id, $nir_id));

        return $work_plan;
    }

    /*
    table: mdl_nir_work_plans, mdl_nir
    fields: id
    */
    public static function get_work_plan_by_nir_and_teacher($nir_id, $user_id){
        global $DB;

        $sql_work_plan = "SELECT mdl_nir_work_plans.id FROM {nir_work_plans}, {nir} WHERE mdl_nir_work_plans.nir_id = ? AND 
                            mdl_nir.id = mdl_nir_work_plans.nir_id AND mdl_nir.teacher_id = ?";
        $work_plan = $DB->get_record_sql($sql_work_plan, array($nir_id, $user_id));

        return $work_plan;
    }

    /*
    table: mdl_nir, mdl_nir_work_plans
    fields: ***
    */
    public static function get_work_plan_by_student_and_teacher($student_id, $teacher_id){
        global $DB;

        $sql_work_plan = "SELECT mdl_nir_work_plans.id, mdl_nir_work_plans.is_sign_user, mdl_nir_work_plans.is_sign_teacher, mdl_nir_work_plans.is_sign_kaf, 
                                mdl_nir_work_plans.theme, mdl_nir_work_plans.goal, mdl_nir.teacher_id FROM {nir}, {nir_work_plans} WHERE mdl_nir.user_id = ? AND 
                                mdl_nir.is_closed = 0 AND mdl_nir.teacher_id = ? AND mdl_nir_work_plans.nir_id = mdl_nir.id";

        $work_plan = $DB->get_record_sql($sql_work_plan, array($student_id, $teacher_id));

        return $work_plan;
    }

    /*
    table: mdl_nir, mdl_nir_work_plans
    fields: ***
    */
    public static function get_work_plan_by_student($student_id){
        global $DB;

        $sql_work_plan = "SELECT mdl_nir_work_plans.id, mdl_nir_work_plans.is_sign_user, mdl_nir_work_plans.is_sign_teacher, mdl_nir_work_plans.is_sign_kaf, 
                                mdl_nir_work_plans.theme, mdl_nir_work_plans.goal, mdl_nir.teacher_id FROM {nir}, {nir_work_plans} WHERE mdl_nir.user_id = ? AND 
                                mdl_nir.is_closed = 0 AND mdl_nir_work_plans.nir_id = mdl_nir.id";

        $work_plan = $DB->get_record_sql($sql_work_plan, array($student_id));

        return $work_plan;
    }

    /*
    table: mdl_nir_user_info
    fields: id, user_id, work_plan_id, name, surname, patronymic, phone_number, mail
    */
    public static function get_student_info_by_work_plan($work_plan_id){
        global $DB;

        $sql_user_info = "SELECT * FROM {nir_user_info} WHERE mdl_nir_user_info.work_plan_id = ?";
        $user_info = $DB->get_record_sql($sql_user_info, array($work_plan_id));

        return $user_info;
    }

    /*
    table: mdl_nir_teacher_info
    fields: id, work_plan_id, name, surname, patronymic, phone_number, mail, place_work, position_work, academic_title, academic_degree, type
    */
    public static function get_teacher_info_by_work_plan($work_plan_id){
        global $DB;

        $sql_teacher_info = "SELECT * FROM {nir_teacher_info} WHERE mdl_nir_teacher_info.work_plan_id = ? AND mdl_nir_teacher_info.type = 'T'";
        $teacher_info = $DB->get_record_sql($sql_teacher_info, array($work_plan_id));

        return $teacher_info;
    }

    /*
    table: mdl_nir, mdl_user
    fields: firstname, lastname, email
    */
    public static function get_teacher_info_by_nir($nir_id){
        global $DB;

        $sql_info = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.email FROM {nir}, {user} 
                            WHERE mdl_nir.id = ? AND mdl_user.id = mdl_nir.teacher_id";

        $info = $DB->get_record_sql($sql_info, array($nir_id));

        return $info;
    }

    /*
    table: mdl_nir_teacher_info
    fields: id, work_plan_id, name, surname, patronymic, phone_number, mail, place_work, position_work, academic_title, academic_degree, type
    */
    public static function get_consultant_info_by_work_plan($work_plan_id){
        global $DB;

        $sql_consultant_info = "SELECT * FROM {nir_teacher_info} WHERE mdl_nir_teacher_info.work_plan_id = ? AND mdl_nir_teacher_info.type = 'C'";
        $consultant_info = $DB->get_record_sql($sql_consultant_info, array($work_plan_id));

        return $consultant_info;
    }

    /*
    table: mdl_nir_work_plan_items
    fields: id, type, text, work_plan_id, order_number
    */
    public static function get_work_plan_items_by_id($work_plan_id){
        global $DB;

        $sql_work_plan_items = "SELECT * FROM {nir_work_plan_items} WHERE mdl_nir_work_plan_items.work_plan_id = ?";
        $work_plan_items = $DB->get_records_sql($sql_work_plan_items, array($work_plan_id));

        return $work_plan_items;
    }

    /*
    table: mdl_nir_files
    fields: id, filename, user_id, nir_id, type, is_new, is_sign_teacher, is_sign_kaf, date
    */
    public static function get_file($user_id, $work_id, $type, $filename){
        global $DB;

        $sql_file = "SELECT * FROM {nir_files} WHERE mdl_nir_files.user_id = ? AND mdl_nir_files.nir_id = ? AND 
                      mdl_nir_files.type = ? AND mdl_nir_files.filename = ?";

        $file = $DB->get_record_sql($sql_file, array($user_id, $work_id, $type, $filename));

        return $file;
    }

    /*
    table: mdl_nir_files
    fields: id, filename, user_id, nir_id, type, is_new, is_sign_teacher, is_sign_kaf, date
    */
    public static function get_file_by_id($file_id){
        global $DB;

        $sql_file = "SELECT * FROM {nir_files} WHERE id = ?";

        $file = $DB->get_record_sql($sql_file, array($file_id));

        return $file;
    }

    /*
    table: mdl_nir_files, mdl_nir, mdl_user
    fields: file_id, filename, file_date, is_new, is_sign_kaf, firstname, lastname
    */
    public static function get_file_for_kafedra($nir_id, $type){
        global $DB;

        $sql_file = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.date, mdl_nir_files.is_new, mdl_nir_files.is_sign_kaf, mdl_user.firstname, mdl_user.lastname 
                          FROM {nir_files}, {nir}, {user} WHERE mdl_nir.id = ? AND mdl_nir_files.nir_id = ? AND mdl_nir_files.type = ? 
                          AND mdl_user.id = mdl_nir_files.user_id AND mdl_nir_files.is_sign_teacher = 1";
        $file = $DB->get_record_sql($sql_file, array($nir_id, $nir_id, $type));

        return $file;
    }

    /*
    table: mdl_nir_files, mdl_nir, mdl_user
    fields: ***
    */
    public static function get_files_by_type($user_id, $work_id, $type){
        global $DB;

        $sql_files = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.is_sign_kaf, mdl_nir_files.date, 
                          mdl_nir_files.is_new, mdl_nir_files.is_rejected, mdl_user.firstname,
                          mdl_user.lastname, mdl_user.id as user_id FROM {nir_files}, {nir}, {user} WHERE mdl_nir.id = ? AND 
                          (mdl_nir.teacher_id = ? OR  mdl_nir.user_id = ?) AND mdl_nir_files.nir_id = ? AND mdl_nir_files.type = ? 
                          AND mdl_user.id = mdl_nir_files.user_id ORDER BY mdl_nir_files.date";


        $files = $DB->get_records_sql($sql_files, array($work_id, $user_id, $user_id, $work_id, $type));

        return $files;
    }

    /*
    table: mdl_nir_files
    fields: ***
    */
    public static function get_number_files_by_nir($work_id){
        global $DB;

        $sql_count_files = "SELECT COUNT(*) as count FROM {nir_files} WHERE mdl_nir_files.nir_id = ?";

        $count = $DB->get_record_sql($sql_count_files, array($work_id));

        return $count;
    }

    /*
    table: mdl_nir_files, mdl_nir
    fields: count
    */
    public static function get_number_files_student_signed_teacher($student_id){
        global $DB;

        $sql_count_files = "SELECT COUNT(*) as count FROM {nir}, {nir_files} WHERE mdl_nir.user_id = ? AND mdl_nir.is_closed = 0
                                AND mdl_nir_files.nir_id = mdl_nir.id AND mdl_nir_files.is_sign_teacher = 1 AND mdl_nir_files.is_sign_kaf = 0";
        $count = $DB->get_record_sql($sql_count_files, array($student_id));

        return $count;
    }

    /*
    table: mdl_nir_files, mdl_nir
    fields: count
    */
    public static function get_number_new_files_uploaded_user_by_nir($teacher_id, $nir_id){
        global $DB;

        $sql_count_files = "SELECT COUNT(*) as count FROM {nir_files} WHERE nir_id = ? AND user_id != ? AND is_new = 1";
        $count = $DB->get_record_sql($sql_count_files, array($nir_id, $teacher_id));

        return $count;
    }

    /*
    table: mdl_nir_files, mdl_nir
    fields: count
    */
    public static function get_number_new_files_uploaded_student($student_id, $teacher_id){
        global $DB;

        $sql_count = "SELECT COUNT(*) as count FROM {nir_files}, {nir} WHERE mdl_nir_files.user_id = ? AND mdl_nir.teacher_id = ? 
                                AND mdl_nir.is_closed = 0 AND mdl_nir_files.is_new = 1 AND mdl_nir_files.nir_id = mdl_nir.id";
        $count = $DB->get_record_sql($sql_count, array($student_id, $teacher_id));

        return $count;
    }

    /*
    table: mdl_nir_messages, mdl_user
    fields: text, date, firstname, lastname, user_id
    */
    public static function get_comments_by_date($date, $work_id, $type){
        global $DB;

        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM {nir_messages}, {user} WHERE 
                          mdl_nir_messages.date > ? AND mdl_nir_messages.nir_id = ? AND mdl_user.id = mdl_nir_messages.user_id AND 
                          mdl_nir_messages.nir_type = ? ORDER BY mdl_nir_messages.date";

        $messages = $DB->get_records_sql($sql_messages, array($date, $work_id, $type));

        return $messages;
    }

    /*
    table: mdl_nir_messages, mdl_user
    fields: text, date, firstname, lastname, user_id
    */
    public static function get_comments($work_id, $type){
        global $DB;

        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM {nir_messages}, {user} WHERE 
                          mdl_nir_messages.nir_id = ? AND mdl_user.id = mdl_nir_messages.user_id AND mdl_nir_messages.nir_type = ? ORDER BY mdl_nir_messages.date";

        $messages = $DB->get_records_sql($sql_messages, array($work_id, $type));

        return $messages;
    }

    /*
    table: mdl_nir_messages
    fields: text, date
    */
    public static function get_comments_for_kafedra_by_date($date, $work_id, $user_id, $type){
        global $DB;

        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM {nir_messages} WHERE mdl_nir_messages.date > ? AND 
                            mdl_nir_messages.nir_id = ? AND mdl_nir_messages.user_id = ? AND mdl_nir_messages.nir_type = ? ORDER BY mdl_nir_messages.date";

        $messages = $DB->get_records_sql($sql_messages, array($date, $work_id, $user_id, $type));

        return $messages;
    }

    /*
    table: mdl_nir_messages
    fields: id, text, date
    */
    public static function get_comments_for_kafedra($work_id, $user_id, $type){
        global $DB;

        $sql_messages = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date FROM {nir_messages} WHERE mdl_nir_messages.nir_id = ? AND 
                            mdl_nir_messages.user_id = ? AND mdl_nir_messages.nir_type = ? ORDER BY mdl_nir_messages.date";

        $messages = $DB->get_records_sql($sql_messages, array($work_id, $user_id, $type));

        return $messages;
    }

    /*
    table: mdl_nir_messages, mdl_user
    fields: ***
    */
    public static function get_comments_limit_by_date($work_id, $type, $date){
        global $DB;

        $sql_messages = "SELECT * FROM (SELECT mdl_nir_messages.id as message_id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM 
                                {nir_messages}, {user} WHERE mdl_nir_messages.nir_id = ? AND mdl_user.id = mdl_nir_messages.user_id AND mdl_nir_messages.nir_type = ? 
                                AND mdl_nir_messages.date < ? ORDER BY mdl_nir_messages.id DESC LIMIT 6) as tmp ORDER BY tmp.date";

        $messages = $DB->get_records_sql($sql_messages, array($work_id, $type, $date));

        return $messages;
    }

    /*
    table: mdl_nir_messages, mdl_user
    fields: ***
    */
    public static function get_comments_limit($work_id, $type){
        global $DB;

        $sql_messages = "SELECT * FROM (SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM 
                            {nir_messages}, {user} WHERE mdl_nir_messages.nir_id= ? AND mdl_user.id = mdl_nir_messages.user_id AND mdl_nir_messages.nir_type = ? 
                            ORDER BY mdl_nir_messages.id DESC LIMIT 6) as tmp ORDER BY tmp.date";
        $messages = $DB->get_records_sql($sql_messages, array($work_id, $type));

        return $messages;
    }

    /*
    table: mdl_user_info_data, mdl_user
    fields: firstname, lastname, id, group
    */
    public static function get_student_info($student_id){
        global $DB;

        $sql_student = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id, mdl_user_info_data.data FROM {user}, {user_info_data} 
                          WHERE mdl_user.id = ? AND mdl_user_info_data.userid = mdl_user.id AND mdl_user_info_data.fieldid = ?";

        $student_info = $DB->get_record_sql($sql_student, array($student_id, Config::FIELD_GROUP_ID));

        return $student_info;
    }

    /*
    table: mdl_user_info_data
    fields: ***
    */
    public static function get_groups(){
        global $DB;

        $sql_groups = "SELECT DISTINCT mdl_user_info_data.data, mdl_nir_groups.is_active, mdl_nir_groups.id AS nir_group_id FROM mdl_user_info_data LEFT JOIN mdl_nir_groups 
                        ON mdl_user_info_data.data = mdl_nir_groups.group_name WHERE mdl_user_info_data.fieldid = ?  AND mdl_user_info_data.data != '' 
                        ORDER BY mdl_user_info_data.data";
        $groups = $DB->get_records_sql($sql_groups, array(Config::FIELD_GROUP_ID));

        return $groups;
    }

    /*
    table: mdl_nir_groups
    fields: ***
    */
    public static function get_nir_groups(){
        global $DB;

        $sql_groups = "SELECT * FROM mdl_nir_groups";
        $groups = $DB->get_records_sql($sql_groups);

        return $groups;
    }

    /*
    table: mdl_user, mdl_user_info_data
    fields: ***
    */
    public static function get_teachers(){
        global $DB;

        $sql_teachers = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM {user}, {user_info_data} WHERE mdl_user.deleted = 0 AND 
              mdl_user_info_data.userid = mdl_user.id AND mdl_user_info_data.fieldid = ? AND mdl_user_info_data.data = ?";
        $teachers = $DB->get_records_sql($sql_teachers, array(Config::FIELD_USER_TYPE_ID, Config::USER_TYPE_TEACHER));

        return $teachers;
    }

    /*
    table: mdl_user_info_data, mdl_user
    fields: ***
    */
    public static function get_students_by_group($group){
        global $DB;

        $sql_users = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM {user}, {user_info_data} 
                                    WHERE mdl_user.id = mdl_user_info_data.userid AND mdl_user_info_data.data = ? ORDER BY mdl_user.lastname";

        $users = $DB->get_records_sql($sql_users, array($group));

        return $users;
    }

    /*
    table: mdl_user_info_data, mdl_nir, mdl_user
    fields: ***
    */
    public static function get_students_by_teacher($teacher_id){
        global $DB;

        $sql_users = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM {nir}, {user}, {user_info_data} 
                                    WHERE mdl_nir.teacher_id = ? AND mdl_user.id = mdl_nir.user_id AND mdl_user_info_data.userid = mdl_nir.user_id AND 
                                    mdl_user_info_data.fieldid = ? ORDER BY mdl_user_info_data.data, mdl_user.lastname";

        $users = $DB->get_records_sql($sql_users, array($teacher_id, Config::FIELD_GROUP_ID));

        return $users;
    }
}