<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 9/30/17
 * Time: 8:43 AM
 *
 * /*
 * Copyright 2012-2017 David Rodal
 * This program is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wargame;

use stdClass;

class FutureTacticalCombatRules extends ModernTacticalCombatRules
{
    public $currentAttacker = false;

    public function isAttacking($defender){
        echo "Is Attacking ";
        $cA = $this->currentAttacker;
        $curDef = $this->combats->$cA ?? false;
        if($curDef && isset($curDef->defenders->$defender)){
            return true;
        }
        return false;
    }
    public function notAttacking(){
        echo "not attacking ";
        $cA = $this->currentAttacker;
        $this->currentDefender = false;
        unset($this->combats->$cA);
    }
    public function attacking($defender, $bearing){

        $cA = $this->currentAttacker;
        $this->currentDefender = $defender;
        $newCombat = new Combat();
        $newCombat->addAttacker($cA, $defender, $bearing);
        $this->combats->$cA = $newCombat;
    }

    function validCombat($defenderId, $attackerId) : bool {
        $battle = Battle::getBattle();
        $unit = $this->force->getUnit($attackerId);
        $victory = $battle->victory;

        $los = new Los();

        $los->setOrigin($this->force->getUnitHexagon($attackerId));
        $los->setEndPoint($this->force->getUnitHexagon($defenderId));
        $range = $los->getRange();
        echo $range;
        if ($range > $unit->getRange($attackerId)) {
            $good = false;
            return $good;
        }
        if ($range > 1) {
            $good = $this->checkBlocked($los, $attackerId);
            if ($good) {
                $isHidden = false;

                $hexagon = $this->force->getUnitHexagon($defenderId);
                $hexpart = new Hexpart();
                $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
                $isHidden |= $battle->terrain->terrainIs($hexpart, 'town');
                $isHidden |= $battle->terrain->terrainIs($hexpart, 'forest');
                if ($isHidden && !$this->isSighted($hexagon->name, $defenderId)) {
                    /* confirm observer is in los too */
                    $bad = true;
                    $adjacentUnits = $this->force->getAdjacentUnits($defenderId);
                    $observerLos = new Los();
                    $observerLos->setOrigin($this->force->getUnitHexagon($attackerId));
                    foreach ($adjacentUnits as $adjacentUnitId => $v) {
                        $observerLos->setEndPoint($this->force->getUnitHexagon($adjacentUnitId));
                        if ($this->checkBlocked($observerLos, $adjacentUnitId)) {
                            $bad = false;
                            break;
                        }
                    }
                    if ($bad) {
                        $good = false;
                    }
                }
            }
        }


        if ($range == 1) {
            if ($this->terrain->terrainIsHexSide($this->force->getUnitHexagon($attackerId)->name, $this->force->getUnitHexagon($defenderId)->name, "blocked")) {
                $good = false;
            }
        }
        if(method_exists($unit, "checkLos")){
            if($unit->checkLos($los, $defenderId) === false){
                $good = false;
            }
        }

        if($victory->isCombatVetoed($unit, $this->currentDefender) === true){
            $good = false;
        }
        return $good;
    }
    function setupCombat($id, $shift = false)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $unit = $battle->force->units[$id];

        $cd = $this->currentDefender;
        $ca = $this->currentAttacker ?? false;

        if ($this->force->unitIsEnemy($id) == true) {

            if($this->currentAttacker === false){
                $this->cleanUpAttacklessDefenders();
                return;
            }

            $isHidden = false;
            $hexagon = $battle->force->units[$id]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isHidden |= $battle->terrain->terrainIs($hexpart, 'town');
            $isHidden |= $battle->terrain->terrainIs($hexpart, 'forest');
            if ($isHidden && !$battle->force->unitIsAdjacent($id) && !$this->isSighted($hexagon->name, $id)) {
                return false;
            }

            if($this->isAttacking($id)){
                $this->notAttacking();
            }else{
                if($this->validCombat($id, $ca)){
                    $los = new Los();

                    $los->setOrigin($this->force->getUnitHexagon($ca));
                    $los->setEndPoint($this->force->getUnitHexagon($id));
                    $range = $los->getRange();
                    $bearing = $los->getBearing();
                    $this->attacking($id, $bearing);
                    $this->crt->setCombatIndex($id, $ca);
                }
                return;
            }
//            $this->sighted($hexagon->name);


            // defender is already in combatRules, so make it currently selected
//            if(isset($this->defenders->$id)){
//                $id = $this->defenders->$id;
//            }
            $combats = isset($this->combats->$id) ? $this->combats->$id : [];
            $combatId = $id;
            if (isset($this->defenders->$id)) {
                $combatId = $this->defenders->$id;
//                $cd = $this->defenders->$id;
                $combats = $this->combats->$combatId;
            }
        } else // attacker
        {

            if ($this->force->units[$id]->status != STATUS_UNAVAIL_THIS_PHASE) {
                if($this->currentAttacker === false){
                    $this->currentAttacker = $id;
                    $this->cleanUpAttacklessDefenders();
                    return;
                }
                if($this->currentAttacker === $id){
                    $this->currentAttacker = false;
                    $this->currentDefender = false;
                    $this->cleanUpAttacklessDefenders();
                    return;
                }
                if($this->currentAttacker !== $id){
                    $this->currentAttacker = $id;
                    $this->currentDefender = false;
                    $this->cleanUpAttacklessDefenders();
                    return;
                }
                $this->checkBombardment();
            }
        }
        $this->cleanUpAttacklessDefenders();
    }

    function cleanUpAttacklessDefenders(){

    }
}