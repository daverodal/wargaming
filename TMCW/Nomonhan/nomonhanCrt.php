<?php
// crt.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 


class NomonhanCombatResultsTable extends CombatResultsTable
{

    function setCombatIndex($defenderId)
    {
        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
        $terrain = $battle->terrain;
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
        $attackStrength = 0;

//        $attackStrength = $this->force->getAttackerStrength($combats->attackers);
        $defenseStrength = 0;
        $defMarsh = false;
        $defArt = false;
        foreach ($defenders as $defId => $defender) {
            $unitStr = $force->getDefenderStrength($defId);
            $unit = $force->units[$defId];
            $unitHex = $unit->hexagon;
            if($unit->class == "artillery"){
                $defArt = true;
            }
            if($terrain->terrainIsHex($unitHex->name, "rough") || $terrain->terrainIsHex($unitHex->name, "hills")){
                $unitStr *= 2;
            }
            if($terrain->terrainIsHex($unitHex->name, "marsh")){
                $defMarsh = true;
                if($unit->class == "inf" || $unit->class == "cavalry"){
                    $unitStr *= 2;
                }
            }
            $defenseStrength += $unitStr;
        }

        $defHex = $unitHex;
        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $unitStr = $unit->strength;
            if($unit->class != 'artillery' && $terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "blocked")){
                $unitStr = 0;
            }
            if($unit->class != 'artillery' && ($terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "river") || $terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "ford"))){
                $unitStr /= 2;
            }
            if($defMarsh && $force->units[$id]->class == 'mech'){
                $unitStr /= 2;
            }
            if($defArt && $force->units[$id]->class != 'artillery'){
                $unitStr *= 2;
            }
            $attackStrength += $unitStr;
        }

        $terrainCombatEffect = 0;

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }



        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
//    $this->force->storeCombatIndex($defenderId, $combatIndex);
    }

}