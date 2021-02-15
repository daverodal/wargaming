<?php
namespace Wargame\TMCW\KievCorps;
use Wargame\TMCW\KievCorps\UnitFactory;



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



class KievCorps extends \Wargame\ModernLandBattle
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
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 2, 0, 1, true);
        $this->terrain->addAltEntranceCost('swamp', 'mech', 3);
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("road", "road", "r", 1, 0, 0, true);
        $this->terrain->addNatAltEntranceCost('road','soviet','inf',1);


    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    function terrainInit($terrainDoc){
        /*
         * https://en.wikipedia.org/wiki/Order_of_battle_for_Operation_Barbarossa
         * http://www.cgsc.edu/CARL/nafziger/939GXXP.PDF
         * http://www.axishistory.com/axis-nations/148-germany-heer/heer-armeen/2655-1-panzerarmee
         */
        parent::terrainInit($terrainDoc);
        UnitFactory::$injector = $this->force;

        if(!empty($this->scenario->preDeploy)) {

            $list = $terrainDoc->terrain->reinforceZones;
            $cnt = 0;
            $unitsDeployed = 0;
            do {
                foreach ($list as $item) {
                    if ($item->name != 'A') {
                        continue;
                    }
                    $cnt++;
                    $isReduced = false;
                    if ($cnt%2 == 0) {
                        $isReduced = true;

                    }
                    if($item->hex == 408){
                        UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);
                    }else if($item->hex == 409){
                        UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);

                    }else if($item->hex == 510){
                        UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);

                    }else{
                        UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, $isReduced ? 1 : 2,3 , 3);

                    }
                    $unitsDeployed++;
                    if ($unitsDeployed >= 25) {
                        break;
                    }

                }
            } while ($unitsDeployed < 25);
            UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, 609, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);
            UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, 508, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);
            UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, 509, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);


            $A = $B = $C = $D = $E = $F = $G = [];
            $cnt = 0;
            foreach ($list as $item) {
                ${$item->name}[] = $item->hex;
            }
            $i = 0;
            /* Second panzer army */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++ ], "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "german",  "mech", "16", 2,2);

            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i ++], "MechInf.svg", 5, 8, STATUS_READY, "B", 1, "german", "mech", "10", 2, 2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++ ], "MechInf.svg", 5, 8, STATUS_READY, "B", 1, "german", "mech", "29", 2, 2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i ], "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "3", 2, 2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "18", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i ], "Armor.svg", 6, 8, STATUS_READY, "B", 1, "german", "mech", "4", 2, 2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i ++], "Armor.svg", 6, 8, STATUS_READY, "B", 1, "german", "mech", "17", 2, 2);
            $i = 0;

            /* Second Army */

            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "131", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "34", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "193", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "260", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "17", 2,2);
            $i = 0;

            /* Sixth Army */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 8,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "17",3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "29", 3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "44", 3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 8,  5,  STATUS_READY, "D",  1, "sixthArmy",  "inf", "55", 3);

            $i = 0;


            /* Seventeenth Army */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "24", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "297", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "9", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "60", 2,2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "94", 2,2);

            $i = 0;

            /* First panzer army */
             UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "9", 2, 2);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i ], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "16", 2, 2 );
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i++], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "14", 2, 2 );
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i ], "MechInf.svg", 5, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "25", 2, 2 );
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i ++], "MechInf.svg", 5, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "16", 2, 2 );

             UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i++], "Infantry.svg", 6, 5,  STATUS_UNAVAIL_THIS_PHASE, "B", 1,  "first-panzer-army",  "inf", "67", 3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i], "Infantry.svg", 6, 5,  STATUS_UNAVAIL_THIS_PHASE, "B", 1,  "first-panzer-army",  "inf", "11", 3);



        }
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


        for($i = 0; $i < 4;$i++){
            UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, "deadpile", "Infantry.svg", 3,  4,  STATUS_ELIMINATED, "A",  1, "soviet",  'inf', "renf$1", 1, 3);
        }

    }

    function __construct($data = null, $arg = false, $scenario = false)
    {

        parent::__construct($data, $arg, $scenario);

        $crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(KievCorps::GERMAN_FORCE);
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\KievCorps\\kievVictoryCore");
            if (!empty($scenario->supplyLen)) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->moveRules->enterZoc = 3;
            $this->moveRules->exitZoc = 2;
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->stacking = 1;

            $this->moveRules->friendlyAllowsRetreat = true;
            $this->moveRules->blockedRetreatDamages = true;
            $this->gameRules->legacyExchangeRule = false;

            // game data
            $this->gameRules->setMaxTurn(6);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);

            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, KievCorps::SOVIET_FORCE, KievCorps::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, KievCorps::SOVIET_FORCE, KievCorps::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, KievCorps::SOVIET_FORCE, KievCorps::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, true);
            $this->gameRules->flashMessages[] = "First Panzer Group may not move till turn 3";
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            if($unit->name == "xxx" || $unit->name == "xxxx"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    return true;
                }
            }

            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->name !== "xx"){
                        return true;
                }
            }
            return count((array)$mapHex->forces[$forceId]) >= 3;
        };


    }
}