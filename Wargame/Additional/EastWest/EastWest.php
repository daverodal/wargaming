<?php
namespace Wargame\Additional\EastWest;
use Wargame\EastWestLandBattle;
use Wargame\ModernLandBattle;
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



class EastWest extends EastWestLandBattle
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
    protected function reinforceSovietInf($turn){
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$turn", "Infantry.svg",
            2, 4, 2, STATUS_CAN_REINFORCE, "E", $turn, "soviet", "inf", "$turn R");
    }
    protected function  reinforceSovietTank($turn){
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$turn", "Armor.svg",
            2, 1, 5,STATUS_CAN_REINFORCE, "E", $turn, "soviet", "armor", "$turn a");
    }
    protected function reinforceSovietMechInf($turn){
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$turn", "MechInf.svg",
            1, 2, 5, STATUS_CAN_REINFORCE, "E", $turn,
            "soviet", "mech", "$turn m");
    }
    protected function reinforceSovietSupply($turn){
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$turn", "SupplyBox.svg",
            0, 1, 2,STATUS_CAN_REINFORCE, "E", $turn, "soviet", "supply", "$turn S");

    }
    protected function deploySovietInf($hex = false){
        static $unitDesig = 0;
        $status = STATUS_READY;
        if($hex === false){
            $hex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, $hex, "Infantry.svg",
            2, 4, 2, $status, "B", 1,
            "soviet", "inf", "$unitDesig i");
        $unitDesig++;
    }
    protected function deploySovietShock($hex = false){
        static $unitDesig = 0;
        $status = STATUS_READY;
        if($hex === false){
            $hex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, $hex, "Infantry.svg",
            5, 9, 3, $status, "B", 1,
            "soviet", "inf", "$unitDesig i");
        $unitDesig++;
    }
    protected function deploySovietTank($hex = false){
        static $unitDesig = 0;
        $status = STATUS_READY;
        if($hex === false){
            $hex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, $hex, "Armor.svg",
            2, 1, 5, $status, "B", 1,
            "soviet", "armor", "$unitDesig t");
        $unitDesig++;
    }
    protected function deploySovietArmor($hex = false){
        static $unitDesig = 0;
        $status = STATUS_READY;
        if($hex === false){
            $hex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, $hex, "Armor.svg",
            6, 5, 5, $status, "B", 1,
            "soviet", "armor", "$unitDesig a");
        $unitDesig++;
    }
    protected function  deploySovietMechInf($hex = false){
        static $unitDesig = 0;
        $status = STATUS_READY;
        if($hex === false){
            $hex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, $hex, "MechInf.svg",
            1, 2, 5, $status, "B", 1,
            "soviet", "mech", $unitDesig);
        $unitDesig++;
    }
    protected function onMapDeploy(){
        $scenario = $this->scenario;
        if($scenario->scenarioName === 'barbarossa') {

            UnitFactory::$injector = $this->force;


            for ($i = 0; $i < 2; $i++) {
                $this->deploySovietInf(2214+$i);
            }
            $this->deploySovietTank(2215);
            $this->deploySovietMechInf(2215);
            $this->deploySovietInf(717);
            $this->deploySovietTank(718);
            $this->deploySovietMechInf(716);
            $this->deploySovietInf(2121);
            $this->deploySovietTank(2121);
            $this->deploySovietInf(2122);
        }
    }

    public function init()
    {
        UnitFactory::$injector = $this->force;


        $scenario = $this->scenario;
        $numGerInf = 7;
        $numGerAir = 3;
        $numGerSupply = 4;
        $numFinInf = 2;
        $numAlliedInf = 0;
        $numSovInf = 11;
        $numSovTank = 8;
        $numSovMech = 0;
        $numSovSupply = 2;
        $numSovArmor = 0;
        $numSovShock = 0;
        if($scenario->scenarioName === 'stalingrad'){
            $numGerInf = 8;
            $numGerAir = 2;
            $numGerSupply = 3;
            $numAlliedInf = 2;
            $numFinInf = 3;
            $numSovInf = 37;
            $numSovTank = 6;
            $numSovMech = 1;
            $numSovSupply = 2;
        }
        if($scenario->scenarioName === 'zitadelle'){
            $numGerInf = 8;
            $numGerAir = 1;
            $numGerSupply = 3;
            $numAlliedInf = 2;
            $numFinInf = 4;
            $numSovInf = 43;
            $numSovTank = 1;
            $numSovMech = 0;
            $numSovSupply = 2;
            $numSovArmor = 5;
            $numSovShock = 1;
        }
        $i = 0;
        for($i = 0; $i < 4; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Armor.svg",
                11, 8, 8,STATUS_CAN_DEPLOY, "A", 1,
                "german", "armor", "");

        }
        for($i = 0; $i < $numGerInf; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg",
                5, 7, 3,STATUS_CAN_DEPLOY, "A", 1,
                "german", "inf", "");

        }
        for($i = 0; $i < $numGerAir; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "AirPower.svg",
                2, 1, 2,STATUS_CAN_DEPLOY, "A", 1, "german", "art", "", 4);

        }

        for($i = 0; $i < $numFinInf; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg",
                2, 4, 2,STATUS_CAN_DEPLOY, "F", 1,
                "german", "inf", "F");

        }
        for($i = 0; $i < $numAlliedInf; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg",
                2, 4, 2,STATUS_CAN_DEPLOY, "A", 1,
                "german", "inf", "R");

        }
        for($i = 0; $i < $numGerSupply; $i++){
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "SupplyBox.svg",
                0, 2, 2,STATUS_CAN_DEPLOY, "A", 1,
                "german", "supply", "S");

        }

        for($i = 0; $i < $numSovInf; $i++){
            $this->deploySovietInf();
        }
        for($i = 0; $i < $numSovTank; $i++){
            $this->deploySovietTank();
        }
        for ($i = 0; $i < $numSovMech; $i++) {
            $this->deploySovietMechInf();
        }
        for($i = 0; $i < $numSovShock; $i++){
            $this->deploySovietShock();
        }
        for($i = 0; $i < $numSovArmor; $i++){
            $this->deploySovietArmor();
        }
        for($i = 0; $i < $numSovSupply; $i++){
            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "deployBox", "SupplyBox.svg",
                0, 1, 2,STATUS_CAN_DEPLOY, "B", 1, "soviet", "supply", "$i S");

        }


        $this->onMapDeploy();

        if($scenario->scenarioName === 'barbarossa') {

            for($i = 0; $i < 2; $i++){
                UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "deployBox", "Infantry.svg",
                    2, 4, 2,STATUS_CAN_DEPLOY, "C", 1, "german", "inf", "R");

            }


        /* turn 2 */


            for($i = 2; $i <= 8; $i += 2){
                UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn$i", "SupplyBox.svg",
                    0, 1, 2,STATUS_CAN_REINFORCE, "E", $i, "soviet", "supply", "$i S");

            }

            for($i = 2; $i <= 8; $i++){
                UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn$i", "SupplyBox.svg",
                    0, 1, 2,STATUS_CAN_REINFORCE, "G", $i, "german", "supply", "$i S");

            }

            for($i = 0; $i < 1; $i++){
                UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn2", "Infantry.svg",
                    5, 7, 3,STATUS_CAN_REINFORCE, "G", 2, "german", "inf", "");

            }

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
            2, 1, 5,STATUS_CAN_REINFORCE, "E", 3, "soviet", "armor", "$i a");
        
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
                2, 1, 5, STATUS_CAN_REINFORCE, "E", 6, "soviet", "armor", "$i a");
        }
        /* turn 7 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn7", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 7, "soviet", "inf", "$i i");
        }
        UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn7", "Armor.svg",
            2, 1, 5,STATUS_CAN_REINFORCE, "E", 7, "soviet", "armor", "$i a");

        /* turn 8 */
        for($i = 0; $i < 2; $i++) {

            UnitFactory::create("xxxx", EastWest::SOVIET_FORCE, "gameTurn8", "Infantry.svg",
                2, 4, 2, STATUS_CAN_REINFORCE, "D", 8, "soviet", "inf", "$i i");
        }
     }
        if($scenario->scenarioName === 'stalingrad') {
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn2", "Infantry.svg",
                2, 4, 2,STATUS_CAN_REINFORCE, "G", 2,
                "german", "inf", "I");
            for($i = 1; $i <= 8; $i++){
                UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn$i", "SupplyBox.svg",
                    0, 1, 2,STATUS_CAN_REINFORCE, "G", $i, "german", "supply", "$i S");
            }
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietTank(1);
            $this->reinforceSovietSupply(1);

            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietTank(2);
            $this->reinforceSovietSupply(2);


            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietTank(3);
            $this->reinforceSovietTank(3);
            $this->reinforceSovietSupply(3);

            $this->reinforceSovietInf(4);
            $this->reinforceSovietInf(4);
            $this->reinforceSovietInf(4);
            $this->reinforceSovietTank(4);
            $this->reinforceSovietTank(4);
            $this->reinforceSovietMechInf(4);
            $this->reinforceSovietSupply(4);

            $this->reinforceSovietInf(5);
            $this->reinforceSovietInf(5);
            $this->reinforceSovietInf(5);
            $this->reinforceSovietInf(5);
            $this->reinforceSovietTank(5);
            $this->reinforceSovietTank(5);
            $this->reinforceSovietMechInf(5);
            $this->reinforceSovietSupply(5);

            $this->reinforceSovietInf(6);
            $this->reinforceSovietInf(6);
            $this->reinforceSovietInf(6);
            $this->reinforceSovietInf(6);
            $this->reinforceSovietTank(6);
            $this->reinforceSovietTank(6);
            $this->reinforceSovietMechInf(6);
            $this->reinforceSovietSupply(6);

            $this->reinforceSovietInf(7);
            $this->reinforceSovietInf(7);
            $this->reinforceSovietInf(7);
            $this->reinforceSovietInf(7);
            $this->reinforceSovietTank(7);
            $this->reinforceSovietTank(7);
            $this->reinforceSovietMechInf(7);
            $this->reinforceSovietSupply(7);

            $this->reinforceSovietInf(8);
            $this->reinforceSovietInf(8);
            $this->reinforceSovietInf(8);
            $this->reinforceSovietInf(8);
            $this->reinforceSovietInf(8);
            $this->reinforceSovietTank(8);
            $this->reinforceSovietTank(8);
            $this->reinforceSovietMechInf(8);
            $this->reinforceSovietSupply(8);
        }
        if($scenario->scenarioName === 'zitadelle') {
            UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn3", "Infantry.svg",
                2, 4, 2,STATUS_CAN_REINFORCE, "G", 3,
                "german", "inf", "I");
            for($i = 1; $i <= 9; $i++){
                UnitFactory::create("xxxx", EastWest::GERMAN_FORCE, "gameTurn$i", "SupplyBox.svg",
                    0, 1, 2,STATUS_CAN_REINFORCE, "G", $i, "german", "supply", "$i S");
            }
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietInf(1);
            $this->reinforceSovietTank(1);
            $this->reinforceSovietTank(1);
            $this->reinforceSovietSupply(1);
            $this->reinforceSovietMechInf(1);


            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietInf(2);
            $this->reinforceSovietTank(2);
            $this->reinforceSovietTank(2);
            $this->reinforceSovietSupply(2);
            $this->reinforceSovietMechInf(2);


            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietInf(3);
            $this->reinforceSovietTank(3);
            $this->reinforceSovietTank(3);
            $this->reinforceSovietMechInf(3);
            $this->reinforceSovietSupply(3);

            $this->reinforceSovietInf(4);
            $this->reinforceSovietInf(4);
            $this->reinforceSovietInf(4);
            $this->reinforceSovietInf(4);
            $this->reinforceSovietTank(4);
            $this->reinforceSovietTank(4);
            $this->reinforceSovietMechInf(4);
            $this->reinforceSovietSupply(4);

            $this->reinforceSovietInf(5);
            $this->reinforceSovietInf(5);
            $this->reinforceSovietInf(5);
            $this->reinforceSovietTank(5);
            $this->reinforceSovietTank(5);
            $this->reinforceSovietMechInf(5);
            $this->reinforceSovietSupply(5);

            $this->reinforceSovietInf(6);
            $this->reinforceSovietInf(6);
            $this->reinforceSovietTank(6);
            $this->reinforceSovietTank(6);
            $this->reinforceSovietTank(6);
            $this->reinforceSovietMechInf(6);
            $this->reinforceSovietMechInf(6);
            $this->reinforceSovietSupply(6);

            $this->reinforceSovietInf(7);
            $this->reinforceSovietInf(7);
            $this->reinforceSovietTank(7);
            $this->reinforceSovietTank(7);
            $this->reinforceSovietTank(7);
            $this->reinforceSovietMechInf(7);
            $this->reinforceSovietMechInf(7);
            $this->reinforceSovietSupply(7);

            $this->reinforceSovietInf(8);
            $this->reinforceSovietTank(8);
            $this->reinforceSovietTank(8);
            $this->reinforceSovietTank(8);
            $this->reinforceSovietMechInf(8);
            $this->reinforceSovietMechInf(8);
            $this->reinforceSovietSupply(8);

            $this->reinforceSovietInf(9);
            $this->reinforceSovietTank(9);
            $this->reinforceSovietTank(9);
            $this->reinforceSovietTank(9);
            $this->reinforceSovietMechInf(9);
            $this->reinforceSovietMechInf(9);
            $this->reinforceSovietMechInf(9);
            $this->reinforceSovietSupply(9);
        }
        }

    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\Additional\EastWest\CombatResultsTable(EastWest::GERMAN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;

            $this->combatRules = new SupplyCombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules->inject($this->moveRules, $this->combatRules, $this->force);

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Additional\\EastWest\\VictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }

            if($scenario->scenarioName === 'barbarossa'){
                $this->victory->setVictoryPoints([0, 26, 77, 0, 0]);
            }
            if($scenario->scenarioName === 'stalingrad'){
                $this->victory->setVictoryPoints([0, 53, 50, 0, 0]);
            }
            foreach($this->mapViewer as $mapView){
                $mapView->trueRows = false;
                $mapView->mirror = false;
            }
