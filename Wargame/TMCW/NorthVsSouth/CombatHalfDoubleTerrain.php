<?php
namespace Wargame\TMCW\NorthVsSouth;
use Wargame\Battle;
use Wargame\Hexpart;
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

trait CombatHalfDoubleTerrain
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
        $isRoughOne = $isForest = $isSwamp  = false;

        foreach ($defenders as $defId => $defender) {
            $hexagon = $battle->force->units[$defId]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isForest |= $battle->terrain->terrainIs($hexpart, 'forest');
            $isSwamp |= $battle->terrain->terrainIs($hexpart, 'swamp');
            $isRoughOne |= $battle->terrain->terrainIs($hexpart, 'roughone');

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

            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isFortA = $battle->terrain->terrainIs($hexpart, 'forta');
            $isFortB = $battle->terrain->terrainIs($hexpart, 'fortb');
            $unitDefenseStrength = $unit->defStrength;
            if($isFortB && $unit->forceId === NorthVsSouth::SOUTHERN_FORCE){
                $unitDefenseStrength *= 2;
                $combatLog .= "In Defense Zone doubled $unitDefenseStrength<br>";
            }
            if($isFortA && $unit->forceId === NorthVsSouth::NORTHERN_FORCE){
                $unitDefenseStrength *= 2;
                $combatLog .= "In Defense Zone doubled $unitDefenseStrength<br>";
            }
            if($isRoughOne){
                $unitDefenseStrength *= 2;
                $combatLog .= "In Rough Terrain doubled $unitDefenseStrength<br>";
            }
            $defenseStrength += $unitDefenseStrength;

        }

        $combatLog .= " Total $defenseStrength<br>";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);

        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }
        $dieOffset = 0;
        if($isForest || $isSwamp){
            $dieOffset = -1;
            $combatLog .= "-1 dieroll for attack into ";
            if($isForest){
                $combatLog .= "forest";
            }else{
                $combatLog .= "swamp";
            }
        }
        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
        $combats->dieOffset = $dieOffset;
    }
}
