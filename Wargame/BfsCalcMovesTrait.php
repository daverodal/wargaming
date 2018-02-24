<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/12/17
 * Time: 1:22 PM
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


trait BfsCalcMovesTrait
{
    function calcSupplyHex($startHex, $goal, $bias = array(), $attackingForceId = false, $maxHex = false)
    {
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = true;
        $hexPath->isOccupied = true;
        if($maxHex !== false){
            $hexPath->pointsLeft = $maxHex;
            $maxHex = true;
        }
        $this->moveQueue[] = $hexPath;
        $ret = $this->bfsCommunication($goal, $bias, $attackingForceId, $maxHex);
        $this->moves = new stdClass();
        $this->moveQueue = array();
        return $ret;
    }

    function calcRoadSupply($forceId, $goal, $bias = array())
    {
        $attackingForceId = $forceId;

        $this->moves = new stdClass();
        $this->moveQueue = array();
        if(!is_array($goal)){
            $goals = [$goal];
        }else{
            $goals = $goal;
        }
        foreach($goals as $aGoal){
            $hexPath = new HexPath();
            $hexPath->name = $aGoal;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $hexPath->isOccupied = true;
            $this->moveQueue[] = $hexPath;
        }
        $ret = $this->bfsRoadTrace($goal, $bias, $attackingForceId);
        $moves = $this->moves;
        $goal = [];
        foreach($moves as $hex => $move){
            $goal[] = $hex;
        }
        $this->moves = new stdClass();
        $this->moveQueue = array();
        return $goal;
    }
    function calcSupply($id, $goal, $bias = array(), $maxHex = false)
    {
        global $numWalks;
        global $numBangs;
        $attackingForceId = $this->force->units[$id]->forceId;
        $startHex = $this->force->units[$id]->hexagon;
        return $this->calcSupplyHex($startHex->name, $goal, $bias, $attackingForceId, $maxHex);
    }

    function calcMove($id, $firstHex = true)
    {
        global $numWalks;
        global $numBangs;
        $unit = $this->force->units[$id];
        $numWalks = 0;
        $numBangs = 0;
        $startHex = $unit->hexagon;
        $movesLeft = $unit->getMaxMove() - $unit->moveAmountUsed;
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex->name;
        $hexPath->pointsLeft = $movesLeft;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = $firstHex;
        $hexPath->isOccupied = true;
        if(isset($this->force->units[$id]->facing)){
            $hexPath->facing = $this->force->units[$id]->facing;
        }
        $this->moveQueue[] = $hexPath;
        $this->bfsMoves();

    }

    function calcRetreat($id)
    {
        global $numWalks;
        global $numBangs;
        $numWalks = 0;
        $numBangs = 0;
        $done = false;
        $startHex = $this->force->units[$id]->hexagon;
        $movesLeft = $this->force->units[$id]->retreatCountRequired;
        do{
            $this->moves = new stdClass();
            $this->moveQueue = array();
            $hexPath = new HexPath();
            $hexPath->name = $startHex->name;
            $hexPath->pointsLeft = $movesLeft;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $hexPath->isOccupied = true;
            $this->moveQueue[] = $hexPath;
            $this->bfsRetreat();
            $moves = $this->moves;
            $validCount = 0;
            foreach($moves as $key => $val){
                if($moves->$key->pointsLeft){
                    unset($moves->$key);
                    continue;
                }
                if($moves->$key->isOccupied === false){
                    $validCount++;
                }
            }
            /* no possible retreats */
            if(count((array)$this->moves) === 0){
                $this->force->addToRetreatHexagonList($id, $startHex);
                $this->movingUnitId = NONE;
                $this->anyUnitIsMoving = false;
                $this->moves = new stdClass();
                if($this->blockedRetreatDamages){
                    if($this->force->units[$id]->damageUnit()) {
                        $this->force->eliminateUnit($id);
                    }else{
                        $this->force->units[$id]->setStatus(STATUS_STOPPED);
                        $this->force->clearAdvancing();
                    }
                }else{
                    $this->force->eliminateUnit($id);
                }

                $done = true;
                return;
            }

            if($validCount > 0){
                $done = true;
            }else{
                $movesLeft++;
                /* fail safe for strange things */
                if($this->retreatCannotOverstack || $movesLeft > 12){
                    $this->force->addToRetreatHexagonList($id, $startHex);
                    $this->movingUnitId = NONE;
                    $this->anyUnitIsMoving = false;
                    $this->moves = new stdClass();
                    if($this->blockedRetreatDamages){
                        if($this->force->units[$id]->damageUnit()) {
                            $this->force->eliminateUnit($id);
                        }else{
                            $this->force->units[$id]->setStatus(STATUS_STOPPED);
                            $this->force->clearAdvancing();
                        }
                    }else{
                        $this->force->eliminateUnit($id);
                    }
                    $done = true;
                }
            }

        }while($done === false);


    }

