<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12/11/16
 * Time: 1:32 PM
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

namespace Wargame\TMCW\Airborne;
use Wargame\TMCW\Kiev1941\MultiStepUnit;
use Wargame\TransportableUnit;
use Wargame\Battle;

class MultiStepCombatSupplyUnit extends MultiStepUnit implements TransportableUnit
{
    public $supplyUsed = false;
    public $supplyRadius = false;

    public $canTransport = false;

    public $carries = false;
    public $carriedBy = false;

    public function fetchData(){
        $mapUnit = parent::fetchData();
        $mapUnit->supplyUsed = $this->supplyUsed;
        if($this->supplyRadius !== false){
            $mapUnit->supplyRadius = $this->supplyRadius;
        }
        $mapUnit->carriedBy = $this->carriedBy;
        $mapUnit->carries = $this->carries;
        $mapUnit->canTransport = $this->canTransport;
        return $mapUnit;
    }

    public function postSet(){
        if($this->class === "supply"){
            $this->supplyRadius = 2;
        }
    }




    public function canBeTransported(){
        if($this->class === 'supply'){
            if($this->moveAmountUsed === 0){
                return true;
            }
        }
        return false;
    }


    public function updateMoveStatus($hexagon, $moveAmount){
        parent::updateMoveStatus($hexagon, $moveAmount);
        if($cargo = $this->getCargo()){
            $b = Battle::getBattle();
            $unit = $b->force->getUnit($cargo);
            $unit->updateMoveStatus($hexagon, 0);
        }
    }


    public function canTransport() : bool {
        return $this->class === "truck";
    }

    public function setCargo(TransportableUnit $carriedUnit){
        $this->carries = $carriedUnit->id;
    }

    public function setTransporter(TransportableUnit $carryingUnit){
        $this->carriedBy = $carryingUnit->id;
    }

    public function getCargo(){
        return $this->carries;
    }

    public function getTransporter(){
        return $this->carriedBy;
    }

    public function unsetCargo(){
        $this->carries = false;
    }

    public function unsetTransporter(){
        $this->carriedBy =false;
    }


}