<?php
namespace App;

class Agent 
{
	function __construct()
	{
		$this->csvFile = __DIR__."/agentlist.csv";
	}

	public function agentList()
	{
		$data = $this->modData();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function agentListByArea($areaCode = NULL)
	{
		$data = $this->filterModData($areaCode);
		header('Content-Type: application/json');
		echo json_encode($data);	
	}

	private function filterModData($areaCode = NULL)
	{
		$inputData = $this->modData();

		$modifiedData = [];

		foreach ($inputData as $i => $v) 
		{
			$key = $v['agent_areacode'];
			$modifiedData[$key][] = $v;
		}

		if($areaCode AND array_key_exists($areaCode, $modifiedData))
			$outputData = $modifiedData[$areaCode];
		else
			$outputData = FALSE;

		return $outputData;
	}	


	/**
	* Modify the data, to have correct index
	*
	*/
	private function modData()
	{
		$inputData = $this->getData();

		$outputData = [];

		foreach ($inputData as $i => $v)
		{
			if($v)
			{
				$row = [
					"id" => intval($v[0]),
					"agent_name" => $v[1],
					"agent_phone" => $v[2],
					"agent_areacode" => $v[3],
				];
				$outputData [] = $row;
			}
		}

		return $outputData;
	}

	/**
	* Get the data from csv provided
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