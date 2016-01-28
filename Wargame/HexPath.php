<?php
namespace Wargame;
/**
 * Copyright 2016 David Rodal
 * User: David Markarian Rodal
 * Date: 1/27/16
 * Time: 10:35 PM
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
class HexPath implements \JsonSerializable{
    public $name = false;
    public $pointsLeft = false;
    public $isZoc = false;
    public $isValid = true;
    public $isOccupied = false;
    public $pathToHere = array();
    public $depth = false;
    public $firstHex = false;
    public function jsonSerialize(){
        unset($this->isZoc);
        unset($this->isValid);
//        unset($this->isOccupied);
        unset($this->name);
        unset($this->depth);
        unset($this->firstHex);
        return $this;
    }

}