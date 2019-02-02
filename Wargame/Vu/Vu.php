<?php
namespace Wargame\Vu;
use Wargame\UnitFactory;
use Wargame\MapViewer;
use Wargame\Force;
use Wargame\MoveRules;
use Wargame\CombatRules;
use Wargame\GameRules;
use Wargame\Terrain;
use \stdClass;
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
global $force_name;
$force_name = array();
$force_name[0] = "unknown";
$force_name[1] = "Red";
$force_name[2] = "Blue";

global $phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;



class Vu extends \Wargame\ModernLandBattle{

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;
    public $genTerrain;


    public $players;

    static function getPlayerData($scenario){
        $forceName = ["Observer", "Red", "Blue"];
        $deployName = [$forceName[0], $forceName[1], $forceName[2]];
        return compact("forceName", "deployName");
    }

    function save()
    {
        $data = parent::save();
        return $data;
    }


    public function init(){
        // unit data -----------------------------------------------
        //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
        UnitFactory::$injector = $this->force;


        UnitFactory::create("infantry-1", BLUE_FORCE, 204, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 205, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 206, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 104, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 105, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 106, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 202, "Infantry.svg", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 208, "Infantry.svg", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 207, "Infantry.svg", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 203, "Infantry.svg", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 209, "Infantry.svg", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");


        UnitFactory::create("infantry-1", RED_FORCE, 1804, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1805, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1806, "Cavalry.svg", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1904, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1905, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1906, "Artillery.svg", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1802, "Infantry.svg", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1808, "Infantry.svg", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1807, "Infantry.svg", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1803, "Infantry.svg", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1809, "Infantry.svg", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Vu\\victoryCore");


            $this->mapData->setSpecialHexes(array(1005=>0));
            $this->force->combatRequired = true;
            $this->moveRules->stickyZoc = true;

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);


            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE,BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
        $crt = new \Wargame\Vu\CombatResultsTable();
        $this->combatRules->injectCrt($crt);
    }

    /*
  * terrainGen() gets called when a map is "published" from the map editor. It's not
  * related to a game start or a game file. It just generates the terrain info that gets saved to the
  * file terrain-Gamename
  */
}
