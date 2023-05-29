<?php
namespace Wargame\TMCW\NorthVsSouth;

use Wargame\TMCW\Manchuria1976\Unit;

/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */



class NorthVsSouth extends \Wargame\ModernLandBattle
{
    const SOUTHERN_FORCE = 2;
    const NORTHERN_FORCE = 1;
    const RED_FORCE = 2;
    const BLUE_FORCE = 1;

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Northern States", "Southern States"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function terrainGen($mapDoc, $terrainDoc)
    {

        parent::terrainGen($mapDoc, $terrainDoc);

        $this->terrain->addTerrainFeature("forest", "forest", "f", 1, 0, 1, true);
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 1, 0, 1, true);
        $this->terrain->addAltEntranceCost("forest", 'mech', 2);
        $this->terrain->addAltEntranceCost("swamp", 'mech', 2);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 1, true);

        $this->terrain->addAltEntranceCost("clear", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("forest", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("swamp", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("forta", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("fortb", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost('road', 'railhead', 1);


//
//        $this->terrain->addNatAltEntranceCost('forta', 'german', 'mech', 3);
//        $this->terrain->addNatAltEntranceCost('forta',  'german', 'inf', 3);
//        $this->terrain->addNatAltEntranceCost('fortb', 'soviet', 'mech', 3);
//        $this->terrain->addNatAltEntranceCost('fortb', 'soviet', 'inf', 3);


    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    function terrainInit($terrainDoc){

        parent::terrainInit($terrainDoc);
        UnitFactory::$injector = $this->force;

    }

    function save()
    {
        $data = parent::save();

        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;

        return $data;
    }

    public function westernInit(){
        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;

        $id = 0;
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Armor.svg",
                10, 8,  8,STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Armor.svg",
                8, 8,  8,STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "Armor.svg",
                6, 8,  8,STATUS_ELIMINATED, "A", 1,
                "southern", "mech", $id++);

        }
        UnitFactory::flush();
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "MechInf.svg",
                7,5,  8,STATUS_CAN_DEPLOY, "A", 1, "southern", "mech", $id++);

        }
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "MechInf.svg",
                5,5,  8,STATUS_CAN_DEPLOY, "A", 1, "southern", "mech", $id++);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "MechInf.svg",
                3,5,  8,STATUS_ELIMINATED, "A", 1, "southern", "mech", $id++);

        }
        UnitFactory::flush();
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "Infantry.svg",
                5, 3, 8,STATUS_ELIMINATED, "A", 1, "southern", "inf", $id++);

        }
        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Infantry.svg",
                3, 3, 8,STATUS_CAN_DEPLOY, "A", 1, "southern", "inf", $id++);

        }
        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Infantry.svg",
                2, 3, 8,STATUS_CAN_DEPLOY, "A", 1, "southern", "inf", $id++);

        }
        $units = UnitFactory::getShuffled();
        for($i = 0; $i < 10; $i++){
            $unit = array_shift($units);
            $unit->setHexagon("deployBox");
            $unit->reinforceZoneName = "A";
            $unit->reinforceTurn = 1;
            $unit->status = STATUS_CAN_DEPLOY;
            $this->force->injectUnit($unit);
        }
        for(; count($units) > 0;) {
            $unit = array_shift($units);
            $unit->setHexagon("deadpile");
            $unit->reinforceZoneName = "C";
            $unit->reinforceTurn = 1;
            $unit->status = STATUS_ELIMINATED;
            $this->force->injectUnit($unit);
        }
//        UnitFactory::flush();

