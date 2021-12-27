<?php
namespace Wargame;

use stdClass;
use Wargame\Additional\EastWest\EastWest;

class EastWestForce extends Force {
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
            }
        }
        return;
    }

    public function findSimilarInHex($unit)
    {
        $b = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $b->mapData;
        $units = $mapData->getHex($unit->hexagon->name)->getForces($unit->forceId);

        if($unit->forceId === EastWest::SOVIET_FORCE){
            if($unit->name != "xxx"){
                return false;
            }
            $infStack = true;
            $armorStack = true;
            $hasMech = false;
            if($unit->class === 'inf'){
                $armorStack = false;
            }
            if($unit->class === 'armor'){
                $infStack = false;
            }
            if($unit->class === 'mech'){
                $hasMech = true;
            }
            $unitCount = 0;
            foreach($units as $k => $v){
                $otherUnit = $this->units[$k];
                if($otherUnit->forceId === $unit->forceId){
                    if($otherUnit->name === "xxx") {
                        if ($k != $unit->id) {
                            $unitCount++;
                            if ($otherUnit->class === 'inf') {
                                $armorStack = false;
                            }
                            if ($otherUnit->class === 'armor') {
                                $infStack = false;
                            }
                            if ($otherUnit->class === 'mech') {
                                $hasMech = true;
                            }
                        }
                    }
                }
            }

            if($infStack && $hasMech && $unitCount >= 3){
                $unit->combine($units, 'inf');
            }
            if($armorStack && $hasMech && $unitCount >= 3){
                $unit->combine($units, 'armor');
            }
            return ($infStack || $armorStack) && $hasMech;
        }
        $similarUnits = [];
        if($unit->forceId === EastWest::GERMAN_FORCE) {
            $required = 2;
            if($unit->maxMove === 2){
                $required = 1;
            }
            $infStack = true;
            $armorStack = true;
            if($unit->class === 'inf'){
                $armorStack = false;
            }
            if($unit->class === 'armor'){
                $infStack = false;
            }
            $unitCount = 0;
            foreach($units as $k => $v){
                $otherUnit = $this->units[$k];
                if($otherUnit->forceId === $unit->forceId){
                    if($otherUnit->name === "xxx" && $unit->maxMove === $otherUnit->maxMove) {
                        if ($k != $unit->id) {
                            $unitCount++;
                            if ($otherUnit->class === 'inf') {
                                $armorStack = false;
                            }
                            if ($otherUnit->class === 'armor') {
                                $infStack = false;
                            }
                            $similarUnits[] = $k;
                        }
                    }
                }
            }
            if(($armorStack || $infStack) &&  $unitCount >= $required){
                return $similarUnits;
            }
            return [];
        }
    }
}

