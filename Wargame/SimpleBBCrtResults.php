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
            if ($this->units[$attacker]->status == STATUS_BOMBARDING) {
                $this->units[$attacker]->status = STATUS_ATTACKED;
                $this->units[$attacker]->retreatCountRequired = 0;

                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }

            if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE){
                $this->units[$attacker]->torpFired();
            }else{
                if(is_callable([$this->units[$attacker],'firedGun'])){
                    $this->units[$attacker]->firedGun();
                }
            }

            if ($this->units[$attacker]->status == STATUS_ATTACKING) {
                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }
        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$force->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $force->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $force->removeEliminatingUnits();
    }
}