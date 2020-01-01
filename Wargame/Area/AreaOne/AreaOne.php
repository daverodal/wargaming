<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/11/16
 * Time: 8:30 PM
 */

namespace Wargame\Area\AreaOne;
use Wargame\Area\AreaGame;
use Wargame\AreaData;
use Wargame\AreaForce;
use Wargame\AreaMoveRules;
use Wargame\AreaTerrain;
use Wargame\CombatRules;
use Wargame\AreaGameRules;
use Wargame\Cnst;
use Wargame\Victory;
use stdClass;
use Wargame\PlayersReady;

class AreaOne extends AreaGame
{

    public $gameRules;
    public $moveRules;
    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Blue", "Red"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }


    function terrainInit($terrainDoc)
    {
        $terrainInfo = $terrainDoc->terrain;

        $mapHexes = new stdClass();
//        foreach ($specialHexes as $hexName => $specialHex) {
//            $mapHexes->$hexName = $this->specialHexesMap[$specialHex];
//            $this->{lcfirst($specialHex)}[] = $hexName;
//        }
//        $this->mapData->setSpecialHexes($mapHexes);

        $this->players = array("", "", "");
        $count = count($this->players);
        foreach($this->players as $id => $val){
            if($id === 0){
                continue;
            }
            $this->playersReady->addPlayer($id);
        }
//        for ($player = 0; $player <= 2; $player++) {
//            $this->mapViewer[$player]->setData($terrainInfo->originX, $terrainInfo->originY, // originX, originY
//                $terrainInfo->b, $terrainInfo->b, // top hexagon height, bottom hexagon height
//                $terrainInfo->a, $terrainInfo->c,// hexagon edge width, hexagon center width
//                $terrainInfo->mapWidth);
//        }

//        $oldMapUrl = $this->mapData->mapUrl;
//        if (!$oldMapUrl) {
//            $maxCol = $terrainInfo->maxCol;
//            $maxRow = $terrainInfo->maxRow;
//            $mapUrl = $terrainInfo->mapUrl;
//            $this->mapData->setData($maxCol, $maxRow, $mapUrl);
//
//            Hexagon::setMinMax();
//            $this->terrain->setMaxHex();
//        }
        return;
    }

    function xterrainInit($terrainDoc ){

//        $areas = $terrainDoc->terrain->areas;
//        $this->players = array("", "", "");
//
//        foreach($areas as $aName => $aValue){
//            $this->areaData->addArea($aName);
//        }

    }

    function terrainGen($mapDoc, $terrainDoc)
    {
//
//        $terrainArr = json_decode($terrainDoc->hexStr->hexEncodedStr);
//
//        $map = $mapDoc->map;
//        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
//        $this->terrain->maxCol = $maxCol = $map->numX;
//        $this->terrain->maxRow = $maxRow = $map->numY;
//        $this->terrain->mapWidth = $map->mapWidth;
////        $this->mapData->setData($maxCol, $maxRow, $mapUrl);
//
//        \Wargame\Hexagon::setMinMax();
////        $this->terrain->setMaxHex();
//        $a = $map->a;
//        $b = $map->b;
//        $c = $map->c;
//        $this->terrain->a = $a;
//        $this->terrain->b = $b;
//        $this->terrain->c = $c;
//        $this->terrain->originY = $b * 3 - $map->y;
//        $xOff = ($a + $c) * 2 - ($c/2 + $a);
//        $this->terrain->originX = $xOff - $map->x;
////        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
    }


    function __construct($data = null, $arg = false, $scenario = false)
    {
        $this->areaData = AreaData::getInstance();
        $this->mapData = $this->areaData;


        if ($data) {

            $this->scenario = $data->scenario;
            $this->force = new AreaForce($data->force);
            if(isset($data->terrain)){
                $this->terrain = new AreaTerrain($data->terrain);

            }else{
                $this->terrain = new AreaTerrain();
            }


//            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->moveRules = new AreaMoveRules($this->force, $this->terrain, $data->moveRules);
            $this->gameRules = new AreaGameRules($data->gameRules);

//            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force,  $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
            $this->playersReady = new PlayersReady($data);
        } else {

            $this->arg = $arg;
            $this->scenario = $scenario;
//            $this->game = $game;
//            $this->display = new stdClass();


//            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());

            $this->force = new AreaForce();

            $this->terrain = new AreaTerrain();
            $this->moveRules = new AreaMoveRules($this->force, $this->terrain);

//
//            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new AreaGameRules();
            $this->gameRules->addPhaseChange(Cnst::COMMAND_PHASE, Cnst::COMMAND_MODE, false);
            $this->gameRules->addPhaseChange(Cnst::RESULTS_PHASE, Cnst::RESULTS_MODE, true);
            $this->gameRules->setInitialPhaseMode();
            $this->gameRules->setMaxTurn(12);
            $this->playersReady = new PlayersReady();
        }


















        if ($data) {

        } else {
            $this->victory = new Victory("\\Wargame\\Area\\AreaOne\\VictoryCore");
//            if ($scenario->supplyLen) {
//                $this->victory->setSupplyLen($scenario->supplyLen);
//            }
//            $this->moveRules = new MoveRules($this->force, $this->terrain);
//            if ($scenario && $scenario->supply === true) {
//                $this->moveRules->enterZoc = 2;
//                $this->moveRules->exitZoc = 1;
//                $this->moveRules->noZocZocOneHex = true;
//            } else {
//                $this->moveRules->enterZoc = "stop";
//                $this->moveRules->exitZoc = 0;
//                $this->moveRules->noZocZocOneHex = false;
//            }
            // game data
//            $this->gameRules->setMaxTurn(7);
//            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
//            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
//            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
//            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
//
//            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }

    }

}