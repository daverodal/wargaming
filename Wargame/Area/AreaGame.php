<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/11/16
 * Time: 8:27 PM
 */

namespace Wargame\Area;
use Wargame\LandBattle;
use stdClass;
class AreaGame extends LandBattle
{


    function save()
    {
        $data = new stdClass();
        $data->arg = 'main';
        $data->terrainName = "terrain-Area1";
        $data->terrain = $this->terrain;
        $data->areaData = $this->areaData;
//        $data->display = $this->display;
        $data->moveRules = $this->moveRules->save();
        $data->gameRules = $this->gameRules->save();
        $data->force = $this->force;
        $data->players = $this->players;
        $data->scenario = $this->scenario;
        $data->mapData = $this->mapData;
        return $data;
    }
}