//        for($i = 0; $i < 2; $i++){
//            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deadpile", "MechInf.svg",
//                8, 5, 6,STATUS_ELIMINATED, "D", 1, "northern", "mech", $id++);
//
//        }
//
//        for($i = 0; $i < 5; $i++){
//            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn5", "MechInf.svg",
//                5, 5, 6,STATUS_CAN_REINFORCE, "C", 5, "northern", "mech",  $id++);
//
//        }
//
//        for($i = 0; $i < 2; $i++){
//            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn6", "MechInf.svg",
//                3, 5, 6,STATUS_CAN_REINFORCE, "C", 6, "northern", "mech",  $id++);
//
//        }
//        UnitFactory::flush();

        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deadpile", "MechInf.svg",
                8, 5, 6,STATUS_ELIMINATED, "C", 1, "northern", "mech", $id++);

        }

        for($i = 0; $i < 5; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn5", "MechInf.svg",
                5, 5, 6,STATUS_CAN_REINFORCE, "C", 5, "northern", "mech",  $id++);

        }

        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn6", "MechInf.svg",
                3, 5, 6,STATUS_CAN_REINFORCE, "C", 6, "northern", "mech",  $id++);

        }
        $units = UnitFactory::getShuffled();
        for($i = 2; $i <= 6; $i++){
            $unit = array_shift($units);
            $unit->setHexagon("gameTurn$i");
            $unit->reinforceZoneName = "C";
            $unit->reinforceTurn = $i;
            $unit->status = STATUS_CAN_REINFORCE;
            $this->force->injectUnit($unit);
        }
        for($i = 0; count($units) > 0; $i++) {
            $unit = array_shift($units);
            $unit->setHexagon("deadpile");
            $unit->reinforceZoneName = "C";
            $unit->reinforceTurn = 1;
            $unit->status = STATUS_ELIMINATED;
            $this->force->injectUnit($unit);
        }

        for($i = 0; $i < 6; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Infantry.svg",
                6,  4,8,STATUS_CAN_DEPLOY, "D", 1, "northern", "inf",  $id++);

        }
        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Infantry.svg",
                4,  4,8,STATUS_CAN_DEPLOY, "D", 1, "northern", "inf",  $id++);

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Infantry.svg",
                2,  4,8,STATUS_CAN_DEPLOY, "D", 1, "northern", "inf",  $id++);

        }
        UnitFactory::flush();

        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn4", "Armor.svg",
                10,  8,6,STATUS_CAN_REINFORCE, "C", 4, "northern", "mech",  $id++);

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn2", "Armor.svg",
                8,  8,6,STATUS_CAN_REINFORCE, "C", 2, "northern", "mech",  $id++);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "gameTurn3", "Armor.svg",
                4,  8,6,STATUS_CAN_REINFORCE, "C", 3, "northern", "mech",  $id++);

        }
