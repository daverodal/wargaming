<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/8/17
 * Time: 9:54 AM
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


class ModernGtMoveRules extends MoveRules implements TransportMoveRules
{
    use GtTransportMoveRules;

    protected function clickMoveTarget($id, $hexagon){
        $movingUnit = $this->force->units[$this->movingUnitId];

        $dirty = parent::clickMoveTarget($id, $hexagon);
        if($movingUnit->unitCanUnload()){
            $this->unload($movingUnit, new Hexagon($hexagon));
            $dirty = true;
        }
        return $dirty;
    }

    public function finishCurrentUnit($id){
        $dirty = false;
        /* @var Unit $movingUnit */
        $movingUnit = $this->force->getUnit($this->movingUnitId);
        if ($movingUnit->unitIsMoving() == true) {
            $this->stopMove($movingUnit);
            $dirty = true;
        }
        if ($movingUnit->unitIsReinforcing() == true) {
            $this->stopReinforcing($movingUnit);
            $dirty = true;
        }
        if ($movingUnit->unitIsDeploying() == true) {
            $this->stopDeploying($movingUnit);
            $dirty = true;
        }

        if($movingUnit->unitCanUnload()){
            $this->stopUnloading($movingUnit);
            $dirty = true;
        }

        if($movingUnit->unitIsLoading()) {
            $unit = $this->force->getUnit($id);

            if (!$unit->unitIsTransporting()) {
                $this->stopLoading($movingUnit);
                $dirty = true;
            }
        }

        if($movingUnit->unitIsTransporting()) {
            $unit = $this->force->getUnit($id);
            if($unit->canBeTransported() !== true){
                $this->stopLoading($movingUnit);
                $dirty = true;
            }

        }
        return $dirty;
    }

    public function startNextUnit($eventType, $id, $turn, $prevMovingUnitId){
        $dirty = false;
        $movingUnitId = $this->movingUnitId;

        if ($eventType == KEYPRESS_EVENT) {
            if ($this->force->unitCanMove($prevMovingUnitId) == true) {
                $this->startMoving($prevMovingUnitId);
                $this->calcMove($prevMovingUnitId);
                $dirty = true;
            }
        } else {
            $unit = $this->force->getUnit($id);
            if ($this->force->unitCanMove($id) == true) {
                $this->startMoving($id);
                $this->calcMove($id);
                $dirty = true;
            }
            if ($this->force->unitCanReinforce($id) == true) {
                $this->startReinforcing($id, $turn);
                $dirty = true;
            }
            if ($this->force->unitCanDeploy($id) == true) {
                $this->startDeploying($id, $turn);
                $dirty = true;
            }
            if($unit->unitIsTransporting()){
                $this->transport($unit);
                $dirty = true;
            }
            if($unit->unitCanLoad()){
                $this->transport($unit);
                $dirty = true;
            }
            if($unit->unitIsUnloading() === true){
                $this->stopUnloading($unit);
                $dirty = true;
            }
        }
        return $dirty;
    }

    public function clickUnitSelected($id){

        $dirty = parent::clickUnitSelected($id);
        if($dirty){
            return $dirty;
        }
        $movingUnit = $this->force->units[$this->movingUnitId];

        if ($movingUnit->unitIsLoading() == true) {
            $this->stopLoading($movingUnit);
            $dirty = true;
        }
        if ($movingUnit->unitCanUnload() == true) {
            $this->stopUnloading($movingUnit);
            $dirty = true;
        }
        if ($movingUnit->unitIsUnloading() == true) {
            $this->stopUnloading($movingUnit);
            $dirty = true;
        }
        if($movingUnit->unitIsTransporting()) {
            $this->cancelLoading($movingUnit);
            $dirty = true;
        }
        return $dirty;
    }

}