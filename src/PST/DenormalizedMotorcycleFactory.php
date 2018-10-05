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
            "motorcycle_id", "title", "description", "category", "type", "sku", "numeric_sku", "model"
        );
    }

    public function moveMotorcycle($motorcycle_id) {
        $motorcycle = $this->master()->motorcycle()->get($motorcycle_id);
        if (is_null($motorcycle)) {
            return; // can't do it!
        }

        $sku = $motorcycle->get("sku");
        $pieces = explode("-", $sku);
        $number_sku = $pieces[0];
        $number_sku = preg_replace("/[^0-9]/", "", $number_sku);
        $number_sku = intVal($number_sku);

        $normal_characters = "a-zA-Z0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]";

        $this->add(array(
            "motorcycle_id" => $motorcycle_id,
            "title" => preg_replace("/[^$normal_characters]/", ' ', $motorcycle->get("title")),
            "description" => $motorcycle->get("description"),
            "category" => $motorcycle->get("category_name"),
            "type" => $motorcycle->get("type"),
            "sku" => $motorcycle->get("sku"),
            "numeric_sku" => $number_sku,
            "model" => $motorcycle->get("model")
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