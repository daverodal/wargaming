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

$numWalks = 0;
class SimpleBBMoveRules extends NavalMoveRules
{
        public int $spottedRange = 24;


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

    function updateMoveData(MovableUnit $movingUnit, $hexagon)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $fromHex = $movingUnit->hexagon;
        $moveAmount = $this->terrain->getTerrainMoveCost($movingUnit->getUnitHexagon()->name, $hexagon, $movingUnit->forceMarch, $movingUnit);
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex($hexagon);
        $fromMapHex = $mapData->getHex($fromHex->name);

        $movingUnit->updateMoveStatus(new Hexagon($hexagon), $moveAmount);

        if ($movingUnit->unitHasUsedMoveAmount() == true) {
            $this->stopMove($movingUnit);
        }

        foreach($this->force->units as $unit){
            if($movingUnit->forceId === $unit->forceId){
                continue;
            }
            if($unit->hexagon->parent !== 'gameImages'){
                continue;
            }
            if($this->inRange($movingUnit->hexagon->name, $unit->hexagon->name, $this->spottedRange)){
                $movingUnit->spotted = true;
                $unit->spotted = true;
            }
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($movingUnit->id);
        }
    }
}
