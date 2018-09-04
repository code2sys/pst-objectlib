<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 9/4/18
 * Time: 6:20 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class DenormalizedMotorcycleFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\DenormalizedMotorcycleObject", $table = "denormalized_motorcycle", $id = "denormalized_motorcycle_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "motorcycle_id", "title", "description", "category", "type", "sku", "numeric_sku"
        );
    }

    public function moveMotorcycle($motorcycle_id) {
        $motorcycle = $this->master()->motorcycle()->get($motorcycle_id);
        if (is_null($motorcycle)) {
            return; // can't do it!
        }
        $this->add(array(
            "motorcycle_id" => $motorcycle_id,
            "title" => $motorcycle->get("title"),
            "description" => $motorcycle->get("description"),
            "category" => $motorcycle->get("category_name"),
            "type" => $motorcycle->get("type"),
            "sku" => $motorcycle->get("sku"),
            "numeric_sku" => intVal(preg_replace("/[^0-9]/", "", $motorcycle->get("real_sku") != "" ? $motorcycle->get("real_sku") : $motorcycle->get("sku")))
        ));
    }

    public function moveAllMotorcycles() {
        $stmt = $this->dbh->prepare("Select id from motorcycle where deleted = 0");
        $stmt->execute();
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($ids as $id) {
            $this->moveMotorcycle($id);
        }
    }

}