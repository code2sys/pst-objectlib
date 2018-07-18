<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 2:18 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class HLSMXmlFeedObject extends AbstractObject {

    /*
     * Return this:
     *                     $_SESSION['cart'][$pn["partnumber"]] = array(
                        "part_id" => $pn["part_id"],
                        "display_name" => $pn["name"],
                        "images" => null,
                        "partnumber" => $pn["partnumber"],
                        "qty" => $pn["qty"],
                        "price" => $pn["price"],
                        "type" => "cart"
                    );
     */
    public function getPartNumbersForCart() {
        $stmt = $this->dbh->prepare("Select partpartnumber.part_id, partnumber.partnumber, part.name, hlsmxmlfeedrow.qty, hlsmxmlfeedrow.hlsm_price as price, hlsmxmlfeedrow.hlsm_cost as cost from hlsmxmlfeedrow join partvariation using (partvariation_id) join partnumber using (partnumber_id) join partpartnumber using (partnumber_id) join part using (part_id) where hlsmxmlfeedrow.hlsmxmlfeed_id = ?");
        $stmt->bindValue(1, $this->id());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    // Return true on success, false on failure...
    public function convertFromRaw() {
        if ($this->get("claimed") > 0) {
            throw new \Exception("Cannot convert what is already claimed: " . $this->id());
        }

        if ($this->get("converted") > 0) {
            throw new \Exception("Conversion already started: " . $this->id());
        }

        $valid = false;

        $document = new \DOMDocument();
        $document->loadXML($this->geT("raw_xml"));

        $item_count = 0;
        $current_row = 0;
        $current_row_object = null;

        $this->set("converted", 1); // begin the conversion;
        $this->save();
        $uniqid = uniqid(gethostname());

        $x = $document->documentElement;
        foreach ($x->childNodes AS $item) {
            $clean_node_name = strtolower(trim($item->nodeName));
            if ($clean_node_name == "#text") {
                continue; // just whitespace..
            }

            if ($clean_node_name == "hlsmno") {
                $this->set("hlsmno", $item->nodeValue);
                $valid = true;
            } else if ($clean_node_name == "itemcnt") {
                $item_count = $item->nodeValue;
                $this->set("item_count", $item_count);
                $valid = true;
            } else if ($clean_node_name == "dirid") {
                continue; // just skip this one.
            } else {
                // OK, we are expecting to find a field...
                $field_name_number1 = substr($clean_node_name, -1 * strlen("" . $item_count));
                if (intVal($field_name_number1) != $current_row ) {
                    if (!is_null($current_row_object)) {
                        $current_row_object->save();
                    }
                    $current_row_object = null;
                    $current_row = $field_name_number1;
                }

                if (is_null($current_row_object)) {
                    $current_row_object = $this->factory()->master()->hlsmxmlfeedrow()->add(array(
                        "hlsmxmlfeed_id" => $this->id(),
                        "number" => $current_row,
                        "uniqid" => $uniqid
                    ));
                }

                $field_name = substr($clean_node_name, 0, strlen($clean_node_name) - strlen("_" . $item_count));
                if (in_array($field_name, array("qty", "partnum", "hlsm_desc", "make", "hlsm_cost", "hlsm_price", "hlsm_year", "hlsm_make", "hlsm_model", "hlsm_cat", "hlsm_dealer", "hlsm_showprice", "hlsm_ip_address", "dirid"))) {
                    $current_row_object->set($field_name, $item->nodeValue);
                } else {
                    error_log("HLSMXMLFeed: Unrecognized field name for row $current_row of " . $this->id()  . ": " . $clean_node_name);
                }
            }
        }

        $this->set("converted", 2);
        $this->save();
        if (!is_null($current_row_object)) {
            $current_row_object->save();
        }

        return $valid;
    }
}