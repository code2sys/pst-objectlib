<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 1:47 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class OrderObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    // This is going to encapsulate everything to do a lightspeed shipment.
    public function lightspeedShip($date, $carrier, $method, $tracking_number, $items = array()) {
        // stick them in that there table..
        if (count($items) == 0) {
            $items[] = array("itemID" => "", "quantity" => 0);
        }
        foreach ($items as $item) {
            $this->factory()->master()->orderLightspeedShipment()->add(array(
                "order_id" => $this->id(),
                "lightspeed_date" => date("Y-m-d H:i:s", strtotime($date)),
                "shipment_carrier" => $carrier,
                "shipment_method" => $method,
                "tracking_number" => $tracking_number,
                "item_id" => $item["itemID"],
                "quantity" => $item["quantity"]
            ));
        }

        // add the tracking ID
        $ship_tracking_code = $this->get('ship_tracking_code');
        if ($ship_tracking_code == "") {
            $ship_tracking_code = array();
        } else {
            $ship_tracking_code = json_decode($ship_tracking_code, true);
        }
        $ship_tracking_code[] = array(
            $carrier, $tracking_number
        );
        $this->set($ship_tracking_code, json_encode($ship_tracking_code));
        $this->save();

        // Is the whole thing shipped?
        $partial_shipped = false;
        foreach ($items as $item) {
            if ($item["itemID"] != "") {
                $matches = $this->factory()->master()->orderProduct()->fetch(array(
                    "order_id" => $this->id(),
                    "lightspeed_partnumber" => $item["itemID"]
                ));

                if (count($matches) > 0) {
                    foreach ($matches as $m) {
                        $m->set("lightspeed_shipped", intVal($item["quantity"]) + intVal($m->get("lightspeed_shipped")));
                        $m->set("status", "Shipped");
                        $m->save(); // OK, that should mark these guys...

                        if (intVal($m->get("lightspeed_shipped")) < intVal($m->get("qty"))) {
                            $partial_shipped = true;
                        }
                    }
                }
            }
        }

        // OK, we have to figure out the status for this.
        if (!$partial_shipped) {
            // LOOK AT THEM!
            $partial_shipped = false;
            $matches = $this->factory()->master()->orderProduct()->fetch(array(
                "order_id" => $this->id()
            ), true);

            foreach ($matches as $m) {
                if ($m["status"] != "Shipped") {
                    $partial_shipped = true;
                } else if (intVal($m["lightspeed_shipped"]) > 0 && intVal($m["lightspeed_shipped"]) < intVal($m["quantity"])) {
                    $partial_shipped = true;
                }
            }
        }

        if ($partial_shipped) {
            $this->factory()->master()->orderStatus()->add(array(
                "order_id" => $this->id(),
                "status" => "Partially Shipped",
                "datetime" => strtotime($date),
                "notes" => "Lightspeed Shipment"
            ));
        } else {
            $this->factory()->master()->orderStatus()->add(array(
                "order_id" => $this->id(),
                "status" => "Shipped",
                "datetime" => strtotime($date),
                "notes" => "Lightspeed Shipment"
            ));
        }

    }

}