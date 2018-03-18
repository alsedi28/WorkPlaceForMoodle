<?php

class Config
{
    const ADMIN = 2;
    const KAFEDRA = 88;

    const USER_TYPE_TEACHER = "1";
    const USER_TYPE_STUDENT = "0";
    const USER_TYPE_KAFEDRA = "666";

    const FIELD_GROUP_ID = 3; //id field for group of student in table mdl_user_info_field
    const FIELD_USER_TYPE_ID = 2; //id field for user type (student, teacher or kafedra) in table mdl_user_info_field
    const FIELD_USER_TYPE_NAME = "isTeacher"; //field name for user type (student, teacher or kafedra) in table mdl_user_info_field
    const FIELD_STUDENT_GROUP_NAME = "group"; //field name for student group in table mdl_user_info_field
}

?>