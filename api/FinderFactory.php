<?php
namespace App;

include_once(__DIR__."/FinderFacade.php");

class FinderFactory
{
    public function getFinder($appUri = "")
    {
            return new FinderFacade(
                    new Agent(), new Area(), $appUri
            );
    }
}