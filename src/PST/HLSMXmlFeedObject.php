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
            } else {
                // OK, we are expecting to find a field...
                $field_name_number1 = substr($clean_node_name, -1 * strlen("" . $item_count));
                if (intVal($field_name_number1) != $current_row) {
                    if (!is_null($current_row_object)) {
                        $current_row_object->save();
                    }

                    $current_row++;
                    $current_row_object = $this->factory()->master()->hlsmxmlfeedrow()->add(array(
                        "hlsmxmlfeed_id" => $this->id(),
                        "number" => $current_row,
                        "uniqid" => $uniqid
                    ));
                }

                $field_name = substr($clean_node_name, 0, strlen($clean_node_name) - strlen("_" . $item_count));
                if (in_array($field_name, array("qty", "partnum", "hlsm_desc", "make", "hlsm_price", "hlsm_year", "hlsm_make", "hlsm_model", "hlsm_cat", "hlsm_dealer", "hlsm_showprice", "hlsm_ip_address"))) {
                    $current_row_object->set($field_name, $item->nodeValue);
                } else {
                    throw new \Exception("Unrecognized field name for row $current_row: " . $clean_node_name);
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