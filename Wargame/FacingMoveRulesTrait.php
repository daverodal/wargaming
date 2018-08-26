<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/12/17
 * Time: 1:05 PM
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


trait FacingMoveRulesTrait
{
    function turnLeft($isDeploy = false){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            if($movingUnit->facing === false){
                return false;
            }
            $movesLeft = $movingUnit->maxMove - $movingUnit->moveAmountUsed;
            $turnCost = 1;
            $origFacing = $movingUnit->facing;
            if($isDeploy || $movesLeft >= $turnCost){


                if($isDeploy){
                    $movingUnit->updateFacingStatus(-1, 0);
                    return true;
                }
                $battle = Battle::getBattle();
                $mapHex = $battle->mapData->getHex($movingUnit->hexagon->name);
                if($movingUnit->hexagon->name === $this->turnHex){
                    if($this->rightOf($origFacing, $this->turnFacing)) {
                        $turnCost = 0 - $turnCost;
                    }
                    if($this->twoLeftOf($origFacing, $this->turnFacing)){
                        $turnCost =  0;
                    }
                    if($this->behind($origFacing, $this->turnFacing)){
                        $turnCost =  0;
                    }
                }

                $movingUnit->updateFacingStatus(-1, $turnCost);
                if($this->turnHex === false || $movingUnit->hexagon->name !== $this->turnHex || $movingUnit->id !== $this->turnId){
                    $this->turnHex = $movingUnit->hexagon->name;
                    $this->turnFacing = $origFacing;
                    $this->turnId = $movingUnit->id;
                }

                if ($mapHex->isZoc($this->force->defendingForceId) == true) {
                    if ($this->enterZoc === "stop") {
                        $this->stopMove($movingUnit);
                        return true;
                    }
                }
                if($movingUnit->moveAmountUsed >= $movingUnit->maxMove){
                    $this->stopMove($movingUnit);
                    return true;
                }
                $this->calcMove($this->movingUnitId, $movingUnit->moveAmountUsed === 0  );
                return true;
            }
            return false;
        }
        return false;
    }

    function turnAbout($isDeploy = false){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            if($movingUnit->facing === false){
                return false;
            }
            $movesLeft = $movingUnit->maxMove - $movingUnit->moveAmountUsed;
            $origFacing = $movingUnit->facing;

            $turnCost = 2;
            if($movingUnit->hexagon->name === $this->turnHex){
                if($this->rightOf($origFacing, $this->turnFacing) || $this->leftOf($origFacing, $this->turnFacing)) {
                    $turnCost = 1;
                }
                if($this->twoLeftOf($origFacing, $this->turnFacing) || $this->twoRightOf($origFacing, $this->turnFacing)){
                    $turnCost =  -1;
                }
                if($this->behind($origFacing, $this->turnFacing)){
                    $turnCost =  -2;
                }
            }

            if($isDeploy || $movesLeft >= $turnCost){

                if($isDeploy){
                    $movingUnit->updateFacingStatus(3, 0);

                    return true;
                }
                $battle = Battle::getBattle();
                $mapHex = $battle->mapData->getHex($movingUnit->hexagon->name);

                $movingUnit->updateFacingStatus(3, $turnCost);
                if($this->turnHex === false || $movingUnit->hexagon->name !== $this->turnHex || $movingUnit->id !== $this->turnId){
                    $this->turnHex = $movingUnit->hexagon->name;
                    $this->turnFacing = $origFacing;
                    $this->turnId = $movingUnit->id;
                }



                if ($mapHex->isZoc($this->force->defendingForceId) == true) {
                    if ($this->enterZoc === "stop") {
                        $this->stopMove($movingUnit);
                        return true;
                    }
                }
                if($movingUnit->moveAmountUsed >= $movingUnit->maxMove){
                    $this->stopMove($movingUnit);
                    return true;
                }
                $this->calcMove($this->movingUnitId, $movingUnit->moveAmountUsed === 0 );
                return true;
            }
            return false;
        }
        return false;

    }

    function leftOf($newCourse, $prevCourse){

        if(($newCourse + 1) % 6 === $prevCourse ){
            return true;
        }
        if(($newCourse + 2) % 6 === $prevCourse ){
            return true;
        }
        return false;
    }

    function rightOf($newCourse, $prevCourse){

        if(($prevCourse + 1) % 6 === $newCourse ){
            return true;
        }
        if(($prevCourse + 2) % 6 === $newCourse ){
            return true;
        }
        return false;
    }

    function twoLeftOf($newCourse, $prevCourse){

        if(($newCourse + 2) % 6 === $prevCourse ){
            return true;
        }

        return false;
    }

    function twoRightOf($newCourse, $prevCourse){
        if(($prevCourse + 2) % 6 === $newCourse ){
            return true;
        }
        return false;
    }

    function behind($newCourse, $prevCourse){

        if(($newCourse + 3) % 6 === $prevCourse ){
            return true;
        }

        return false;
    }

    function turnRight($isDeploy = false){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            if($movingUnit->facing === false){
                return false;
            }
            $movesLeft = $movingUnit->maxMove - $movingUnit->moveAmountUsed;
            $origFacing = $movingUnit->facing;
            $turnCost = 1;
            if($isDeploy || $movesLeft >= $turnCost){

                if($isDeploy){
                    $movingUnit->updateFacingStatus(1, 0);
                    return true;
                }

                if($movingUnit->hexagon->name === $this->turnHex){
                    if($this->leftOf($origFacing, $this->turnFacing)) {
                        $turnCost = 0 - $turnCost;
                    }
                    if($this->twoRightOf($origFacing, $this->turnFacing)){
                        $turnCost =  0;
                    }
                    if($this->behind($origFacing, $this->turnFacing)){
                        $turnCost =  0;
                    }
                }
                $movingUnit->updateFacingStatus(1, $turnCost);
                if($this->turnHex === false || $movingUnit->hexagon->name !== $this->turnHex || $movingUnit->id !== $this->turnId){
                    $this->turnHex = $movingUnit->hexagon->name;
                    $this->turnFacing = $origFacing;
                    $this->turnId = $movingUnit->id;
                }

                $battle = Battle::getBattle();
                $mapHex = $battle->mapData->getHex($movingUnit->hexagon->name);
                if ($mapHex->isZoc($this->force->defendingForceId) == true) {
                    if ($this->enterZoc === "stop") {
                        $this->stopMove($movingUnit);
                        return true;
                    }
                }
                if($movingUnit->moveAmountUsed >= $movingUnit->maxMove){
                    $this->stopMove($movingUnit);
                    return true;
                }
                $this->calcMove($this->movingUnitId, $movingUnit->moveAmountUsed === 0 );
                return true;
            }
            return false;
        }
        return false;

    }
}