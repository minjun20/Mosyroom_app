<?php

class gcm extends db_connect
{
    private $data = array();

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    private function addToHistory($msgTitle, $msg, $toId)
    {
            $currentTime = time();

            $stmt = $this->db->prepare("INSERT INTO gcm_history (msgTitle, msg, toId, createAt) value (:msgTitle, :msg, :toId, :createAt)");
            $stmt->bindParam(":msgTitle", $msgTitle, PDO::PARAM_STR);
            $stmt->bindParam(":msg", $msg, PDO::PARAM_STR);
            $stmt->bindParam(":toId", $toId, PDO::PARAM_STR);
            $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
            $stmt->execute();
    }

    public function setData($title, $msg, $toId)
    {
        $this->data = array("title" => $title,
                            "msg" => $msg,
                            "toId" => $toId
                        );

        $this->addToHistory($this->data['title'], $this->data['msg'], $this->data['toId']);

    }

    public function getData()
    {
        return $this->data;
    }

    public function clearData()
    {
        $this->data = array();
    }
}