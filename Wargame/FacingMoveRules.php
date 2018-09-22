<?php
namespace Wargame;
use stdClass;
use Wargame\Medieval\MedievalUnit;
// FacingMoveRules.php
/*
Copyright 2012-2017 David Rodal

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

class FacingMoveRules extends MoveRules
{
    use FacingMoveRulesTrait;


    function calcNeighbors($oldNeighbors, $hexPath){


        /*
         * Just the front facing hex
         */
        $frontHexNum = $oldNeighbors[ $hexPath->facing];
        $neighbors = [];
        $obj = new stdClass();
        $obj->hexNum = $frontHexNum;
        $obj->facing = $hexPath->facing;
        $neighbors[] = $obj;
        $unitId = $this->movingUnitId;
        $unit = $this->force->units[$unitId];
        if($unit->orgStatus === MedievalUnit::HEDGE_HOG_MODE){
            return [$neighbors, false, false];
        }
        $backupHexNum = $behind = null;


        if($unit->forceMarch) {
            foreach ($oldNeighbors as $oldFacing => $oldNeighbor) {
                if ($oldNeighbor == $frontHexNum) {
                    continue;
                }
                if ($this->terrain->terrainIsHexSideOnly($hexPath->name, $oldNeighbor, "trail")) {
                    $obj = new stdClass();
                    $obj->hexNum = $oldNeighbor;
                    $obj->facing = $oldFacing;
                    $neighbors[] = $obj;
                }
            }
        }else{
            if($hexPath->firstHex === true){
                /* first hex can do backup move */
                $behind = $hexPath->facing + 3;
                $behind %= 6;
                $backupHexNum = $oldNeighbors[$behind];
                $obj = new stdClass();
                $obj->hexNum = $backupHexNum;
                $obj->facing = $hexPath->facing;
                $neighbors[] = $obj;
            }
        }

        return [$neighbors, $backupHexNum, $behind];
    }
}
