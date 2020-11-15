<?php
namespace Wargame;
// terrain.js

// Copyright (c) 2009-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */


class Area{
    public $name;
    public $provance;
    public $entranceCost;
    public $routes;
    public function __construct($data = null){
        if($data){
            $this->routes = $data->routes;
        }
    }
}

class AreaTerrain
{
    public $mapUrl = false;
    public $areas;

    function __construct($data = null)
    {

        if ($data) {

            $this->mapUrl = $data->mapUrl;
        } else {


        }
    }

    public function getRoutes($areaName){
        return $this->areas->$areaName->routes;
    }


}
