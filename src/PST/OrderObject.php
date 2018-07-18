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
        $ship_tracking_code = trim($this->get('ship_tracking_code'));
        if ($ship_tracking_code == "") {
            $ship_tracking_code = array();
        } else {
            $ship_tracking_code = json_decode($ship_tracking_code, true);
        }
        $ship_tracking_code[] = array(
            $carrier, $tracking_number
        );
        $this->set("ship_tracking_code", json_encode($ship_tracking_code));
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

    public function void($date, $reason) {
        // Get the parts; mark them as refunded if not shipped
        $matches = $this->factory()->master()->orderProduct()->fetch(array(
            "order_id" => $this->id()
        ));

        foreach ($matches as $m) {
            if ($m->get("status") != "Shipped") {
                $m->set("status", "Refunded");
                $m->save();
            }
        }

        // Mark the order itself as refunded
        $this->factory()->master()->orderStatus()->add(array(
            "order_id" => $this->id(),
            "status" => "Refunded",
            "datetime" => strtotime($date),
            "notes" => "Lightspeed: $reason"
        ));

        $this->set("ack_cancel_by_lightspeed", 1);
        $this->save(); // we don't need to expect another update.
    }

    public function getCancellationDate() {
        // we have to go get the time the status was changed to refunded.
        $matches = $this->factory()->master()->fetch(array(
            "order_id" => $this->id(),
            "status" => "Refunded"
        ));

        if (count($matches) > 0) {
            return array(date("Y-m-d", $matches[0]->get("datetime")), $matches[0]->get("notes"));
        } else {
            return array(date("Y-m-d"), "");
        }

    }

    public function toXMLStruct(&$orders_node) {
        $webOrder = $orders_node->addChild("webOrder");
        $structure = $this->toJSONArray();
        foreach ($structure as $key => $value) {
            if ($key == "customers") {
                $customers_node = $webOrder->addChild("customers");

                foreach ($value as $customer) {
                    $customer_node = $customers_node->addChild("customer");
                    foreach ($customer as $key => $value) {
                        $customer_node->addChild($key, htmlspecialchars($value));
                    }
                }

            } else if ($key == "payments") {
                $payments_node = $webOrder->addChild("payments");
                foreach ($value as $v) {
                    $payment_node = $payments_node->addChild("payment");
                    $payment_node->addChild("type", htmlspecialchars($v["type"]));
                    $payment_node->addChild("amount", htmlspecialchars($v["amount"]));
                }

            } else if ($key == "orderItems") {
                $orderitems_node = $webOrder->addChild("orderItems");

                foreach ($value as $orderItem) {
                    $orderitem_node = $orderitems_node->addChild("orderItem");
                    foreach ($orderItem as $key => $value) {
                        $orderitem_node->addChild($key, htmlspecialchars($value));
                    }
                }
            } else {
                $webOrder->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function getContact($contact_id, $type) {
        $contact = $this->factory()->master()->contact()->get($contact_id);
        if (!is_null($contact)) {
            return array(
                "type" => $type,
                "customerID" => $this->get("user_id"),
                "prefixName" => "",
                "firstName" => $contact->get("first_name"),
                "middleName" => "",
                "lastName" => $contact->get("last_name"),
                "suffixName" => "",
                "companyName" => $contact->get("company"),
                "address1" => $contact->get("street_address"),
                "address2" => $contact->get("address_2"),
                "city" => $contact->get("city"),
                "state" => $contact->get("state"),
                "county" => "",
                "country" => $contact->get("country"),
                "zipCode" => $contact->get("zip"),
                "phone" => $contact->get("phone"),
                "workPhone" => "",
                "email" => $contact->get("email")
            );
        } else {
            return array("type" => $type);
        }
    }

    public function getTransactionAmount() {
        // NOTE: you could go get this from the transaction table,
        return floatVal($this->get("sales_price")) + floatVal($this->get("shipping")) + floatVal($this->get("tax"));
    }

    public function getTaxRuleByState($state, $country) {
        $matches = $this->factory()->master()->taxes()->fetch(array(
            "mailcode" => $state
        ), true);
        if (count($matches) > 0) {
            return $matches[0]["id"];
        } else {
            return 0;
        }
    }

    public function getOrderItems() {
        $stmt = $this->dbh->prepare("select order_product.order_product_id as itemID, part.name as description, order_product.lightspeed_partnumber as itemNumber, manufacturer.name  as manufacturerName, manufacturer.manufacturer_id as manufacturerID , order_product.qty as quantity , order_product.price as amount from order_product join partnumber on order_product.product_sku = partnumber.partnumber join partvariation on order_product.lightspeed_partnumber = partvariation.part_number  AND partnumber.partnumber_id = partvariation.partnumber_id join part using (part_id) join manufacturer on part.manufacturer_id = manufacturer.manufacturer_id where order_product.order_id = ?");
        $stmt->bindValue(1, $this->id());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toJSONArray() {
        return array(
            "orderID" => $this->id(),
            "date" => date("Y-m-d H:i:s", $this->get("order_date")),
            "shippingVendor" => "UNKNOWN",
            "shippingMethod" => "UNKNOWN",
            "shippingCost" => $this->get("shipping"),
            "comment" => "",
            "taxAmount" => $this->get("tax"),
            "customers" => array(
                $this->getContact($this->get("contact_id"), "BILLING"),
                ($sc = $this->getContact($this->get("shipping_id"), "SHIPPING"))
            ),
            "payments" => array(
                "type" => "PAYPAL",
                "amount" => $this->getTransactionAmount()
            ),
            "taxRuleID" => $this->getTaxRuleByState($sc["state"], $sc["country"]),
            "orderItems" => $this->getOrderItems()
        );

    }
}