//        UnitFactory::flush();
        $units = UnitFactory::getShuffled();
        for($i = 2; $i <= 6; $i++){
            $unit = array_shift($units);
            $unit->setHexagon("gameTurn$i");
            $unit->reinforceZoneName = "C";
            $unit->reinforceTurn = $i;
            $unit->status = STATUS_CAN_REINFORCE;
            $this->force->injectUnit($unit);
        }
        for($i = 0; count($units) > 0; $i++) {
            $unit = array_shift($units);
            $unit->setHexagon("deadpile");
            $unit->reinforceZoneName = "C";
            $unit->reinforceTurn = 1;
            $unit->status = STATUS_ELIMINATED;
            $this->force->injectUnit($unit);
        }
    }

    public function init()
    {
        $Die = $this->dieRolls->getEvent(10000);
        /* shuffle uses rand() so we call srand with a remembered random number */
        srand($Die);

        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;
        if($scenario->name === "Western"){
            $this->westernInit();
            return;
        }

        $id = 0;
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "Armor.svg",
                10, 8,  8,STATUS_ELIMINATED, "A", 1,
                "southern", "mech", $id++);

        }
        for($i = 0; $i < 6; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Armor.svg",
                8, 8,  8,STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Armor.svg",
                6, 8,  8,STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);

        }
        UnitFactory::flush();
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "MechInf.svg",
                7,5,  8,STATUS_ELIMINATED, "A", 1, "southern", "mech", $id++);

        }
        for($i = 0; $i < 8; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "MechInf.svg",
                5,5,  8,STATUS_CAN_DEPLOY, "A", 1, "southern", "mech", $id++);

        }
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "MechInf.svg",
                3,5,  8,STATUS_CAN_DEPLOY, "A", 1, "southern", "mech", $id++);

        }
        UnitFactory::flush();
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deadpile", "Infantry.svg",
                5, 3, 8,STATUS_ELIMINATED, "A", 1, "southern", "inf", $id++);

        }
        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Infantry.svg",
                2, 3, 8,STATUS_CAN_DEPLOY, "A", 1, "southern", "inf", $id++);

        }
        for($i = 0; $i < 8; $i++){
            UnitFactory::create("xx", NorthVsSouth::SOUTHERN_FORCE, "deployBox", "Infantry.svg",
                2, 3, 8,STATUS_CAN_DEPLOY, "A", 1, "southern", "inf", $id++);

        }


        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deadpile", "MechInf.svg",
                8, 5, 6,STATUS_ELIMINATED, "D", 1, "northern", "mech", $id++);

        }

        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "MechInf.svg",
                5, 5, 6,STATUS_CAN_DEPLOY, "D", 1, "northern", "mech",  $id++);

        }

        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "MechInf.svg",
                3, 5, 6,STATUS_CAN_DEPLOY, "D", 1, "northern", "mech",  $id++);

        }
        UnitFactory::flush();

        for($i = 0; $i < 6; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deadpile", "Infantry.svg",
                6,  4,8,STATUS_ELIMINATED, "D", 1, "northern", "inf",  $id++);

        }
        for($i = 0; $i < 10; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Infantry.svg",
                4,  4,8,STATUS_CAN_DEPLOY, "D", 1, "northern", "inf",  $id++);

        }
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Infantry.svg",
                2,  4,8,STATUS_CAN_DEPLOY, "D", 1, "northern", "inf",  $id++);

        }
        UnitFactory::flush();

        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deadpile", "Armor.svg",
                10,  8,6,STATUS_ELIMINATED, "D", 1, "northern", "mech",  $id++);

        }
        for($i = 0; $i < 6; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Armor.svg",
                8,  8,6,STATUS_CAN_DEPLOY, "D", 1, "northern", "mech",  $id++);

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xxx", NorthVsSouth::NORTHERN_FORCE, "deployBox", "Armor.svg",
                4,  8,6,STATUS_CAN_DEPLOY, "D", 1, "northern", "mech",  $id++);

        }
        UnitFactory::flush();

    }

    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\TMCW\NorthVsSouth\CombatResultsTable(NorthVsSouth::SOUTHERN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;


        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\NorthVsSouth\\VictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }

            $this->moveRules->enterZoc = 'stop';
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZoc = false;
            $this->moveRules->stacking = 1;
            $this->moveRules->oneHex = false;
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->zocBlocksSupply = true;

            $this->moveRules->friendlyAllowsRetreat = false;
            $this->moveRules->blockedRetreatDamages = true;
            $this->gameRules->legacyExchangeRule = false;

            // game data
            $this->gameRules->setMaxTurn(10);



            if($scenario->name === "Western"){
                $this->startGameSouth();
            }else{
                $this->startGameNorth();
            }
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            if($unit->class === 'railhead'){
                return false;
            }
            $onlyRailhead = true;
            $numNonRailhead = 0;
            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->class !== "railhead" ){
                    $onlyRailhead = false;
                    $numNonRailhead++;
                }
            }
            if($onlyRailhead === true){
                return false;
            }

            if($numNonRailhead >= 1){
                return true;
            }
            return false;
        };

    }
    public function startGameSouth(){
        $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, RED_MOVE_PHASE, MOVING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);

        $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, true);

    }

    public function startGameNorth()
    {
        $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
        $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);

        $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);

        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, NorthVsSouth::RED_FORCE, NorthVsSouth::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, NorthVsSouth::BLUE_FORCE, NorthVsSouth::RED_FORCE, true);
    }
}