//            $this->mapViewer[2]->mirror = true;
            $this->mapViewer[1]->mirror = true;
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

        $sovietStacking = 2;
        if($scenario->scenarioName == "zitadelle"){
            $this->gameRules->setMaxTurn(9);
            $sovietStacking = 3;
        }
        $this->moveRules->stacking = function($mapHex, $forceId, $unit) use ($sovietStacking) {
            if($unit->class === 'art' || $unit->class === 'supply'){
                return false;
            }
            $onlySupport = true;
            $onlyInf = true;
            $onlyArmor = true;
            $numNonSupport = 0;
            $numArmor = 0;
            $numInf = 0;
            $numMech = 0;
            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if(!($this->force->units[$mKey]->class === "supply" || $this->force->units[$mKey]->class === "art")){
                    if($this->force->units[$mKey]->class === "inf"){
                        $onlyArmor = false;
                        $numInf++;
                    }
                    if($this->force->units[$mKey]->class === "armor"){
                        $onlyInf = false;
                        $numArmor++;
                    }
                    if($this->force->units[$mKey]->class === "mech"){
                        $numMech++;
                    }
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
                if($numNonSupport >= $sovietStacking){
                    if(!$this->victory->canSovietCombine()){
                        return true;
                    }
                    if($onlyInf){
                            if($unit->class === 'inf' && $numInf <= 2 && $numMech <= 1){
                                return false;
                            }
                            if($unit->class === 'mech' && $numInf <= 3 && $numMech === 0){
                                return false;
                            }
                            return true;
                    }
                    if($onlyArmor){
                        if($unit->class === 'armor' && $numArmor <= 2 && $numMech <= 1){
                            return false;
                        }
                        if($unit->class === 'mech' && $numArmor <= 3 && $numMech === 0){
                            return false;
                        }
                        return true;
                    }
                    return true;
                }
                return false;
            }
        };


    }
}