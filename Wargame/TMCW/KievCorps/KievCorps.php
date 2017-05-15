<?php
namespace Wargame\TMCW\KievCorps;
use \Wargame\TMCW\KievCorps\UnitFactory;
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
                    if ($cnt & 1) {
                        $isReduced = $unitsDeployed & 1;
                        UnitFactory::create("xxxx", KievCorps::SOVIET_FORCE, $item->hexagon->number, "multiInf.png", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3 , 3);
                        $unitsDeployed++;
                    }
                    if ($unitsDeployed >= 20) {
                        break;
                    }

                }
            } while ($unitsDeployed < 20);
            UnitFactory::create("xxxx", KievCorps::SOVIET_FORCE, 808, "multiInf.png", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3);
            UnitFactory::create("xxxx", KievCorps::SOVIET_FORCE, 909, "multiInf.png", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3);
            UnitFactory::create("xxxx", KievCorps::SOVIET_FORCE, 910, "multiInf.png", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3);


            $A = $B = $C = $D = $E = $F = $G = [];
            $cnt = 0;
            foreach ($list as $item) {
                ${$item->name}[] = $item->hexagon->number;
            }
            $i = 0;
            /* Second panzer army */
            /* 21 corp */
            $i += 2;
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiArmor.png", 3, 8,  STATUS_READY, "B", 1, "german",  "mech", "24/3", 2, 2, "pink");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiArmor.png", 3, 8,  STATUS_READY, "B", 1, "german",  "mech", "24/4", 2,2, "pink");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiMech.png", 3, 8,  STATUS_READY, "B", 1, "german",  "mech", "24/10", 2,2, "pink");
            //            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 9, 8,  STATUS_READY, "B", 1, "german",  "mech", "21", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiArmor.png", 6, 3, 8, false, STATUS_READY, "B", 1, 1, "german", true, "mech", "4");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiMech.png", 5, 2, 8, false, STATUS_READY, "B", 1, 1, "german", true, "mech", "10");

            /* 47 Corps */
//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 8, 8,  STATUS_READY, "B", 1, "german",  "mech", "47", 3);

            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "german", "mech", "47/17", 2, 2, "orange");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "german", "mech", "47/18", 2, 2, "orange");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "german", "mech", "47/29", 2, 2, "orange");

            /* 48 Corps */
//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 7, 8,  STATUS_READY, "B", 1, "german",  "mech", "48", 3);
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "german", "mech", "48/9", 2, 2, "red");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "german", "mech", "48/16", 2, 2, "red");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "german", "mech", "48/25", 2, 2, "red");

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiArmor.png", 5, 2, 8, false, STATUS_READY, "B", 1, 1, "german", true, "mech", "48");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiArmor.png", 5, 2, 8, false, STATUS_READY, "B", 1, 1, "german", true, "mech", "16");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiMech.png", 4, 2, 8, false, STATUS_READY, "B", 1, 1, "german", true, "mech", "25");

            /* 35 Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 1], "multiInf.png", 6, 5,  STATUS_READY, "B", 1,  "german",  "inf", "35", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i += 1], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "95");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "262");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "293");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "296");

            /* 34 Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 1], "multiInf.png", 3, 5,  STATUS_READY, "B", 1,  "german",  "inf", "34", 3);

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $B[$i += 2], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "34");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $B[$i++], "multiInf.png", 2, 1, 5, false, STATUS_READY, "B", 1, 1, "german", true, "inf", "134");

            $i = 0;
            /* Second Army */
            /* 13 Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "13", 3);

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "13");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "260");

            /* 53 Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "53", 3);

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 3, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "53");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "56");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "167");

            /* 42 Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "42", 3);

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $C[$i += 2], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "42");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "131");

            /* army reserve */
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $C[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "C", 1, 1, "secondArmy", true, "inf", "112");


            $i = 0;

            /*  Sixth Army */
            /* 17'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 2], "multiInf.png", 3,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "17",3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "62");

            /* 29'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 2], "multiInf.png", 5,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "29", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "111");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "299");

            /* 44'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 2], "multiInf.png", 3,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "44", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "297");

            /* 55'th Corps  */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 2], "multiInf.png", 6,  5,  STATUS_READY, "D",  1, "sixthArmy",  "inf", "55", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "57");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "168");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $D[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "D", 1, 1, "sixthArmy", true, "inf", "298");


            $i = 0;

            /* First panzer army */
            /* 3 Corps */
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 1], "multiArmor.png", 9,  8,  STATUS_READY, "E",  1, "firstPanzerArmy",  "mech", "3", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 6, 3, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "14");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiMech.png", 5, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "25");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "3/13", 2, 2, "#58c3ff");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "3/14", 2, 2, "#58c3ff");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiMech.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "3/25", 2, 2, "#58c3ff");

            /* 14 Corps */
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 1], "multiMech.png", 8,  8,  STATUS_READY, "E",  1, "firstPanzerArmy",  "mech", "14", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiMech.png", 5, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS AH");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiMech.png", 4, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS W");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "14/90", 2, 2, "white");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "14/SS AH", 2, 2, "white");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "14/SS W", 2, 2, "white");

            /* 48 Corps ? */
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 5, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "48");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 5, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiMech.png", 5, 2, 8, false, STATUS_READY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "48/11", 2, 2, "lawngreen");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiArmor.png", 3, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "48/16", 2, 2, "lawngreen");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $E[$i], "multiMech.png", 2, 8, STATUS_READY, "B", 1, "firstPanzerArmy", "mech", "48/16", 2, 2, "lawngreen");

            /* 4 Corps ? */

