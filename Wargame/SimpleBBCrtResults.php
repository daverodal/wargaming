<?php

namespace Wargame;

trait SimpleBBCrtResults
{
    function applyAllCRTResults($defenders, $attackers, $others, $combatResults, $dieRoll, $force, $combatOdds = "")
    {
        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;

        foreach($defenders as $defenderId => $defender) {
            list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
            $defUnit = $force->units[$defenderId];
            switch ($combatResults) {


                case NE:
                case MISS:
                    $defUnit->status = STATUS_NO_RESULT;
                    $defUnit->retreatCountRequired = 0;
                    $battle->victory->noEffectUnit($defUnit,$combatOdds, $dieRoll);
                break;
                case S:
                    $eliminated = $defUnit->damageUnit($combatResults);
                    if ($eliminated) {
                        $defUnit->moveCount = 0;
                        $battle->victory->sinkUnit($defUnit,$combatOdds, $dieRoll);
                    } else {
                        $defUnit->status = STATUS_DEFENDED;
                    }
                    break;
                default:
                    break;
            }
            $defUnit->combatResults = $combatResults;
            $defUnit->dieRoll = $dieRoll;
            $defUnit->combatNumber = 0;
            $defUnit->moveCount = 0;
            $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);
        }

        foreach ($attackers as $attacker => $val) {
            if ($force->units[$attacker]->status == STATUS_BOMBARDING) {
                $force->units[$attacker]->status = STATUS_ATTACKED;
                $force->units[$attacker]->retreatCountRequired = 0;

                $force->units[$attacker]->combatResults = $combatResults;
                $force->units[$attacker]->dieRoll = $dieRoll;
                $force->units[$attacker]->combatNumber = 0;
                $force->units[$attacker]->moveCount = 0;
            }

            if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE){
                $force->units[$attacker]->torpFired();
            }else{
                if(is_callable([$force->units[$attacker],'firedGun'])){
                    $force->units[$attacker]->firedGun();
                }
            }

            if ($force->units[$attacker]->status == STATUS_ATTACKING) {
                $force->units[$attacker]->combatResults = $combatResults;
                $force->units[$attacker]->dieRoll = $dieRoll;
                $force->units[$attacker]->combatNumber = 0;
                $force->units[$attacker]->moveCount = 0;
            }
        }

        foreach($others as $defenderId => $defender) {
            list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
            $defUnit = $force->units[$defenderId];
            switch ($combatResults) {


                case NE:
                case MISS:
                    $defUnit->status = STATUS_NO_RESULT;
                    $defUnit->retreatCountRequired = 0;
                    break;
                case S:
                    $eliminated = $defUnit->damageUnit($combatResults);
                    if ($eliminated) {
                        $defUnit->moveCount = 0;
                    } else {
                        $defUnit->status = STATUS_DEFENDED;
                    }
                    break;
                default:
                    break;
            }
            $defUnit->combatResults = $combatResults;
            $defUnit->dieRoll = $dieRoll;
            $defUnit->combatNumber = 0;
            $defUnit->moveCount = 0;
            $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);
        }

        foreach($defenders as $defenderId => $defender) {
            $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        }
        foreach($others as $defenderId => $defender) {
            $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        }
        $force->removeEliminatingUnits();
    }
}