    function calcNeighbors($oldNeighbors, $hexPath){
        /*
         * Front 3 hexes kept just in case game designer chnages his mind.
         * $neighbors = array_slice(array_merge($mapHex->neighbors,$mapHex->neighbors), ($hexPath->facing + 6 - 1)%6, 3);
         */

        /*
         * Just the front facing hex
         */
        $frontHexNum = $oldNeighbors[ $hexPath->facing];
        $neighbors = [];
        $obj = new stdClass();
        $obj->hexNum = $frontHexNum;
        $obj->facing = $hexPath->facing;
        $neighbors[] = $obj;
        foreach($oldNeighbors as $oldFacing => $oldNeighbor){
            if($oldNeighbor == $frontHexNum){
                continue;
            }
            if ($this->terrain->terrainIsHexSideOnly($hexPath->name, $oldNeighbor,  "trail")) {
                $obj = new stdClass();
                $obj->hexNum = $oldNeighbor;
                $obj->facing = $oldFacing;
                $neighbors[] = $obj;
            }
        }
        $backupHexNum = $behind = null;
        /* first hex can do backup move */
        if($hexPath->firstHex === true){
            $behind = $hexPath->facing + 3;
            $behind %= 6;
            $backupHexNum = $oldNeighbors[$behind];
            $obj = new stdClass();
            $obj->hexNum = $backupHexNum;
            $obj->facing = $hexPath->facing;
            $neighbors[] = $obj;
        }

        return [$neighbors, $backupHexNum, $behind];
    }

