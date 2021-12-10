<?php
namespace Wargame\TMCW\Chawinda1965;

use stdClass;

// force.js

// Copyright (c) 20092011 Mark Butler
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
use Wargame\Battle;
use Wargame\MapData;

class Force extends \Wargame\Force
{

    public function getCombine()
    {

        $idMap = [];
        $numCombines = 0;
        $fId = $this->attackingForceId;
        $units = $this->units;
        foreach($units as $unit){
            if($unit->forceId === $fId){
                if(!empty($idMap[$unit->id])){
                    continue;
                }
                $inHex = $this->findSimilarInHex($unit);
                if($inHex && count($inHex) > 0){
                    $unit->status = STATUS_CAN_COMBINE;
                    $idMap[$inHex[0]->id] = true;
                    $this->units[$inHex[0]->id]->status = STATUS_CAN_COMBINE;
                    $numCombines++;
                }
            }
        }
        return $numCombines;
    }

    public function findSimilarInHex($unit)
    {
        $b = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $b->mapData;
        if($unit->isReduced !== true){
            return false;
        }
        $units = $mapData->getHex($unit->hexagon->name)->getForces($unit->forceId);
        $similarUnits = [];
        foreach($units as $k => $v){
            if($this->units[$k]->forceId === $unit->forceId){
                if( $this->units[$k]->class === $unit->class && $k != $unit->id){
                    if( $this->units[$k]->isReduced === true){
                        $similarUnits[] = $this->units[$k];
                    }
                }
            }
        }
        return $similarUnits;
    }
}