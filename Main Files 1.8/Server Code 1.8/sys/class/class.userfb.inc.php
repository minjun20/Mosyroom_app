<?php

class userfb extends db_connect
{


    public function __construct($dbo = NULL)
    {

        parent::__construct($dbo);

    }

    public function get($id)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM userfb WHERE id = (:id) LIMIT 1");
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


        public function add($id, $text)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);


        $stmt = $this->db->prepare("REPLACE INTO userfb (id, text) value (:id, :text)");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":text", $text, PDO::PARAM_STR);

        if ($stmt->execute()) {


            $result = array("error" => false,
                            'id' => $id,
                            'text' => $text
                        );

            return $result;
        }

        return $result;
    }


/*    public function remove($categoryId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE category SET removeAt = (:removeAt) WHERE id = (:categoryId)");
        $stmt->bindParam(":categoryId", $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }
*/

    public function search($query)
    {
        $result = array("error" => true,
                        "ids" => array()
                    );

        $query = '%'.$query.'%';

        $stmt = $this->db->prepare("SELECT id FROM userfb WHERE text LIKE :query LIMIT 100");
        $stmt->bindParam(":query", $query, PDO::PARAM_STR);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                array_push($result['ids'], $row['id']);

            }
        }

        return $result;
    }
    
}

