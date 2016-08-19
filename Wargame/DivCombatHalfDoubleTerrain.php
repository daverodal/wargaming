<?php
namespace Wargame;
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 11:01 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

trait DivCombatHalfDoubleTerrain
{
    function setCombatIndex($defenderId)
    {
        $combatLog = "";

        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
        $combats = $battle->combatRules->combats->$defenderId;
        /* @var Force $force */
        $force = $battle->force;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        $attackingForceId = $battle->force->attackingForceId;
        $attackingForceName = preg_replace("/ /", "-", Battle::$forceName[$attackingForceId]);

        $defendingForceId = $battle->force->defendingForceId;
        $defendingForceName = preg_replace("/ /", "-", Battle::$forceName[$defendingForceId]);


        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }


        $defenders = $combats->defenders;
        $isTown = $isHill = $isForest = $isSwamp = $attackerIsSunkenRoad = $isRedoubt = $isElevated = false;

        foreach ($defenders as $defId => $defender) {
            $hexagon = $battle->force->units[$defId]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isTown |= $battle->terrain->terrainIs($hexpart, 'town');
            $isHill |= $battle->terrain->terrainIs($hexpart, 'hill');
            $isForest |= $battle->terrain->terrainIs($hexpart, 'forest');
            $isSwamp |= $battle->terrain->terrainIs($hexpart, 'swamp');
            $isElevated |= $battle->terrain->terrainIs($hexpart, 'elevation');
        }
        $isClear = true;
        if ($isTown || $isForest || $isHill || $isSwamp) {
            $isClear = false;
        }

        $attackStrength = 0;
        $combatLog .= "<span class='combatants $attackingForceName'>Attackers</span><br>";

        foreach ($combats->attackers as $attackerId => $v) {
            $unit = $force->units[$attackerId];
            $combatLog .= $unit->class ." ".$unit->strength."<br>";


            $acrossRiver = false;
            foreach ($defenders as $defId => $defender) {
                if ($battle->combatRules->thisAttackAcrossRiver($defId, $attackerId)) {
                    $acrossRiver = true;
                }
            }

            $strength = $unit->strength;
            if($acrossRiver){
                $strength /= 2;
                $combatLog  .= " halved across river or wadi $strength<br>";
            }
            $attackStrength += $strength;
        }
        $defenseStrength = 0;
        $combatLog .= " total $attackStrength<br>";
        $combatLog .= "<br><span class='combatants $defendingForceName'>Defenders</span><br>";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .=  $unit->class." " .$unit->defStrength."<br>";
            $defenseStrength += $unit->defStrength;
        }
        if($isTown){
            $defenseStrength *= 2;
            $combatLog .= "In Town doubled $defenseStrength<br>";
        }
        $combatLog .= " Total $defenseStrength<br>";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);

        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}
