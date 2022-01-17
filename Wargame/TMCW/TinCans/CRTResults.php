<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/25/16
 * Time: 9:35 AM
 */

namespace Wargame\TMCW\TinCans;


trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = \Wargame\Battle::getBattle();

        $distance = 1;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        $vacated = false;
        $exchangeMultiplier = 1;
        if ($combatResults === EX02) {
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 2;
        }
        if ($combatResults === EX03) {
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 3;
        }
        if ($combatResults === EX0) {
            $distance = 0;
            $combatResults = EX;
        }
        $defUnit = $force->units[$defenderId];
        $numDefenders = $battle->combatRules->numDefenders($defenderId);

        switch ($combatResults) {
            case EX2:
                $distance = 2;
            case EX:
                $eliminated = $defUnit->damageUnit($force->exchangesKill);
                if (!$eliminated) {
                    if ($distance) {
                        $defUnit->status = STATUS_CAN_RETREAT;
                    } else {
                        $defUnit->status = STATUS_EXCHANGED;
                    }
                    $defUnit->retreatCountRequired = $distance;
                } else {
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                }
                $force->exchangeAmount += $defUnit->defExchangeAmount * $exchangeMultiplier;
                $defUnit->moveCount = 0;
                break;

            case DD:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                $defUnit->disruptUnit($battle->gameRules->phase);
                $battle->victory->disruptUnit($defUnit);
                break;


            case ALR:
            case ALF:
            case AL:
            case AL2:
            case AL2F:
            case AL2R:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
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
            case DEAL:
                $defUnit->status = STATUS_ELIMINATING;
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                break;

            case DLR2:
                $distance = 2;
            case DLR:
            case DRL:
            case DL2R:
            case DL2AL:
            case DL2AL2:

                if ($numDefenders > 1) {
                    $defUnit->status = STATUS_CAN_DEFEND_LOSE;
                    $force->exchangeAmount = 1;
                    if ($combatResults === DL2R || $combatResults === DL2AL || $combatResults === DL2AL2) {
                        $force->exchangeAmount = 2;
                    }
                    $defUnit->retreatCountRequired = $distance;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));

                    break;
                }
                $eliminated = $defUnit->damageUnit();
                if (($combatResults === DL2R ||  $combatResults === DL2AL || $combatResults === DL2AL2) && !$eliminated) {
                    $eliminated = $defUnit->damageUnit();
                }
                if ($eliminated) {
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));

                } else {
                    if($combatResults == DL2R || $combatResults === DLR || $combatResults === DRL || $combatResults === DLR2 ){
                        $defUnit->status = STATUS_CAN_RETREAT;
                    }else{
                        $defUnit->status = STATUS_DEFENDED;
                    }
                }
                $defUnit->retreatCountRequired = $distance;
                if($combatResults === DL2F || $combatResults === DLF){
                    $defUnit->retreatCountRequired = $defUnit->maxMove;
                }
                break;
            case DR2:
                $distance = 2;
            case DR:
                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = $distance;
                break;

            case NE:
                $defUnit->status = STATUS_NO_RESULT;
                $defUnit->retreatCountRequired = 0;
                $battle->victory->noEffectUnit($defUnit);
                break;
            case DL:
            case BL:
            case BLDR:
            case DL2:

                if ($numDefenders > 1) {
                    $defUnit->status = STATUS_CAN_DEFEND_LOSE;
                    $defUnit->retreatCountRequired = 0;
                    if($combatResults === BLDR){
                        $defUnit->retreatCountRequired = 1;
                    }
                    $force->exchangeAmount = 1;
                    if ($combatResults === DL2) {
                        $force->exchangeAmount = 2;
                    }
                    break;
                }

                $eliminated = $defUnit->damageUnit();
                if ($combatResults === DL2 && !$eliminated) {
                    $eliminated = $defUnit->damageUnit();
                }
                if ($eliminated) {
                    $vacated = true;
                    $defUnit->retreatCountRequired = 0;
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));

                } else {
                    $defUnit->status = STATUS_DEFENDED;
                    $defUnit->retreatCountRequired = 0;
                }
                if($combatResults === BLDR){
                    $defUnit->status = STATUS_CAN_RETREAT;
                }
                $defUnit->retreatCountRequired = 1;

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
            if ($attUnit->status == STATUS_BOMBARDING) {
                $attUnit->status = STATUS_ATTACKED;
                $attUnit->retreatCountRequired = 0;

                $attUnit->combatResults = $combatResults;
                $attUnit->dieRoll = $dieRoll;
                $attUnit->combatNumber = 0;
                $attUnit->moveCount = 0;
            }
            if ($attUnit->status == STATUS_ATTACKING) {
                switch ($combatResults) {
                    case EX2:
                    case EX:
                        $attUnit->status = STATUS_CAN_EXCHANGE;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case AE:
                        $attUnit->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
                        break;

                    case AL:
                    case ALR:
                    case AL2:
                    case ALF:
                    case AL2R:
                    case AL2F:
                    case BL:
                    case BLDR:
                    case DEAL:
                    case DL2AL:
                    case DL2AL2:
                        if ($numAttackers > 1) {
                            $attUnit->status = STATUS_CAN_ATTACK_LOSE;
                            $attUnit->retreatCountRequired = 0;
                            $force->exchangeAmount = 1;
                            if ($combatResults === DL2AL2 || $combatResults === AL2 || $combatResults === AL2R || $combatResults === AL2F) {
                                $force->exchangeAmount = 2;
                            }
                            if ($combatResults === ALR || $combatResults === ALF || $combatResults === AL2R || $combatResults === AL2F) {
                                $attUnit->retreatCountRequired = 1;
                                if ($combatResults === ALF || $combatResults === AL2F) {
                                    $attUnit->retreatCountRequired = $attUnit->maxMove;

                                }
                            }
                            break;
                        }
                        $eliminated = $attUnit->damageUnit();
                        if (($combatResults === AL2 || $combatResults === AL2R || $combatResults === AL2F || $combatResults === DL2AL2) && !$eliminated) {
                            $eliminated = $attUnit->damageUnit();
                        }
                        if (!$eliminated) {
                            $attUnit->status = STATUS_ATTACKED;
                            $attUnit->retreatCountRequired = 0;
                            if ($combatResults === ALR || $combatResults === ALF || $combatResults === AL2R || $combatResults === AL2F) {
                                $attUnit->status = STATUS_CAN_RETREAT;
                                $attUnit->retreatCountRequired = 1;
                                if ($combatResults === ALF || $combatResults === AL2F) {
                                    $attUnit->retreatCountRequired = $attUnit->maxMove;

                                }
                            }
                            if ($combatResults === DEAL || $combatResults === BLDR || $combatResults === DL2AL || $combatResults === DL2AL2) {
                                $attUnit->status = STATUS_CAN_ADVANCE;
                            }
                        }
                        break;

                    case DE:
                        $attUnit->status = STATUS_CAN_ADVANCE;
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case AR:
                        $attUnit->status = STATUS_CAN_RETREAT;
                        $attUnit->retreatCountRequired = $distance;
                        break;

                    case DR2:
                    case DLR:
                    case DRL:
                    case DR:
                    case DLF:
                    case DL2R:
                    case DL2F:

                        if ($attUnit->status !== STATUS_NO_RESULT) {
                            $attUnit->status = STATUS_CAN_ADVANCE;
                        }
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case DL:
                    case DL2:
                        /* for multi defender combats */
                        if ($vacated || $attUnit->status == STATUS_CAN_ADVANCE) {
                            $attUnit->status = STATUS_CAN_ADVANCE;
                        } else {
                            $attUnit->status = STATUS_NO_RESULT;
                        }
                        $attUnit->retreatCountRequired = 0;
                        break;

                    case NE:
                        $attUnit->status = STATUS_NO_RESULT;
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
        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId, $force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }

}