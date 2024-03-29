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

        if ($this->master()->config()->getKeyValue("dealer_track_delete_if_not_in_upload", 1) > 0) {
            $stmt = $this->dbh->prepare("Update motorcycle set deleted = 1, lightspeed_deleted = 1 where dealertrack = 1 and lightspeed_flag = 0 and customer_deleted = 0");
            $stmt->execute();
        }

        $stmt = $this->dbh->prepare("Update motorcycle set deleted = 0 where dealertrack = 1 and lightspeed_flag = 1 and customer_deleted = 0 and lightspeed_deleted = 1");
        $stmt->execute();
    }

    /*
     * The keys are going to come from this list:
     *                        	                           	                 	Type                          	Vehicle Code                  	Year                          	Make                     	Model                    	Model Code                    	Body                     	Color                    	Color Code                    	Trim                     	Fuel                          	MPG                           	Cylinders                     	Truck                         	4WD                           	Turbo                         	Engine                                	                      	Location                      	Date in Inventory             	Warranty Months               	Warranty Miles                	Price                         	Cost                          	Work-in-Process               	Options                       	License #                     	Gross Weight                  	Inspection Month              	Options 1                     	Options 2                     	Options 3                     	Options 4                     	Options 5                     	Options 6                     	Options 7                     	Options 8                     	Options 9                     	Options10                     	Options11                     	Options12                     	Options13                     	Options14                     	Options15                     	Options16                     	Options17                     	Options18                     	Options19                     	Options20                     	Memo Info 1                             	Memo Info 2                             	Memo Info 3                             	Memo Info 4                             	Memo Info 5                             	Memo Info 6                             	Memo Info 7                             	Memo Info 8                             	Memo Info 9                             	Memo Info10                             	Memo Info11                             	Memo Info12                             	Memo Info13                             	Memo Info14                             	Memo Info15                             	Memo Info16                             	Memo Info17                             	Memo Info18                             	Memo Info19                             	Memo Info20                             	Sale Group                    	Dealer Code                   	Radio Code                    	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     	Sale Account                  	Inventory Account             	Options21                     	Options22                     	Options23                     	Options24                     	Options25                     	Options26                     	Options27                     	Options28                     	Options29                     	Options30                     	Options31                     	Options32                     	Options33                     	Options34                     	Options35                     	Options36                     	Options37                     	Options38                     	Options39                     	Options40                     	Memo Info21                             	Memo Info22                             	Memo Info23                             	Memo Info24                             	Memo Info25                             	Memo Info26                             	Memo Info27                             	Memo Info28                             	Memo Info29                             	Memo Info30                             	Memo Info31                             	Memo Info32                             	Memo Info33                             	Memo Info34                             	Memo Info35                             	Memo Info36                             	Memo Info37                             	Memo Info38                             	Memo Info39                             	Memo Info40                             	Enterprise                    	Enterprise-Company            	Style ID            	Certified Used Car
     */
    public function processRow($row, $default_type, $default_category) {
        $dealership_id = trim($row["Company"]);
        $sku = trim($row["Stock Number"]);

        if ("#" == substr($sku, 0, 1)) {
            $sku = trim(substr($sku, 1));
        }

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

        $row["Make"] = normalize_incoming_make(trim($row["Make"]));

        // OK, now, we must consider adding this here bike!
        $new_data = array(
            "sku" => $sku,
            "title" => trim($row["Year"]) . " " . $row["Make"]  . " " . trim($row["Model"]) . " " . trim($row["Trim"]) . " " . trim($row["Model Code"]) ,
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
            "make" => $row["Make"] ,
            "model" => trim($row["Model"]),
            "year" => trim($row["Year"]),
            "codename" => trim($row["Model Code"]),
            "retail_price" => preg_replace("/[^0-9\.]/", "", trim($row["Price"])),
            "status" => $this->master()->config()->getKeyValue("dealer_track_active_immediately", 0),
            "cycletrader_feed_status" => $this->master()->config()->getKeyValue("dealer_track_default_cycletrader", 1),
            "dealertrack" => 1
        );

        if ($add_new) {
            $new_data["category"] = $default_category;
            $new_data["vehicle_type"] = $default_type;
            $motorcycle_id = $this->master()->motorcycle()->add($new_data)->id();



            // Engine => Cylinders
            if (trim($row["Cylinders"]) != "") {
                $this->addFeedSpec($motorcycle_id, "Engine", "Cylinders", 30004, trim($row["Cylinders"]) , 3);
            }
            // Engine => US Miles Per Gallon (Combined)
            if (trim($row["MPG"]) != "") {
                $this->addFeedSpec($motorcycle_id, "Engine", "US Miles Per Gallon (Combined)", 30035, trim($row["MPG"]), 3);
            }

            // Engine => Fuel Type
            if (trim($row["Fuel"]) != "") {
                $value = trim($row["Fuel"]) == "G" ? "Gasoline" : (trim($row["Fuel"]) == "D" ? "Diesel" : trim($row["Fuel"]));
                $this->addFeedSpec($motorcycle_id, "Engine", "Fuel Type", 30039, $value, 3);
            }
            // Engine => Turbocharged
            if (trim($row["Turbo"]) == "Y") {
                $this->addFeedSpec($motorcycle_id, "Engine", "Turbocharged", 30040, "Yes", 3);
            }


            // Transmission => Transmission Type
            if (trim($row["Transmission"]) != "") {
                $this->addFeedSpec($motorcycle_id, "Transmission", "Transmission Type", 40002, trim($row["Transmission"]), 4);
            }

            // Only if it's new might we add some dealer specs.
            // Driveline       | Driveline Type
            if (trim($row["4WD"]) == "Y") {
                $this->addFeedSpec($motorcycle_id, "Driveline", "Driveline Type", 180003, "4WD", 18);
            }

        } else {
            unset($new_data["dealertrack"]);
            $motorcycle = $this->master()->motorcycle()->get($motorcycle_id);

            // Now, there could be some items set by the customer,
            // and if that is the case, you must squash them.
            foreach (array(
                 "customer_set_vin_number" => "vin_number",
                 "customer_set_mileage" => "mileage",
                "customer_set_color" => "color",
                "customer_set_condition" => "condition",
                "customer_set_make" => "make",
                "customer_set_model" => "model",
                "customer_set_title" => "title",
                "customer_set_year" => "year",
                "customer_set_price" => "retail_price",
                "customer_set_description" => "description",
                "customer_set_transmission" => "transmission",
                "customer_set_engine_type" => "engine_type",
                "customer_set_type" => "vehicle_type"
                     ) as $flag => $value) {
                if ($motorcycle->get($flag) > 0) {
                    unset($new_data[$value]);
                }
            }

            $this->master()->motorcycle()->update($motorcycle_id, $new_data);
        }

        return $motorcycle_id;
    }

    protected function addFeedSpec($motorcycle_id, $feature, $attribute_name, $crs_attribute_id, $value, $crs_attributegroup_number) {
        // I am going to have to make the group, if it doesn't exist, and then I'm going to have to make the entry, if it doesn't exist. Then, we just need to set this, and we'll be done.
        // Is there such a spec group?
        $matches = $this->master()->motorcycleSpecGroup()->fetch(array(
            "motorcycle_id" => $motorcycle_id,
            "crs_attributegroup_number" => $crs_attributegroup_number
        ), true);

        if (count($matches) > 0) {
            $sg_id = $matches[0]["motorcyclespecgroup_id"];
        } else {
            $sg = $this->master()->motorcycleSpecGroup()->add(array(
                "name" => $feature,
                "ordinal" => $crs_attributegroup_number,
                "source" => "Dealer Track",
                "crs_attributegroup_number" => $crs_attributegroup_number,
                "motorcycle_id" => $motorcycle_id
            ));
            $sg_id = $sg->id();
        }

        // OK, now, we have to attempt to add this attribute...
        // If we are here, and now, we are adding the motorcycle too, so just add
        $this->master()->motorcycleSpec()->add(array(
            "value" => $value,
            "feature_name" => $feature,
            "attribute_name" => $attribute_name,
            "type" => "string",
            "motorcycle_id" => $motorcycle_id,
            "final_value" => $value,
            "override" => 1,
            "source" => "Dealer Track",
            "crs_attribute_id" => $crs_attribute_id,
            "motorcyclespecgroup_id" => $sg_id
        ));
    }
}