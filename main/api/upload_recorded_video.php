<?php

include_once("../include_files.php");

header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);    

set_error_handler("someFunction");

$test_id = $_POST['test_id'];
$jobseeker_id = $_POST['jobseeker_id'];
$question_id = $_POST['question_id'];
$assessment_id = $_POST['assessment_id'];
$is_same_window = (isset($_POST['same_window']) ? tep_db_prepare_input($_POST['same_window']) : null);

function someFunction($errno, $errstr) {
    echo '<h2>Upload failed.</h2><br>';
    echo '<p>'.$errstr.'</p>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $ip_address = getenv("REMOTE_ADDR");

    if ($_SERVER['SERVER_NAME'] != 'localhost') {
        $client_ip = ip2long($ip_address);
    } else {
        $client_ip = null;
    }

    /*function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }*/

    function selfInvoker(int $test_id, int $jobseeker_id, int $question_id, int $assessment_id, $client_ip = null, $is_same_window)
    {
        $currentDate = date("Y-m-d H:i:s");

        if (!isset($test_id)) {
            echo 'test_id is required';
            return;
        }

        if (!isset($jobseeker_id)) {
            echo 'jobseeker_id is required';
            return;
        }

        if (!isset($question_id)) {
            echo 'question_id is required';
            return;
        }

        if (!isset($_POST['audio-filename']) && !isset($_POST['video-filename'])) {
            echo 'Empty file name.';
            return;
        }
    
        // do NOT allow empty file names
        if (empty($_POST['audio-filename']) && empty($_POST['video-filename'])) {
            echo 'Empty file name.';
            return;
        }
    
        // do NOT allow third party audio uploads
        if (false && isset($_POST['audio-filename']) && strrpos($_POST['audio-filename'], "RecordRTC-") !== 0) {
            echo 'File name must start with "RecordRTC-"';
            return;
        }
    
        // do NOT allow third party video uploads
        if (false && isset($_POST['video-filename']) && strrpos($_POST['video-filename'], "RecordRTC-") !== 0) {
            echo 'File name must start with "RecordRTC-"';
            return;
        }
        
        $fileName = '';
        $tempName = '';
        $file_idx = '';
        
        if (!empty($_FILES['audio-blob'])) {
            $file_idx = 'audio-blob';
            $fileName = $_POST['audio-filename'];
            $tempName = $_FILES[$file_idx]['tmp_name'];
        } else {
            $file_idx = 'video-blob';
            $fileName = $_POST['video-filename'];
            $tempName = $_FILES[$file_idx]['tmp_name'];
            $fileSize = $_FILES[$file_idx]['size'];
        }
        
        if (empty($fileName) || empty($tempName)) {
            if(empty($tempName)) {
                echo 'Invalid temp_name: '.$tempName;
                return;
            }
    
            echo 'Invalid file name: '.$fileName;
            return;
        }
    
        /*
        $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
    
        if ($_FILES[$file_idx]['size'] > $upload_max_filesize) {
           echo 'upload_max_filesize exceeded.';
           return;
        }
    
        $post_max_size = return_bytes(ini_get('post_max_size'));
    
        if ($_FILES[$file_idx]['size'] > $post_max_size) {
           echo 'post_max_size exceeded.';
           return;
        }
        */
    
        $filePath = '../uploads/videos/'.$fileName;
        
        // make sure that one can upload only allowed audio/video files
        $allowed = array(
            'webm',
            'wav',
            'mp4',
            'mkv',
            'mp3',
            'ogg'
        );
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!$extension || empty($extension) || !in_array($extension, $allowed)) {
            echo 'Invalid file extension: '.$extension;
            return;
        }
        
        if (!move_uploaded_file($tempName, $filePath)) {
            if(!empty($_FILES["file"]["error"])) {
                $listOfErrors = array(
                    '1' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                    '2' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                    '3' => 'The uploaded file was only partially uploaded.',
                    '4' => 'No file was uploaded.',
                    '6' => 'Missing a temporary folder. Introduced in PHP 5.0.3.',
                    '7' => 'Failed to write file to disk. Introduced in PHP 5.1.0.',
                    '8' => 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.'
                );
                $error = $_FILES["file"]["error"];
    
                if(!empty($listOfErrors[$error])) {
                    echo $listOfErrors[$error];
                }
                else {
                    echo 'Not uploaded because of error #'.$_FILES["file"]["error"];
                }
            }
            else {
                echo 'Problem saving file: '.$tempName;
            }
            return;
        }

        $vidData = [
            'file_name'     => $fileName,
            'file_path'     => 'uploads/videos/'.$fileName,
            'size'          => $fileSize,
            'mime_type'     => $extension,
            'assessment_id' => $assessment_id,
            'quiz_id'       => $test_id,
            'question_id'   => $question_id,
            'jobseeker_id'  => $jobseeker_id,
            'created_at'    => $currentDate,
            'updated_at'    => $currentDate,
        ];
        
        store_video_with_result($vidData, $client_ip, $is_same_window);

        echo 'success'; // do not change this response
    }


    function store_video_with_result($data, $ip = null, $is_same_window)
    {
        // store data in quiz_videos table
        tep_db_perform(TEST_VIDEO_TABLE, $data);

        // then store in result table with some selected values
        tep_db_perform(QUIZ_RESULT_TABLE, [
            'quiz_id'      => $data['quiz_id'],
            'assessment_id'=> $data['assessment_id'],
            'member_id'    => $data['jobseeker_id'],
            'ip_address'   => $ip,
            'device_on_same_window' => $is_same_window,
            'created_at'   => $data['created_at'],
            'updated_at'   => $data['updated_at'],
        ]);

        return;

    }

    if (tep_not_null($test_id) AND tep_not_null($jobseeker_id) AND tep_not_null($question_id)) {
        selfInvoker($test_id, $jobseeker_id, $question_id, $assessment_id, $client_ip, $is_same_window);
    } else {
        $err = ["message" => "Something went wrong! maybe test_id, question_id and jobseeker_id is missing"];
        echo json_encode($err);
    }
} else {
    echo $_SERVER['REQUEST_METHOD'] . ' method are not allowed';
}



?>