<?php

class cdn extends db_connect
{
    private $ftp_url = "";
    private $ftp_server = "";
    private $ftp_user_name = "";
    private $ftp_user_pass = "";
    private $cdn_server = "";
    private $conn_id = false;

    public function __construct($dbo)
    {
        if (strlen($this->ftp_server) > 0) {

            $this->conn_id = @ftp_connect($this->ftp_server);
        }

        parent::__construct($dbo);
    }


    public function uploadVideoImg($imgFilename)
    {
        rename($imgFilename, VIDEO_IMAGE_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".VIDEO_IMAGE_PATH.basename($imgFilename));

        @unlink($imgFilename);

        return $result;
    }


    public function uploadFeedImg($imgFilename)
    {
        rename($imgFilename, FEED_IMAGE_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".FEED_IMAGE_PATH.basename($imgFilename));

        @unlink($imgFilename);

        return $result;
    }

    public function uploadVideo($imgFilename)
    {
        rename($imgFilename, VIDEO_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".VIDEO_PATH.basename($imgFilename));

        @unlink($imgFilename);

        return $result;
    }

}
