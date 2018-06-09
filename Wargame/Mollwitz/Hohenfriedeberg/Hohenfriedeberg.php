<?php
namespace Wargame\Mollwitz\Hohenfriedeberg;
use \Wargame\Mollwitz\UnitFactory;
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
//
//global $force_name;
//$force_name[1] = "Prussian";
//$force_name[2] = "Austrian";

class Hohenfriedeberg extends \Wargame\Mollwitz\JagCore
{

    const PRUSSIAN_FORCE = 1;
    const AUSTRIAN_FORCE = 2;

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;


    public $players;

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Prussian", "Austrian"],
            ["Observer", "Austrian", "Prussian" ]);
    }

    function save()
    {
        $data = parent::save();

        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;

        return $data;
    }

    public function init(){




        $artRange = 3;
        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;
        $unitSets = $scenario->units;

        UnitFactory::$injector = $this->force;

        foreach ($unitSets as $unitSet) {
            if (empty($scenario->commandControl)) {
                if ($unitSet->class === 'hq'){
                    continue;
                }
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                UnitFactory::create("infantry-1", $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
            }
        }
        return;

//        $ret = "";
//        for($i = 0;$i < 5;$i++){
//                $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
//        }
//        $ret .= '"num": 5},';
//        echo $ret;
//        for($i = 0;$i < 12;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
//
//        }
//        $ret .= '"num": 12},';
//        echo $ret;
//        for($i = 0;$i < 4;$i++){
//                $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
//        }
//        $ret .= '"num": 4},';
//        echo $ret;
//        for($i = 0;$i < 4;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
//        }
//        $ret .= '"num": 4},';
//        echo $ret;
//        for($i = 0;$i < 2;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
//        }
//        $ret .= '"num": 2},';
//        echo $ret;
//        for($i = 0;$i < 2;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
//        }
//        $ret .= '"num": 2},';
//        echo $ret;
//        for($i = 0;$i < 4;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
//        }
//        $ret .= '"num": 4},';
//        echo $ret;
//
//        if(!empty($this->scenario->bigAustrian)){
//            $nFourThrees = 7;
//            $nThreeThrees = 10;
//            $nFourTwos = 2;
//            $nTwoTwos = 3;
//            $nFourFives = 2;
//            $nFiveFives = 2;
//        }else{
//            $nFourThrees = 3;
//            $nThreeThrees = 14;
//            $nFourTwos = 0;
//            $nTwoTwos = 5;
//            $nFourFives = 4;
//            $nFiveFives = 0;
//        }
//
//
//        for($i = 0;$i < $nFourThrees;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
//
//        }
//        $ret .= '"num": ' . $nFourThrees .'},';
//        echo $ret;
//        for($i = 0;$i < $nThreeThrees;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
//        }
//        $ret .= '"num": ' . $nThreeThrees .'},';
//        echo $ret;
//        for($i = 0;$i < $nFourFives;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
//        }
//        $ret .= '"num": ' . $nFourFives .'},';
//        echo $ret;
//        for($i = 0;$i < $nFiveFives;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
//        }
//        $ret .= '"num": ' . $nFiveFives .'},';
////        echo $ret;
//        for($i = 0;$i < 4;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
//        }
//        $ret .= '"num": ' . 4 .'},';
//        echo $ret;
//        for($i = 0;$i < 2;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
//        }
//        $ret .= '"num": ' . 2 .'},';
//        echo $ret;
//        for($i = 0;$i < $nFourTwos;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
//        }
//        $ret .= '"num": ' . $nFourTwos .'},';
////        echo $ret;
//        for($i = 0;$i < $nTwoTwos;$i++){
//            $ret = UnitFactory::create("infantry-1", Hohenfriedeberg::AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
//        }
//        $ret .= '"num": ' . $nTwoTwos .'},';
//        echo $ret;
//        dd("Mem");
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Hohenfriedeberg\\hohenfriedebergVictoryCore");

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;

            // game data
            if(!empty($scenario->deployForward)){
                $this->gameRules->setMaxTurn(14);
            }else{
                $this->gameRules->setMaxTurn(15);
            }
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}