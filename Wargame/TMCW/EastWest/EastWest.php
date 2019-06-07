<?php
namespace Wargame\TMCW\EastWest;
use \Wargame\TMCW\EastWest\UnitFactory;
use Wargame\SupplyCombatRules;

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



class EastWest extends \Wargame\ModernLandBattle
{
    const GERMAN_FORCE = 1;
    const SOVIET_FORCE = 2;

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    static function getPlayerData($scenario){
        $forceName = ["Neutral Observer", "German", "Soviet"];
        return \Wargame\Battle::register($forceName,
            [$forceName[0], $forceName[2], $forceName[1]]);
    }

    function terrainGen($mapDoc, $terrainDoc)
    {

        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addAltEntranceCost('roughone', 'mech', 3);
        $this->terrain->addAltEntranceCost('roughone', 'art', 3);
        $this->terrain->addAltEntranceCost('roughone', 'supply', 3);
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
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Armor.svg", 11, 8, 8,STATUS_CAN_DEPLOY, "A", 1, "german", "mech", "");

        }
        for($i = 0; $i < 7; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg", 5, 7, 3,STATUS_CAN_DEPLOY, "A", 1, "german", "inf", "");

        }
        for($i = 0; $i < 3; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "AirPower.svg", 2, 1, 2,STATUS_CAN_DEPLOY, "A", 1, "german", "art", "", 4);

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg", 2, 4, 2,STATUS_CAN_DEPLOY, "A", 1, "german", "inf", "R");

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg", 2, 4, 2,STATUS_CAN_DEPLOY, "F", 1, "german", "inf", "F");

        }
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "SupplyBox.svg", 0, 2, 2,STATUS_CAN_DEPLOY, "A", 1, "german", "supply", "S");

        }

        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn2", "Infantry.svg", 5, 7, 3,STATUS_CAN_REINFORCE, "G", 2, "german", "inf", "");

        }

        for($i = 2; $i <= 8; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn$i", "SupplyBox.svg",
                0, 1, 2,STATUS_CAN_REINFORCE, "G", $i, "german", "supply", "$i S");

        }

        for($i = 0; $i < 11; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "deployBox", "Infantry.svg",
                2, 4, 2,STATUS_CAN_DEPLOY, "B", 1, "soviet", "inf", "$i i");

        }
        for($i = 0; $i < 8; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "deployBox", "Armor.svg",
                2, 1, 5,STATUS_CAN_DEPLOY, "C", 1, "soviet", "mech", "$i a");

        }

        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "deployBox", "SupplyBox.svg",
                0, 1, 2,STATUS_CAN_DEPLOY, "C", 1, "soviet", "supply", "$i S");

        }


        for($i = 2; $i <= 8; $i += 2){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$i", "SupplyBox.svg",
                0, 1, 2,STATUS_CAN_REINFORCE, "E", $i, "soviet", "supply", "$i S");

        }
        for($i = 0; $i < 2; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2214 + $i, "Infantry.svg",
                2, 4, 2,STATUS_CAN_DEPLOY, "C", 1, "soviet", "inf", "$i i");

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2215, "Armor.svg",
                2, 1, 5,STATUS_READY, "C", 1, "soviet", "mech", "$i a");

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2215, "MechInf.svg",
                1, 2, 5,STATUS_READY, "C", 1, "soviet", "mech", "$i m");

        }


        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 717, "Infantry.svg",
                2, 4, 2,STATUS_READY, "C", 1, "soviet", "inf", $i);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 718, "Armor.svg",
                2, 1, 5,STATUS_READY, "C", 1, "soviet", "mech", $i);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 716, "MechInf.svg",
                1, 2, 5,STATUS_READY, "C", 1, "soviet", "mech", $i);

        }


        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2121, "Infantry.svg",
                2, 4, 2,STATUS_READY, "C", 1, "soviet", "inf", $i);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2121, "Armor.svg",
                2, 1, 5,STATUS_READY, "C", 1, "soviet", "mech", $i);

        }
        for($i = 0; $i < 1; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, 2122, "MechInf.svg",
                1, 2, 5,STATUS_READY, "C", 1, "soviet", "mech", $i);

        }


        /* turn 2 */
        for($i = 0; $i < 6; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn2", "Infantry.svg",
                2, 4, 2,STATUS_CAN_REINFORCE, "D", 2, "soviet", "inf", "$i i");

        }
        /* turn 3 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn3", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 3, "soviet", "inf", "$i i");
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn3", "Infantry.svg",
            2, 4, 2, STATUS_CAN_REINFORCE, "E", 3, "soviet", "inf", "$i i");
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn3", "Armor.svg",
            2, 1, 5,STATUS_CAN_REINFORCE, "E", 3, "soviet", "mech", "$i a");
        
        /* turn 4 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn4", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 4, "soviet", "inf", "$i i");
        }

        /* turn 5 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn5", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 5, "soviet", "inf", "$i i");
        }

        /* turn 6 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn6", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 6, "soviet", "inf", "$i i");
        }
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn6", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "E", 6, "soviet", "inf", "$i i");
        }
        for($i = 0; $i < 4; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn6", "Armor.svg",
                2, 1, 5, STATUS_CAN_REINFORCE, "E", 6, "soviet", "mech", "$i a");
        }
        /* turn 7 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn7", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 7, "soviet", "inf", "$i i");
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn7", "Armor.svg",
            2, 1, 5,STATUS_CAN_REINFORCE, "E", 7, "soviet", "mech", "$i a");

        /* turn 8 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn8", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 8, "soviet", "inf", "$i i");
        }
    }

    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\TMCW\EastWest\CombatResultsTable(EastWest::GERMAN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;

            $this->combatRules = new SupplyCombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules->inject($this->moveRules, $this->combatRules, $this->force);

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\EastWest\\VictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }

            foreach($this->mapViewer as $mapView){
                $mapView->trueRows = true;
            }
            $this->combatRules = new SupplyCombatRules($this->force, $this->terrain);
            $this->gameRules->inject($this->moveRules, $this->combatRules, $this->force);
            $this->moveRules->enterZoc = 2;
            $this->moveRules->exitZoc = 1;
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->stacking = 1;

            $this->moveRules->friendlyAllowsRetreat = true;
            $this->moveRules->blockedRetreatDamages = true;
            $this->gameRules->legacyExchangeRule = false;

            // game data
            $this->gameRules->setMaxTurn(8);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, EastWest::SOVIET_FORCE, EastWest::GERMAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, EastWest::SOVIET_FORCE, EastWest::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, EastWest::SOVIET_FORCE, EastWest::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, EastWest::SOVIET_FORCE, EastWest::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, EastWest::GERMAN_FORCE, EastWest::SOVIET_FORCE, true);
        }
        $this->combatRules->injectCrt($crt);

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            if($unit->class === 'art' || $unit->class === 'supply'){
                return false;
            }
            $onlySupport = true;
            $numNonSupport = 0;
            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if(!($this->force->units[$mKey]->class === "supply" || $this->force->units[$mKey]->class === "art")){
                    $onlySupport = false;
                    $numNonSupport++;
                }else{
                }
            }
            if($onlySupport === true){
                return false;
            }
            if($unit->forceId === EastWest::GERMAN_FORCE){

                if($numNonSupport >= 1){
                    return true;
                }
                return false;
            }else{
                if($numNonSupport >= 2){
                    return true;
                }
            }
        };


    }
}