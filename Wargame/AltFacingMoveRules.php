<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/25/18
 * Time: 10:46 AM
 *
 * /*
 * Copyright 2012-2018 David Rodal
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


class AltFacingMoveRules extends FacingMoveRules
{
    use AltFacingMoveRulesTrait;

    function calcNeighbors($oldNeighbors, $hexPath){
        /*
         * Front 3 hexes kept just in case game designer chnages his mind.
         * $neighbors = array_slice(array_merge($mapHex->neighbors,$mapHex->neighbors), ($hexPath->facing + 6 - 1)%6, 3);
         */

        /*
         * Just the front facing hex
         */
        $neighbors = [];

        if(isset($hexPath->firstPath ) && $hexPath->firstPath && isset($hexPath->facing)){
            $neighborsNums = array_slice(array_merge($oldNeighbors,$oldNeighbors), ($hexPath->facing + 6 - 1)%6, 3);
            $newFacing = ($hexPath->facing + 6 - 1);
            foreach($neighborsNums as $neighborNum){
                $obj = new stdClass();
                $obj->hexNum = $neighborNum;
                $obj->facing = ($newFacing++) % 6;
                $neighbors[] = $obj;
            }

        }else{
            $obj = new stdClass();
            $obj->hexNum = $oldNeighbors[$hexPath->facing];
            $obj->facing = $hexPath->facing;
            $neighbors[] = $obj;

        }

        $backupHexNum = $behind = null;
        /* first hex can do backup move */


        return [$neighbors, $backupHexNum, $behind];
    }

}