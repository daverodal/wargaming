<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/25/16
 * Time: 9:35 AM
 */

namespace Wargame\TMCW\EastWest;


trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = \Wargame\Battle::getBattle();

        $distance = 1;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $defUnit = $force->units[$defenderId];

        switch ($combatResults) {


            case AR1:
            case AR2:
            case AR3:

                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case AE:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;


            case DE:
                $defUnit->status = STATUS_ELIMINATING;
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                break;

            case DR4;
            case DR3;
            case DR2:
            case DR1:
                if($combatResults == DR4){
                    $distance = 4;
                }
                if($combatResults == DR3){
                    $distance = 3;
                }
                if($combatResults == DR2){
                    $distance = 2;
                }
                if($combatResults == DR1){
                    $distance = 1;
                }
                if($defUnit->class === "supply"){
                    $defUnit->status = STATUS_ELIMINATING;
                    $defUnit->retreatCountRequired = $distance;
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                }else{
                    $defUnit->status = STATUS_CAN_RETREAT;
                    $defUnit->retreatCountRequired = $distance;
                }
                break;


            default:
                break;
        }
        $defUnit->combatResults = $combatResults;
        $defUnit->dieRoll = $dieRoll;
        $defUnit->combatNumber = 0;
        $defUnit->moveCount = 0;


        $numAttackers = count((array)$attackers);
        foreach ($attackers as $attacker => $val) {
            $attUnit = $force->units[$attacker];
                switch ($combatResults) {


                    case AE:
                        $attUnit->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
                        break;

                    case DE:
                        $attUnit->status = STATUS_ATTACKED;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case AR1:
                    case AR2:
                    case AR3:
                        $distance = 1;
                        if($combatResults === AR2){
                            $distance = 2;
                        }
                        if($combatResults === AR3){
                            $distance = 3;
                        }
                        $attUnit->status = STATUS_CAN_RETREAT;
                            $attUnit->retreatCountRequired = $distance;
                        break;

                    case DR1:
                    case DR2:
                    case DR3:
                    case DR4:

                        $attUnit->status = STATUS_ATTACKED;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $attUnit->combatResults = $combatResults;
                $attUnit->dieRoll = $dieRoll;
                $attUnit->combatNumber = 0;
                $attUnit->moveCount = 0;

        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId, $force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }

}