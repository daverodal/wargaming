<?php
namespace Wargame\TMCW;
use \Wargame\Battle;
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

trait ModernSupplyRules
{
    protected function restoreAllCombatEffects($force){
        foreach($this->combatCache as $id => $strength){
            $unit = $force->getUnit($id);
            $unit->removeAdjustment('supply');
            unset($this->combatCache->$id);
        }
    }

    protected function unitSupplyEffects($unit, $goal, $bias, $supplyLen){

        $b = Battle::getBattle();
        $id = $unit->id;

        if ($b->gameRules->mode == REPLACING_MODE) {
            if ($unit->status == STATUS_CAN_UPGRADE) {
                $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
                if (!$unit->supplied) {
                    /* TODO: make this not cry  (call a method) */
                    $unit->status = STATUS_STOPPED;
                }
            }
            return;
        }
        if ($unit->isOnMap()) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }
        if (!$unit->supplied) {
            $unit->addAdjustment('movement','floorHalfMovement');

        }
        if ($unit->supplied) {
            $unit->removeAdjustment('movement');
        }
        if ((!empty($this->unsuppliedDefenderHalved) || $unit->forceId == $b->gameRules->attackingForceId) && !$unit->supplied) {
            $unit->addAdjustment('supply','half');
        }else{
            $unit->removeAdjustment('supply');
        }

    }

    protected function unitCombatSupplyEffects($unit, $goal, $bias, $supplyLen){

        $b = Battle::getBattle();
        $id = $unit->id;

        if ($unit->hexagon->name) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }

        if (!$unit->supplied) {
            $unit->addAdjustment('movement','floorHalfMovement');

        }
        if ($unit->supplied) {
            $unit->removeAdjustment('movement');
        }
        if (!$unit->supplied) {
            if(($unit->forceId != $b->gameRules->attackingForceId) ){
                if(!empty($this->unsuppliedDefenderHalved)){
                    $unit->addAdjustment('supply','half');
                }else{
                    $unit->removeAdjustment('supply');
                }
            }else{
                $unit->addAdjustment('supply','zero');
            }
            $b->combatRules->recalcCombat($unit->id);
        }else{
            $unit->removeAdjustment('supply');
            $b->combatRules->recalcCombat($unit->id);
        }

    }
}