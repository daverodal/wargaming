<?php
namespace Wargame;
use \stdClass;
// moveRules.js

// Copyright (c) 2009-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

use \Wargame\Battle;
$numWalks = 0;
class MoveRules
{
    use BfsCalcMovesTrait;

    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

    /* @var MapData */
    public $mapData;
    // local variables
    public $movingUnitId;
    public $anyUnitIsMoving;
    public $moves;
    public $path;
    public $moveQueue;
    public $stickyZoc;
    public $enterZoc = "stop";
    public $exitZoc = 0;
    public $noZocZoc = false;
    public $noZocZocOneHex = true;
    public $oneHex = true;
    public $zocBlocksRetreat = true;
    public $friendlyAllowsRetreat = false;
    public $stacking = 1;
    public $blockedRetreatDamages = false;
    public $noZoc = false;
    public $retreatCannotOverstack = false;
    public $moveCannotOverstack = false;
    public $turnHex = false;
    public $turnFacing = false;
    public $turnId = false;
    public $transitStacking = 1;

    /*
     * usually used for a closure, it's the amount of enemies or greater you CANNOT stack with
     * so 1 means you can't stack with even 1 enemy. Use a closure here to allow for air units stacking with
     * enemy land units only, for example. and vice a versa.
     */
    public $enemyStackingLimit = 1;

    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if (is_object($v) && $k != "path" && $k != "moves") {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    function __construct($Force, $Terrain, $data = null)
    {
        // Class references

        $this->mapData = MapData::getInstance();
        $this->moves = new stdClass();
        $this->path = new stdClass();
        $this->force = $Force;
        $this->terrain = $Terrain;

        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $this->movingUnitId = NONE;
            $this->anyUnitIsMoving = false;
            $this->stickyZoc = false;
        }
    }

    public function inject($force, $terrain){
        $this->force = $force;
        $this->terrain = $terrain;
    }

    public function movesLeft(){
        return false;
    }

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

    function transport(MovableUnit $unit)
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

    function stopLoading(MovableUnit $unit)
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

