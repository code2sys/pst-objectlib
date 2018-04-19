<?php


namespace PST;

use \PDO;
use \PDOException;

class VSeptProspectObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function isPushed() {
        return $this->get("PCHId") != "";
    }

    public function pushToVSept() {
        // OK, this is supposed to do the CURL, parse the response, and, if possible, it is supposed to push it up...
        // Generate the XML..
        $xml_string = $this->to_XML("Item",
            array(
                "vsept_raw_response", "vseptprospect_id", "created", "PCHId"
        ),
            array(
                "SourceProspectId" => $this->id(),
                "DealershipId" => $this->factory()->master()->config()->getKeyValue("vsept_dealership_id")
        ), false, false);

        $xml_string = '<?xml version="1.0" encoding="utf-8"?><ProspectImport>' . $xml_string . '</ProspectImport>';

        // now, we need to post it to a URL and get the results...
        try {
            $result = $this->factory()->postXMLToURL($xml_string, "http://pch.v-sept.com/VSEPTPCHPostService.aspx?method=AddProspect&sourceid=" . $this->factory()->master()->config()->getKeyValue("vsept_source_id"));
            $this->set("vsept_raw_response", print_r($result, true));

            $results = simplexml_load_string($result["result"]);
            if (isset($results->Prospect) && isset($result->Prospect->PCHId)) {
                $this->set("PCHId", $result->Prospect->PCHId);
            } else {
                error_log("Unexpected result: " . print_r($results));
            }

        } catch(\Exception $e) {
            $this->set("vsept_raw_response", "Exception: " . $e->getMessage() . " - " . $e->getTraceAsString());
        }

        $this->save();
    }

}