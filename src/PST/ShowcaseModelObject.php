<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/18/18
 * Time: 2:23 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class ShowcaseModelObject extends ShowcaseAbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function disable() {

    }
    public function doFullURL() {
        $this->addToParentFullURL("showcasemachinetype", "showcasemachinetype_id");
    }

}