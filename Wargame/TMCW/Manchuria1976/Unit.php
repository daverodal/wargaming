<?php
namespace Wargame\TMCW\Manchuria1976;
use Wargame\Battle;
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:19 AM
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
class Unit extends \Wargame\Unit implements \JsonSerializable
{
    public $saveMaxMove = false;
    public $railMode = false;

    public    function set($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $forceMarch, $class, $unitDesig){
        parent::set($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);
        if($this->class === 'gorilla'){
            $this->noZoc = true;
        }
    }


    public function railMove(bool $mode)
    {
        $b = Battle::getBattle();
        $turn = $b->gameRules->turn;
                if($this->railMode === false){
                    if($b->terrain->terrainIsHex($this->hexagon->name, 'rr') && $this->forceId === Manchuria1976::PRC_FORCE){
                        $this->saveMaxMove = $this->maxMove;
                        $this->maxMove = 30;
                        $this->forceMarch = true;
                        $this->saveClass = $this->class;
                        $this->class = "rr";
                        $this->railMode = true;
                        $b->moveRules->oneHex = false;
                        return;
                    }
                }else{
                    $b->moveRules->oneHex = true;
                    $this->recover();
                }
            $this->forceMarch = true;

    }

    function getReplacing( $hexagon)
    {
        if(parent::getReplacing($hexagon) !== false){
            $this->forceMarch = true;
            return $this->id;
        }
        return false;
    }
    public function recover(){
        if($this->railMode){
            $this->railMode = false;
            $this->maxMove = $this->saveMaxMove;
            $this->class = $this->saveClass;
            $this->saveMaxMove = $this->saveClass = false;
            $this->forceMarch = true;
        }
    }

}
