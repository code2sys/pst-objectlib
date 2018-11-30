<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/25/18
 * Time: 7:07 AM
 */

namespace PST;

use \PDO;
use \PDOException;


class PageSectionFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PageSectionObject", $table = "page_section", $id = "page_section_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "page_id", "ordinal", "type", "slider_seconds"
        );
    }
}
