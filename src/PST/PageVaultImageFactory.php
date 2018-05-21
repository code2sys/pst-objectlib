<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 5/20/18
 * Time: 6:58 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class PageVaultImageFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PageVaultImageObject", $table = "page_vault_image", $id = "page_vault_image_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "image_name", "description", "priority_number", "page_section_id"
        );
    }

    public function getNextOrdinal($page_section_id) {
        $stmt = $this->dbh->prepare("Select max(`priority_number`) from page_vault_image where page_section_id = ?");
        $stmt->bindValue(1, $page_section_id);
        $stmt->execute();
        return 1 + $stmt->fetch(PDO::FETCH_COLUMN);
    }
}