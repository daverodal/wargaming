<?php
namespace Wargame;
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:21 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class MovableUnit{
    public $id;
    public $forceId;
    public $name;
    /* @var Hexagon */
    public $hexagon;
    public $image;
    public $maxMove;
    public $status;
    public $moveAmountUsed;
    public $reinforceZone;
    public $reinforceTurn;

    public $nationality;
    public $forceMarch = false;
    public $class;
    public $dirty;
    public $unitDesig;
    public $moveAmountUnused;
    public $noZoc = false;

    use UnitAdjustment;

    /*
     * called when unit is eliminated
     */
    function resetUnit(){
        $this->forceMarch = false;
        $this->moveAmountUsed = 0;
        $this->removeAllAdjustments();
        $this->moveAmountUnused = $this->getMaxMove();
    }

    function unitHasMoveAmountAvailable($moveAmount)
    {
        if ($this->moveAmountUsed + $moveAmount <= $this->getMaxMove()) {
            $canMove = true;
        } else {
            $canMove = false;
        }
        return $canMove;
    }

    public function __call($name, $args){
        dd($name);
        echo $name;
        if(strpos($name, 'can') === 0){
            return false;
        }
        throw new Exception("Bad Call to Movable Unit $name ");
    }

    public function getMaxMove(){
        $maxMove = $this->maxMove;
        $maxMove = $this->getMovementAdjustments($maxMove);

        return $maxMove;
    }

    /* return filtered array of neighbor hexes, allows units to
     * modify their zoc's array. For example unit with  facing.
     * Or a unit with no ZOc's to return empty [];
     */
    public function getZocNeighbors($neighbors){
        if($this->noZoc === true){
            return [];
        }
        return $neighbors;
    }

    function unitHasNotMoved()
    {
        if ($this->moveAmountUsed == 0) {
            $hasMoved = true;
        } else {
            $hasMoved = false;
        }
        return $hasMoved;
    }

    function unitIsMoving()
    {
        $isMoving = false;
        if ($this->status == STATUS_MOVING) {
            $isMoving = true;
        }
        return $isMoving;
    }

    function unitHasUsedMoveAmount()
    {
        // moveRules amount used can be larger if can always moveRules at least one hexagon
        if ($this->moveAmountUsed >= $this->getMaxMove()) {
            $maxMove = true;
        } else {
            $maxMove = false;
        }
        return $maxMove;
    }

    function getUnitHexagon() : Hexagon
    {

        return $this->hexagon;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function railMove(bool $mode){

    }

    function updateFacingStatus($facing, $moveAmount)
    {

        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $attackingForceId = $battle->force->attackingForceId;
        /* @var MapHex $mapHex */
        $fromHex = $this->hexagon->getName();
        $toHex = $fromHex;
        $mapHex = $mapData->getHex($fromHex);
        if ($mapHex) {
            $mapHex->unsetUnit($this->forceId, $this->id);
        }

        $this->dirty = true;
        $this->facing += $facing;
        if($this->facing < 0){
            $this->facing += 6;
        }
        if($this->facing >= 6){
            $this->facing -= 6;
        }
        $mapData->breadcrumbMove($this->id, $attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $fromHex, $toHex);
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->moveCount++;
        $this->moveAmountUsed = $this->moveAmountUsed + $moveAmount;
    }

    function updateMoveStatus($hexagon, $moveAmount)
    {

        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $attackingForceId = $battle->force->attackingForceId;
//        $mapData = MapData::getInstance();
        /* @var MapHex $mapHex */
        $fromHex = $this->hexagon->getName();
        $toHex = $hexagon->getName();
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->unsetUnit($this->forceId, $this->id);
        }

        $this->hexagon = $hexagon;
        $this->dirty = true;
        $mapData->breadcrumbMove($this->id, $attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $fromHex, $toHex);
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
            $mapHexName = $mapHex->name;
            $mapData->fireTrigger($mapHexName, $this);
            if (isset($mapData->specialHexes->$mapHexName)) {

                if ($mapData->specialHexes->$mapHexName >= 0 && $mapData->specialHexes->$mapHexName != $this->forceId) {
                    $victory = $battle->victory;
                    $mapData->specialHexesChanges->$mapHexName = true;
                    $mapData->alterSpecialHex($mapHexName, $this->forceId);
                    $victory->specialHexChange($mapHexName, $this->forceId);
                }
            }
            if ($mapData->getMapSymbols($mapHexName) !== false) {
                $victory = $battle->victory;
                $victory->enterMapSymbol($mapHexName, $this);
            }
        }
        $this->moveCount++;
        $this->moveAmountUsed = $this->moveAmountUsed + $moveAmount;
    }

    function isDeploy(){
        return $this->hexagon->parent == "deployBox";
    }

    function isOnMap(){
       return $this->hexagon->parent == "gameImages";
    }

    function getReplacing( $hexagon)
    {
        if ($this->status == STATUS_REPLACING) {
            $hexagon = new Hexagon($hexagon);
            $this->status = STATUS_REPLACED;
            $this->updateMoveStatus($hexagon, 0);
            return $this->id;
        }
        return false;
    }

    function unitIsReinforcing()
    {
        if ($this->status == STATUS_REINFORCING) {
            $isReinforcing = true;
        } else {
            $isReinforcing = false;
        }
        return $isReinforcing;
    }

    function unitIsDeploying()
    {
        if ($this->status == STATUS_DEPLOYING) {
            $isDeploying = true;
        } else {
            $isDeploying = false;
        }
        return $isDeploying;
    }

    function unitIsLoading() :bool {
        if($this->status === STATUS_LOADING){
            return true;
        }
        return false;
    }

    function unitIsTransporting() : bool{
        if($this->status === STATUS_CAN_TRANSPORT){
            return true;
        }
        return false;
    }

    function unitIsUnloading() : bool{
        return $this->status === STATUS_UNLOADING;
    }

    function unitCanUnload(): bool{
        return $this->status ===STATUS_CAN_UNLOAD;
    }

    function unitCanLoad(): bool{
        return $this->status ===STATUS_CAN_LOAD;
    }

    function getUnitReinforceTurn()
    {
        return $this->reinforceTurn;
    }

    function getUnitReinforceZone()
    {
        return $this->reinforceZone;
    }

    function canBeTransported(){
        return false;
    }

    function canTransport(){
        return false;
    }

}
