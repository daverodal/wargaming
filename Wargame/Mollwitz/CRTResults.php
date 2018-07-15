<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/25/16
 * Time: 9:35 AM
 */

namespace Wargame\Mollwitz;
use Wargame\Battle;


trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = Battle::getBattle();

        $distance = 1;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        $vacated = false;
        $exchangeMultiplier = 1;

        $defUnit = $force->units[$defenderId];
        switch ($combatResults) {
            case EX:
                $eliminated = $defUnit->damageUnit($force->exchangesKill);
                if (!$eliminated){
                    if($distance) {
                        $defUnit->status = STATUS_CAN_RETREAT;
                    }else{
                        $defUnit->status = STATUS_EXCHANGED;
                    }
                    $defUnit->retreatCountRequired = $distance;
                }else{
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                }
                $force->exchangeAmount += $defUnit->defExchangeAmount * $exchangeMultiplier;
                $defUnit->moveCount = 0;
                break;




            case AE:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case AR:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case DE:
                $defUnit->status = STATUS_ELIMINATING;
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                break;



            case DR:
                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = $distance;
                break;

            case NE:
                $defUnit->status = STATUS_NO_RESULT;
                $defUnit->retreatCountRequired = 0;
                $battle->victory->noEffectUnit($defUnit);
                break;

            default:
                break;
        }
        $defUnit->combatResults = $combatResults;
        $defUnit->dieRoll = $dieRoll;
        $defUnit->combatNumber = 0;
        $defUnit->moveCount = 0;


        foreach ($attackers as $attacker => $val) {
            $attackingUnit = $force->units[$attacker];
            if ($attackingUnit->status == STATUS_BOMBARDING) {
                $attackingUnit->status = STATUS_ATTACKED;
                $attackingUnit->retreatCountRequired = 0;

                $attackingUnit->combatResults = $combatResults;
                $attackingUnit->dieRoll = $dieRoll;
                $attackingUnit->combatNumber = 0;
                $attackingUnit->moveCount = 0;
            }

            if ($attackingUnit->status == STATUS_ATTACKING) {
                switch ($combatResults) {
                    case EX:
                        $attackingUnit->status = STATUS_CAN_EXCHANGE;
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    case AE:
                        $attackingUnit->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
                        break;



                    case DE:
                        if($battle->victory->isUnitProhibitedFromAdvancing($attackingUnit) === true){
                            $attackingUnit->status = STATUS_ATTACKED;
                        }else {
                            $attackingUnit->status = STATUS_CAN_ADVANCE;
                        }
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    case AR:
                        $attackingUnit->status = STATUS_CAN_RETREAT;
                        $attackingUnit->retreatCountRequired = $distance;
                        break;


                    case DR:

                        if($battle->victory->isUnitProhibitedFromAdvancing($attackingUnit) === true){
                            $attackingUnit->status = STATUS_ATTACKED;
                        }else{
                            $attackingUnit->status = STATUS_CAN_ADVANCE;
                        }
                        $attackingUnit->retreatCountRequired = 0;
                        break;


                    case NE:
                        $attackingUnit->status = STATUS_NO_RESULT;
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $attackingUnit->combatResults = $combatResults;
                $attackingUnit->dieRoll = $dieRoll;
                $attackingUnit->combatNumber = 0;
                $attackingUnit->moveCount = 0;
            }
        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }

}