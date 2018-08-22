<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:35 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class MotorcycleObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function removeCRSTrim() {
        $this->set("crs_trim_id", null);
        $this->save();

        // remove the specs...
        $specs = $this->factory()->master()->motorcyclespec()->fetch(array(
            "motorcycle_id" => $this->id(),
            "source" => "PST"
        ));

        foreach ($specs as $spec) {
            $spec->remove();
        }

        $images = $this->factory()->master()->motorcycleimage()->fetch(array(
            "motorcycle_id" => $this->id(),
            "source" => "PST"
        ));

        foreach ($images as $image) {
            $image->remove();
        }
    }
}