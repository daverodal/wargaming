<?php
namespace Wargame\ModernBattles\Bulge;
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



class Bulge extends ModernLandBattle
{
    const GERMAN_FORCE = 2;
    const US_FORCE = 1;
    const RED_FORCE = 2;
    const BLUE_FORCE = 1;

    public $specialHexesMap = ['SpecialHexA'=>0, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "US", "German"];
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
        $this->terrain->addAltEntranceCost("forest", 'mech', 'blocked');
        $this->terrain->addAltEntranceCost("roughtwo", 'mech', 'blocked');
        $this->terrain->addAltEntranceCost("roughone", 'mech', 'blocked');
        $this->terrain->addAltTraverseCost("river", 'mech', 'blocked');

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
//        for ($i = 0; $i < 6; $i++) {
//            UnitFactory::create("||", Bulge::GERMAN_FORCE, "airpowerWrapper", "jetPlane.svg",
//                2, 3, 12, STATUS_CAN_REINFORCE, "A", 1,
//                "german", "airpower", $id++);
//
//        }
//        for ($i = 0; $i < 6; $i++) {
//            UnitFactory::create("||", Bulge::US_FORCE, "airpowerWrapper", "jetPlane.svg",
//                2, 3, 12, STATUS_CAN_REINFORCE, "A", 1,
//                "us", "airpower", $id++);
//
//        }
        if($scenario->name === 'one') {

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "MechInf.svg",
                    4, 4, 12, STATUS_CAN_DEPLOY, "H", 1,
                    "us", "mech", "20");
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "Armor.svg",
                    4, 3, 12, STATUS_CAN_DEPLOY, "H", 1,
                    "us", "mech", "2");
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "Armor.svg",
                    5, 4, 12, STATUS_CAN_DEPLOY, "H", 1,
                    "us", "mech", "811");
            }
            $unitDesig = ["73", "58"];
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "SpArtillery.svg",
                    1, 2, 12, STATUS_CAN_DEPLOY, "H", 1,
                    "us", "mech", $id++, 12, 2);
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "MechInf.svg",
                    4, 4, 12, STATUS_CAN_DEPLOY, "E", 1,
                    "us", "mech", "20");
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "Armor.svg",
                    4, 3, 12, STATUS_CAN_DEPLOY, "E", 1,
                    "us", "mech", "3");
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "ArmorRecon.svg",
                    1, 3, 12, STATUS_CAN_DEPLOY, "E", 1,
                    "us", "mech", "90");
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "deployBox", "SpArtillery.svg",
                    1, 2, 12, STATUS_CAN_DEPLOY, "E", 1,
                    "us", "mech", "420", 12, 2);
            }
            $unitDesig = ['1/3','2/3'];
            $unitName = ['2', '2'];
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create($unitName[$i], Bulge::GERMAN_FORCE, "deployBox", "Armor.svg",
                    6, 4, 12, STATUS_CAN_REINFORCE, "A", 1,
                    "german", "mech", $unitDesig[$i]);

            }
            $unitDesig = ['1/304','2/304','1/902','2/902'];
            $unitName = ['', '', 'Lehr', 'Lehr'];
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create($unitName[$i], Bulge::GERMAN_FORCE, "deployBox", "MechInf.svg",
                    5, 5, 12, STATUS_CAN_REINFORCE, "A", 1,
                    "german", "mech", $unitDesig[$i]);

            }
            UnitFactory::create("", Bulge::GERMAN_FORCE, "deployBox", "ArmorRecon.svg",
                2, 3, 14, STATUS_CAN_REINFORCE, "A", 1,
                "german", "mech", '2');

            /* turn 2 */
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn2", "MechInf.svg",
                    5, 5, 12, STATUS_CAN_REINFORCE, "B", 2,
                    "german", "mech", "1/2");
            }
            $unitDesig = ['1/903','2/903'];

            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("Lehr", Bulge::GERMAN_FORCE, "gameTurn2", "MechInf.svg",
                    4, 4, 12, STATUS_CAN_REINFORCE, "B", 2,
                    "german", "mech", $unitDesig[$i]);
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn2", "MechInf.svg",
                    4, 4, 7, STATUS_CAN_REINFORCE, "B", 2,
                    "german", "mech", "2/2");
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("Lehr", Bulge::GERMAN_FORCE, "gameTurn2", "ArmorRecon.svg",
                    2, 3, 14, STATUS_CAN_REINFORCE, "B", 2,
                    "german", "mech", "Lehr");
            }

            $unitDesig = ['1/77','2/77','3/77', '1/78', '2/78', '3/78'];

            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn2", "Infantry.svg",
                    3, 3, 7, STATUS_CAN_REINFORCE, "B", 2,
                    "german", "infantry", $unitDesig[$i]);
            }
            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn2", "SpArtillery.svg",
                4, 2, 12, STATUS_CAN_REINFORCE, "A", 2,
                "german", "mech", "2", 12, 2);


            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn2", "SpArtillery.svg",
                3, 1, 12, STATUS_CAN_REINFORCE, "A", 2,
                "german", "mech", "2", 18, 2);

            $unitDesig = ['1/501','2/501','3/501', '1/502', '2/502', '3/502', '1/506', '2/506', '3/506', '1/327', '2/327', '3/327'];

            for ($i = 0; $i < 12; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "gameTurn2", "Para.svg",
                    2, 4, 7, STATUS_CAN_REINFORCE, "F", 2,
                    "us", "infantry", $unitDesig[$i]);
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "gameTurn2", "Artillery.svg",
                    1, 2, 7, STATUS_CAN_REINFORCE, "F", 2,
                    "us", "artillery", "101", 12, 2);
            }

            /* game turn 3 */
            $unitDesig = ['755', '969'];
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "gameTurn3", "Artillery.svg",
                    3, 1, 7, STATUS_CAN_REINFORCE, "G", 3,
                    "us", "artillery", $unitDesig[$i], 18, 2);
            }

            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::US_FORCE, "gameTurn3", "Armor.svg",
                    5, 4, 12, STATUS_CAN_REINFORCE, "G", 3,
                    "us", "mech", "705");
            }

            /* game Turn 4 */
            $unitDesig = ['1/901','2/901'];
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("Lehr", Bulge::GERMAN_FORCE, "gameTurn4", "MechInf.svg",
                    5, 5, 12, STATUS_CAN_REINFORCE, "C", 4,
                    "german", "mech", $unitDesig[$i]);
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn4", "Infantry.svg",
                    3, 3, 7, STATUS_CAN_REINFORCE, "C", 4,
                    "german", "infantry", $id++);
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn4", "ArmorRecon.svg",
                    1, 3, 12, STATUS_CAN_REINFORCE, "C", 4,
                    "german", "mech", $id++);
            }
            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn4", "Artillery.svg",
                3, 1, 7, STATUS_CAN_REINFORCE, "C", 4,
                "german", "artillery", "26", 18, 2);
            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn4", "Artillery.svg",
                4, 2, 7, STATUS_CAN_REINFORCE, "C", 4,
                "german", "artillery", "26", 12, 2);
            UnitFactory::create("Lehr", Bulge::GERMAN_FORCE, "gameTurn4", "SpArtillery.svg",
                3, 1, 12, STATUS_CAN_REINFORCE, "C", 4,
                "german", "mech", "Lehr", 18, 2);
            UnitFactory::create("Lehr", Bulge::GERMAN_FORCE, "gameTurn4", "SpArtillery.svg",
                4, 2, 12, STATUS_CAN_REINFORCE, "C", 4,
                "german", "mech", "Lehr", 12, 2);

            /* turn 7 */
            $unitDesig = ['1/13','2/13','1/14', '2/14'];

            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn7", "Infantry.svg",
                    3, 3, 7, STATUS_CAN_REINFORCE, "D", 7,
                    "german", "infantry", $unitDesig[$i]);
            }
            $unitDesig = ['1/14','2/15'];
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn7", "MechInf.svg",
                    3, 3, 12, STATUS_CAN_REINFORCE, "D", 7,
                    "german", "mech", $unitDesig[$i]);
            }
            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn7", "Artillery.svg",
                3, 1, 7, STATUS_CAN_REINFORCE, "D", 7,
                "german", "artillery", "5", 18, 2);
            UnitFactory::create("", Bulge::GERMAN_FORCE, "gameTurn7", "Artillery.svg",
                4, 2, 7, STATUS_CAN_REINFORCE, "D", 7,
                "german", "artillery", "5", 12, 2);
        }
        if($scenario->name === 'three') {
           $this->three();
        }
        if($scenario->name === 'four') {
            $this->four();
        }

    }
    private function four() {
        $id = 0;
        $usInf = [418, 720, 1021,1323,1623,1925,2224,2423,2622];// 12 total
        $usTank = [523,823,1123,1124, 1826, 1927, 2027, 2327, 2426]; // 10 total
        $usScout = [217, 2720, 2817, 2915]; // 5 total
        $usFpfArt = [421, 822, 1125, 1827, 2726, 2724]; // 6 total
        $usLongRangeArt = [2029, 2228]; // 2 total
        $usArtillery = [2329, 1828, 2028]; // 6 total

        $sovInf = [215,416,517, 818, 919, 1120, 2420, 2421, 2418, 2616, 2715, 2913];
        $sovArmInf = [1621, 1722, 1923, 2123, 2222, 1321];
        $sovFpfArt = [916, 2317];
        $sovArtillery = [1417, 1920];


    }
    private function three(){
        $id = 0;
    }
    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\ModernBattles\Bulge\CombatResultsTable(Bulge::GERMAN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;


        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\ModernBattles\\Bulge\\VictoryCore");


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
            if($scenario->name === 'one'){
                $this->gameRules->setMaxTurn(7);
                $this->sovietFirstNatoDeployFirst();
            }

            if($scenario->name === 'four'){
                $this->gameRules->setMaxTurn(10);
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

        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, RED_MOVE_PHASE, MOVING_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);

        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, true);
    }

    public function natoFirstNoDeploy()
    {
        $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);

        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, true);
    }

    public function sovietFirstNatoDeployFirst()
    {
        $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
        $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
        $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
        $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

        $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_MOVE_PHASE, MOVING_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);

        $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, false);
        $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, Bulge::BLUE_FORCE, Bulge::RED_FORCE, false);
        $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, Bulge::RED_FORCE, Bulge::BLUE_FORCE, true);
    }
}