    function bfsMoves()
    {
        $hist = array();
        $cnt = 0;
        $unit = $this->force->units[$this->movingUnitId];

        while (count($this->moveQueue) > 0) {
            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            $movePoints = $hexPath->pointsLeft;
            if (!$hexNum) {
                continue;
            }
            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;
            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* already been here with more points */
                if ($this->moves->$hexNum->pointsLeft >= $movePoints) {
                    continue;

                }
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($this->force->attackingForceId, $this->stacking, $unit)) {
                $this->moves->$hexNum->isOccupied = true;
            }

            if ($mapHex->isOccupied($this->force->defendingForceId,$this->enemyStackingLimit, $unit)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex);
            }
            $exitCost = 0;
            if ($this->moves->$hexNum->isZoc) {
                if (is_numeric($this->exitZoc)) {
                    $exitCost += $this->exitZoc;
                }
                if (!$hexPath->firstHex) {
                    if ($this->enterZoc === "stop") {
                        continue;
                    }
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            $neighbors = $mapHex->neighbors;
            $backupHexNum = false;
            $behind = false;
            if(isset($hexPath->facing)){
                list($neighbors, $backupHexNum, $behind) = $this->calcNeighbors($neighbors, $hexPath);
            }
            $curHex = Hexagon::getHexPartXY($hexNum);

            foreach ($neighbors as $neighbor) {
                $newHexNum = $neighbor;
                if(is_object($neighbor)){
                    $newHexNum = $neighbor->hexNum;
                    $newFacing = $neighbor->facing;

                }
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }

                /* This can and should be dealt with by the "blocked" moveAmount below
                 * History, can't live with it can't live with it
                 */
                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
                    continue;
                }
                if (!$unit->forceMarch && $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocksnonroad")) {
                    continue;
                }
                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {

                    continue;
                }
                $moveAmount = $this->terrain->getTerrainMoveCostXY($curHex[0], $curHex[1], $gnuHex[0], $gnuHex[1], $unit->forceMarch, $unit);
                if ($moveAmount === "blocked") {
                    continue;
                }
                $moveAmount += $exitCost;
                $newMapHex = $this->mapData->getHex($newHexNum);

                if ($newMapHex->isOccupied($this->force->defendingForceId, $this->enemyStackingLimit, $unit)) {
                    continue;
                }

                if ($this->moveCannotOverstack  && $newMapHex->isOccupied($this->force->attackingForceId, $this->transitStacking, $unit)) {
                    continue;
                }

                $isZoc = $this->force->mapHexIsZOC($newMapHex);
                if($isZoc && $this->noZoc){
                    continue;
                }
                if ($isZoc && is_numeric($this->enterZoc)) {
                    $moveAmount += (int)$this->enterZoc;
                }
                if ($moveAmount <= 0) {
                    $moveAmount = 1;
                }
                if ($this->noZocZoc && $isZoc && $hexPath->isZoc) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($moveAmount <= $movePoints && $behind !== false && $newHexNum === $backupHexNum){
                    $moveAmount = $movePoints;
                }
                if ($movePoints - $moveAmount >= 0 || (($isZoc && $hexPath->isZoc && !$this->noZocZocOneHex) && $hexPath->firstHex === true) || ($hexPath->firstHex === true && $this->oneHex === true && !($isZoc && $hexPath->isZoc && !$this->noZocZoc))) {
                    $head = false;
                    if (isset($this->moves->$newHexNum)) {
                        if ($this->moves->$newHexNum->pointsLeft > ($movePoints - $moveAmount)) {
                            continue;
                        }
                        $head = true;
                    }
                    $newPath = new HexPath();
                    $newPath->name = $newHexNum;
                    $newPath->pathToHere = $path;
                    $newPath->pointsLeft = $movePoints - $moveAmount;

                    if(isset($newFacing)){
                        $newPath->facing = $newFacing;
                    }

                    if ($newPath->pointsLeft < 0) {
                        $newPath->pointsLeft = 0;
                    }
                    if ($this->exitZoc === "stop" && $hexPath->isZoc) {
                        $newPath->pointsLeft = 0;
                    }
                    if ($head) {
                        array_unshift($this->moveQueue, $newPath);
                    } else {
                        $this->moveQueue[] = $newPath;

                    }
                }
            }

        }
        return;
    }

    function bfsRetreat()
    {

        $id = $this->movingUnitId;
        $unit = $this->force->units[$this->movingUnitId];

        if($unit->forceId == $this->force->attackingForceId){
            /* attacker retreating leave sides normal */
            $attackingForceId = $this->force->attackingForceId;
            $defendingForceId = $this->force->defendingForceId;
        }else{
            /* Reverse attack and defender for defender retreats (retreating units are moving) */
            /* Reverse attack and defender for defender retreats (retreating units are moving) */
            $defendingForceId = $this->force->attackingForceId;
            $attackingForceId = $this->force->defendingForceId;
        }


        $cnt = 0;
        while (count($this->moveQueue) > 0) {


            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            $movePoints = $hexPath->pointsLeft;

            if (!$hexNum) {
                continue;
            }

            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId, $this->stacking, $unit)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId,$this->enemyStackingLimit, $unit)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

//            if ($this->moves->$hexNum->isZoc == NULL) {
//                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
//            }
//            if ((!$hexPath->firstHex) && $this->moves->$hexNum->isZoc) {
//                continue;
//            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                if($this->hexagonBlocksRetreat($id, new Hexagon($hexNum), new Hexagon($newHexNum))){
                    continue;
                }
