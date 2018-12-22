<?php
namespace Wargame\TMCW\Amph;
use \Wargame\ModernLandBattle;
use \Wargame\TMCW\UnitFactory;
use \Wargame\MoveRules;
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


global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

class Amph extends ModernLandBattle
{
    /* a comment */


    const REBEL_FORCE = 1;
    const LOYALIST_FORCE = 2;

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Rebel", "Loyalist"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
    }
    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;

        $data->options = $this->gameRules->options;
        $data->option = $this->gameRules->option;
        return $data;
    }

    public function init()
    {
        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;
        $baseValue = 6;
        $reducedBaseValue = 3;
        if(!empty($scenario->weakerLoyalist)){
            $baseValue = 5;
            $reducedBaseValue = 2;
        }
        if(!empty($scenario->strongerLoyalist)){
            $baseValue = 7;
        }
        /* Loyalists units */

        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 305, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 803, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, 208, "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, 907, "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, 1807, "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1205, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1405, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');

        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1705, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1904, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1809, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 1004, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, 604, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, 1810, "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'mountain');

        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn2", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", Amph::LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalGuards", true, 'mountain');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'mountain');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn5", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');

        if(empty($scenario->weakerLoyalist)) {
            UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn6", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", Amph::LOYALIST_FORCE, "gameTurn6", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'heavy');
        }

        /* Rebel Units */

        UnitFactory::create("lll", BLUE_FORCE, "beach-landing", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "beach-landing", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "beach-landing", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "beach-landing", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "airdrop", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");
        UnitFactory::create("lll", BLUE_FORCE, "airdrop", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 8, 4, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "rebel", true, "para");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "Ranger.svg", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "ranger");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "Ranger.svg", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "ranger");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "inf");
    }

    public static function myName(){
        echo __CLASS__;
    }
    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\TMCW\CombatResultsTable();
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
            $this->gameRules->options = $data->options;
            $this->gameRules->option = $data->option;


        } else {

            $this->victory = new \Wargame\Victory("Wargame\\TMCW\\Amph\\amphVictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            if ($scenario && !empty($scenario->supply) === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            // game data
            $this->gameRules->setMaxTurn(7);



            /*
             * comment out this and uncomment below to turn on option
             */
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
            /*
             * comment out above to turn on options
             */




            /*
             * uncomment this to turn on options
             */

//            $this->gameRules->options = ['Nuclear Facility','Chateau sur mer', 'Marine Science Facility'];
//
//            $this->gameRules->setInitialPhaseMode(BLUE_OPTION_PHASE, OPTION_MODE);
//            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
//            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
//            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

//            $this->gameRules->addPhaseChange(BLUE_OPTION_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
            /*
             * uncommenet above to turn on options
             */
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}