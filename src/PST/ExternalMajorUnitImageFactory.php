<?php
/**
 * User: robert
 * Date: 11/14/18
 * Time: 6:20 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class ExternalMajorUnitImageFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ExternalMajorUnitImageObject", $table = "externalmajorunitimagefeed", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "created", "processed", "start_timestamp", "end_timestamp", "image_count", "errors", "source_file"
        );
    }

    public function begin($source_file, $errors = '') {
        $id = $this->add(array(
            "processed" => 1,
            "start_timestamp" => date("Y-m-d H:i:s"),
            "errors" => $errors,
            "source_file" => $source_file
        ))->id();

        return $id;
    }

    public function end($id, $image_count = 0) {
        $this->update($id, array(
            "processed" => 2,
            "end_timestamp" => date("Y-m-d H:i:s"),
            "image_count" => $image_count
        ));

    }
  

    public function processRow($row) {
        $dealership_id  = trim($row["dealership_id"]);
        $vin_number     = trim($row["vin"]);
        $images         = explode("|", trim($row["images"]));

        $image_count = 0;

        // find the motorcycle, or add it...
        $motorcycles = $this->master()->motorcycle()->fetch(array(
            "vin_number" => $vin_number
        ), true);

        if (count($motorcycles) > 0) {
            foreach ($motorcycles as $motorcycle) {                
                
                // Remove old images that came from lightspeed, CRS, or Dealer Track.
                $this->master()->motorcycleimage()->removeWhere(array(
                    "motorcycle_id" => $motorcycle['id'],
                    "disable" => 0
                ));

                foreach ($images as $image) {

                    // Checking if the image is removed by admin
                    $motorcycleimages = $this->master()->motorcycleimage()->fetch(array(
                        "motorcycle_id" => $motorcycle['id'],
                        "image_name" => $image
                    ), true);

                    if (count($motorcycleimages) <= 0) {

                        // Adding new images
                        $this->master()->motorcycleimage()->add(array(
                            "motorcycle_id" => $motorcycle['id'],
                            "image_name" => $image,
                            "date_added" => date("Y-m-d H:i:s"),
                            "priority_number" => 1,
                            "external" => 1,
                            "source" => "Dealer Made"
                        ));

                        $image_count++;
                    }
                }

//                // Updating motorcycle source
//                $this->master()->motorcycle()->update($motorcycle['id'], array(
//                    "source" => "Dealer Made"
//                ));
            }
        }

        return $image_count;
    }
}