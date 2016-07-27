<?php
namespace Wargame\Troops\ModernTactics;
use stdClass;
use Wargame\Battle;
use Wargame\Hexpart;
use Wargame\Los;
// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

/**
 *
 * Copyright 2012-2015 David Rodal
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

class CombatResultsTable
{
    use CRTResults;
    public $combatIndexCount;
    public $maxCombatIndex;
    public $dieSideCount;
    public $dieMaxValue;
    public $combatResultCount;

    public $combatResultsTable;
    public $combatResultsHeader;
    public $combatOddsTable;

    //     combatIndexeCount is 6; maxCombatIndex = 5
    //     index is 0 to 5;  dieSidesCount = 6

    function __construct()
    {

        global $results_name;
        $results_name[DE] = "D-4";
        $this->combatResultsHeader = array("-3", "-2", "-1", "0", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8");
        $this->crts = new stdClass();

        $this->crts->normal = array(
            array(NE,   NE,   PIN,    PIN,   D1, D1, PIN, D1, D1, D2, D2, D3),
            array(NE,  PIN,   PIN,    D1, D1, D1, D1,    D1, D2, D2, D3, DE),
            array(PIN, PIN,   D1,  D1, D1, D1,    D1,    D2, D2, D3, D3, DE),
            array(PIN, D1, D1,  D1, D1,    D1,    D2,    D2, D3, D3, DE, DE),
            array(D1, D1,  D1,     D1,    D2,    D2,    D2,    D3, D3, DE, DE, DE),
            array(D1,  D1,    D1,     D2,    D2,    D3,    D3,    D3, DE, DE, DE, DE),
        );

        $this->combatIndexCount = 12;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;

    }

    function getCombatResults($Die, $index, $combat)
    {
        $index += 3;
        if($index > $this->maxCombatIndex){
            $index = $this->maxCombatIndex;
        }
        if ($combat->useAlt) {
            return $this->crts->normal[$Die][$index];
        } else {
            if($combat->useDetermined){
                return $this->combatResultsTableDetermined[$Die][$index];
            }
            return $this->crts->normal[$Die][$index];
        }
    }

    function getCombatDisplay()
    {
        return $this->combatResultsHeader;
    }

    public function setCombatIndex($defenderId)
    {

        $combatLog = "";
        $battle = Battle::getBattle();
        $scenario = $battle->scenario;
        $combats = $battle->combatRules->combats->$defenderId;

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $defenders = $combats->defenders;
        $isCrest = $isCavalry = $isTown = $isHill = $isForest = $isSwamp = $attackerIsSunkenRoad = $isRedoubt = $isElevated = false;

        $combats->index = [];

        foreach ($defenders as $defId => $defender) {
            $defUnit = $battle->force->units[$defId];
            $hexagon = $defUnit->hexagon;
            if($defUnit->class === 'cavalry'){
                $isCavalry = true;
            }
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isTown |= $battle->terrain->terrainIs($hexpart, 'town');
            $isForest |= $battle->terrain->terrainIs($hexpart, 'forest');
            if($battle->terrain->terrainIs($hexpart, 'elevation')){
                $isElevated = 1;
            }
            if($battle->terrain->terrainIs($hexpart, 'elevation2')){
                $isElevated = 2;
            }
            $defenseStrength = $defUnit->defenseStrength;
        }
        $isClear = true;
        if ($isTown || $isForest || $isHill || $isSwamp) {
            $isClear = false;
        }

        $attackers = $combats->attackers;
        $attackStrength = 0;
        $attackersCav = false;
        $combinedArms = array();

        $combatLog .= "<br>";
        foreach ($attackers as $attackerId => $attacker) {
            $terrainReason = "";
            $unit = $battle->force->units[$attackerId];
            $unitStrength = $unit->strength;

            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

            $los = new Los();

            $los->setOrigin($battle->force->getUnitHexagon($attackerId));
            $los->setEndPoint($battle->force->getUnitHexagon($defenderId));
            $range = $los->getRange();

            $hexParts = $los->getlosList();

            $targetHex = array_pop($hexParts);
            $targetHexSide = array_pop($hexParts);

            if ($battle->terrain->terrainIs($targetHexSide, "crest")) {
                $isCrest = true;
            }






                $combatLog .= $unit->strength ." ".$unit->class." = ".$unit->strength."<br>";
            
            if($range == 1){

                $isCloseAssault = false;


                if($unit->weapons === ModernTacticalUnit::SM_WEAPONS){
                    if($defenderId !== false){
                        $defUnit = $battle->force->units[$defenderId];
                        if($defUnit->target === ModernTacticalUnit::HARD_TARGET){
                            $isCloseAssault = true;
                        }
                    }
                }
                if(!$isCloseAssault){
                    $unitStrength *= 2;
                    $combatLog .= "range 1, doubled = $unitStrength<br>";
                }else{
                    $combatLog .= "close assult no range attenuation = $unitStrength<br>";

                }

            }
            
            if($range >= 4 && $range <= 5){
                $unitStrength -= 1;
                $combatLog .= " -1 range attentuation = $unitStrength<br>";
            }
            if($range >= 6 && $range <= 8){

                $unitStrength -= 2;
                $combatLog .= " -2 range attentuation = $unitStrength<br>";
            }
            if($range >= 9 && $range <= 10){
                $combatLog .= " -3 range attentuation = $unitStrength<br>";

                $unitStrength -= 3;
            }
            if($range >= 11){
                $combatLog .= "-6 range attentuation = $unitStrength<br>";

                $unitStrength -= 6;
            }

            $attackerIsElevated = false;
            if($battle->terrain->terrainIs($hexpart, 'elevation')){
                $attackerIsElevated = 1;
            }

            if($battle->terrain->terrainIs($hexpart, 'elevation2')){
             $attackerIsElevated = 2;
            }

            $unitStrength -= $defenseStrength;
            $combatLog .= " - $defenseStrength defender = $unitStrength<br>";
            if($isForest){
                $unitStrength--;
                $combatLog .= " in forest - 1 = <span class='fixed-width' >$unitStrength</span><br>";
            }
            if($isTown){
                $unitStrength -= 2;
                $combatLog .= " in town - 2 = <span class='fixed-width' >$unitStrength</span><br>";
            }

            if($isCrest){
                $unitStrength -= 1;
                $combatLog .= " in crest - 1 = <span class='fixed-width' >$unitStrength</span><br>";
            }

            $combatLog .= "final = $unitStrength<br><br>";
            $combats->index[] = $unitStrength;
        }
        $combats->combatLog = $combatLog;
    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $difference = $attackStrength - $defenseStrength;
        if ($attackStrength >= $defenseStrength) {
            $combatIndex = $difference + 3;;
        } else {
            $combatIndex = $difference + 3;
        }
        return $combatIndex;
    }



}
