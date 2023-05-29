<?php
namespace Wargame;

use stdClass;

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

    public function findSimilarGerman($unit){
        $b = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $b->mapData;
        $units = $mapData->getHex($unit->hexagon->name)->getForces($unit->forceId);
        $similarUnits = [];
        $infStack = true;
        $armorStack = true;
        $hasAlly = false;
        if($unit->class === 'inf'){
            $armorStack = false;
            if($unit->maxMove === 2) {
                $hasAlly = true;
            }
        }
        if($unit->class === 'armor'){
            $infStack = false;
        }
        $unitCount = 0;
        foreach($units as $k => $v){
            $otherUnit = $this->units[$k];
            if($otherUnit->forceId === $unit->forceId){
                if( $k != $unit->id){
                    $unitCount++;
                    if($otherUnit->class === 'inf'){
                        $armorStack = false;
                        if($unit->maxMove === 2) {
                            $hasAlly = true;
                        }
                    }
                    if($otherUnit->class === 'armor'){
                        $infStack = false;
                    }
                }

            }
        }
        if($infStack && $unitCount === 2){
            $unit->combine($units, 'inf');
        }
        if($armorStack && $unitCount === 2){
            $unit->combine($units, 'armor');
        }
        return ($infStack || $armorStack);
    }

    public function findSimilarInHex($unit)
    {
        if($unit->forceId === \Wargame\Additional\EastWest\EastWest::GERMAN_FORCE){
            return $this->findSimilarGerman($unit);
        }
        $b = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $b->mapData;
        $units = $mapData->getHex($unit->hexagon->name)->getForces($unit->forceId);
        $similarUnits = [];
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
                if( $k != $unit->id){
                    $unitCount++;
                    if($otherUnit->class === 'inf'){
                        $armorStack = false;
                    }
                    if($otherUnit->class === 'armor'){
                        $infStack = false;
                    }
                    if($otherUnit->class === 'mech'){
                        $hasMech = true;
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
}
