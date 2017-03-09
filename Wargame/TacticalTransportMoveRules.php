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

trait TacticalTransportMoveRules
{

    public function getUnloadableHexes(TransportableUnit $unit){
        $b = Battle::getBattle();

        $mD = $b->getMapData();
        /* @var MapData $mapData */
        $hex = $unit->getUnitHexagon()->name;
        $mapHex = $mD->getHex($hex);
        $neighbors = $mapHex->neighbors;
        $unloadableHexes = [];
        foreach($neighbors as $nKey => $nVal) {
            $nMapHex = $mD->getHex($nVal);

            if ($nMapHex->isOccupied($unit->forceId) ||
                $mD->hexagonIsOccupiedEnemy(new Hexagon($nVal), $unit->forceId)
            ) {
                continue;
            }
            $unloadableHexes[] = $nVal;
        }
        return $unloadableHexes;
    }


    public function getTransportableUnits(TransportableUnit $unit, $status = STATUS_CAN_TRANSPORT){
        $b = Battle::getBattle();

        $mD = $b->getMapData();
        $mapData = $b->mapData;
        $hex = $unit->getUnitHexagon()->name;
        $mapHex = $mapData->getHex($hex);
        $neighbors = $mapHex->neighbors;
        $transportableUnits = [];
        foreach($neighbors as $nKey => $nVal){
            $nMapHex = $mapData->getHex($nVal);
            $forces = $nMapHex->getForces($unit->forceId);
            foreach($forces as $force){
                $nUnit = $b->force->getUnit($force);
                if($nUnit->canTransport() ){
                    if($status === STATUS_CAN_TRANSPORT && $nUnit->status === STATUS_READY){
                        $nUnit->status = $status;
                        $transportableUnits[] = $nUnit;
                    }
                    if($status === STATUS_READY  && $nUnit->status === STATUS_CAN_TRANSPORT){
                        $nUnit->status = $status;
                        $transportableUnits[] = $nUnit;
                    }
                }
            }
        }
        return $transportableUnits;
    }

    function unload(TransportableUnit $movingUnit, $hexagon)
    {
        /* @var Unit $movingUnit */
        if ($movingUnit->unitCanUnload() == true) {
            $carriedUnitId = $movingUnit->getCargo();
            /* var \Wargame\TransportableUnit $cargoUnit */
            $cargoUnit = $this->force->getUnit($carriedUnitId);
            $movingUnit->unsetCargo();
            $cargoUnit->unsetTransporter();
            $movingUnit->status = STATUS_STOPPED;
            $movingUnit->updateMoveStatus($hexagon, 0);
            $cargoUnit->status = STATUS_STOPPED;
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
            $this->moves = new stdClass();
            return true;
        }
    }

    function transport(TransportableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsTransporting() == true) {
            $this->anyUnitIsMoving = false;
            $loadingUnit = $this->force->getUnit($this->movingUnitId);
            $newHex = $loadingUnit->getUnitHexagon();
            $loadingUnit->setStatus(STATUS_STOPPED);
            $unit->updateMoveStatus($newHex, 0);
            $this->movingUnitId = NONE;
            $unit->status = STATUS_STOPPED;
            $this->moves = new stdClass();
            $loadingUnit->setTransporter($unit);
            $unit->setCargo($loadingUnit);
            $this->getTransportableUnits($loadingUnit, STATUS_READY);
        }
    }

    function stopLoading(TransportableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsLoading() == true) {
            $unit->setStatus(STATUS_READY);
            $this->getTransportableUnits($unit, STATUS_READY);
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
            $this->moves = new stdClass();

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
            $unit = $this->force->getUnit($this->movingUnitId);
            if($unit->canBeTransported()){
                if($unit->unitIsLoading()){
                    $this->stopLoading($unit);
                    return true;
                }else{
                    $unit->status = STATUS_LOADING;
                    $this->moves = new stdClass();
                    $transportUnits = $this->getTransportableUnits($unit);
                    if(count($transportUnits) > 0){
                        return true;
                    }
                }
            }
            if($unit->canTransport()){
                if($cargo = $unit->getCargo()){
                    $b = Battle::getBattle();
                    $cargoUnit = $b->force->getUnit($cargo);
                    $unloadableHexes = $this->getUnloadableHexes($unit);
                    $this->moves = new stdClass();
                    foreach($unloadableHexes as $hex){
                        $newPath = new HexPath();
                        $newPath->name = $hex;
                        $newPath->pathToHere = [];
                        $newPath->pointsLeft = 0;
                        $this->moves->$hex = $newPath;
                    }

                    $cargoUnit->status = STATUS_UNLOADING;
                    $unit->status = STATUS_CAN_UNLOAD;
                    return true;
                }
            }
        }
        return false;

    }
}