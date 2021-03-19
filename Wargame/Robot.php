<?php


namespace Wargame;


class Robot
{

    function robotBuild($robotId){
        $b = Battle::getBattle();
        $areas = $b->areaModel->areas;

        $cities = $this->findAllCities($robotId);
        $cityCnt = count($cities);
        $resourcePlayer = $b->gameRules->resources[$robotId];
        $loopCntr = 0;
        while($resourcePlayer->food > 0 &&
            $resourcePlayer->energy > 0 &&
            $resourcePlayer->materials){
            $resourcePlayer->food--;
            $resourcePlayer->energy--;
            $resourcePlayer->materials--;
            $areas->{$cities[$loopCntr++%$cityCnt]}->armies->$robotId += 1;
        }

    }
    function robotMove($robotId){
        $b = Battle::getBattle();
        $areas = Battle::getBattle()->areaModel->areas;
        $commands = $b->gameRules->commands;
        $robotAreas = $this->findAllArmies($robotId);
        foreach($robotAreas as $robotArea){
            $neighbors = $areas->$robotArea->neighbors;
            $neighbors = $this->findByNotOwner($robotId, $neighbors);
            if(count($neighbors) === 0){
                $neighbors = $this->findByGoal($b->scenario->goal->$robotId, $areas->$robotArea->neighbors);
            }
            if(count($neighbors) > 1){
                $numArmiesHere = $areas->$robotArea->armies->$robotId;
                if($numArmiesHere > 1){
                    $neighborCnt = count($neighbors);
                    $loopCntr = 0;
                    while($numArmiesHere > 0){
                        $command = new \stdClass();
                        $command->from = $robotArea;
                        $command->to = $neighbors[$loopCntr++ % $neighborCnt];
                        $command->playerId = $robotId;
                        $command->user = "Robot";
                        $command->amount = 1;
                        if(!($commands->Robot ?? false)){
                            $commands->Robot = [];
                        }
                        $commands->Robot[] = $command;
                        $numArmiesHere--;
                    }
                    continue;
                }
                $withCities = $this->findByHasCity($neighbors);
                if(count($withCities) > 0){
                    $neighbors = $this->findByGoal($b->scenario->goal->$robotId , $withCities);
                }
                $neighbors = $this->findByGoal($b->scenario->goal->$robotId, $neighbors);
            }
            if(count($neighbors) === 1){
                $command = new \stdClass();
                $command->from = $robotArea;
                $command->to = $neighbors[0];
                $command->playerId = $robotId;
                $command->user = "Robot";
                $command->amount = $areas->$robotArea->armies->$robotId;
                if(!($commands->Robot ?? false)){
                    $commands->Robot = [];
                }
                $commands->Robot[] = $command;
            }
        }
    }

    function findAllCities($robotId){
        $cities = [];
        $areas = Battle::getBattle()->areaModel->areas;
        foreach($areas as $areaK => $areaV){
            if($areaV->isCity === true){
                if($areaV->owner === $robotId){
                    $cities[] = $areaK;
                }
            }
        }
        return $cities;
    }
    function findAllArmies($robotId){
        $areas = Battle::getBattle()->areaModel->areas;
        $areaWArmies = [];
        foreach($areas as $areaK => $areaV){
            if(($areaV->armies->$robotId ?? 0) > 0){
                $areaWArmies[] = $areaK;
            }
        }
        return $areaWArmies;
    }
    function findByHasCity($areasIds){
        $areas = Battle::getBattle()->areaModel->areas;
        $notByUs = [];
        foreach($areasIds as $areaId){
            $area = $areas->$areaId;
            if($area->isCity){
                $notByUs[] = $area->id;
            }
        }
        return $notByUs;
    }
    function findByNotOwner($robotId, $areasIds){
        $areas = Battle::getBattle()->areaModel->areas;
        $notByUs = [];
        foreach($areasIds as $areaId){
            $area = $areas->$areaId;
            if($area->owner !== $robotId){
                $notByUs[] = $area->id;
            }
        }
        return $notByUs;
    }
    function findByWesternMost( $areasIds){
        $areas = Battle::getBattle()->areaModel->areas;
        $notByUs = [];
        $westernMostX = 10000000;
        $westernMostId = -1;
        foreach($areasIds as $areaId){
            $area = $areas->$areaId;
            if($westernMostId === -1){
                $westernMostId = $area->id;
                $westernMostX = $area->x;
                continue;
            }
            if($area->x < $westernMostX){
                $westernMostX = $area->y;
                $westernMostId = $area->id;
            }
        }
        return [$westernMostId];
    }
    function findByGoal($goal, $areasIds){
        $areas = Battle::getBattle()->areaModel->areas;
        $mostArea = null;
        if(count($areasIds) == 0){
            return [];
        }
        foreach($areasIds as $areaId){
            $area = $areas->$areaId;
            if($mostArea === null){
                $mostArea = $area;
                continue;
            }
            switch($goal) {
                case 'north':
                    if($area->y < $mostArea->y){
                        $mostArea  = $area;
                    }
                    break;
                case 'south':
                    if($area->y > $mostArea->y){
                        $mostArea  = $area;
                    }
                    break;
                case 'west':
                    if($area->x < $mostArea->x){
                        $mostArea  = $area;
                    }
                    break;
                case 'east':
                    if($area->x > $mostArea->x){
                        $mostArea  = $area;
                    }
                    break;
            }

        }
        return [$mostArea->id];
    }
    function findByNorthernMost($areasIds){
        $areas = Battle::getBattle()->areaModel->areas;
        $notByUs = [];
        $northernMostY = 10000000;
        $northernMostId = -1;
        foreach($areasIds as $areaId){
            $area = $areas->$areaId;
            if($northernMostId === -1){
                $northernMostId = $area->id;
                $northernMostY = $area->y;
                continue;
            }
            if($area->y < $northernMostY){
                $northernMostY = $area->y;
                $northernMostId = $area->id;
            }
        }
        return [$northernMostId];
    }
}