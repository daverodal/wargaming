<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/8/17
 * Time: 9:34 AM
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

trait GtTransportMoveRules
{

    public function getUnloadableHexes(TransportableUnit $unit){
        $b = Battle::getBattle();

        $mD = $b->getMapData();
        /* @var MapData $mapData */
        $hex = $unit->getUnitHexagon()->name;
        $mapHex = $mD->getHex($hex);
        $this->unload($unit, $unit->hexagon);
        return true;
    }


    public function getLoadableUnits(TransportableUnit $unit, $status = STATUS_CAN_LOAD){
        $b = Battle::getBattle();

        $mD = $b->getMapData();
        $mapData = $b->mapData;
        $hex = $unit->getUnitHexagon()->name;
        $mapHex = $mapData->getHex($hex);
        $forces = $mapHex->getForces($unit->forceId);
        $loadableUnits = [];
        foreach($forces as $force){
            if($force === $unit->id){
                continue;
            }
            $nUnit = $b->force->getUnit($force);
            if($nUnit->canBeTransported() ){
                if($status === STATUS_CAN_LOAD  && $nUnit->status === STATUS_READY){
                    $nUnit->status = $status;
                    $loadableUnits[] = $nUnit;
                }
                if($status === STATUS_READY  && $nUnit->status === STATUS_CAN_LOAD){
                    $nUnit->status = $status;
                    $loadableUnits[] = $nUnit;
                }
            }
        }
        $this->moves = new stdClass();
        if(count($loadableUnits) === 1){
            $this->transport($loadableUnits[0]);
        }
        return $loadableUnits;
    }

    function unload(TransportableUnit $movingUnit, $hexagon)
    {
        /* @var Unit $movingUnit */
        if (($carriedUnitId = $movingUnit->getCargo()) !== false) {
            /* var \Wargame\TransportableUnit $cargoUnit */
            $cargoUnit = $this->force->getUnit($carriedUnitId);
            $movingUnit->unsetCargo();
            $cargoUnit->unsetTransporter();
            $movingUnit->status = STATUS_MOVING;
            $movingUnit->updateMoveStatus($hexagon, 0);
            $cargoUnit->status = STATUS_READY;
            $this->moves = new stdClass();
            $newHex = $movingUnit->hexagon->name;
            $this->moveQueue = array();
            $hexPath = new HexPath();
            $hexPath->name = $newHex; //$startHex->name;
            $hexPath->pointsLeft = $movingUnit->getMaxMove() - $movingUnit->moveAmountUsed;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = false;
            $hexPath->isOccupied = true;
            if(isset($facing)){
                $hexPath->facing = $facing;
            }

            $this->moveQueue[] = $hexPath;

            $this->bfsMoves();
            return true;
        }
    }

    function transport(TransportableUnit $loadingUnit)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($this->movingUnitId);
        $hex = $unit->hexagon->name;
        if ($loadingUnit->unitCanLoad() == true) {
            $loadingUnit->status = STATUS_STOPPED;
            $loadingUnit->setTransporter($unit);
            $unit->setCargo($loadingUnit);
            $this->getLoadableUnits($loadingUnit, STATUS_READY);
            $unit->status = STATUS_MOVING;


            $newHex = $unit->hexagon->name;
            $this->moveQueue = array();
            $hexPath = new HexPath();
            $hexPath->name = $newHex; //$startHex->name;
            $hexPath->pointsLeft = $unit->getMaxMove() - $unit->moveAmountUsed;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = false;
            $hexPath->isOccupied = true;
            if(isset($facing)){
                $hexPath->facing = $facing;
            }

            $this->moveQueue[] = $hexPath;

            $this->bfsMoves();
        }
    }

    function cancelLoading(TransportableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsTransporting() == true) {
            $unit->setStatus(STATUS_MOVING);
            $this->getLoadableUnits($unit, STATUS_READY);
            $this->moves = new stdClass();
            $newHex = $unit->hexagon->name;
            $this->moveQueue = array();
            $hexPath = new HexPath();
            $hexPath->name = $newHex; //$startHex->name;
            $hexPath->pointsLeft = $unit->getMaxMove() - $unit->moveAmountUsed;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = false;
            $hexPath->isOccupied = true;
            if(isset($facing)){
                $hexPath->facing = $facing;
            }

            $this->moveQueue[] = $hexPath;

            $this->bfsMoves();

        }
    }
    function stopLoading(TransportableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsTransporting() == true) {
            if ($unit->unitHasNotMoved()) {
                $unit->status = STATUS_READY;

            }else{
                $unit->status = STATUS_STOPPED;

            }

            $this->getLoadableUnits($unit, STATUS_READY);
            $this->moves = new stdClass();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
        }
    }

    function stopUnloading(TransportableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitCanUnload() == true) {
            $unit->setStatus(STATUS_READY);
            $cargoId = $unit->getCargo($unit);
            $cargo = $this->force->getUnit($cargoId);
            $cargo->status = STATUS_UNAVAIL_THIS_PHASE;
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
            $this->moves = new stdClass();
        }
        if ($unit->unitIsUnloading() == true) {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            $transporterId = $unit->getTransporter($unit);
            $transporter = $this->force->getUnit($transporterId);
            $transporter->status = STATUS_READY;

            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
            $this->moves = new stdClass();
        }
    }

    public function loadUnit(){
        if ($this->anyUnitIsMoving) {
            /* @var $unit \Wargame\TransportableUnit */
            $unit = $this->force->getUnit($this->movingUnitId);
            if($unit->canTransport() && !$unit->getCargo()){

                    $unit->status = STATUS_CAN_TRANSPORT;
                    $this->moves = new stdClass();
                    $transportUnits = $this->getLoadableUnits($unit);
                    if(count($transportUnits) > 0){
                        return true;
                    }

            }
            if($unit->canTransport()){
                if($cargo = $unit->getCargo()){
                    $b = Battle::getBattle();
                    $cargoUnit = $b->force->getUnit($cargo);
                    $this->getUnloadableHexes($unit);

                    return true;
                }
            }
        }
        return false;

    }
}