<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/17/18
 * Time: 8:40 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class ShowcaseAbstractObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function disable() {

    }

    public function fixURLTitle() {
        if ($this->get("url_title") != "") {
            $this->doFullURL();
            return;
        }

        $url_candidate = preg_replace("/[^a-z0-9\-]+/i", "_", $this->get("title"));

        $matches = $this->factory()->fetch(array("url_title" => $url_candidate));

        if (count($matches) > 0 && $matches[0]->id() != $this->id()) {
            $url_candidate = $url_candidate . "_" . $this->id();
            $matches = $this->factory()->fetch(array("url_title" => $url_candidate));

            $count = 0;
            $base_url_candidate = $url_candidate;
            while (count($matches) > 0 && $matches[0]->id() != $this->id()) {
                $count++;
                $url_candidate = $base_url_candidate . "_" . $count;
                $matches = $this->factory()->fetch(array("url_title" => $url_candidate));
            }
        }

        $this->set("url_title", $url_candidate);
        $this->save();

        $this->doFullURL();
    }

    public function doFullURL() {
        $this->set("full_url", $this->get("url_title"));
        $this->save();
    }

    public function addToParentFullURL($parent_factory, $id_column) {
        $parent = $this->factory()->master()->$parent_factory()->get($this->get($id_column));
        $this->set("full_url", $parent->get("full_url") . "/" . $this->get("url_title"));
        $this->save();
    }
}