<?php
namespace App;

class Area 
{
    public function __construct($appUri)
    {
        $this->appUri = $appUri;
        $this->csvFile = __DIR__."/arealist.csv";
        $this->uriPrefix = $this->appUri."?f=agent/agentlistbyarea/"; 
    }

    public function areaList()
    {
        $data = $this->modData($this->uriPrefix);
        header('Content-Type: application/json');
        echo json_encode($data);
    }	

    /**
    * Modify the data, to have correct index
    *
    * @param string $uriprefix The url for search the agent based on area code 
    */
    private function modData($uriprefix = "")
    {
        $inputData = $this->getData();

        $outputData = [];

        foreach ($inputData as $i => $v)
        {
            if($v)
            {
                $row = [
                        "id" => intval($v[0]),
                        "area_code" => $v[1],
                        "area_name" => $v[2],
                        "area_url" => $uriprefix.$v[1],
                ];
                $outputData [] = $row;
            }
        }

        return $outputData;
    }

    /**
    *
    */
    private function getData()
    {
            $data = [];

            $file = fopen($this->csvFile,"r");

            while(! feof($file))
              {
                    $row = fgetcsv($file);
                    $data[] = $row;
              }

            fclose($file);

            return $data;

    }

}
