<?php
namespace Wargame\TMCW\Nomonhan;
use \Wargame\TMCW\UnitFactory;
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
$force_name[1] = "Japanese";
$force_name[2] = "Soviet";

$phase_name[16] = "<span class='playerOneFace'>Japanese</span> surprise movement phase";

$force_name[0] = "Neutral Observer";
$force_name[1] = "Japanese";
$force_name[2] = "Soviet";

class Nomonhan extends \Wargame\ModernLandBattle
{
    const JAPANESE_FORCE = 1;
    const SOVIET_FORCE = 2;
    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Japanese", "Soviet"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function save()
    {
        $data = parent::save();
        return $data;
    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    public function init(){
        // unit data -----------------------------------------------
        //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
        UnitFactory::$injector = $this->force;

        // SOVIET Initial forces, can deploy on turn 1
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');

        // Soviet Reinforcemenets, can deploy turn 6
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "cavalry");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        UnitFactory::create("xx", Nomonhan::SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");


        // Japanese Forces, all can enter on turn 1
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "cavalry");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "japanese", true, "artillery");
        UnitFactory::create("xx", Nomonhan::JAPANESE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "japanese", true, "artillery");
        // end unit data -------------------------------------------


    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);

        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("marsh", "marsh", "m", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("rough", "rough", "g", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("hills", "hills", "h", 4, 0, 2, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 2, 2, 1, false);
        $this->terrain->addTerrainFeature("ford", "ford", "v", 2, 1, 1, true);
        $this->terrain->addAltEntranceCost('marsh', 'artillery', 6);
        $this->terrain->addAltEntranceCost('marsh', 'mech', 6);


        for ($i = 1; $i <= 1; $i++) {
            for ($j = 1; $j <= 37; $j++) {
                if ($j == 10) {
                    continue;
                }
                $this->terrain->addReinforceZone($j * 100 + $i, "J");

            }
        }
        for ($i = 4; $i <= 25; $i++) {
            for ($j = 1; $j <= 40; $j++) {
                if ($i == 4 && ($j == 34 || $j == 35)) {
                    continue;
                }
                if ($i == 2 && ($j == 36 || $j == 37)) {
                    continue;
                }
                $this->terrain->addReinforceZone($j * 100 + $i, "R");
            }
        }
        $this->terrain->addReinforceZone(3603, "R");

        for ($i = 25; $i <= 25; $i++) {
            for ($j = 1; $j <= 40; $j++) {

                $this->terrain->addReinforceZone($j * 100 + $i, "W");

            }
        }
//        $map = $mapDoc->map;
//        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
//        $this->terrain->maxCol = $maxCol = $map->numX;
//        $this->terrain->maxRow = $maxRow = $map->numY;
//        $this->terrain->mapWidth = $map->mapWidth;
//        $this->mapData->setData($maxCol, $maxRow, $mapUrl);

    }
    function __construct($data = null, $arg = false, $scenario = false)
    {
        parent::__construct($data, $arg, $scenario);

        $crt = new NomonhanCombatResultsTable();
        $this->combatRules->injectCrt($crt);
        if ($data) {

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\Nomonhan\\nomonhanVictoryCore");
//            $this->mapData->setData(40, 25, "js/Nomonhan3Small.png");

//            $this->terrain->setMaxHex("4025");
            if ($scenario && !empty($scenario->supply) === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            $this->players = array("", "", "");

//            /* Observer, BLUE_FORCE, RED_FORCE */
//            for($i = 0; $i < 3;$i++){
//                $this->mapViewer[$i]->setData(54, 79, // originX, originY
//                    25.5, 25.5, // top hexagon height, bottom hexagon height
//                    14.725, 29.45, // hexagon edge width, hexagon center width
//                    4025, 4025 // max right hexagon, max bottom hexagon
//                );
//            }

            // game data
            $this->gameRules->setMaxTurn(20);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_SURPRISE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_SURPRISE_MOVE_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data

            // unit terrain data---------------------------------------
            // end terrain data ----------------------------------------
        }
        $this->combatRules->crt = new NomonhanCombatResultsTable();
    }
}
