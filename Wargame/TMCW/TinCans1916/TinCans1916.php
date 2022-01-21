<?php
namespace Wargame\TMCW\TinCans1916;

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
$force_name[1] = "IJN";
$force_name[2] = "USN";


define("North", 0);
define("NorthEast", 1);
define("SouthEast", 2);
define("South", 3);
define("SouthWest", 4);
define("NorthWest", 5);

class TinCans1916 extends \Wargame\SimpleBBNavalBattle
{
    const FORCE_ONE = 1;
    const FORCE_TWO = 2;
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "TinCansHeader.php";
    }

    static function getPlayerData($scenario)
    {
        $forceName = ["Neutral Observer", "IJN", "USN"];
        if(!empty($scenario->battlecruiser)){
            $forceName[1] = "GER";
            $forceName[2] = "RN";
        }
        if(!empty($scenario->one)){
            $forceName[1] = "RN";
            $forceName[2] = "IJN";
        }
        if(!empty($scenario->eight)){
            $forceNname[1] = "USN";
            $forceNname[2] = "IJN";
        }
        $deployName = $forceName;
        return \Wargame\Battle::register($forceName, $deployName);
    }



    public static function buildUnit($data = false){
        return \Wargame\TMCW\TinCans1916\UnitFactory::build($data);
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        global $force_name;
        $scenario = $arg;
        if($arg === "one"){
            $force_name[1] = "RN";
            $force_name[2] = "IJN";
        }
        if($arg === "eight"){
            $force_name[1] = "USN";
            $force_name[2] = "IJN";
        }

        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        @include_once "playMulti.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
        $this->terrain->addAltEntranceCost('blocked', 'air', 0);
        $this->terrain->addAltEntranceCost('blocked', 'air', 0);


    }
    function save()
    {
        $data = parent::save();

        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        UnitFactory::$injector = $this->force;

        if(!empty($scenario->battlecruiser)){
            UnitFactory::create("Seydlitz", BLUE_FORCE, 4404, "multiInf.png", 6, 20, 6, 0, 8, NorthWest,  STATUS_READY, "A", 1, "GE",  "bc");
            UnitFactory::create("Derfflinger", BLUE_FORCE, 4505, "multiInf.png", 6, 20, 6, 0, 8, NorthWest,  STATUS_READY, "A", 1, "GE",  "bc");
            UnitFactory::create("Lützow", BLUE_FORCE, 4708, "multiInf.png", 6, 20, 6, 0, 8, NorthWest,  STATUS_READY, "A", 1, "GE",  "bc");
            UnitFactory::create("Moltke", BLUE_FORCE, 4709, "multiInf.png", 6, 20, 6, 0, 8, NorthWest,  STATUS_READY, "A", 1, "GE",  "bc");
            UnitFactory::create("Von Der Tann", BLUE_FORCE, 4710, "multiInf.png", 6, 20, 6, 0, 8, NorthWest,  STATUS_READY, "A", 1, "GE",  "bc");

            UnitFactory::create("Indefatigable", RED_FORCE, 2401, "multiInf.png", 4, 20, 2, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Indomitable", RED_FORCE, 2402, "multiInf.png", 4, 20, 2, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Inflexible", RED_FORCE, 2403, "multiInf.png", 4, 20, 2, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Invincible", RED_FORCE, 2404, "multiInf.png", 4, 20, 2, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("New Zealand", RED_FORCE, 2405, "multiInf.png", 4, 20, 2, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Lion", RED_FORCE, 2406, "multiInf.png", 5, 20, 3, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Princess Royal", RED_FORCE, 2407, "multiInf.png", 5, 20, 3, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Queen Mary", RED_FORCE, 2408, "multiInf.png", 5, 20, 3, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
            UnitFactory::create("Tiger", RED_FORCE, 2409, "multiInf.png", 5, 20, 4, 0, 8, North,  STATUS_READY, "A", 1, "RN",  "bc");
        }
        if(!empty($scenario->one)){
            UnitFactory::create("BB-5", BLUE_FORCE, 4404, "multiInf.png", 30, 17, 23, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "bb");
            UnitFactory::create("BC-w", BLUE_FORCE, 4505, "multiInf.png", 22, 15, 17, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "bc");
            UnitFactory::create("DD-5", BLUE_FORCE, 4708, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");
            UnitFactory::create("DD-5", BLUE_FORCE, 4808, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");
            UnitFactory::create("DD-5", BLUE_FORCE, 4909, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");

            UnitFactory::create("BC-2", RED_FORCE, 716, "multiInf.png", 23, 20, 18, 0, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "bc");
            UnitFactory::create("BC-2", RED_FORCE, 717, "multiInf.png", 23, 20, 18, 0, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "bc");

            UnitFactory::create("DD-2", RED_FORCE, 710, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 1209, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 1022, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 316, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

        }

        if(!empty($scenario->two)){
            /* IJN */
            UnitFactory::create("CA-1", BLUE_FORCE, 5326, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5624, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5525, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5425, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CA-2", BLUE_FORCE, 5724, "multiInf.png", 14, 14, 7, 39, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CL-1", BLUE_FORCE, 5226, "multiInf.png", 3, 12, 2, 15, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");
            UnitFactory::create("CL-3", BLUE_FORCE, 5127, "multiInf.png", 5, 12, 2, 10, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("DD-1", BLUE_FORCE, 5027, "multiInf.png", 1, 12, 2, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN Groups 1 */

            UnitFactory::create("CA-4", RED_FORCE, 6015, "multiInf.png", 12, 14, 6, 12, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("CA-2", RED_FORCE, 6115, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-3", RED_FORCE, 6017, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 6113, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            /* USN Group 2 */

            UnitFactory::create("CA-2", RED_FORCE, 4206, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 4306, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 4405, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-3", RED_FORCE, 4004, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 4407, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");


            UnitFactory::create("DD-3", RED_FORCE, 6119, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

        }

        if(!empty($scenario->three)){
            UnitFactory::create("CA-1", BLUE_FORCE, 2110, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 2009, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 1909, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("DD-2", BLUE_FORCE, 2107, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", BLUE_FORCE, 2113, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("CA-1", RED_FORCE, 3221, "multiInf.png", 14, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 3420, "multiInf.png", 13, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 3321, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 3122, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-5", RED_FORCE, 3719, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3619, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3520, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 2923, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3022, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        }

        if(!empty($scenario->seven)){

            /* IJN froces */
            UnitFactory::create("CL-2", BLUE_FORCE, 3116, "multiInf.png", 5, 12, 3, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("DD-1", BLUE_FORCE, 3216, "multiInf.png", 1, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-4", BLUE_FORCE, 3015, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", BLUE_FORCE, 2915, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", BLUE_FORCE, 2814, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-2", BLUE_FORCE, 2714, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN forces */


            UnitFactory::create("CL-3", RED_FORCE, 4725, "multiInf.png", 11, 12, 6, 0, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 4926, "multiInf.png", 11, 12, 6, 0, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("CL-6", RED_FORCE, 4825, "multiInf.png", 6, 12, 4, 12, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-6", RED_FORCE, 4222, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4323, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4423, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4524, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4624, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            UnitFactory::create("DD-3", RED_FORCE, 5026, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 5127, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 5227, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            UnitFactory::create("DD-5", RED_FORCE, 5529, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 5328, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 5428, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");


        }


        if(!empty($scenario->eight)){

            /* IJN froces */
            UnitFactory::create("CA-2", RED_FORCE, 2211, "multiInf.png", 14, 14, 7, 39, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 2111, "multiInf.png", 14, 14, 7, 39, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CL-2", RED_FORCE, 2310, "multiInf.png", 5, 12, 3, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("CL-4", RED_FORCE, 2013, "multiInf.png", 7, 16, 3, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("DD-3", RED_FORCE, 2209, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 2109, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 2008, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-5", RED_FORCE, 1712, "multiInf.png", 3, 8, 2, 10, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-4", RED_FORCE, 1913, "multiInf.png", 3, 12, 2, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", RED_FORCE, 1812, "multiInf.png", 3, 12, 2, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN forces */

            UnitFactory::create("CL-5", BLUE_FORCE, 3925, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3922, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3923, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3924, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-6", BLUE_FORCE, 3822, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3823, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3825, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3824, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4022, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4023, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4024, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4025, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

        }



    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        $this->moveRules->spottedRange = 24;
        if ($data) {

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\TinCans1916\\VictoryCore");

            // game data
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, BLUE_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_FIRST_COMBAT_PHASE, RED_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_FIRST_COMBAT_PHASE, BLUE_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_COMBAT_RES_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE,RED_FORCE , true);



            if(!empty($scenario->one)){
                $this->gameRules->setMaxTurn(20);
                $this->combatRules->dayTime = true;
            }

            if(!empty($scenario->two)){
                $this->gameRules->setMaxTurn(15);
            }
            if(!empty($scenario->three)){
                $this->gameRules->setMaxTurn(15);
            }
            if(!empty($scenario->seven)){
                $this->gameRules->setMaxTurn(15);
            }
            if(!empty($scenario->eight)){
                $this->gameRules->setMaxTurn(20);
            }
        }
        $this->gameRules->gameHasCombatResolutionMode = false;
        $this->gameRules->setMaxTurn(30);

        $this->combatRules->injectCrt(new  \Wargame\TMCW\SimpleBBCombatResultsTable());
        $this->moveRules->spottedRange = 24;
        if($this->gameRules->turn < 2 && $this->gameRules->attackingForceId === 1){
            $this->moveRules->spottedRange = 24;
        }
        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $land = $air = 0;
            if($unit->class === "air"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $air = 1;
                }
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    if($this->force->units[$mKey]->class === "air"){
                        $air++;
                    }
                }
                return $air > 2;
            }else{
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $land = 1;
                }
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    if($this->force->units[$mKey]->class !== "air"){
                        $land++;
                    }
                }
                return $land > 2;
            }

        };

        $this->moveRules->enemyStackingLimit = function($mapHex, $forceId, $unit){
            $land = $air = 0;
            if($unit->class === "air"){
                if(count((array)$mapHex->forces[$forceId]) >= 1) {
                    foreach ($mapHex->forces[$forceId] as $mKey => $mVal) {
                        if ($this->force->units[$mKey]->class === "air") {
                            return true;
                        }
                    }
                }
                return false;
            }else{
                if(count((array)$mapHex->forces[$forceId]) >= 1) {

                    foreach ($mapHex->forces[$forceId] as $mKey => $mVal) {
                        if ($this->force->units[$mKey]->class !== "air") {
                            return true;
                        }
                    }
                }
                return false;
            }

        };
    }
}
//class TinCans extends \Wargame\ModernLandBattle
//{
//    const GERMAN_FORCE = 1;
//    const SOVIET_FORCE = 2;
//
//    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>2];
//
//    static function getPlayerData($scenario){
//        $forceName = ["Neutral Observer", "German", "Soviet"];
//        return \Wargame\Battle::register($forceName,
//            [$forceName[0], $forceName[2], $forceName[1]]);
//    }
//
//    function terrainGen($mapDoc, $terrainDoc)
//    {
//        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 2, 0, 1, true);
//        $this->terrain->addAltEntranceCost('swamp', 'mech', 3);
//        parent::terrainGen($mapDoc, $terrainDoc);
//        $this->terrain->addTerrainFeature("road", "road", "r", 1, 0, 0, true);
//        $this->terrain->addNatAltEntranceCost('road','soviet','inf',1);
//
//
//    }
//
//    public static function buildUnit($data = false){
//        return UnitFactory::build($data);
//    }
//
//    function terrainInit($terrainDoc){
//        /*
//         * https://en.wikipedia.org/wiki/Order_of_battle_for_Operation_Barbarossa
//         * http://www.cgsc.edu/CARL/nafziger/939GXXP.PDF
//         * http://www.axishistory.com/axis-nations/148-germany-heer/heer-armeen/2655-1-panzerarmee
//         */
//        parent::terrainInit($terrainDoc);
//        UnitFactory::$injector = $this->force;
//
//        if(!empty($this->scenario->preDeploy)) {
//
//            $list = $terrainDoc->terrain->reinforceZones;
//            $cnt = 0;
//            $unitsDeployed = 0;
//            do {
//                foreach ($list as $item) {
//                    if ($item->name != 'B') {
//                        continue;
//                    }
//                    $cnt++;
//                    $isReduced = false;
//
//                    UnitFactory::create("xxx", TinCans::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "B", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);
//
//
////                    if($item->hex == 408){
////                        UnitFactory::create("xxx", TinCans::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);
////                    }else if($item->hex == 409){
////                        UnitFactory::create("xxx", TinCans::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);
////
////                    }else if($item->hex == 510){
////                        UnitFactory::create("xxx", TinCans::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 3,3 , 3);
////
////                    }else{
////                        UnitFactory::create("xxx", TinCans::SOVIET_FORCE, $item->hex, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, $isReduced ? 1 : 2,3 , 3);
////
////                    }
//                    $unitsDeployed++;
//                    if ($unitsDeployed >= 21) {
//                        break;
//                    }
//
//                }
//            } while ($unitsDeployed < 21);
////            UnitFactory::create("xxx", TinCans::SOVIET_FORCE, 609, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);
////            UnitFactory::create("xxx", TinCans::SOVIET_FORCE, 508, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);
////            UnitFactory::create("xxx", TinCans::SOVIET_FORCE, 509, "Infantry.svg", 3, 4, STATUS_READY, "A", 1, "soviet", 'inf', $unitsDeployed+1, 1, 3);
////
////
////            $A = $B = $C = $D = $E = $F = $G = [];
////            $cnt = 0;
////            foreach ($list as $item) {
////                ${$item->name}[] = $item->hex;
////            }
//            $i = 0;
//            /* Second panzer army */
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 503, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 503, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 503, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2, 2);
//
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 603, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 603, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 603, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 604, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 604, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 604, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "", 2, 2);
//
//
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 404, "Infantry.svg", 8,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "17",3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 305, "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "29", 3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 306, "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "44", 3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 504, "Infantry.svg", 8,  5,  STATUS_READY, "D",  1, "sixthArmy",  "inf", "55", 3);
//
//            /* Sixth Army */
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 307, "Infantry.svg", 8,  5,  STATUS_READY, "D", 1,  "seventeenthArmy",  "inf", "17",3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 407, "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "seventeenthArmy",  "inf", "29", 3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 508, "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "seventeenthArmy",  "inf", "44", 3);
//            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, 608, "Infantry.svg", 8,  5,  STATUS_READY, "D",  1, "seventeenthArmy    ",  "inf", "55", 3);
//
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 609, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 609, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 609, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 610, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 610, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2, 2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 610, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2, 2);
//
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 611, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 611, "MechInf.svg", 5, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2,2);
//            UnitFactory::create("xx", TinCans::GERMAN_FORCE, 611, "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "first-panzer-army",  "mech", "", 2, 2);
//
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i ++], "MechInf.svg", 5, 8, STATUS_READY, "B", 1, "german", "mech", "10", 2, 2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i++ ], "MechInf.svg", 5, 8, STATUS_READY, "B", 1, "german", "mech", "29", 2, 2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i ], "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "3", 2, 2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i++], "Armor.svg", 6, 8,  STATUS_READY, "B", 1, "german",  "mech", "18", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i ], "Armor.svg", 6, 8, STATUS_READY, "B", 1, "german", "mech", "4", 2, 2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $B[$i ++], "Armor.svg", 6, 8, STATUS_READY, "B", 1, "german", "mech", "17", 2, 2);
//            $i = 0;
//
//            /* Second Army */
//
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "131", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $C[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "34", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "193", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $C[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "260", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $C[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "secondArmy",  "inf", "17", 2,2);
////            $i = 0;
//
//            /* Sixth Army */
////            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 8,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "17",3);
////            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "29", 3);
////            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 9,  5,  STATUS_READY, "D", 1,  "sixthArmy",  "inf", "44", 3);
////            UnitFactory::create("xxx", TinCans::GERMAN_FORCE, $D[$i += 1], "Infantry.svg", 8,  5,  STATUS_READY, "D",  1, "sixthArmy",  "inf", "55", 3);
//
//            $i = 0;
//
//
////            /* Seventeenth Army */
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "24", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "297", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "9", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $E[$i++], "Infantry.svg", 3, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "60", 2,2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $E[$i++], "Infantry.svg", 4, 5,  STATUS_READY, "B", 1,  "seventeenthArmy",  "inf", "94", 2,2);
//
//            $i = 0;
//
//            /* First panzer army */
////             UnitFactory::create("xx", TinCans::GERMAN_FORCE, $F[$i], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "9", 2, 2);
////            UnitFactory::create("xx", TinCans::GERMAN_FORCE, $F[$i ], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "16", 2, 2 );
////            UnitFactory::create("xx", Minsk1941::GERMAN_FORCE, $F[$i++], "Armor.svg", 6, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "14", 2, 2 );
////            UnitFactory::create("xx", Minsk1941::GERMAN_FORCE, $F[$i ], "MechInf.svg", 5, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "25", 2, 2 );
////            UnitFactory::create("xx", Minsk1941::GERMAN_FORCE, $F[$i ++], "MechInf.svg", 5, 8, STATUS_UNAVAIL_THIS_PHASE, "B", 1, "first-panzer-army", "mech", "16", 2, 2 );
////
////             UnitFactory::create("xxx", Minsk1941::GERMAN_FORCE, $F[$i++], "Infantry.svg", 6, 5,  STATUS_UNAVAIL_THIS_PHASE, "B", 1,  "first-panzer-army",  "inf", "67", 3);
////            UnitFactory::create("xxx", Minsk1941::GERMAN_FORCE, $F[$i], "Infantry.svg", 6, 5,  STATUS_UNAVAIL_THIS_PHASE, "B", 1,  "first-panzer-army",  "inf", "11", 3);
//
//
//
//        }
//    }
//
//    function save()
//    {
//        $data = parent::save();
//
//        $data->specialHexA = $this->specialHexA;
//        $data->specialHexB = $this->specialHexB;
//        $data->specialHexC = $this->specialHexC;
//
//        return $data;
//    }
//
//    public function init()
//    {
//        UnitFactory::$injector = $this->force;
//
//
//        $scenario = $this->scenario;
//
//
//        for($i = 0; $i < 4;$i++){
//            UnitFactory::create("xxx", TinCans::SOVIET_FORCE, "deadpile", "Infantry.svg", 3,  4,  STATUS_ELIMINATED, "A",  1, "soviet",  'inf', "renf$1", 1, 3);
//        }
//
//    }
//
//    function __construct($data = null, $arg = false, $scenario = false)
//    {
//
//        parent::__construct($data, $arg, $scenario);
//
//        $crt = new \Wargame\TMCW\TinCans\CombatResultsTable(TinCans::GERMAN_FORCE);
//        $this->combatRules->injectCrt($crt);
//
//        if ($data) {
//            $this->specialHexA = $data->specialHexA;
//            $this->specialHexB = $data->specialHexB;
//            $this->specialHexC = $data->specialHexC;
//
//        } else {
//            $this->victory = new \Wargame\Victory("\\Wargame\\TMCW\\TinCans\\VictoryCore");
//            if (!empty($scenario->supplyLen)) {
//                $this->victory->setSupplyLen($scenario->supplyLen);
//            }
//            $this->moveRules->enterZoc = 3;
//            $this->moveRules->exitZoc = 2;
//            $this->moveRules->noZocZocOneHex = true;
//            $this->moveRules->stacking = 1;
//
//            $this->moveRules->friendlyAllowsRetreat = true;
//            $this->moveRules->blockedRetreatDamages = true;
//            $this->gameRules->legacyExchangeRule = false;
//
//            // game data
//            $this->gameRules->setMaxTurn(6);
//            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
//
//            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
//            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
//            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
//
//            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, false);
//
//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, false);
//
//            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, TinCans::SOVIET_FORCE, TinCans::GERMAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, TinCans::SOVIET_FORCE, TinCans::GERMAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, TinCans::SOVIET_FORCE, TinCans::GERMAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, TinCans::GERMAN_FORCE, TinCans::SOVIET_FORCE, true);
//        }
//
//        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
//            if($unit->name == "xxx" || $unit->name == "xxxx"){
//                if(count((array)$mapHex->forces[$forceId]) >= 1){
//                    return true;
//                }
//            }
//
//            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
//                if($this->force->units[$mKey]->name !== "xx"){
//                        return true;
//                }
//            }
//            return count((array)$mapHex->forces[$forceId]) >= 3;
//        };
//
//
//    }
//}
