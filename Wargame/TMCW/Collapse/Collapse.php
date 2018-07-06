<?php
namespace Wargame\TMCW\Collapse;
use \Wargame\TMCW\Collapse\UnitFactory;

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



class Collapse extends \Wargame\ModernLandBattle
{
    const GERMAN_FORCE = 2;
    const SOVIET_FORCE = 1;
    const RED_FORCE = 2;
    const BLUE_FORCE = 1;

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Soviet", "German"];
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
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 0, 1, true);

        $this->terrain->addAltEntranceCost("clear", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("forest", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("swamp", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("forta", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost("fortb", 'railhead', "blocked");
        $this->terrain->addAltEntranceCost('road', 'railhead', 1);



        $this->terrain->addNatAltEntranceCost('forta', 'german', 'mech', 3);
        $this->terrain->addNatAltEntranceCost('forta',  'german', 'inf', 3);
        $this->terrain->addNatAltEntranceCost('fortb', 'soviet', 'mech', 3);
        $this->terrain->addNatAltEntranceCost('fortb', 'soviet', 'inf', 3);


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

    public function init()
    {
        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;

        $i = 0;
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", Collapse::GERMAN_FORCE, "deployBox", "multiArmor.png", 5,  8,STATUS_CAN_DEPLOY, "B", 1, "german", "mech", "1");

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xx", Collapse::GERMAN_FORCE, "deployBox", "multiArmor.png", 4,  8,STATUS_CAN_DEPLOY, "B", 1, "german", "mech", "1");

        }
        for($i = 0; $i < 36; $i++){
            UnitFactory::create("xx", Collapse::GERMAN_FORCE, "deployBox", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "A", 1, "german", "inf", "2");

        }
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xx", Collapse::GERMAN_FORCE, "deployBox", "multiInf.png", 1,  5,STATUS_CAN_DEPLOY, "A", 1, "german", "inf", "2");

        }
        for($i = 0; $i < 11; $i++){
            UnitFactory::create("xx", Collapse::GERMAN_FORCE, "deployBox", "multiInf.png", 1,  4,STATUS_CAN_DEPLOY, "A", 1, "german", "inf", "2");

        }

        //
        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn2", "multiArmor.png", 5,  8,STATUS_CAN_DEPLOY, "B", 2, "german", "mech", "1");
        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn3", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "B", 3, "german", "inf", "2");
        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn3", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "C", 3, "german", "inf", "2");

        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn4", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "B", 4, "german", "inf", "2");
        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn4", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "B", 4, "german", "inf", "2");
        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn4", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "C", 4, "german", "inf", "2");

        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn5", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "C", 5, "german", "inf", "2");

        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn6", "multiArmor.png", 4,  8,STATUS_CAN_DEPLOY, "B", 6, "german", "mech", "1");

        UnitFactory::create("xx", Collapse::GERMAN_FORCE, "gameTurn7", "multiInf.png", 2,  5,STATUS_CAN_DEPLOY, "B", 7, "german", "inf", "2");

        for($i = 0; $i < 44; $i++){
            UnitFactory::create("xxx", Collapse::SOVIET_FORCE, "deployBox", "multiInf.png",
                3,  4,STATUS_CAN_DEPLOY, "D", 1, "soviet", "inf", "$i i");

        }
        for($i = 0; $i < 9; $i++){
            UnitFactory::create("xxx", Collapse::SOVIET_FORCE, "deployBox", "multiInf.png",
                4,  5,STATUS_CAN_DEPLOY, "D", 1, "soviet", "inf", "$i i");

        }
        for($i = 0; $i < 7; $i++){
            UnitFactory::create("xxx", Collapse::SOVIET_FORCE, "deployBox", "multiArmor.png",
                5,  6,STATUS_CAN_DEPLOY, "D", 1, "soviet", "mech", "$i a");

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxx", Collapse::SOVIET_FORCE, "deployBox", "multiMech.png",
                6,  6,STATUS_CAN_DEPLOY, "D", 1, "soviet", "mech", "$i a");

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xxx", Collapse::SOVIET_FORCE, "deployBox", "multiCav.png",
                3,  5,STATUS_CAN_DEPLOY, "D", 1, "soviet", "mech", "$i a");

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("", Collapse::SOVIET_FORCE, "deployBox", "railhead.png",
                1,  1,STATUS_CAN_DEPLOY, "E", 1, "soviet", "railhead", $i + 1);

        }

    }

    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\TMCW\Collapse\CombatResultsTable(Collapse::GERMAN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;


        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\Collapse\\VictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }

            $this->moveRules->enterZoc = 2;
            $this->moveRules->exitZoc = 2;
            $this->moveRules->stacking = 3;
            $this->moveRules->oneHex = true;
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->zocBlocksSupply = true;

            $this->moveRules->friendlyAllowsRetreat = false;
            $this->moveRules->blockedRetreatDamages = true;
            $this->gameRules->legacyExchangeRule = false;

            // game data
            $this->gameRules->setMaxTurn(10);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, Collapse::BLUE_FORCE, Collapse::RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Collapse::BLUE_FORCE, Collapse::RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Collapse::GERMAN_FORCE, Collapse::SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Collapse::BLUE_FORCE, Collapse::RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, Collapse::BLUE_FORCE, Collapse::RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, Collapse::RED_FORCE, Collapse::BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Collapse::SOVIET_FORCE, Collapse::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Collapse::RED_FORCE, Collapse::BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, Collapse::RED_FORCE, Collapse::BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Collapse::BLUE_FORCE, Collapse::RED_FORCE, true);
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

            if($numNonRailhead >= 3){
                return true;
            }
            return false;
        };

    }
}