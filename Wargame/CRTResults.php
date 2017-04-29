<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 5/25/16
 * Time: 9:35 AM
 */

namespace Wargame;


trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = Battle::getBattle();

        $distance = 1;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        $vacated = false;
        $exchangeMultiplier = 1;
        if($combatResults === EX02){
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 2;
        }
        if($combatResults === EX03){
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 3;
        }
        if ($combatResults === EX0) {
            $distance = 0;
            $combatResults = EX;
        }
        $defUnit = $force->units[$defenderId];
        switch ($combatResults) {
            case EX2:
                $distance = 2;
            case EX:
                $eliminated = $defUnit->damageUnit($force->exchangesKill);
                if (!$eliminated){
                    if($distance) {
                        $defUnit->status = STATUS_CAN_RETREAT;
                    }else{
                        $force->clearAdvancing();
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

            case DD:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                $defUnit->disruptUnit($battle->gameRules->phase);
                $battle->victory->disruptUnit($defUnit);
                break;

            case AL:
            case ALF:
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
                $defUnit->status = STATUS_ELIMINATING;
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));
                break;

            case DRL2:
                $distance = 2;
            case DRL:
            case DLR:
            case DLF:
            case DL2R:

                $eliminated = $defUnit->damageUnit();
                if($combatResults === DL2R && !$eliminated){
                    $eliminated = $defUnit->damageUnit();
                }
                if ($eliminated) {
                    $defUnit->moveCount = 0;
                    $force->addToRetreatHexagonList($defenderId, $force->getUnitHexagon($defenderId));

                } else {
                    $defUnit->status = STATUS_CAN_RETREAT;
                }
                $defUnit->retreatCountRequired = $distance;
                break;
            case DR2:
                $distance = 2;
            case DR:
                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = $distance;
                break;

            case NE:
            case MISS:
                $defUnit->status = STATUS_NO_RESULT;
                $defUnit->retreatCountRequired = 0;
                $battle->victory->noEffectUnit($defUnit);
                break;
            case DL:
            case BL:
            case DL2:

                $eliminated = $defUnit->damageUnit();
                if($combatResults === DL2 && !$eliminated){
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
                    case EX2:
                    case EX:
                        $attackingUnit->status = STATUS_CAN_EXCHANGE;
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    case AE:
                        $attackingUnit->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
                        break;

                    case AL:
                    case AL2:
                    case ALF:
                    case BL:
                        $attackingUnit->status = STATUS_CAN_ATTACK_LOSE;
                        $attackingUnit->retreatCountRequired = 0;
                        $force->exchangeAmount = 1;
                        if($combatResults === AL2){
                            $force->exchangeAmount = ceil(ceil($attackingUnit->getUnmodifiedStrength()/2) + .1);
                        }
                        break;

                    case DE:
                        if($battle->victory->unitProhibitedFromAdvancing($this->units[$attacker])){
                            $this->units[$attacker]->status = STATUS_ATTACKED;
                        }else {
                            $attackingUnit->status = STATUS_CAN_ADVANCE;
                        }
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    case AR:
                        $attackingUnit->status = STATUS_CAN_RETREAT;
                        $attackingUnit->retreatCountRequired = $distance;
                        break;

                    case DRL2:
                    case DR2:
                    case DRL:
                    case DLR:
                    case DR:
                    case DLF:
                        if($attackingUnit->status !== STATUS_NO_RESULT)
                        {
                            if($battle->victory->unitProhibitedFromAdvancing($this->units[$attacker])){
                                $this->units[$attacker]->status = STATUS_ATTACKED;
                            }else{
                                $attackingUnit->status = STATUS_CAN_ADVANCE;
                            }
                        }
                        $attackingUnit->retreatCountRequired = 0;
                        break;

                    case DL:
                        /* for multi defender combats */
                        if ($vacated || $attackingUnit->status == STATUS_CAN_ADVANCE) {
                            if($battle->victory->unitProhibitedFromAdvancing($this->units[$attacker])){
                                $this->units[$attacker]->status = STATUS_ATTACKED;
                            }else {
                                $attackingUnit->status = STATUS_CAN_ADVANCE;
                            }
                        } else {
                            $attackingUnit->status = STATUS_NO_RESULT;
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