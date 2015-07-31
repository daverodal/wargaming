<?php
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

define("EASTERN_FORCE", 1);
define("WESTERN_EMPIRE_FORCE", 3);
define("WESTERN_FORCE", 2);
define("EASTERN_EMPIRE_FORCE", 4);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Eastern";
$force_name[2] = "Western";
$force_name[3] = "Germany (west)";
$force_name[4] = "Germany (east)";

require_once "constants.php";
require_once "ModernLandBattle.php";

class FinalChapter extends ModernLandBattle
{

    public $specialHexesMap = ['SpecialHexA'=>3, 'SpecialHexB'=>3, 'SpecialHexC'=>3];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "finalChapterHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        $playerThree = $force_name[3];
        $playerFour = $force_name[4];
        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
    }

    function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->mapData->alterSpecialHex(112, WESTERN_FORCE);
        $this->mapData->alterSpecialHex(517, WESTERN_FORCE);
        $this->mapData->alterSpecialHex(618, WESTERN_FORCE);
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

        $scenario = $this->scenario;
        $baseValue = 2;
        $reducedBaseValue = 2;

        /* Loyalists units */
        /* @var $unit SimpleUnit */
        $unitNum = 100;
        UnitFactory::$injector = $this->force;
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2302, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2302, "multiArmor.png", 5,4, 6, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2504, "multiInf.png", 4,6, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2504, "multiArmor.png", 7,5, 8, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2506, "multiInf.png", 3,5, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2506, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2307, "multiInf.png", 3,4, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2307, "multiArmor.png", 5,5, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2108, "multiArmor.png", 4,3, 8, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2309, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2309, "multiInf.png", 2,3, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2510, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2410, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2410, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2312, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2312, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2414, "multiMountain.png", 3,2, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2416, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2317, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2317, "multiArmor.png", 7,5, 8, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2118, "multiInf.png", 1,2, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2118, "multiInf.png", 3,2, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2120, "multiArmor.png", 5,3, 7, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2021, "multiArmor.png", 3,2, 7, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2024, "multiArmor.png", 4,3, 6, STATUS_READY, "A", 1, 1, "easternEmpire",  'mech', $unitNum++);


        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2224, "multiInf.png", 3,2, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);


        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2426, "multiInf.png", 4,4, 5, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2429, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2431, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternEmpire",  'inf', $unitNum++);





        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 210, "multiPara.png", 5,11, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 412, "multiArmor.png", 6,5, 6, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 613, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 613, "multiInf.png", 5,6, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 514, "multiInf.png", 3,5, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 615, "multiArmor.png", 6,5, 8, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 615, "multiArmor.png", 6,5, 8, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 516, "multiArmor.png", 5,3, 6, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 516, "multiArmor.png", 6,5, 6, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 717, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 718, "multiInf.png", 6,7, 6, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 718, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 719, "multiInf.png", 2,3, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 719, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 720, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 930, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1029, "multiPara.png", 3,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1129, "multiInf.png", 4,6, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1228, "multiInf.png", 1,3, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1228, "multiArmor.png", 3,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'mech', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 617, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 617, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernEmpire",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_FORCE, 111, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 111, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 312, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 312, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 413, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 513, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 513, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 414, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 414, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 416, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 518, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 518, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 618, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 619, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 620, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 620, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1030, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1130, "multiMech.png", 6, 6, 7, STATUS_READY, "D", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1229, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1229, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1329, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1329, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);





        UnitFactory::create("xxxxx", BLUE_FORCE, 2401, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2503, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2503, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2604, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2606, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2507, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2407, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2408, "multiInf.png", 3, 3, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2509, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",13);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2509, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2610, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2610, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2511, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2511, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2411, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2411, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",13);

        UnitFactory::create("xxxxx", BLUE_FORCE, 2412, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2417, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxxx", BLUE_FORCE, 2221, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2515, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxxx", BLUE_FORCE, 2419, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);


        UnitFactory::create("xxxx", BLUE_FORCE, 2423, "multiInf.png", 2, 5, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

        UnitFactory::create("xxxx", BLUE_FORCE, 2526, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2529, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2531, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);
        UnitFactory::create("xxxx", BLUE_FORCE, 2532, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",13);

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new Victory("SPI/FinalChapter/finalChapterVictoryCore.php");

            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->blockedRetreatDamages = false;
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZoc = true;
            $this->moveRules->friendlyAllowsRetreat = false;

//            $this->moveRules->stacking = 2;
            // game data
            $this->gameRules->setMaxTurn(10);

            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);

            $this->gameRules->attackingForceId = EASTERN_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = EASTERN_EMPIRE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId, $this->gameRules->defendingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, TEAL_REPLACEMENT_PHASE, REPLACING_MODE,  WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);


            $this->gameRules->addPhaseChange(TEAL_REPLACEMENT_PHASE, TEAL_MOVE_PHASE, MOVING_MODE, WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);
            $this->gameRules->addPhaseChange(TEAL_MOVE_PHASE, TEAL_COMBAT_PHASE, COMBAT_SETUP_MODE, WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);
            $this->gameRules->addPhaseChange(TEAL_COMBAT_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, PURPLE_REPLACEMENT_PHASE, REPLACING_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);

            $this->gameRules->addPhaseChange(PURPLE_REPLACEMENT_PHASE, PURPLE_MOVE_PHASE, MOVING_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);
            $this->gameRules->addPhaseChange(PURPLE_MOVE_PHASE, PURPLE_COMBAT_PHASE, COMBAT_SETUP_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);
            $this->gameRules->addPhaseChange(PURPLE_COMBAT_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, true);


            $this->victory->victoryPoints[WESTERN_FORCE] = 3;
        }

        if($this->players){
            /*
             * if player 3 set, they become player 3 and four
             * if 3 and 4 not set 3 becomes 1 and 4 becomes 2
             */
            if(!isset($this->players[4]) && $this->players[3]) {
                $this->players[4] = $this->players[3];
            }
            if(!isset($this->players[3]) && $this->players[1]){
                $this->players[3] = $this->players[1];
            }
            if(!isset($this->players[4]) && $this->players[2]) {
                $this->players[4] = $this->players[2];
            }
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $armyGroup = false;
            if($unit->name == "xxxxx"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $armyGroup = true;
                }
            }

            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->name == "xxxxx"){
                    if($armyGroup){
                        return true;
                    }
                }
            }
            return count((array)$mapHex->forces[$forceId]) >= 2;
        };

    }
}