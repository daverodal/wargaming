<?php
namespace Wargame\TMCW\Manchuria1976;
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
set_include_path(__DIR__ . "/Manchuria1976". PATH_SEPARATOR .  get_include_path());


global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();


class Manchuria1976 extends \Wargame\ModernLandBattle
{
    
    const SOVIET_FORCE = 1;
    const PRC_FORCE = 2;
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "Soviet", "PRC"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        return $data;
    }


    function terrainGen($mapDoc, $terrainDoc){
        parent::TerrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 2, false);
        $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("mountain", "mountain", "g", 1.5, 0, 2, true);
        $this->terrain->addAltEntranceCost('mountain', 'mech', 6);

    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    public function init(){

        UnitFactory::$injector = $this->force;

        $scenario = $this->scenario;

        for($i = 0; $i < 30; $i++){
            UnitFactory::create("xxxx", self::PRC_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "inf");
        }
        UnitFactory::create("xxx", self::PRC_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "mech");
        for($i = 2; $i <= 12;$i++){
            UnitFactory::create("x", self::PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
            UnitFactory::create("x", self::PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
            UnitFactory::create("x", self::PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
        }




        for($i = 0;$i < 5;$i++){
            UnitFactory::create("xxx", self::SOVIET_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 10;$i++){
            UnitFactory::create("xxx", self::SOVIET_FORCE, "deployBox", "multiMech.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 15;$i++){
            UnitFactory::create("xxx", self::SOVIET_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }

        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xxx", self::SOVIET_FORCE, "deployBox", "multiArt.png", 3, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 2, "soviet", true, "mech");
        }
        for($i = 0;$i < 2;$i++){
            UnitFactory::create("", self::SOVIET_FORCE, "deployBox", "Supply.svg", 1, 1, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "supply");
        }
    }
    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\Manchuria1976\\victoryCore");

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
            $this->gameRules->setMaxTurn(12);


            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
        $crt = new CombatResultsTable();
        $this->combatRules->injectCrt($crt);
        $this->moveRules->noZocZocOneHex = false;

    }
}