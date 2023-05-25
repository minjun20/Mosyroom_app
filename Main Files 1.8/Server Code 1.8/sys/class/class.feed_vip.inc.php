<?php

class feed_vip extends db_connect
{


    public function __construct($dbo = NULL)
    {

        parent::__construct($dbo);

    }

    public function get($id)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM feeds_vip WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "text" => $row['text']
                            );
            }
        }

        return $result;
    }


        public function add($id, $text, $userId, $createAt)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);


        $stmt = $this->db->prepare("REPLACE INTO feeds_vip (id, text, userId, createAt) value (:id, :text, :userId, :createAt)");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":text", $text, PDO::PARAM_STR);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $createAt, PDO::PARAM_INT);

        if ($stmt->execute()) {


            $result = array("error" => false,
                            'id' => $id,
                            'text' => $text
                        );

            return $result;
        }

        return $result;
    }

    public function search($query)
    {
        $result = array("error" => true,
                        "ids" => array()
                    );

        $query = '%'.$query.'%';

        $stmt = $this->db->prepare("SELECT id FROM feeds_vip WHERE text LIKE :query LIMIT 100");
        $stmt->bindParam(":query", $query, PDO::PARAM_STR);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                array_push($result['ids'], $row['id']);

            }
        }

        return $result;
    }

        public function getByUserId($userId, $createAt)
    {
        $result = array();

        $stmt = $this->db->prepare("SELECT id, createAt FROM feeds_vip WHERE userId = (:userId) AND createAt < (:createAt) ORDER BY createAt  DESC LIMIT 5");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $createAt, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                array_push($result, array($row['id'] => $row['createAt']));

            }
        }

        return $result;
    }

    
}

