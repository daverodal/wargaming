<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/25/16
 * Time: 9:35 AM
 */

namespace Wargame\TMCW\NorthVsSouth;


trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = \Wargame\Battle::getBattle();

        $distance = 1;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $defUnit = $force->units[$defenderId];

        switch ($combatResults) {


            case AR:

                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case AE:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;


            case DE:
                $defUnit->damageUnit(true);
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                break;

            case DR:

                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = 1;
                break;

            case BR:

                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = 1;
                break;

            case EX:
                $defUnit->damageUnit(true);

                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                $force->exchangeAmount += $defUnit->defExchangeAmount;
                $defUnit->moveCount = 0;
                break;

            case EX2:
                $defUnit->damageUnit(true);

                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                $force->exchangeAmount += $defUnit->defExchangeAmount / 2;
                $defUnit->moveCount = 0;
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
                        $attUnit->damageUnit(true);
                        $defUnit->retreatCountRequired = 0;
                        break;

                    case DE:
                        $attUnit->status = STATUS_CAN_ADVANCE;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case AR:

                        $attUnit->status = STATUS_CAN_RETREAT;
                        $attUnit->retreatCountRequired = 1;
                        break;

                    case BR:

                        $attUnit->status = STATUS_CAN_RETREAT;
                        $attUnit->retreatCountRequired = 1;
                        break;

                    case DR:

                        $attUnit->status = STATUS_CAN_ADVANCE;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case EX:
                    case EX2:
                        $attUnit->status = STATUS_CAN_EXCHANGE;
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