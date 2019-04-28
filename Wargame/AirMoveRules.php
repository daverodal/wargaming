<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/12/17
 * Time: 2:12 PM
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

class AirMoveRules extends MoveRules
{
    use AirMoveRulesTrait;

    function moveUnit($eventType, $id, $hexagon, $turn)
    {
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving) {
                // click on map, so try to move
                /* @var Unit $movingUnit */
                $movingUnit = $this->force->units[$this->movingUnitId];
                if ($movingUnit->unitIsMoving() == true) {
                    $newHex = $hexagon;

                    if ($movingUnit instanceof \Wargame\AirMovement) {
                        $ret = $this->airMove($movingUnit, $newHex);
                        if ($ret) {
                            $this->stopMove($movingUnit);
                            return true;
                        }
                        return false;
                    }
                }
            }
        }
        return parent::moveUnit($eventType, $id, $hexagon, $turn);
    }

    function bfsMoves(){
        $hist = array();
        $cnt = 0;
        $unit = $this->force->units[$this->movingUnitId];
        if($unit instanceof AirMovement){
            if($unit->maxMove === 'U'){
                $this->unlimitedMoves();
                return;
            }
            if($unit->maxMove === 'L'){
                $this->limitedMoves();
                return;
            }

        }
        if($unit->class === 'air'){
            return $this->airBfsMoves();
        }
        return parent::bfsMoves();

    }

}