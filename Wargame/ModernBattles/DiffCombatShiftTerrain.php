<?php
namespace Wargame\ModernBattles;
use Wargame\{Hexpart,Battle};

/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:58 AM
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
trait DiffCombatShiftTerrain
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

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $defenders = $combats->defenders;

        $isAcrossRiver = $isTown = $isForest = $isRough = false;

        foreach ($defenders as $defId => $defender) {
            $hexagon = $battle->force->units[$defId]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isTown |= $battle->terrain->terrainIs($hexpart, 'town');
            $isRough |= $battle->terrain->terrainIs($hexpart, 'roughtwo');
            $isForest |= $battle->terrain->terrainIs($hexpart, 'forest');
        }

        $attackStrength = 0;
        $combatLog .= "Attackers<br>";

        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $combatLog .= $unit->strength." ".$unit->class."<br>";

            foreach ($defenders as $defId => $defender) {
                if ($battle->combatRules->thisAttackAcrossRiver($defId, $id)) {
                    $acrossRiver = true;
                }
            }
            $strength = $unit->strength;
            $attackStrength += $strength;
        }

        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->defStrength. " " .$unit->class." ";

            $defenseStrength += $unit->defStrength;
            $combatLog .= "<br>";
        }
        $shift = 0;
        if($isTown || $isForest){
            $shift = 2;
        }elseif($isRough){
            $shift = 3;
        }elseif($isAcrossRiver){
            $shift=2;
        }
        $combatLog .= " = $defenseStrength<br>";
        $combatLog .= "$attackStrength - $defenseStrength = ".($attackStrength - $defenseStrength);
        $combatLog .= "<br>shift $shift because of terrain";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        $combatIndex -= $shift;
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }
        if($combatIndex < 0){
            $combatIndex = 0;
        }


        /* @var $combatRules CombatRules */
//        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

//        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
//        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->terrainCombatEffect = 0;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}
