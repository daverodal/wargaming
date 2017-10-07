<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/17/16
 * Time: 11:08 AM
 *
 * /*
 * Copyright 2012-2016 David Rodal
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

namespace Wargame\Tactical\ModernTactics;
use Wargame\Battle;

trait CRTResults
{
    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = Battle::getBattle();
//        $force->clearRetreatHexagonList();

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

            case PIN:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                $defUnit->pinUnit();
                $battle->victory->pinUnit($defUnit);
                break;
            
            case D1:
            case D2:
            case D3:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                $defUnit->disruptUnit($battle->gameRules->phase, $combatResults);
                $battle->victory->disruptUnit($defUnit, $combatResults);
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
            case P:
            case W:
            case PW:
            case P2:
            case S:
                $eliminated = $defUnit->damageUnit($combatResults);
                if ($eliminated) {
                    $vacated = true;
                    $defUnit->retreatCountRequired = 0;
                    $defUnit->moveCount = 0;
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
                $force->units[$attacker]->status = STATUS_ATTACKED;
                $force->units[$attacker]->retreatCountRequired = 0;

                $force->units[$attacker]->combatResults = $combatResults;
                $force->units[$attacker]->dieRoll = $dieRoll;
                $force->units[$attacker]->combatNumber = 0;
                $force->units[$attacker]->moveCount = 0;



        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }


}