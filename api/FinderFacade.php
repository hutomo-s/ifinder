<?php
namespace App;

include_once(__DIR__."/App/Area.php");
include_once(__DIR__."/App/Agent.php");

class FinderFacade
{
    /**
     * Class Constructor, generate facade for Agent and Area
     * 
     * @param \App\Agent $agent
     * @param \App\Area $area
     */
    public function __construct(
            Agent $agent,
            Area $area,
            $appUri
            )
    {
        $this->_agent = $agent;
        $this->_area = $area;
        $this->appUri = $appUri;
    }

    /**
     * A single function for searching Agents and Areas
     * 
     * @param string $path
     */
    public function find($path)
    {
        // split the input supplied into module, function, and param
        $split_path = explode('/', $path);

        if(isset($split_path[0]))
                $module = $split_path[0];

        if(isset($split_path[1]))
                $function = $split_path[1];

        if(isset($split_path[2]))
                $param = $split_path[2];

        // break down the input to specific module, function, and param
        if(isset($module) AND isset($function))
        {
                switch ($module) 
                {
                        case 'area':
                                $area = new Area($this->appUri);
                                if($function == 'arealist')
                                        $area->areaList();
                                break;

                        case 'agent':
                                $agent = new Agent();
                                if($function == 'agentlistbyarea' AND !empty($param))
                                        $agent->agentListByArea($param);
                                break;

                        default:
                                $data = [
                                        "status" => 500,
                                        "message" => "Invalid Request"
                                ];
                                header('Content-Type: application/json');
                                echo json_encode($data);
                                break;
                }
        }
        else
        {
                $data = [
                        "status" => 500,
                        "message" => "Invalid Request"
                        ];
                header('Content-Type: application/json');
                echo json_encode($data);
        }	


}

}