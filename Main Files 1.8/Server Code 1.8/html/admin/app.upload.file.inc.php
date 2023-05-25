<?php
/*
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}
*/
    $imgFileUrl = "";
    $videoFileUrl = "";

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "error_description" => '');

    $error = false;
    $error_code = ERROR_UNKNOWN;
    $error_description = "";

if (!empty($_POST)) {

    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    if (!empty($_FILES['uploaded_video_file']['name']) && $accessToken == API_KEY_SERVER) {

        switch ($_FILES['uploaded_video_file']['error']) {

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

        if ($_FILES['uploaded_video_file']['size'] > FILE_VIDEO_MAX_UPLOAD_SIZE) {

            $error = true;
            $error_code = ERROR_UNKNOWN;
            $error_description = 'File size is big.';
        }

        if (!$error) {

            $currentTime = time();
            $uploaded_file_ext = @pathinfo($_FILES['uploaded_video_file']['name'], PATHINFO_EXTENSION);
            $uploaded_file_ext = strtolower($uploaded_file_ext);

            if ($uploaded_file_ext === "mp4" || $uploaded_file_ext === "mov") {

                if (@move_uploaded_file($_FILES['uploaded_video_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                    $cdn = new cdn($dbo);

                    $response = $cdn->uploadVideo(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                    if (!$response['error']) {

                        $error = false;
                        $error_code = ERROR_SUCCESS;
                        $error_description = 'ok.';

                        $videoFileUrl = $response['fileUrl'];

                        if (isset($_FILES['uploaded_file']['name'])) {

                            $currentTime = time();
                            $uploaded_file_ext = @pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
                            $uploaded_file_ext = strtolower($uploaded_file_ext);

                            if ($uploaded_file_ext === "jpg") {

                                if (@move_uploaded_file($_FILES['uploaded_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                                    $response = $cdn->uploadVideoImg(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                                    if (!$response['error']) {

                                        $imgFileUrl = $response['fileUrl'];
                                    }
                                }
                            }
                        }
                    }

                    unset($cdn);

                } else {

                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = 'Cannot save file on server.';
                }

            } else {

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'Error file format.';
            }
        }
    }

    $result = array(
        "error" => $error,
        "error_code" => $error_code,
        "error_description" => $error_description,
        "imgFileUrl" => $imgFileUrl,
        "videoFileUrl" => $videoFileUrl);

    echo json_encode($result);
    exit;
}
