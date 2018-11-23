<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/17/18
 * Time: 5:33 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class ShowcaseTrimObject extends ShowcaseAbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function disable() {

    }
    public function doFullURL() {
        $this->addToParentFullURL("showcasemodel", "showcasemodel_id");
    }

    public function addDecorations() {
        $showcasemodel = $this->factory()->master()->showcasemodel()->get($this->get("showcasemodel_id"));
        $this->set("model", $showcasemodel->get("title"));
        $showcasemachinetype = $this->factory()->master()->showcasemachinetype()->get($showcasemodel->get("showcasemachinetype_id"));
        $this->set("type", $showcasemachinetype->get("title"));
        $showcasemake = $this->factory()->master()->showcasemake()->get($showcasemachinetype->get("showcasemake_id"));
        $this->set("make", $showcasemake->get("title"));
        $this->set("year", $showcasemodel->get("year"));
        $this->set("condition", 1);
    }

}