    function stopUnloading(MovableUnit $unit)
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



// id will be map if map event, id will be unit id if counter event
    function moveUnit($eventType, $id, $hexagon, $turn)
    {
        $dirty = false;
        $this->turnHex = false;
        $this->turnFacing = false;
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving) {
                // click on map, so try to move
                /* @var Unit $movingUnit */
                $movingUnit = $this->force->units[$this->movingUnitId];
                if ($movingUnit->unitIsMoving() == true) {
                    $newHex = $hexagon;

                    if ($this->moves->$newHex) {
                        $this->path = $this->moves->$newHex->pathToHere;

                        foreach ($this->path as $moveHex) {
                            $this->move($movingUnit, $moveHex);
                        }
                        $movesLeft = $this->moves->$newHex->pointsLeft;
                        if(isset($this->moves->$newHex->facing)) {
                            $facing = $this->moves->$newHex->facing;
                            $movingUnit->facing = $facing;
                        }
                        $this->moves = new stdClass();

                        $this->move($movingUnit, $newHex);
                        $this->path = array();
                        if ($this->anyUnitIsMoving) {
                            $this->moveQueue = array();
                            $hexPath = new HexPath();
                            $hexPath->name = $newHex; //$startHex->name;
                            $hexPath->pointsLeft = $movesLeft;
                            $hexPath->pathToHere = array();
                            $hexPath->firstHex = false;
                            $hexPath->isOccupied = true;
                            if(isset($facing)){
                                $hexPath->facing = $facing;
                            }

                            $this->moveQueue[] = $hexPath;
                            $this->bfsMoves();

                            $movesAvail = 0;
                            foreach ($this->moves as $move) {
                                if ($move->isOccupied || !$move->isValid) {
                                    continue;
                                }
                                $movesAvail++;
                            }

                            if ($movesAvail === 0) {
                                $this->stopMove($movingUnit);
                            }
                        }
                        $dirty = true;
                    }
                }
                if ($movingUnit->unitIsReinforcing($this->movingUnitId) == true) {
                    $this->reinforce($movingUnit, new Hexagon($hexagon));
                    $this->calcMove($id);
                    $dirty = true;
                }
                if ($movingUnit->unitIsDeploying() == true) {
                    $this->deploy($movingUnit, new Hexagon($hexagon));
                    $dirty = true;
                }
                if($movingUnit->unitCanUnload()){
                    $this->unload($movingUnit, new Hexagon($hexagon));
                    $dirty = true;
                }

            }
        } else // click on a unit
        {
            if ($this->anyUnitIsMoving == true) {
                if ($id == $this->movingUnitId) {
                    $movingUnit = $this->force->getUnit($id);
                    // clicked on moving or reinforcing unit
                    /* @var Unit $movingUnit */
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsReinforcing($id) == true) {
                        $this->stopReinforcing($movingUnit);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsDeploying() == true) {
                        $this->stopDeploying($movingUnit);
                        $dirty = true;
                    }
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
                } else {
                    /* @var Unit $movingUnit */
                    $movingUnit = $this->force->getUnit($this->movingUnitId);
                    $movingUnitId = $this->movingUnitId;
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsReinforcing($movingUnitId) == true) {
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
                    $unit = $this->force->getUnit($id);

                    if($movingUnit->unitIsLoading() && !$unit->unitIsTransporting()){
                        $this->stopLoading($movingUnit);
                        $dirty = true;
                    }

                    if ($eventType == KEYPRESS_EVENT) {
                        if ($this->force->unitCanMove($movingUnitId) == true) {
                            $this->startMoving($movingUnitId);
                            $this->calcMove($movingUnitId);
                            $dirty = true;
                        }
                    } else {
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
                        if($unit->unitIsUnloading() === true){
                            $this->stopUnloading($unit);
                            $dirty = true;
                        }
                    }
                    // clicked on another unit
                    return $dirty;
//                    $this->moveOver($this->movingUnitId, $id, $hexagon);
                }
            } else {
                // no one is moving, so start new move
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
            }
        }
        return $dirty;
    }

    function selectUnit($eventType, $id, $hexagon, $turn)
    {
        $dirty = false;
        if ($eventType == SELECT_MAP_EVENT) {
           return false;
        } else // click on a unit
        {
            if ($this->anyUnitIsMoving == true) {
                if ($id == $this->movingUnitId) {
                    $movingUnit = $this->force->getUnit($id);
                    // clicked on moving or reinforcing unit
                    /* @var Unit $movingUnit */
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsReinforcing($id) == true) {
                        $this->stopReinforcing($movingUnit);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsDeploying() == true) {
                        $this->stopDeploying($movingUnit);
                        $dirty = true;
                    }
                } else {
                    /* @var Unit $movingUnit */
                    $movingUnit = $this->force->getUnit($this->movingUnitId);
                    $movingUnitId = $this->movingUnitId;
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($this->force->unitCanMove($id) == true) {
                        $this->startMoving($id);
                        $dirty = true;
                    }


                    // clicked on another unit
                    return $dirty;
//                    $this->moveOver($this->movingUnitId, $id, $hexagon);
                }
            } else {
                // no one is moving, so start new move
                if ($this->force->unitCanMove($id) == true) {
                    $this->startMoving($id);
                    $dirty = true;
                }

            }
        }
        return $dirty;
    }


    function startMoving($id)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $victory->preStartMovingUnit($unit);

        /*
         * Don't think this is important test. Unit will be STATUS_STOPPED if cannot move.
         */
        if (!$this->stickyZoc || $this->force->unitIsZOC($id) == false) {
            if ($unit->setStatus(STATUS_MOVING) == true) {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
        $victory->postStartMovingUnit($unit);
    }

    function move(MovableUnit $movingUnit, $hexagon)
    {
        if ($movingUnit->unitIsMoving()
            && $this->moveIsValid($movingUnit, $hexagon)
        ) {
            $this->updateMoveData($movingUnit, $hexagon);
        }
    }

    function stopMove(MovableUnit $movingUnit, $force = false)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preStopMovingUnit($movingUnit);

        $this->moves = new stdClass();
        if ($movingUnit->unitIsMoving() == true) {
            if ($movingUnit->unitHasNotMoved() && !$force) {
                $movingUnit->setStatus(STATUS_READY);
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
            } else {
                if ($movingUnit->setStatus(STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                }
            }
        }
        if($movingUnit->unitIsDeploying()){
            $this->stopDeploying($movingUnit);
        }
        $victory->preStopMovingUnit($movingUnit);
    }

    function exitUnit($id)
    {
        /* @var Unit $unit */
        $unit = $this->force->units[$id];
        if ($unit->unitIsMoving() == true) {
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            $ret = $victory->isExit($unit);
            if($ret === false){
                return false;
            }
            if ($unit->setStatus(STATUS_EXITED) == true) {
                /* TODO: awful. probably don't need to set $id for Hexagon name */
                $hexagon = new Hexagon($id);
                $hexagon->parent = 'exitBox';
                $this->force->updateMoveStatus($unit->id, $hexagon, 1);
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
                return true;
            }

        }
        return false;
    }

    function moveIsValid(MovableUnit $movingUnit, $hexagon, $startHex = false, $firstHex = false)
    {
        // all 4 conditions must be true, so any one that is false
        //    will make the move invalid

        $isValid = true;

        if ($startHex === false) {
            $startHex = $movingUnit->getUnitHexagon()->name;
        }
        if ($firstHex === false) {
            $firstHex = $movingUnit->unitHasNotMoved();
        }
        // condition 1
        // can only move to nearby hexagon
        if ($this->rangeIsOneHexagon($startHex, $hexagon) == false) {
            $isValid = false;
        }
        // condition 2
        // check if unit has enough move points
        $moveAmount = $this->terrain->getTerrainMoveCost($startHex, $hexagon, $movingUnit->forceMarch, $movingUnit);

        // need move points, but can always move at least one hexagon
        //  can always move at least one hexagon if this->oneHex is true
        //  only check move amount if unit has been moving
        if (!($firstHex == true && $this->oneHex)) {
            if ($movingUnit->unitHasMoveAmountAvailable($moveAmount) == false) {
                $isValid = false;
            }
        }

        // condition 3
        // can only move across river hexside if at start of move

        // condition 4
        // can not exit
        if (($this->terrain->isExit($hexagon) == true)) {
            $isValid = false;
        }
        return $isValid;
    }

    function updateMoveData(MovableUnit $movingUnit, $hexagon)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $fromHex = $movingUnit->hexagon;
        $moveAmount = $this->terrain->getTerrainMoveCost($movingUnit->getUnitHexagon()->name, $hexagon, $movingUnit->forceMarch, $movingUnit);
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex($hexagon);
        if ($mapHex->isZoc($this->force->defendingForceId) == true) {
            if (is_numeric($this->enterZoc)) {
                $moveAmount += $this->enterZoc;
            }
        }
        $fromMapHex = $mapData->getHex($fromHex->name);
        if ($fromMapHex->isZoc($this->force->defendingForceId) == true) {
            if (is_numeric($this->exitZoc)) {
                $moveAmount += $this->exitZoc;
            }
        }

        $movingUnit->updateMoveStatus(new Hexagon($hexagon), $moveAmount);

        if ($movingUnit->unitHasUsedMoveAmount() == true) {
            $this->stopMove($movingUnit);
        }

        if ($mapHex->isZoc($this->force->defendingForceId) == true) {
            if ($this->enterZoc === "stop") {
                $this->stopMove($movingUnit);
            }
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($movingUnit->id);
        }
    }

    function rangeIsOneHexagon($startHexagon, $endHexagon)
    {
        $rangeIsOne = false;

        $los = new Los();
        $los->setOrigin($startHexagon);
        $los->setEndPoint($endHexagon);
        if ($los->getRange() == 1) {
            $rangeIsOne = true;
        }

        return $rangeIsOne;
    }

    function startReinforcing($id, $turn)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);

        if ($unit->getUnitReinforceTurn($id) <= $turn) {

            $battle = Battle::getBattle();
            $victory = $battle->victory;
            /* @var Unit $unit */
            $victory->preStartMovingUnit($unit);

            if ($unit->setStatus(STATUS_REINFORCING) == true) {
                $movesLeft = $unit->getMaxMove();
                $zoneName = $unit->reinforceZone;
                $zones = $this->terrain->getReinforceZonesByName($zoneName);
                list($zones) = $battle->victory->postReinforceZones($zones, $unit);
                foreach ($zones as $zone) {
                    if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
                        continue;
                    }
                    $startHex = $zone->hexagon->name;
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex = $hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function startDeploying($id, $turn)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->getUnitReinforceTurn($id) <= $turn) {

            if ($unit->setStatus(STATUS_DEPLOYING) == true) {
                $battle = Battle::getBattle();
                $victory = $battle->victory;
                $movesLeft = 0;
                $zoneName = $unit->reinforceZone;
                $zones = $this->terrain->getReinforceZonesByName($zoneName);
                list($zones) = $battle->victory->postDeployZones($zones, $unit);
                foreach ($zones as $zone) {
                    $startHex = $zone->hexagon->name;
                    if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
                        continue;
                    }
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex = $hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function startReplacing($id)
    {
        $battle = Battle::getBattle();
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_REPLACING) == true) {
            $movesLeft = 0;
            $zones = $this->terrain->getReinforceZonesByName($unit->getUnitReinforceZone($id));
            list($zones) = $battle->victory->postReinforceZones($zones, $unit);
            foreach ($zones as $zone) {
                if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
                    continue;
                }
                if(!$zone->hexagon || !$zone->hexagon->name){
                    continue;
                }
                $startHex = $zone->hexagon->name;
                $hexPath = new HexPath();
                $hexPath->name = $startHex;
                $hexPath->pointsLeft = $movesLeft;
                $hexPath->pathToHere = array();
                $hexPath->firstHex = true;
                $this->moves->$startHex = $hexPath;
            }
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
    }

    function stopReplacing()
    {
        $this->moves = new stdClass();

        $this->anyUnitIsMoving = false;
        $this->movingUnitId = false;
        $this->moves = new stdClass();
    }

    function reinforce($movingUnit, Hexagon $hexagon)
    {
        /* @var Unit $movingUnit */
        $battle = Battle::getBattle();
        if ($movingUnit->unitIsReinforcing() == true) {

            list($zones) = $battle->victory->postReinforceZoneNames($this->terrain->getReinforceZoneList($hexagon), $movingUnit);

            if (in_array($movingUnit->getUnitReinforceZone() , $zones)) {
                if ($movingUnit->setStatus(STATUS_MOVING) == true) {
                    $battle = Battle::getBattle();
                    $victory = $battle->victory;
                    $victory->reinforceUnit($movingUnit, $hexagon);
                    $movingUnit->updateMoveStatus($hexagon, 0);
                }

            }
        }
    }

    function deploy($movingUnit, $hexagon)
    {
        /* @var Unit $movingUnit */
        if ($movingUnit->unitIsDeploying() == true) {
            if (in_array($movingUnit->getUnitReinforceZone(), $this->terrain->getReinforceZoneList($hexagon))) {

                if ($movingUnit->setStatus(STATUS_CAN_DEPLOY) == true) {
                    $movingUnit->updateMoveStatus($hexagon, 0);
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                    $this->moves = new stdClass();
                }

            }
        }
    }

    function stopReinforcing($unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsReinforcing() == true) {
            if ($unit->setStatus(STATUS_CAN_REINFORCE) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }
        }
    }


    function stopDeploying(MovableUnit $unit)
    {
        /* @var Unit $unit */
        if ($unit->unitIsDeploying() == true) {
            if ($unit->setStatus(STATUS_CAN_DEPLOY) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }
        }
    }

    function retreatUnit($eventType, $id, $hexagon)
    {
            // id will be retreating unit id if counter event
            if ($this->anyUnitIsMoving == false) {
                if ($this->force->unitCanRetreat($id) == true) {
                    $this->startRetreating($id);
                    if($this->anyUnitIsMoving){
                        $this->calcRetreat($id);
                    }
                }
            } else {
                    $finalHex = $hexagon;
                    $moves = $this->moves->$finalHex;
                    foreach ($moves->pathToHere as $move){
                        $this->retreat($this->movingUnitId, new Hexagon($move));
                    }
                    $this->retreat($this->movingUnitId, new Hexagon($finalHex));
                    $this->moves = new stdClass();
            }
    }

    function startRetreating($id)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;

        /* @var Unit $movingUnit */
        $movingUnit = $this->force->getUnit($id);
        $victory->preStartMovingUnit($movingUnit);
        if ($movingUnit->setStatus(STATUS_RETREATING) == true) {
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
        $victory->postStartMovingUnit($movingUnit);
    }

    function retreatIsBlocked($id)
    {
        throw new Exception("bad bad call ");
        $isBlocked = true;

        $adjacentHexagonXadjustment = array(0, 2, 2, 0, -2, -2);
        $adjacentHexagonYadjustment = array(-4, -2, 2, 4, 2, -2);

        /* @var Hexagon $hexagon */
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $hexagon = $unit->getUnitHexagon();
        $hexagonX = $hexagon->getX($id);
        $hexagonY = $hexagon->getY($id);
        for ($eachHexagon = 0; $eachHexagon < 6; $eachHexagon++) {
            $adjacentHexagonX = $hexagonX + $adjacentHexagonXadjustment[$eachHexagon];
            $adjacentHexagonY = $hexagonY + $adjacentHexagonYadjustment[$eachHexagon];
            $adjacentHexagon = new Hexagon($adjacentHexagonX, $adjacentHexagonY);

            if ($this->hexagonBlocksRetreat($id, $adjacentHexagon) == false) {
                $isBlocked = false;
                break;
            }

        }

        return $isBlocked;
    }

    function hexagonBlocksRetreat($id, Hexagon $startHex, Hexagon $hexagon)
    {
        $isBlocked = false;

        if (!$hexagon->name) {
            return true;
            /* off map hexes have no name */
        }


        // make sure hexagon is not ZOC
        $unit = $this->force->units[$id];
        $mapHex = $this->mapData->getHex($hexagon->name);
        $forceId = $unit->forceId;

        if ($this->zocBlocksRetreat === true && $this->force->mapHexIsZOC($mapHex, $this->force->enemy($unit->forceId))){
            $isBlocked = true;
            if($this->friendlyAllowsRetreat && $mapHex->isOccupied($forceId)){
                if(!$mapHex->isOccupied($forceId,$this->stacking, $unit)){
                    $isBlocked = false;
                }
            }
        }
        if ($this->terrain->terrainIsHexSide($startHex->name, $hexagon->name, "blocked")) {
            $isBlocked = true;
        }
        if ($this->terrain->getTerrainMoveCost($startHex->name, $hexagon->name, false, $unit) == "blocked") {
            $isBlocked = true;
        }

//        if ($this->zocBlocksRetreat === true && ($this->force->hexagonIsZOC($id, $hexagon) == true)) {
//            $isBlocked = true;
//        }
        // make sure hexagon is not occupied
        if ($this->mapData->hexagonIsOccupiedEnemy($hexagon, $forceId) == true) {
            $isBlocked = true;
        }

        if ($this->terrain->isExit($hexagon) == true) {
            $isBlocked = true;
        }
        //alert(unitHexagon->getName() + " to " + hexagon->getName() + " zoc: " + $this->force->hexagonIsZOC(id, hexagon) + " occ: " + $this->force->hexagonIsOccupied(hexagon)  + " river: " + $this->terrain->terrainIs(hexpart, "river"));
        return $isBlocked;
    }

    function retreat($id, Hexagon $hexagon)
    {
        /* @var  Unit $movingUnit */
        $movingUnit = $this->force->getUnit($id);
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $startHex = $this->force->units[$id]->hexagon;

        if ($this->rangeIsOneHexagon($movingUnit->getUnitHexagon()->name, $hexagon)
            && $this->hexagonBlocksRetreat($id, $startHex, $hexagon) === false
        ) {
            $this->force->addToRetreatHexagonList($id, $movingUnit->getUnitHexagon());
            // set move amount to 0
            $occupied = $mapData->hexagonIsOccupiedForce($hexagon, $movingUnit->forceId, $this->stacking, $movingUnit);
            $movingUnit->updateMoveStatus($hexagon, 0);

            // check crt retreat count required to how far the unit has retreated
            if ($this->force->unitHasMetRetreatCountRequired($id) && !$occupied) {
                // stop if unit has retreated the required amount
                if ($movingUnit->setStatus(STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                    $this->moves = new stdClass();
                }
            }
        }
    }

    function advanceUnit($eventType, $id, $hexagon)
    {
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving == true) {
                $hexagon = new Hexagon($hexagon);
                $this->advance($this->movingUnitId, $hexagon);
            }
        } else {
            if (($this->anyUnitIsMoving == true) && ($id == $this->movingUnitId)) {
                $this->endAdvancing($this->movingUnitId);
            } else {
                if ($this->force->unitCanAdvance($id) == true) {
                    $this->startAdvancing($id);
                }
            }
        }
    }

    function startAdvancing($id)
    {
        /* @var Hexagon $hexagon */
        $hexagon = $this->force->getFirstRetreatHex($id);
        $hexes = $this->force->getAllFirstRetreatHexes($id);
        foreach ($hexes as $hexagon) {
            $startHex = $hexagon->name;
            $hexPath = new HexPath();
            $hexPath->name = $startHex;
            $hexPath->pointsLeft = $this->force->units[$id]->getMaxMove();
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $this->moves->$startHex = $hexPath;
        }

        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_ADVANCING) == true) {
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
    }

    function advance($id, $hexagon)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($this->advanceIsValid($unit, $hexagon) == true) {
            // set move amount to 0

            $unit->updateMoveStatus($hexagon, 0);
            $this->stopAdvance($id);
        }
    }

    function stopAdvance($id)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_ADVANCED) == true) {
            $this->moves = new stdClass();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
            $this->force->resetNonFittingAdvancingUnits($unit);
        }
    }

    function endAdvancing($id)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_ADVANCED) == true) {
            $this->moves = new stdClass();
            $this->force->resetRemainingAdvancingUnits();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
        }
    }

    function advanceIsValid($unit, $hexagon)
    {
        $isValid = false;


        $startHexagon = $unit->getUnitHexagon();
        if ($this->force->advanceIsOnRetreatList($unit->id, $hexagon) == true && $this->rangeIsOneHexagon($startHexagon, $hexagon) == true) {
            $isValid = true;
        } else {
        }

        return $isValid;
    }
}
