<?php
namespace Wargame\ModernBattles\Europe;
use Wargame\ModernBattles\ModernLandBattle;
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



class Europe extends ModernLandBattle
{
    const SOVIET_FORCE = 2;
    const NATO_FORCE = 1;
    const RED_FORCE = 2;
    const BLUE_FORCE = 1;

    public $specialHexesMap = ['SpecialHexA'=>0, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "NATO", "Soviet Union"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function terrainGen($mapDoc, $terrainDoc)
    {

        parent::terrainGen($mapDoc, $terrainDoc);

        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 3, 1, true);
        $this->terrain->addAltEntranceCost("roughtwo", 'air', 1);
        $this->terrain->addAltEntranceCost("forest", 'air', 1);
        $this->terrain->addAltEntranceCost("road", 'air', 1);
        $this->terrain->addAltTraverseCost("river", 'air', 0);
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

        $id = 0;

        if($scenario->name === 'main') {
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "MechInf.svg",
                    1, 2, 12, STATUS_CAN_REINFORCE, "A", 1,
                    "southern", "mech", $id++);

            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "MechInf.svg",
                    3, 2, 12, STATUS_CAN_REINFORCE, "A", 1,
                    "southern", "mech", $id++);

            }
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "A", 1,
                "southern", "artillery", $id++, 8, 0);
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "Artillery.svg",
                3, 1, 9, STATUS_CAN_REINFORCE, "A", 1,
                "southern", "artillery", $id++, 7, 1);

            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("|||", Europe::SOVIET_FORCE, "gameTurn3", "Armor.svg",
                    4, 2, 12, STATUS_CAN_REINFORCE, "A", 3,
                    "southern", "mech", $id++);
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn3", "MechInf.svg",
                    1, 2, 12, STATUS_CAN_REINFORCE, "A", 3,
                    "southern", "mech", $id++);
            }

            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "MechInf.svg",
                    1, 2, 12, STATUS_CAN_REINFORCE, "A", 4,
                    "southern", "mech", $id++);
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "MechInf.svg",
                    3, 2, 12, STATUS_CAN_REINFORCE, "A", 4,
                    "southern", "mech", $id++);
            }

            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "A", 4,
                "southern", "artillery", $id++, 8, 0);
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "Artillery.svg",
                5, 1, 9, STATUS_CAN_REINFORCE, "A", 4,
                "southern", "artillery", $id++, 7, 1);

            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn5", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "A", 5,
                "southern", "artillery", $id++, 8, 0);
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn5", "Artillery.svg",
                3, 1, 9, STATUS_CAN_REINFORCE, "A", 5,
                "southern", "artillery", $id++, 7, 1);

            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Armor.svg",
                    3, 3, 12, STATUS_CAN_REINFORCE, "D", 1, "northern", "mech", $id++);
            }
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Artillery.svg",
                2, 1, 12, STATUS_CAN_REINFORCE, "D", 1,
                "northern", "artillery", $id++, 7, 1);

            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "RotaryWing.svg",
                2, 1, 30, STATUS_CAN_REINFORCE, "D", 1,
                "northern", "air", $id++, 2, 3);


            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn2", "Armor.svg",
                    3, 3, 12, STATUS_CAN_REINFORCE, "D", 2, "northern", "mech", $id++);
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn2", "MechInf.svg",
                    2, 3, 12, STATUS_CAN_REINFORCE, "D", 2, "northern", "mech", $id++);
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn2", "Armor.svg",
                    3, 2, 12, STATUS_CAN_REINFORCE, "D", 2, "northern", "mech", $id++);
            }

            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn3", "Artillery.svg",
                    1, 2, 12, STATUS_CAN_REINFORCE, "D", 3,
                    "northern", "artillery", $id++, 7, 2);
            }

            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn3", "MechInf.svg",
                    2, 3, 12, STATUS_CAN_REINFORCE, "D", 3, "northern", "mech", $id++);
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn3", "Armor.svg",
                    3, 2, 12, STATUS_CAN_REINFORCE, "D", 3,
                    "northern", "mech", $id++);
            }


            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn4", "Artillery.svg",
                    2, 1, 12, STATUS_CAN_REINFORCE, "D", 4,
                    "northern", "artillery", $id++, 7, 1);
            }


            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn4", "Artillery.svg",
                    2, 1, 12, STATUS_CAN_REINFORCE, "D", 4,
                    "northern", "artillery", $id++, 13, 1);
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn4", "MechInf.svg",
                    2, 3, 12, STATUS_CAN_REINFORCE, "D", 4, "northern", "mech", $id++);
            }

            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn4", "Armor.svg",
                    3, 2, 12, STATUS_CAN_REINFORCE, "D", 4, "northern", "mech", $id++);

            }
        }
        if($scenario->name === 'three') {
           $this->three();
        }

    }

    private function three(){
        $id = 0;
        for ($i = 0; $i < 6; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "MechInf.svg",
                2, 3, 12, STATUS_CAN_DEPLOY, "D", 1, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 4; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Armor.svg",
                3, 2, 12, STATUS_CAN_DEPLOY, "D", 1, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 4; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Armor.svg",
                3, 3, 12, STATUS_CAN_DEPLOY, "D", 1, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Artillery.svg",
                1, 2, 12, STATUS_CAN_DEPLOY, "D", 1,
                "northern", "artillery", $id++, 7, 2);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "Artillery.svg",
                2, 1, 12, STATUS_CAN_DEPLOY, "D", 1,
                "northern", "artillery", $id++, 7, 1);
        }
        UnitFactory::create("||", Europe::NATO_FORCE, "deployBox", "RotaryWing.svg",
            2, 1, 30, STATUS_CAN_DEPLOY, "D", 1,
            "northern", "air", $id++, 2, 3);


        for ($i = 0; $i < 12; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "MechInf.svg",
                1, 2, 12, STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);
        }

        for ($i = 0; $i < 6; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "MechInf.svg",
                3, 2, 12, STATUS_CAN_DEPLOY, "A", 1,
                "southern", "mech", $id++);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "Artillery.svg",
                4, 1, 9, STATUS_CAN_DEPLOY, "A", 1,
                "southern", "artillery", $id++, 8, 0);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "deployBox", "Artillery.svg",
                3, 1, 9, STATUS_CAN_DEPLOY, "A", 1,
                "southern", "artillery", $id++, 7, 1);
        }


        /* Turn 5 */

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn5", "MechInf.svg",
                2, 3, 12, STATUS_CAN_REINFORCE, "C", 5, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn5", "Armor.svg",
                3, 2, 12, STATUS_CAN_REINFORCE, "C", 5, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn5", "Armor.svg",
                3, 3, 12, STATUS_CAN_REINFORCE, "C", 5, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn5", "Artillery.svg",
                1, 2, 12, STATUS_CAN_REINFORCE, "C", 5,
                "northern", "artillery", $id++, 7, 2);
        }

        /* Turn 6 */
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn6", "MechInf.svg",
                2, 3, 12, STATUS_CAN_REINFORCE, "C", 6, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn6", "Armor.svg",
                3, 2, 12, STATUS_CAN_REINFORCE, "C", 6, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn6", "Artillery.svg",
                1, 2, 12, STATUS_CAN_REINFORCE, "C", 6,
                "northern", "artillery", $id++, 7, 2);
        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn6", "Artillery.svg",
                2, 1, 12, STATUS_CAN_REINFORCE, "C", 6,
                "northern", "artillery", $id++, 7, 1);
        }

        /* Turn 7 */
        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn7", "MechInf.svg",
                2, 3, 12, STATUS_CAN_REINFORCE, "C", 7, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn7", "Armor.svg",
                3, 2, 12, STATUS_CAN_REINFORCE, "C", 7, "northern", "mech", $id++);
        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn7", "Artillery.svg",
                2, 1, 12, STATUS_CAN_REINFORCE, "C", 7,
                "northern", "artillery", $id++, 7, 1);
        }

        /* Turn 8 */
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn8", "MechInf.svg",
                2, 3, 12, STATUS_CAN_REINFORCE, "C", 8, "northern", "mech", $id++);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn8", "Armor.svg",
                3, 2, 12, STATUS_CAN_REINFORCE, "C", 8, "northern", "mech", $id++);
        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn8", "Artillery.svg",
                2, 1, 12, STATUS_CAN_REINFORCE, "C", 8,
                "northern", "artillery", $id++, 7, 1);
        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("||", Europe::NATO_FORCE, "gameTurn8", "Artillery.svg",
                2, 1, 12, STATUS_CAN_REINFORCE, "C", 8,
                "northern", "artillery", $id++, 13, 1);
        }

        /* Soviet */

        /* Turn 2 */
        for ($i = 0; $i < 6; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn2", "MechInf.svg",
                1, 2, 12, STATUS_CAN_REINFORCE, "B", 2,
                "southern", "mech", $id++);
        }

        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn2", "MechInf.svg",
                3, 2, 12, STATUS_CAN_REINFORCE, "B", 2,
                "southern", "mech", $id++);
        }

        /* Turn 3 */

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn3", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "B", 3,
                "southern", "artillery", $id++, 8, 0);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn3", "Artillery.svg",
                3, 1, 9, STATUS_CAN_REINFORCE, "B", 3,
                "southern", "artillery", $id++, 7, 1);
        }

        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("|||", Europe::SOVIET_FORCE, "gameTurn3", "Armor.svg",
                4, 2, 12, STATUS_CAN_REINFORCE, "B", 3,
                "southern", "mech", $id++);
        }

        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn3", "MechInf.svg",
                1, 2, 12, STATUS_CAN_REINFORCE, "B", 3,
                "southern", "mech", $id++);
        }

        /* Turn 4 */
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "Artillery.svg",
                5, 1, 9, STATUS_CAN_REINFORCE, "B", 4,
                "southern", "artillery", $id++, 7, 1);
        }

        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn4", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "B", 4,
                "southern", "artillery", $id++, 8, 0);
        }

        /* Turn 6 */
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn6", "Artillery.svg",
                7, 1, 9, STATUS_CAN_REINFORCE, "B", 6,
                "southern", "artillery", $id++, 7, 2);
        }

        /* Turn 8 */
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn8", "Artillery.svg",
                3, 1, 9, STATUS_CAN_REINFORCE, "B", 8,
                "southern", "artillery", $id++, 11, 1);
        }
        for ($i = 0; $i < 1; $i++) {
            UnitFactory::create("||", Europe::SOVIET_FORCE, "gameTurn8", "Artillery.svg",
                4, 1, 9, STATUS_CAN_REINFORCE, "B", 8,
                "southern", "artillery", $id++, 8, 1);
        }

    }
    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\ModernBattles\Europe\CombatResultsTable(Europe::SOVIET_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;


        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\ModernBattles\\Europe\\VictoryCore");


            $this->moveRules->enterZoc = 'stop';
            $this->moveRules->stickyZoc = true;
            $this->moveRules->noZocZoc = false;
            $this->moveRules->stacking = 1;
            $this->moveRules->oneHex = false;
            $this->moveRules->noZocZocOneHex = true;

            $this->moveRules->friendlyAllowsRetreat = false;
            $this->moveRules->blockedRetreatDamages = false;
            $this->gameRules->legacyExchangeRule = false;
            $this->force->combatRequired = true;

            // game data
            if($scenario->name === 'main'){
                $this->gameRules->setMaxTurn(6);
                $this->natoFirstNoDeploy();
            }

            if($scenario->name === 'three'){
                $this->gameRules->setMaxTurn(12);
                $this->sovietFirstNatoDeployFirst();
            }
        }
        $this->moveRules->stacking = 1;
        $this->moveRules->stickyZoc = true;
        $this->moveRules->riversBlockRetreat = true;
        $this->moveRules->retreatCannotOverstack = false;
        foreach($this->mapViewer as $mapView){
            $mapView->trueRows = true;
            $mapView->mirror = false;
        }

    }
    public function startGameSouth(){
        $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, RED_MOVE_PHASE, MOVING_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);

        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, true);
    }

    public function natoFirstNoDeploy()
    {
        $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);

        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, true);
    }

    public function sovietFirstNatoDeployFirst()
    {
        $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, RED_MOVE_PHASE, MOVING_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);

        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Europe::BLUE_FORCE, Europe::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Europe::RED_FORCE, Europe::BLUE_FORCE, true);
    }
}