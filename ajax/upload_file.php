<?php
    require_once('../class.datagateway.php');
    require_once('../class.uploader.php');
    require_once(dirname(__FILE__) . '/../../config.php');

    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 50, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => array('txt', 'doc', 'docx', 'docm', 'odt', 'pages', 'pdf', 'ppt', 'pptx', 'key'), //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../uploads/', //Upload directory {String}
        'title' => '{{file_name}}_{{date}}_{{timestamp}}', //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'replace' => false, //Replace the file if it already exists  {Boolean}
        'perms' => null, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => add_file_db, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));

    if($data['isComplete']){
        $files = $data['data'];

        echo json_encode($files['metas'][0]['name']);
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        echo json_encode($errors);
    }
    
    function add_file_db($file){
        global $USER, $DB;
        
        $work_id = (int) $_POST['work'];
        $type = $_POST['type'];

        $rs = DataGateway::get_nir_by_user($USER->id, $work_id);
        
        if(!$rs){
            echo "Error";
            exit;
        }
        
        $record = new stdClass();
        $record->filename = "uploads/".substr($file[0], 11);
        $record->user_id = $USER->id;
        $record->nir_id = (int)$work_id;
        $record->type = $type;
        
        $DB->insert_record('nir_files', $record, false);

        //echo $file[0];
    }

   //exit;
?>
