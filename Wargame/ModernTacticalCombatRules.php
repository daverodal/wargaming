<?php
namespace Wargame;

use stdClass;

// combatRules->js

// Copyright (c) 2009-2011 Mark Butler
// Copyright 2012-2015 David Rodal

// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version->

// This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

class ModernTacticalCombatRules extends TacticalCombatRules
{

    function pinCombat($pinVal)
    {
        /*
         * Do nothing should not be callable.
         */
    }

    protected function isSighted($hexName, $defender)
    {

        $battle = Battle::getBattle();
        if($battle->mapData->getMapSymbols($hexName) !== false){
            return true;
        }
        $battle = Battle::getBattle();
        return $battle->force->unitIsInRange($defender, 3);
    }

    function resolveCombat($id)
    {
        $battle = Battle::getBattle();
        global $results_name;
        if ($this->force->unitIsEnemy($id) && !isset($this->combatsToResolve->$id)) {
            if (isset($this->defenders->$id)) {
                $id = $this->defenders->$id;
            } else {
                return false;
            }
        }
        if ($this->force->unitIsFriendly($id)) {
            if (isset($this->attackers->$id)) {
                $id = $this->attackers->$id;
            } else {
                return false;
            }
        }
        if (!isset($this->combatsToResolve->$id)) {
            return false;
        }
        $this->currentDefender = $id;
        // Math->random yields number between 0 and 1
        //  6 * Math->random yields number between 0 and 6
        //  Math->floor gives lower integer, which is now 0,1,2,3,4,5

        foreach($this->combatsToResolve->$id->index as $indexId => $index) {
            $Die = floor($this->crt->dieSideCount * (rand() / getrandmax()));
//        $Die = $this->crt->dieSideCount - 1;
//        $Die = 0;
//        if(!is_array($this->combatsToResolve->$id->index)) {
//            $index = $this->combatsToResolve->$id->index;
//            if ($this->combatsToResolve->$id->pinCRT !== false) {
//                if ($index > ($this->combatsToResolve->$id->pinCRT)) {
//                    $index = $this->combatsToResolve->$id->pinCRT;
//                }
//            }
//        }
            $combatResults = $this->crt->getCombatResults($Die, $index, $this->combatsToResolve->$id);
            $this->combatsToResolve->$id->Die = $Die + 1;
            $this->combatsToResolve->$id->combatResult = $results_name[$combatResults];
            $this->force->clearRetreatHexagonList();
            $this->force->clearExchangeAmount();

            /* determine which units are defending */
            $defenders = $this->combatsToResolve->{$id}->defenders;
            $defendingHexes = [];
            /* others is units not in combat, but in hex, combat results apply to them too */
            $others = [];
            $phase = $battle->gameRules->phase;

            foreach ($defenders as $defenderId => $defender) {
                $unit = $this->force->units[$defenderId];
                $hex = $unit->hexagon;
                if ($this->force->units[$defenderId]->class === "air" && ($phase == RED_COMBAT_PHASE || $phase == BLUE_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE)) {
//                unset($defenders->$defenderId);
                    continue;
                }
                if (!isset($defendingHexes[$hex->name])) {
                    $mapHex = $battle->mapData->getHex($hex->getName());
                    $hexDefenders = $mapHex->getForces($unit->forceId);
                    foreach ($hexDefenders as $hexDefender) {
                        $hexUnit = $this->force->units[$hexDefender];
                        if ($hexUnit->class !== "air" && ($battle->gameRules->phase == RED_AIR_COMBAT_PHASE || $battle->gameRules->phase == BLUE_AIR_COMBAT_PHASE)) {
                            continue;
                        }
                        if ($hexUnit->class === "air" && ($phase == RED_COMBAT_PHASE || $phase == BLUE_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE)) {
                            continue;
                        }
                        if (isset($defenders->$hexDefender)) {
                            continue;
                        }
                        $others[] = $hexDefender;
                    }
                }
            }
            /* apply combat results to defenders */
            foreach ($defenders as $defenderId => $defender) {
                if (method_exists($this->crt, 'applyCRTResults')) {

                    $this->crt->applyCRTResults($defenderId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die, $this->force);

                } else {

                    $this->force->applyCRTResults($defenderId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
                }
            }
            /* apply combat results to other units in defending hexes */
            foreach ($others as $otherId) {
                if (method_exists($this->crt, 'applyCRTResults')) {
                    $this->crt->applyCRTResults($otherId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die, $this->force);
                } else {
                    $this->force->applyCRTResults($otherId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
                }
            }

        }
        $this->lastResolvedCombat = $this->combatsToResolve->$id;
        if (!$this->resolvedCombats) {
            $this->resolvedCombats = new stdClass();
        }
        $this->resolvedCombats->$id = $this->combatsToResolve->$id;
        unset($this->combatsToResolve->$id);
        foreach ($this->lastResolvedCombat->attackers as $attacker => $v) {
            unset($this->attackers->$attacker);
        }
        /* remove retreat list hexes that are still occupied */

        $this->force->groomRetreatList();
        return $Die;
    }

    function undoDefendersWithoutAttackers()
    {
        $this->currentDefender = false;
        if ($this->combats) {
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            foreach ($this->combats as $defenderId => $combat) {
                if (count((array)$combat->attackers) == 0) {
                    foreach ($combat->defenders as $defId => $def) {
                        $unit = $this->force->getUnit($defId);
                        $unit->setStatus(STATUS_READY);
                        $victory->postUnsetDefender($this->force->units[$defId]);
                    }
                    unset($this->combats->$defenderId);
                    continue;
                }
//                if ($combat->index < 0) {
//                    if ($combat->attackers) {
//                        foreach ($combat->attackers as $attackerId => $attacker) {
//                            $unit = $this->force->getUnit($attackerId);
//                            unset($this->attackers->$attackerId);
//                            $unit->setStatus( STATUS_READY);
//                            $victory->postUnsetAttacker($unit);
//                        }
//                    }
//                    foreach ($combat->defenders as $defId => $def) {
//                        $unit = $this->force->getUnit($defId);
//                        $unit->setStatus( STATUS_READY);
//                        $victory->postUnsetDefender($unit);
//                    }
//                    unset($this->combats->$defenderId);
//                    continue;
//                }
            }
        }
    }
    function checkBlocked($los, $id)
    {
        $mapData = MapData::getInstance();

        $good = true;
        $hexParts = $los->getlosList();
        // remove first and last hexPart

        $src = array_shift($hexParts);
        $target = array_pop($hexParts);
        $srcHexSide = $hexParts[0];
        $targetHexSide = $hexParts[count($hexParts) - 1];

        $localLos = new Los();
        $localLos->originX = $los->originX;
        $localLos->originY = $los->originY;
        $range = $los->getRange();
        $hasElevated1 = $hasElevated2 = false;
        foreach ($hexParts as $hexPart) {

            if ($this->terrain->terrainIs($hexPart, "blocksRanged") ) {
                if($this->terrain->terrainIs($hexPart, "crest")){
                    if($hexPart === $targetHexSide || $hexPart === $srcHexSide){
                        continue;
                    }
                }
                return false;

            }


        }



        if ($good === false) {
            return false;
        }
        return $good;
    }

}
