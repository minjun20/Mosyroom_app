<?php

class stats extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    public function getGcmHistory()
    {
        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "data" => array());

        $stmt = $this->db->prepare("SELECT * FROM gcm_history ORDER BY id DESC LIMIT 20");

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {;

                $dataInfo = array("id" => $row['id'],
                                  "msgTitle" => $row['msgTitle'],
                                  "msg" => $row['msg'],
                                  "toId" => $row['toId'],
                                  "createAt" => $row['createAt']);

                array_push($result['data'], $dataInfo);

                unset($dataInfo);
            }
        }

        return $result;
    }

    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------

        public function addUser($userId)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $online = 1;

        $stmt = $this->db->prepare("INSERT INTO users (userId, online) value (:userId, :online)");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":online", $online, PDO::PARAM_INT);

        if ($stmt->execute()) {

            //$this->setId($this->db->lastInsertId());

            $result = array("error" => false,
                            'userId' => $userId,
                            'online' => $online,
                            'error_code' => ERROR_SUCCESS);

            return $result;
        }

        return $result;
    }

        public function UpdateUserOnline($userId, $online)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);


        $stmt = $this->db->prepare("UPDATE users SET online = (:online) WHERE userId = (:userId)");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":online", $online, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;

    }

        public function isUserOnline($userId)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $online = 0;

        $stmt = $this->db->prepare("SELECT online FROM users WHERE userId = (:userId)");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
                $online = $row['online'];
            }

            $result = array("error" => false,
                            'userId' => $userId,
                            'online' => $online,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;

    }


}

