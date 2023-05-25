<?php
/*
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}
*/
    
    $imgFileUrls = array();

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "error_description" => '');

    $error = false;
    $error_code = ERROR_UNKNOWN;
    $error_description = "";

if (!empty($_POST)) {

    $images_count = isset($_POST['images_count']) ? $_POST['images_count'] : '-1';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';
    $images_count = intval($images_count);

    if (!empty($_FILES['uploaded_file_0']['name']) && $accessToken == API_KEY_SERVER && $images_count > -1 && $images_count < 5) {

        for ($i = 0; $i < $images_count; $i++) {

            $file_name = 'uploaded_file_'. $i;  

            switch ($_FILES[$file_name]['error']) {

                case UPLOAD_ERR_OK:

                    break;

                case UPLOAD_ERR_NO_FILE:

                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = 'No file sent.'; // No file sent.

                    break;

                case UPLOAD_ERR_INI_SIZE:
                    
                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = "Exceeded file size limit. UPLOAD_ERR_INI_SIZE";

                    break;

                case UPLOAD_ERR_FORM_SIZE:

                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = "Exceeded file size limit. UPLOAD_ERR_FORM_SIZE";

                    break;

                default:

                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = 'Unknown error.';
            }

            if ($_FILES[$file_name]['size'] > FILE_IMAGE_MAX_UPLOAD_SIZE) {

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'File size is big.';
            }
        }

        if (!$error) {

            for ($i = 0; $i < $images_count; $i++) {
                
                $file_name = 'uploaded_file_'. $i;  

                if (isset($_FILES[$file_name]['name'])) {

                    $currentTime = microtime();
                    $uploaded_file_ext = @pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
                    $uploaded_file_ext = strtolower($uploaded_file_ext);

                    if ($uploaded_file_ext === "jpg" || $uploaded_file_ext === "png" || $uploaded_file_ext === "jpeg" || $uploaded_file_ext === "JPG" || $uploaded_file_ext === "PNG" || $uploaded_file_ext === "JPEG") {

                        if (@move_uploaded_file($_FILES[$file_name]['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                            $cdn = new cdn($dbo);

                            $response = $cdn->uploadFeedImg(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                            if (!$response['error']) {

                                $imgFileUrls[] = $response['fileUrl'];

                            } else {

                                $error = true;
                                $error_code = ERROR_UNKNOWN;
                                $error_description = 'Error during upload.';
                                break;
                            }

                            unset($cdn);

                        } else {

                            $error = true;
                            $error_code = ERROR_UNKNOWN;
                            $error_description = 'Cannot save file on server.';
                            break;
                        }
                    } else {

                        $error = true;
                        $error_code = ERROR_UNKNOWN;
                        $error_description = 'Error file format.';
                        break;
                    }
                }
            }
                   
        }
    }

    $result = array(
        "error" => $error,
        "error_code" => $error_code,
        "error_description" => $error_description,
        "imgFileUrls" => $imgFileUrls);

    echo json_encode($result);
    exit;
}else{
    $result = array(
        "error" => true,
        "error_code" => 0,
        "error_description" => "No Post");

    echo json_encode($result);
    exit;
}
