<?php
namespace App;

require_once __DIR__.'/Helpers/Uri.php';
require_once __DIR__."/FinderFactory.php";

// generate app uri
$uri = new \Helpers\Uri();
$appUri = $uri->getAppUri();

// construct a new factory
$finderFactory = new FinderFactory();
$finder = $finderFactory->getFinder($appUri);

// call the specific function based on query string input
if(isset($_GET['f']))
{
    #eg: area/arealist
    $finder->find($_GET['f']);
}
else
{
    // default return
    $data = [
            "status" => 500,
            "message" => "Invalid Request"
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
}