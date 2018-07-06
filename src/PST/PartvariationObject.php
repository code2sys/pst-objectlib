<?php

namespace PST;

use \PDO;
use \PDOException;

class PartvariationObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function addDealerInventory($amount, $cost, $price) {
        $partdealervariation = $this->factory()->master()->partdealervariation()->get($this->id());

        $data = $this->to_array();
        $data["quantity_last_updated"] = date("Y-m-d H:i:s");
        $data["cost"] = $cost;
        $data["price"] = $price;
        $data["quantity_available"] = intVal($amount);
        if (!is_null($partdealervariation)) {
            $data["quantity_available"] += intVal($partdealervariation->get("quantity_available"));
            $data["quantity_ten_plus"] = $data["quantity_available"] > 9 ? 1 : 0;
            $this->factory()->master()->partdealervariation()->update($this->id(), $data);
        } else {
            $this->factory()->master()->partdealervariation()->add($data);
        }
    }
}