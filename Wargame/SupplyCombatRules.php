<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12/5/16
 * Time: 9:05 PM
 *
 * /*
 * Copyright 2012-2016 David Rodal
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

use Wargame\Battle;

use stdClass;

class SupplyCombatRules extends CombatRules implements CombatSupply
{

    public function selectSupply(MovableUnit $unit)
    {
        if ($unit->supplyUsed === true) {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            $unit->supplyUsed = false;
        } else {
            $unit->status = STATUS_READY;
            $unit->supplyUsed = true;
        }
        $goal = [];

        $units = $this->force->units;
        foreach ($units as $aUnit) {
            if($aUnit->supplyUsed && $aUnit->hexagon->name){
                $goal[]= $aUnit->hexagon->name;
            }
        }
        $b = Battle::getBattle();
        foreach ($units as $aUnit) {
            if ($aUnit->forceId != $b->gameRules->attackingForceId) {
                continue;
            }
            $b->victory->checkCombatSupply($goal, $aUnit);
        }
    }

    function combatResolutionMode()
    {
        $this->cleanUpUnsuppliedAttackers();
        $this->combatsToResolve = $this->combats;
        unset($this->combats);
    }

    function cleanUpUnsuppliedAttackers()
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;

        foreach ($this->combats as $id => $combat) {

            if (count((array)$combat->attackers) == 0) {
                foreach ($combat->defenders as $defenderId => $defender) {
                    $unit = $this->force->getUnit($defenderId);
                    $unit->setStatus( STATUS_READY);
                    unset($this->defenders->$defenderId);
                    $victory->postUnsetDefender($unit);
                }
                unset($this->combats->$id);
            }else{
                foreach($combat->attackers as $aId => $attacker){
                    if($this->force->units[$aId]->supplied !== true){
                        $this->removeAttacker($id, $aId);
                        $this->recalcCombat($id);
                    }
                }
            }
        }
    }

}