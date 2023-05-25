<?php


class update extends db_connect
{
    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    // For version 1.2 
    // update feeds table
    function addColumnToFeedsTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE feeds ADD createAt bigint(16) UNSIGNED DEFAULT 0 after text");
        $stmt->execute();
    }

    function addColumnToFeedsTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE feeds ADD userId text CHARACTER SET utf8 NOT NULL after text");
        $stmt->execute();
    }

    // update feeds_vip table
    function addColumnToFeedsVipTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE feeds_vip ADD createAt bigint(16) UNSIGNED DEFAULT 0 after text");
        $stmt->execute();
    }

    function addColumnToFeedsVipTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE feeds_vip ADD userId text CHARACTER SET utf8 NOT NULL after text");
        $stmt->execute();
    }


}
