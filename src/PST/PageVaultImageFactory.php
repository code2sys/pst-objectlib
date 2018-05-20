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
            "image_name"< "description", "priority_number", "page_section_id"
        );
    }
}