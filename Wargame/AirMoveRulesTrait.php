<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/12/17
 * Time: 1:56 PM
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


trait AirMoveRulesTrait
{
    function airMoves()
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

            $path = $hexPath->pathToHere;
            $path[] = $hexNum;


            $neighbors = $mapHex->neighbors;

            foreach ($neighbors as $neighbor) {
                $newHexNum = $neighbor;
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }

//                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
//                    continue;
//                }

                $newMapHex = $this->mapData->getHex($newHexNum);

                if ($newMapHex->isOccupied($this->force->defendingForceId, $this->enemyStackingLimit, $unit)) {
                    continue;
                }

                $moveAmount = 1;

                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if ($movePoints - $moveAmount >= 0 ) {
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

                    if ($newPath->pointsLeft < 0) {
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

    function airMove(MovableUnit $movingUnit, $hexagon)
    {
        if ($movingUnit->unitIsMoving()
            && $this->airMoveIsValid($movingUnit, $hexagon)
        ) {
            $this->updateAirMoveData($movingUnit, $hexagon);
        }

        return true;
    }

    function airMoveIsValid(MovableUnit $movingUnit, $hexagon, $startHex = false, $firstHex = false)
    {
        return true;
    }

    function limitedMoves()
    {

        $b = Battle::getBattle();
        if($this->moveQueue[0]->pointsLeft === 0){
            return;
        }
        $startY = [0, 21, 20, 20, 19, 19, 18, 18, 10, 10, 9, 9, 8, 8, 7, 7, 6, 6, 5, 5, 4, 4];
        $unit = $this->force->units[$this->movingUnitId];
        for ($x = 1; $x <= 21; $x++) {
            for ($y = $startY[$x]; $y <= 29; $y++) {
                $hexNum = sprintf("%02d%02d", $x, $y);

                $hexPath = new HexPath();
                $hexPath->name = $hexNum;
                $hexPath->pathToHere = [];
                $hexPath->pointsLeft = 0;
                /* @var MapHex $mapHex */
                $mapHex = $this->mapData->getHex($hexNum);
                if(in_array($hexNum, $b->specialHexB)){
                    $specialHexOwner = $this->mapData->getSpecialHex($hexNum);
                    if($specialHexOwner === 2){
                        continue;
                    }

                }

                if ($mapHex->isOccupied($this->force->attackingForceId, $this->stacking, $unit)) {
//                    $this->moves->$hexNum->isOccupied = true;
                    continue;
                }

                if ($mapHex->isOccupied($this->force->defendingForceId,$this->enemyStackingLimit, $unit)) {
//                    $this->moves->$hexNum->isValid = false;
                    continue;
                }
                $this->moves->$hexNum = $hexPath;


            }
        }
    }
    function unlimitedMoves(){
        $b = Battle::getBattle();
        if($this->moveQueue[0]->pointsLeft === 0){
            return;
        }
        $unit = $this->force->units[$this->movingUnitId];
        for($x = 1; $x <= 21; $x++){
            for($y=1;$y <= 29;$y++){
                $hexNum = sprintf("%02d%02d", $x, $y);

                $hexPath = new HexPath();
                $hexPath->name = $hexNum;
                $hexPath->pathToHere = [];
                $hexPath->pointsLeft = 0;

                /* @var MapHex $mapHex */
                $mapHex = $this->mapData->getHex($hexNum);
                if(in_array($hexNum, $b->specialHexB)){
                    $specialHexOwner = $this->mapData->getSpecialHex($hexNum);
                    if($specialHexOwner === 2){
                        continue;
                    }

                }

                if ($mapHex->isOccupied($this->force->attackingForceId, $this->stacking, $unit)) {
//                    $this->moves->$hexNum->isOccupied = true;
                    continue;
                }

                if ($mapHex->isOccupied($this->force->defendingForceId,$this->enemyStackingLimit, $unit)) {
//                    $this->moves->$hexNum->isValid = false;
                    continue;
                }
                $this->moves->$hexNum = $hexPath;



            }
        }


    }

    function updateAirMoveData(MovableUnit $movingUnit, $hexagon)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $fromHex = $movingUnit->hexagon;
        if($movingUnit->maxMove === 'U' || $movingUnit->maxMove === 'L'){
            $moveAmount = 0;
        }else{
            $moveAmount = 1;
        }
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

}