<?php

namespace Wargame;

trait SimpleBBCrtResults
{
    function applyAllCRTResults($defenders, $attackers, $combatResults, $dieRoll, $force)
    {
        $battle = Battle::getBattle();


        foreach($defenders as $defenderId => $defender) {
            list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
            $defUnit = $force->units[$defenderId];
            switch ($combatResults) {


                case NE:
                case MISS:
                    $defUnit->status = STATUS_NO_RESULT;
                    $defUnit->retreatCountRequired = 0;
                    break;
                case P:
                case W:
                case PW:
                case P2:
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
        }


        foreach ($attackers as $attacker => $val) {
            if ($force->units[$attacker]->status == STATUS_ATTACKING) {
                $force->units[$attacker]->combatResults = $combatResults;
                $force->units[$attacker]->dieRoll = $dieRoll;
                $force->units[$attacker]->combatNumber = 0;
                $force->units[$attacker]->moveCount = 0;
            }
        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }
}