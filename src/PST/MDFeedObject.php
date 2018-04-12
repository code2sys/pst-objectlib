<?php

namespace PST;

use \PDO;
use \PDOException;

class MDFeedObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function getXMLFeed() {
        return file_get_contents($this->get("source_url"));
    }

    public function getStructuredXML() {
        return new \SimpleXMLElement($this->getXMLFeed());
    }

    public function generateMDRecords($debug) {
        $uniq_id = uniqid(gethostname() . "_");
        $structured_xml = $this->getStructuredXML();

        if ($debug) {
            print "Structure has " . count($structured_xml->channel->item) . " parts \n";
        }

        $stmt = $this->dbh->prepare("Update mdrecord set uniqid = ?, active = 0 where mdfeed_id = ?");
        $stmt->bindValue(1, $uniq_id);
        $stmt->bindValue(2, $this->id());
        $stmt->execute();

        // Now, iterate over the items...
        $records = array();
        for ($i = 0; $i < count($structured_xml->channel->item); $i++) {
            $item = $structured_xml->channel->item[$i];
            $data = array(
                "uniqid" => $uniq_id,
                "mdfeed_id" => $this->id(),
                "active" => 1,
                "last_seen" => date("Y-m-d H:i:s")
            );

            // Now, try for each field...
            foreach (array("description", "dsrp", "locatin_city", "link", "location_state", "location_street", "location_street2", "location_tel", "location_zip", "msrp", "recordid", "style", "title") as $f) {
                $value = $item->$f;
                if (isset($value) && !is_null($value) && FALSE !== $value) {
                    $data[$f] = $value;
                } else {
                    $data[$f] = null;
                }
            }

            // You need to get the g-namespaced stuff https://www.sitepoint.com/simplexml-and-namespaces/
            $g_ns = $item->children("http://base.google.com/ns/1.0");
            foreach (array("STOCKNO", "color", "condition", "make", "mileage", "model", "owner", "price_type", "store", "vehicle_type", "vin", "year") as $f) {
                $value = $g_ns->$f;

                if ($f == "condition") {
                    $f = "sale_condition";
                }

                if (isset($value) && !is_null($value) && FALSE !== $value) {
                    $data[$f] = $value;
                } else {
                    $data[$f] = null;
                }
            }

            if ($debug) {
                print "Data: \n";
                print_r($data);
            }

            $records[] = $r = $this->factory()->master()->mdrecord()->add($data);   

            // Now, we must look for images and process them for this record...
            $stmt = $this->dbh->prepare("Update mdrecordimage set active = 0, uniqid = ? where mdrecord_id = ?");
            $stmt->bindValue(1, $uniq_id);
            $stmt->bindValue(2, $r->id());
            $stmt->execute();

            $k = 1;
            $f_name = "image" . $k;
            $value = $item->$f_name;
            while (isset($value) && !is_null($value) && FALSE !== $value && trim($value) !== "" && $k < 100) {
                // we have a new URL...
                $image = $this->factory()->master()->mdrecordimage()->add(array(
                    "mdrecord_id" => $r->id(),
                    "uniqid" => $uniq_id,
                    "last_seen" => date("Y-m-d H:i:s"),
                    "active" => 1,
                    "url" => $value
                ));

                if ($debug) {
                    print "Image: \n";
                    print_r($image->to_array());
                }
                
                $k++;
                $f_name = "image" . $k;
                $value = $item->$f_name;
            }


        }
        return array("uniqid" => $uniq_id, "records" => $records);
    }

}
