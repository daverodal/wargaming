<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/11/16
 * Time: 8:27 PM
 */

namespace Wargame\Area;
use Wargame\AreaBattle;
use stdClass;
class AreaGame extends AreaBattle
{


    function save()
    {
        $data = new stdClass();
        $data->arg = 'main';
        $data->terrainName = "terrain-Area1";
        $data->terrain = $this->terrain;
        $data->areaData = $this->areaData;
        $data->areaModel = $this->areaModel;
//        $data->display = $this->display;
        $data->moveRules = $this->moveRules->save();
        $data->gameRules = $this->gameRules->save();
        $data->force = $this->force;
        $data->players = $this->players;
        $data->scenario = $this->scenario;
        $data->mapData = $this->mapData;
        $data->playersReady = $this->playersReady;
        $data->victory = $this->victory->save();
        return $data;
    }
}