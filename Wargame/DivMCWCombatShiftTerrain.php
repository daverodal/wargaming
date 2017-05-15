<?php
namespace Wargame;
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 8/24/13
 * Time: 5:44 PM
 * To change this template use File | Settings | File Templates.
 */
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version->

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */







trait DivMCWCombatShiftTerrain
{
    function setCombatIndex($defenderId)
    {
        $combatLog = "";
        $battle = \Wargame\Battle::getBattle();
        $combatRules = $battle->combatRules;
        $combats = $battle->combatRules->combats->$defenderId;
        /* @var Force $force */
        $force = $battle->force;
        $attackingForceId = $force->attackingForceId;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
        $isDIntegrity = $isAIntegrity = false;

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $defenders = $combats->defenders;
        $attackStrength = 0;

        $isFortA = $isFortB = $isHeavy = $isShock = $isMountain = $isMountainInf = $isTown = $isHill = $isForest = $isSwamp = $attackerIsSunkenRoad = $isRedoubt = false;

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isMountain |= $battle->terrain->terrainIs($hexpart, 'mountain');
            $isFortA = $battle->terrain->terrainIs($hexpart, 'forta');
            $isFortB = $battle->terrain->terrainIs($hexpart, 'fortb');
            if(($isFortB || $isFortA) && $unit->class == "heavy"){
                $isHeavy = true;
            }
            $integrity = $unit->integrity ?? false;
            if($integrity){
                $isDIntegrity = true;
            }
        }
        $combatLog .= "Attackers<br>";

        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $combatLog .= $unit->class." ".$unit->strength."<br>";

            if($unit->class == "mountain"){
                $combatLog .= "+1 shift Mountain Inf in Mountain<br>";
                $isMountainInf = true;
            }
            if($unit->class == "shock"){
                $combatLog .= "+1 shift Attacking with Shock Troops<br>";
                $isShock = true;
            }
            $attackStrength += $unit->strength;
            $integrity = $unit->integrity ?? false;
            if($integrity){
                $isAIntegrity = true;
            }
        }
        $combatLog .= "total $attackStrength<br>";

        $combatLog .= "<br>Defenders<br> ";

        $defenseStrength = 0;
        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->class. " " .$unit->defStrength."<br>";
            $defenseStrength += $unit->defStrength;
        }
        if($isHeavy){
            $defenseStrength++;
            $combatLog .= "+1 Strength for Heavy Inf in Fortified 1<br>";
        }
        $combatLog .= " total $defenseStrength<br><br>";

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */
        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);
        if($terrainCombatEffect !== 0){
            $combatLog .= "Shift $terrainCombatEffect left for Terrain<br>";
        }

        if($isMountainInf && $isMountain){
            /* Mountain Inf helps combat agains Mountain hexes */
            $terrainCombatEffect--;
            $combatLog .= "Shift 1 right for mountain inf attacking into mountain<br>";
        }
        if($isDIntegrity){
            $combatLog .= "Shift 1 left for defender having integrity<br>";
            $terrainCombatEffect += 1;
        }
        if($isAIntegrity){
            $combatLog .= "Shift 2 right for defender having integrity<br>";
            $terrainCombatEffect -= 2;
        }
        /* FortB trumps FortA in multi defender attacks */
        /* TODO: FORCED ID SHOULD NOT BE HERE!!! */
        if(isset($this->aggressorId) && $attackingForceId !== $this->aggressorId){

        }else{
            $forceName = Battle::$forceName;
            $player = $forceName[$attackingForceId];
            if($isFortB){
                $combatLog .= "Shift 2 left for $player attacking into Fortified B<br>";
                $terrainCombatEffect += 2;
            }else if($isFortA){
                $combatLog .= "Shift 1 left for $player attacking into Fortified A<br>";
                $terrainCombatEffect += 1;
            }
        }
        $dispShift = $terrainCombatEffect * -1;

        $combatLog .= "Total Shift $dispShift<br>";
        $combatIndex -= $terrainCombatEffect;

        if($isShock && $combatIndex >= 0){
            $combatIndex++;
        }
        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}