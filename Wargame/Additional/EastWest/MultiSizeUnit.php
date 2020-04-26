<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/14/17
 * Time: 11:03 AM
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

namespace Wargame\Additional\EastWest;

use Wargame\{Hexagon, Battle};
use stdClass;

class MultiSizeUnit extends MultiStepUnit
{
    public $integrity = false;
    public $integrityColor;
    function updateMoveStatus($hexagon, $moveAmount)
    {
        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        /* @var \Wargame\Force $force */
        $force = $battle->force;
        /* @var \Wargame\MapData $mapData */
        $mapData = $battle->mapData;
        $attackingForceId = $battle->force->attackingForceId;
//        $mapData = MapData::getInstance();
        /* @var MapHex $mapHex */
        $fromHex = $this->hexagon->getName();
        $toHex = $hexagon->getName();
        $fromMapHex = $mapData->getHex($fromHex);
        $unitIds = $fromMapHex->getForces($this->forceId);
        $corps = preg_replace("~([^/]*)/.*$~", "$1", $this->unitDesig);
        $divisionCount = 0;
        $unitsHere = [];
        foreach($unitIds as $unitId){
            $unit = $force->getUnit($unitId);
            $unitsHere[] = $unit;
            if($corps === preg_replace("~([^/]*)/.*$~", "$1", $unit->unitDesig)){
                $divisionCount++;
            }
        }
        if($divisionCount === 3){
            $this->clearIntegrities($unitsHere);
        }
        parent::updateMoveStatus($hexagon, $moveAmount);


        $fromHex = $this->hexagon->getName();
        $fromMapHex = $mapData->getHex($fromHex);
        $unitIds = $fromMapHex->getForces($this->forceId);
        $corps = preg_replace("~([^/]*)/.*$~", "$1", $this->unitDesig);
        $divisionCount = 0;
        $unitsHere = [];
        foreach($unitIds as $unitId){
            $unit = $force->getUnit($unitId);
            $unitsHere[] = $unit;
            if($corps === preg_replace("~([^/]*)/.*$~", "$1", $unit->unitDesig)){
                $divisionCount++;
            }
        }
        if($divisionCount === 3){
            $this->setIntegrities($unitsHere);
        }

    }

    public function clearIntegrities($units){
        foreach($units as $unit){
            $unit->clearIntegrity();
        }
    }

    public function clearIntegrity(){
        $this->integrity = false;
    }

    public function setIntegrities($units){
        foreach($units as $unit){
            $unit->setIntegrity();
        }
    }

    public function setIntegrity(){
        $this->integrity = true;
    }

    public function fetchData(){
        $ret = parent::fetchData();
        $ret->integrity = $this->integrity;
        $ret->integrityColor = $this->integrityColor;
        return $ret;
    }

    public function postProcess(){
        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        /* @var \Wargame\Force $force */
        $force = $battle->force;
        /* @var \Wargame\MapData $mapData */
        $mapData = $battle->mapData;


        $fromHex = $this->hexagon->getName();
        $fromMapHex = $mapData->getHex($fromHex);
        $unitIds = $fromMapHex->getForces($this->forceId);
        $corps = preg_replace("~([^/]*)/.*$~", "$1", $this->unitDesig);
        $divisionCount = 0;
        $unitsHere = [];
        foreach($unitIds as $unitId){
            $unit = $force->getUnit($unitId);
            $unitsHere[] = $unit;
            if($corps === preg_replace("~([^/]*)/.*$~", "$1", $unit->unitDesig)){
                $divisionCount++;
            }
        }
        if($divisionCount === 3){
            $this->setIntegrities($unitsHere);
        }

    }
}
