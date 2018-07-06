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

class HLSMXmlFeedRowObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);

        $this->machine_type_map = array(
            "Motorcycle" => "DIRT BIKE",
            "Streetbike" => "STREET BIKE"
        );
    }

    protected function _fetchGetAdd($factory, $queryKVP, $addKVP) {
        $hlsm = $this->factory()->master()->$factory()->fetch($queryKVP);
        if (count($hlsm) == 0) {
            $hlsm = $this->factory()->master()->distributor()->add($addKVP);
        } else {
            $hlsm = $hlsm[0];
        }
        return $hlsm;
    }

    public function convertToPartVariation() {
        if (is_null($this->get("partvariation_id")) || $this->get("partvariation_id") == 0) {
            // First, get the HLSM distributor, if it exists.
            $hlsm = $this->_fetchGetAdd("distributor", array("name" => "HLSM"), array(
                "name" => "HLSM",
                "customer_distributor" => 1
            ));
            $distributor_id = $hlsm->id();


            // Get the manufacturer, who is the brand, if it exists...
            $manufacturer = $this->_fetchGetAdd("manufacturer", array("name" => $this->get("make")), array(
                "name" => $this->get("make")
            ));
            $manufacturer_id = $manufacturer->id();

            // First, make the part. The part is going to be Make Part Number Description, MX = 0
            $name = $this->get("make") . " " . $this->get("hlsm_desc") . " " . $this->get("partnumber");

            // Make the part number, make it for the MX = 0, and set the price and the cost
            $part = $this->_fetchGetAdd("part", array(
                "name" => $name
            ), array(
                "name" => $name,
                "description" => $this->get("hlsm_desc"),
                "mx" => 0,
                "manufacturer_id" => $manufacturer_id,
                "protect" => 1
            ));
            $part_id = $part->id();

            // make the partnumber entry...
            $partnumber = $this->_fetchGetAdd("partnumber", array(
                "partnumber" => "HLSM " . $this->get("make") . " " . $this->get("partnum")
            ), array(
                "partnumber" => "HLSM " . $this->get("make") . " " . $this->get("partnum"),
                "price" => $this->get("hlsm_price"),
                "cost" => $this->get("hlsm_price"),
                "sale" => $this->get("hlsm_price"),
                "dealer_sale" => $this->get("hlsm_price"),
                "manufacturer_id" => $manufacturer_id,
                "universalfit" => $this->get("hlms_model") == "-" ? 1 : 0,
                "protect" => 1,
                "inventory" => $this->get("qty")
            ));
            $partnumber_id = $partnumber->id();

            // Make the part variation entry, if appropriate
            $partvariation = $this->_fetchGetAdd("partvariation", array(
                "distributor_id" => $distributor_id,
                "partnumber_id" => $partnumber_id,
                "part_number" => $this->get("part_num")
            ), array(
                "part_number" => $this->get("part_num"),
                "partnumber_id" => $partnumber_id,
                "distributor_id" => $distributor_id,
                "quantity_available" => 0,
                "cost" => $this->get("hlsm_price"),
                "price" => $this->get("hlsm_price"),
                "protect" => 1,
                "quantity_last_updated" => date("Y-m-d H:i:s"),
                "clean_part_number" => preg_replace("/[^a-z0-9]/i", "", $this->get("part_num"))
            ));
            $partvariation_id = $partvariation->id();

            // We have to increment the local dealer inventory...
            $this->factory()->master()->partvariation()->setDealerInventory($partvariation_id, $this->get("qty"), $this->get("hlsm_price"), $this->get("hlsm_price"));

            // make a single question
            $question = $this->_fetchGetAdd("partquestion", array(
                "part_id" => $part_id,
                "question" => ""
            ), Array(
                "part_id" => $part_id,
                "question" => ""
            ));
            $question_id = $question->id();

            // make a single answer
            $answer = $this->_fetchGetAdd("partquestionanswer", array(
                "answer" => "",
                "partquestion_id" => $question_id
            ), array(
                "answer" => "",
                "partquestion_id" => $question_id
            ));
            $answer_id = $answer->id();

            // make the part number assigned to it
            $this->_fetchGetAdd("partnumberpartquestion", array(
                "partquestion_id" => $question_id,
                "partnumber_id" => $partnumber_id
            ), array(
                "partnumber_id" => $partnumber_id,
                "partquestion_id" => $question_id,
                "answer" => "",
                "mx" => 0
            ));

            $this->_fetchGetAdd("partpartnumber", array(
                "partnumber_id" => $partnumber_id,
                "part_id" => $part_id
            ), array(
                "partnumber_id" => $partnumber_id,
                "part_id" => $part_id
            ));


            // If model and year are not -, then you need to make it so we can hold it
            // make a model, if necessary
            if (trim($this->get("model")) != "-") {
                // In a quick check, if the model is found by name, with a similar manufacturer, go with that
                // otherwise, do this crap.
                $candidate_models = $this->factory()->master()->model()->fetch(array(
                    "name" => $this->get("model")
                ));

                $model = null;
                if (count($candidate_models) > 0) {
                    foreach ($candidate_models as $cm) {
                        $candidate_make = $this->factory()->master()->make()->get($cm->get("make_id"));
                        if (strtolower($candidate_make->get("name")) == strtolower($this->get("hlsm_make"))) {
                            $model = $cm;
                        }
                    }
                }

                if (is_null($model)) {
                    // get teh machine type, or add it...
                    $machinetype = array_key_exists($this->get("hlsm_cat"), $this->machine_type_map) ?
                        $this->machine_type_map[$this->get("hlsm_cat")] : ("HLSM " . $this->get("hlsm_cat"));
                    $machinetype = $this->_fetchGetAdd("machinetype", array(
                        "name" => $machinetype
                    ), array(
                        "name" => $machinetype,
                        "label" => $machinetype
                    ));

                    // make!
                    $make = $this->_fetchGetAdd("make", array(
                        "machinetype_id" => $machinetype->id(),
                        "name" => $this->get("hlsm_make")
                    ), array(
                        "machinetype_id" => $machinetype->id(),
                        "name" => $this->get("hlsm_make"),
                        "label" => $this->get("hlsm_make")
                    ));

                    // model!
                    $model = $this->_fetchGetAdd("model", array(
                        "name" => $this->get("hlsm_model"),
                        "make_id" => $make->id()
                    ), array(
                        "name" => $this->get("hlsm_model"),
                        "label" => $this->get("hlsm_model"),
                        "make_id" => $make->id()
                    ));
                }
                // in any case, you should have a model, and so, make sure this is added to partnumbermodel with a year...
                $this->_fetchGetAdd("partnumbermodel", array(
                    "model_id" => $model->id(),
                    "partnumber_id" => $partnumber_id,
                    "year" => $this->get("hlsm_year")
                ), array(
                    "model_id" => $model->id(),
                    "partnumber_id" => $partnumber_id,
                    "year" => $this->get("hlsm_year")
                ));
            }


            // better save this part #
            $this->set("partvariation_id", $partvariation_id);
            $this->save();
        }

    }
}