//                $newMapHex = $this->mapData->getHex($newHexNum);
//
//                if ($this->force->mapHexIsZOC($newMapHex, $defendingForceId)){
//                    continue;
//                }
//
//                $gnuHex = Hexagon::getHexPartXY($newHexNum);
//                if (!$gnuHex) {
//                    continue;
//                }
//                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
//                    continue;
//                }
//
//                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
//                    continue;
//                }
//                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "blocked")) {
//                    continue;
//                }
//                $newMapHex = $this->mapData->getHex($newHexNum);
//                if ($newMapHex->isOccupied($defendingForceId)) {
//                    continue;
//                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($movePoints - 1 < 0){
                    continue;
                }
                $head = false;

                if (isset($this->moves->$newHexNum)) {
                    if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                        continue;
                    }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                $newPath->pointsLeft = $movePoints - 1;
                $this->moveQueue[] = $newPath;
            }
        }
        return false;
    }

    function bfsCommunication($goal, $bias, $attackingForceId = false, $maxHex = false)
    {
        $goalArray = array();
        if (is_array($goal)) {
            foreach ($goal as $key => $val) {
                $goalArray[$val] = true;
            }
        } else {
            $goalArray[$goal] = true;
        }
        if ($attackingForceId !== false) {
            $defendingForceId = $this->force->Enemy($attackingForceId);
        } else {
            $attackingForceId = $this->force->attackingForceId;
            $defendingForceId = $this->force->defendingForceId;
        }

        $cnt = 0;
        while (count($this->moveQueue) > 0) {

            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            if($maxHex !== false){
                $movePoints = $hexPath->pointsLeft;
            }
            if (!$hexNum) {
                continue;
            }
            if (!empty($goalArray[$hexNum])) {
                return true;
            }
            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            if($maxHex !== false){
                $this->moves->$hexNum->pointsLeft = $movePoints;
            }
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
            }
            if ($this->moves->$hexNum->isZoc) {
                if (!$this->moves->$hexNum->isOccupied) {
                    continue;
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }
                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
                    continue;
                }

                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
                    continue;
                }
                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "blocked")) {
                    continue;
                }
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($defendingForceId)) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($maxHex !== false && $movePoints - 1 < 0){
                    continue;
                }
                $head = false;
                if (!empty($bias[$i])) {
                    $head = true;
                }
                if (isset($this->moves->$newHexNum)) {
                    if($maxHex !== false){
                        if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                if($maxHex !== false){
                    $newPath->pointsLeft = $movePoints - 1;
                }
                if ($head) {
                    array_unshift($this->moveQueue, $newPath);
                } else {
                    $this->moveQueue[] = $newPath;

                }
            }
        }
        return false;
    }

    function bfsRoadTrace($goal, $bias, $attackingForceId = false, $maxHex = false)
    {
        $goalArray = array();
        if (is_array($goal)) {
            foreach ($goal as $key => $val) {
                $goalArray[$val] = true;
            }
        } else {
            $goalArray[$goal] = true;
        }
        if ($attackingForceId !== false) {
            $defendingForceId = $this->force->Enemy($attackingForceId);
        } else {
            $attackingForceId = $this->force->attackingForceId;
            $defendingForceId = $this->force->defendingForceId;
        }

        $cnt = 0;
        while (count($this->moveQueue) > 0) {

            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            if($maxHex !== false){
                $movePoints = $hexPath->pointsLeft;
            }
            if (!$hexNum) {
                continue;
            }

            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            if($maxHex !== false){
                $this->moves->$hexNum->pointsLeft = $movePoints;
            }
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
            }
            if ($this->moves->$hexNum->isZoc) {
                if (!$this->moves->$hexNum->isOccupied) {
                    unset($this->moves->$hexNum);
//                    $this->moves->$hexNum->isValid = false;
                    continue;
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }
                if (!($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "road") || $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "trail")
                    || $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "secondaryroad"))) {
                    continue;
                }

                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
                    continue;
                }
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($defendingForceId)) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($maxHex !== false && $movePoints - 1 < 0){
                    continue;
                }
                $head = false;
                if (!empty($bias[$i])) {
                    $head = true;
                }
                if (isset($this->moves->$newHexNum)) {
                    if($maxHex !== false){
                        if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                if($maxHex !== false){
                    $newPath->pointsLeft = $movePoints - 1;
                }
                if ($head) {
                    array_unshift($this->moveQueue, $newPath);
                } else {
                    $this->moveQueue[] = $newPath;

                }
            }
        }
        return false;
    }
}