<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/1/18
 * Time: 6:16 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class DealerTrackFeedLogFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\DealerTrackFeedLogObject", $table = "dealertrack_feed_log", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "run_at", "run_by", "status", "error_string", "processing_start", "processing_end", "filename"
        );
    }

    public function begin($filename, $run_by = "api") {
        $id = $this->add(array(
            "run_by" => $run_by,
            "filename" => $filename,
            "processing_start" => date("Y-m-d H:i:s")
        ))->id();

        $stmt = $this->dbh->prepare("Update motorcycle set lightspeed_flag = 0 where dealertrack = 1");
        $stmt->execute();

        return $id;
    }

    public function end($id, $error_string = "") {
        $this->update($id, array(
            "status" => 2,
            "processing_end" => date("Y-m-d H:i:s"),
            "error_string" => $error_string
        ));

        $stmt = $this->dbh->prepare("Update motorcycle set deleted = 1, lightspeed_deleted = 1 where dealertrack = 1 and lightspeed_flag = 0 and customer_deleted = 0");
        $stmt->execute();
        $stmt = $this->dbh->prepare("Update motorcycle set deleted = 0 where dealertrack = 1 and lightspeed_flag = 1 and customer_deleted = 0 and lightspeed_deleted = 1");
        $stmt->execute();
    }

    /*
     * The keys are going to come from this list:
     *                        	                           	                 	Type                          	Vehicle Code                  	Year                          	Make                     	Model                    	Model Code                    	Body                     	Color                    	Color Code                    	Trim                     	Fuel                          	MPG                           	Cylinders                     	Truck                         	4WD                           	Turbo                         	Engine                                	                      	Location                      	Date in Inventory             	Warranty Months               	Warranty Miles                	Price                         	Cost                          	Work-in-Process               	Options                       	License #                     	Gross Weight                  	Inspection Month              	Options 1                     	Options 2                     	Options 3                     	Options 4                     	Options 5                     	Options 6                     	Options 7                     	Options 8                     	Options 9                     	Options10                     	Options11                     	Options12                     	Options13                     	Options14                     	Options15                     	Options16                     	Options17                     	Options18                     	Options19                     	Options20                     	Memo Info 1                             	Memo Info 2                             	Memo Info 3                             	Memo Info 4                             	Memo Info 5                             	Memo Info 6                             	Memo Info 7                             	Memo Info 8                             	Memo Info 9                             	Memo Info10                             	Memo Info11                             	Memo Info12                             	Memo Info13                             	Memo Info14                             	Memo Info15                             	Memo Info16                             	Memo Info17                             	Memo Info18                             	Memo Info19                             	Memo Info20                             	Sale Group                    	Dealer Code                   	Radio Code                    	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     	Sale Account                  	Inventory Account             	Options21                     	Options22                     	Options23                     	Options24                     	Options25                     	Options26                     	Options27                     	Options28                     	Options29                     	Options30                     	Options31                     	Options32                     	Options33                     	Options34                     	Options35                     	Options36                     	Options37                     	Options38                     	Options39                     	Options40                     	Memo Info21                             	Memo Info22                             	Memo Info23                             	Memo Info24                             	Memo Info25                             	Memo Info26                             	Memo Info27                             	Memo Info28                             	Memo Info29                             	Memo Info30                             	Memo Info31                             	Memo Info32                             	Memo Info33                             	Memo Info34                             	Memo Info35                             	Memo Info36                             	Memo Info37                             	Memo Info38                             	Memo Info39                             	Memo Info40                             	Enterprise                    	Enterprise-Company            	Style ID            	Certified Used Car
     */
    public function processRow($row) {
        $dealership_id = trim($row["Company"]);
        $sku = trim($row["Stock Number"]);

        if ("#" == substr($sku, 0, 1)) {
            $sku = trim(substr($sku, 1));
        }

        error_log("SKU: $sku");

        $real_sku = $sku;

        // find the motorcycle, or add it...
        $matches = $this->master()->motorcycle()->fetch(array(
            "sku" => $sku
        ), true);

        $add_new = true;
        $try_for_real = false;
        $motorcycle_id = 0;

        if (count($matches) > 0) {
            // we need to check that it is really our bike...
            if ($matches[0]["source"] != "Dealer Track") {
                $try_for_real = true;
            } else {
                // OK, is it this one?
                if ($matches[0]["dealership_id"] == $dealership_id) {
                    $motorcycle_id = $matches[0]["id"];
                    $add_new = false;
                } else {
                    $try_for_real = true;
                }
            }
        }

        if ($try_for_real) {
            $sku = $real_sku . "-" . $dealership_id;
            // We have to attempt to find the real sku...
            $matches = $this->master()->motorcycle()->fetch(array(
                "sku" => $sku,
                "source" => "Dealer Track"
            ), true);

            if (count($matches) > 0) {
                $add_new = false;
                $motorcycle_id = $matches[0]["id"];
            }
        }

        // OK, now, we must consider adding this here bike!
        $new_data = array(
            "sku" => $sku,
            "title" => trim($row["Year"]) . " " . trim($row["Make"]) . " " . trim($row["Model"]) . " " . trim($row["Trim"]) . " " . trim($row["Model Code"]) . " " . trim($row["Color"]),
            "description" => trim($row["Description"]),
            "condition" => trim($row["Type"]) == "U" ? 2 : 1,
            "vin_number" => trim($row["VIN"]),
            "mileage" => trim($row["Odometer"]),
            "source" => "Dealer Track",
            "dealership_id" => $dealership_id,
            "real_sku" => $real_sku,
            "lightspeed_flag" => 1,
            "transmission" => trim($row["Transmission"]),
            "color" => trim($row["Color"]),
            "make" => trim($row["Make"]),
            "model" => trim($row["Model"]),
            "year" => trim($row["Year"]),
            "codename" => trim($row["Model Code"]),
            "retail_price" => preg_replace("/[^0-9\.]/", "", trim($row["Price"]))

        );

        if ($add_new) {
            $motorcycle_id = $this->master()->motorcycle()->add($new_data)->id();
            error_log("Adding new; id is: $motorcycle_id");
        } else {
            $this->master()->motorcycle()->update($motorcycle_id, $new_data);
            error_log("Updating; id is $motorcycle_id");
        }

        return $motorcycle_id;
    }
}