//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $E[$i += 1], "multiInf.png", 4,  5,  STATUS_READY, "F", 1,  "firstPanzerArmy",  "inf", "4", 3);
//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $E[$i += 1], "multiInf.png", 4,  5,  STATUS_READY, "F", 1,  "firstPanzerArmy",  "inf", "49", 3);
//            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $E[$i += 2], "multiInf.png", 3,  5,  STATUS_READY, "F", 1,  "firstPanzerArmy",  "inf", "Rum", 3);



            $i = 0;

            /* Seventeenth Army */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 3,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "Hun", 3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 3,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "Slo", 3);
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 3,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "Rum", 3);

            /* 4'th Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 3,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "4", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "71");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "262");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 2], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "295");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "296");

            /* 49'th Mountain Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 4,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "49 M", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "257");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiMountain.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "mountain", "1 M");

            /* 59'th Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 5,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "59", 3);
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "97 L");
//            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, $F[$i += 3], "multiInf.png", 2, 1, 5, false, STATUS_READY, "F", 1, 1, "seventeenthArmy", true, "inf", "100 L");

            /* 11'th Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 4,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "11", 3);

            /* 55'th Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 5,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "55", 3);

            /* additional Corps */
            UnitFactory::create("xxx", KievCorps::GERMAN_FORCE, $F[$i += 1], "multiInf.png", 4,  5,  STATUS_READY, "F",  1, "seventeenthArmy",  "inf", "add", 3);

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
            UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, "deadpile", "multiInf.png", 3,  4,  STATUS_ELIMINATED, "A",  1, "soviet",  'inf', "renf$1", 1, 3);
        }


        return;
        if(empty($scenario->preDeploy)) {


            for ($i = 0; $i < 60; $i++) {
                UnitFactory::create("xxx", KievCorps::SOVIET_FORCE, "deployBox", "multiInf.png", 2, 1, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "soviet", true, 'inf');
            }


            /* Second panzer army */
            /* 21 corp */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "3");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "4");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "10");

            /* 47 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "17");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "18");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "29");

            /* 48 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "9");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "16");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "25");

            /* 35 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiCav.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "1 Cav");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "95");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "262");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "293");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "296");

            /* 34 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "45");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "134");

            /* Second Army */
            /* 13 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "17");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "260");

            /* 53 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "31");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "56");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "167");

            /* 42 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "52");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "131");

            /* army reserve */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "112");

            /* First panzer army */
            /* 3 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "13");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "14");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "25");

            /* 14 Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "9");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS AH");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS W");

            /* 48 Corps ? */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "11");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");

            /*  Sixth Army */
            /* 17'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "56");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "62");

            /* 29'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "44");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "111");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "299");

            /* 44'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "9");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "297");

            /* 55'th Corps  */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "75");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "57");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "168");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "298");


            /* Seventeenth Army */
            /* 4'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "24");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "71");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "262");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "295");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "296");

            /* 49'th Mountain Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "69");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "257");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiMountain.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "mountain", "1 M");

            /* 59'th Corps */
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "101 L");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "97 L");
            UnitFactory::create("xx", KievCorps::GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "100 L");

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
            $this->gameRules->setMaxTurn(8);
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
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, KievCorps::SOVIET_FORCE, KievCorps::GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, KievCorps::GERMAN_FORCE, KievCorps::SOVIET_FORCE, true);
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            if($unit->name == "xxx"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    return true;
                }
            }

            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->name == "xxx"){
                        return true;
                }
            }
            return count((array)$mapHex->forces[$forceId]) >= 3;
        };


    }
}