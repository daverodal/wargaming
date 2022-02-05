<?php

namespace Wargame;
use stdClass;
class SimpleBBCombatRules extends NavalCombatRules
{
    function setupCombat($id, $shift = false)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $unit = $battle->force->units[$id];

        $cd = $this->currentDefender;

        if ($this->force->unitIsEnemy($id) == true) {
            if(empty($this->dayTime) && $unit->spotted === false){
                return false;
            }
            // defender is already in combatRules, so make it currently selected
//            if(isset($this->defenders->$id)){
//                $id = $this->defenders->$id;
//            }

            $combats = $combatId = false;
            if (isset($this->defenders->$id)) {
                $combatId = $this->defenders->$id;
                $combats = $this->combats->$combatId;
            }
            if ($combats) {
//            if(count($this->combats->$this->currnetDefender->attackers) == 0){
//                unset($this->currnetDefender[$id]);
//            }
                if ($this->currentDefender === false) {
                    $this->currentDefender = $this->defenders->$id;
                } else {
                    if ($shift) {
                        if (isset($this->defenders->$id)) {
                            if ($combatId === $this->currentDefender) {
                                foreach ($combats->attackers as $attackerId => $attacker) {
                                    $this->force->undoAttackerSetup($attackerId);
                                    unset($this->attackers->$attackerId);
                                    $victory->postUnsetAttacker($this->units[$attackerId]);
                                }
                                foreach ($combats->defenders as $defenderId => $defender) {
                                    $unit = $this->force->getUnit($defenderId);
                                    $unit->setStatus( STATUS_READY);
                                    unset($this->defenders->$defenderId);
                                    $victory->postUnsetDefender($unit);
                                }
                                unset($this->combats->{$combatId});
                                $this->currentDefender = false;
                            } else {
                                $this->currentDefender = $combatId;
                            }
                        }
                    } else {
                        if ($combatId === $this->currentDefender) {
                            $this->currentDefender = false;
                        } else {
                            $this->currentDefender = $combatId;
                        }
                    }
                }
            } else {
                if ($shift) {
                    if ($this->currentDefender !== false) {
                        foreach ($this->combats->{$this->currentDefender}->attackers as $attackerId => $attacker) {
                            $this->force->undoAttackerSetup($attackerId);
                            unset($this->attackers->$attackerId);
                            unset($this->combats->$cd->attackers->$attackerId);
                            unset($this->combats->$cd->thetas->$attackerId);
                            $victory->postUnsetAttacker($this->units[$attackerId]);
                        }
                        $this->defenders->$id = $this->currentDefender;
                    } else {
                        $this->currentDefender = $id;
                        $this->defenders->$id = $id;
                    }
                } else {
                    $mapHex = $battle->mapData->getHex($unit->hexagon->getName());
                    $forces = $mapHex->getForces($unit->forceId);

                    $this->currentDefender = $id;
                    foreach($forces as $force){
                        $this->defenders->$force = $id;
                        if($force != $id){
                            $cd = $this->currentDefender;
                            $this->force->setupDefender($force);
                            if (!$this->combats) {
                                $this->combats = new  stdClass();
                            }
                            if (!$this->combats->$cd) {
                                $this->combats->$cd = new Combat();
                            }
                            $this->combats->$cd->defenders->$force = $id;
                        }
                    }
                }
                $cd = $this->currentDefender;
//                $this->defenders->{$this->currentDefender} = $id;
                $this->force->setupDefender($id);
                if (!$this->combats) {
                    $this->combats = new  stdClass();
                }
                if (empty($this->combats->$cd)) {
                    $this->combats->$cd = new Combat();
                }
                $this->combats->$cd->defenders->$id = $id;
//                $victory->postSetDefender($this->force->units[$id]);
            }
        } else // attacker
        {

            if ($this->currentDefender !== false && $this->force->units[$id]->status != STATUS_UNAVAIL_THIS_PHASE) {
                if (isset($this->combats->$cd->attackers->$id) && $this->combats->$cd->attackers->$id !== false && $this->attackers->$id === $cd) {
                    $this->force->undoAttackerSetup($id);
                    unset($this->attackers->$id);
                    unset($this->combats->$cd->attackers->$id);
                    unset($this->combats->$cd->thetas->$id);
                    $victory->postUnsetAttacker($this->force->units[$id]);
                    $this->crt->setCombatIndex($cd);
                } else {
                    $good = true;
                    foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                        $los = new Los();

                        $los->setOrigin($this->force->getUnitHexagon($id));
                        $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                        $range = $los->getRange();
                        $unitRange = $unit->getRange();
//                        if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE) {
//                            $unitRange = $unit->getRange($id) * 3;
//
//                        }
                        if ($range > $unitRange) {
                            $good = false;
                            break;
                        }
                        if ($range >= 1) {
                            $good = $this->checkBlocked($los,$id);
                        }
                    }
                    if ($good) {
                        foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                            $los = new Los();

                            $los->setOrigin($this->force->getUnitHexagon($id));
                            $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                            $range = $los->getRange();
                            $bearing = $los->getBearing();
                            if ($range <= $unitRange) {
                                $this->force->setupAttacker($id, $range);
                                if (isset($this->attackers->$id) && $this->attackers->$id !== $cd) {
                                    /* move unit to other attack */
                                    $oldCd = $this->attackers->$id;
                                    unset($this->combats->$oldCd->attackers->$id);
                                    unset($this->combats->$oldCd->thetas->$id);
                                    $this->crt->setCombatIndex($oldCd);
                                    $this->checkBombardment($oldCd);

                                }
                                $this->attackers->$id = $cd;
                                $this->combats->$cd->attackers->$id = $bearing;
                                $this->combats->$cd->defenders->$defenderId = $bearing;
                                if (empty($this->combats->$cd->thetas->$id)) {
                                    $this->combats->$cd->thetas->$id = new stdClass();
                                }
                                $this->combats->$cd->thetas->$id->$defenderId = $bearing;
                                $victory->postSetDefender($this->force->units[$defenderId]);
                                $this->crt->setCombatIndex($cd);
                            }
                        }
                        $victory->postSetAttacker($this->force->units[$id]);
                    }
                }
                $this->checkBombardment();
            }
        }
        $this->cleanUpAttacklessDefenders();
    }

    function checkBlocked($los, $id){
        $mapData = MapData::getInstance();

        $good = true;
        $hexParts = $los->getlosList();
        // remove first and last hexPart

        $src = array_shift($hexParts);
        $target = array_pop($hexParts);

        foreach ($hexParts as $hexPart) {
            if ($this->terrain->terrainIs($hexPart, "blocksRanged")) {
                return false;
            }
        }
        return true;
    }

}