<?php
namespace Wargame;

use stdClass;

// force.js

// Copyright (c) 20092011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
use \Wargame\Battle;

class Force extends SimpleForce
{
    /* @var  unit $units */
    public $victor;
    public $ZOCrule;

    public $landForce = true;
    public $retreatHexagonList;
    public $exchangeAmount;
    public $requiredAttacks;
    public $requiredDefenses;
    public $combatRequired;
    public $exchangesKill = false;
    public $defenderLoseAmount = 0;
    public $anyCombatsPossible = false;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "units") {
                    $this->units = array();
                    foreach ($v as $unit) {
//                        $this->units[] = UnitFactory::build($unit);
                    }
                    continue;
                }
                if ($k == "retreatHexagonList") {
                    $this->retreatHexagonList = array();
                    foreach ($v as $retreatStep) {
                        $this->retreatHexagonList[] = new RetreatStep($retreatStep);
                    }
                    continue;
                }
                $this->$k = $v;
            }
            $this->units = [];
        } else {

            $this->reinforceTurns = new stdClass();
            $this->units = array();
            $this->victor = RED_FORCE;
            $this->ZOCrule = true;

            $this->retreatHexagonList = array();
            $this->requiredAttacks = new stdClass();
            $this->requiredDefenses = new stdClass();
            $this->combatRequired = false;
        }
    }


    function xxxaddUnit($unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range = 1, $nationality = "neutral", $forceMarch = true, $class = false, $unitDesig = false)
    {
        if ($unitStatus == STATUS_CAN_REINFORCE) {
            if (!$this->reinforceTurns->$unitReinforceTurn) {
                $this->reinforceTurns->$unitReinforceTurn = new stdClass();
            }
            $this->reinforceTurns->$unitReinforceTurn->$unitForceId++;
        }
        $id = count($this->units);
        $unit = UnitFactory::build();
        $unit->set($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);

        array_push($this->units, $unit);
        return $id;
    }


    /*
     * Move Rules
     */


    function addToRetreatHexagonList($id, $retreatHexagon)
    {

        //alert("function .prototype. adding: " + id + " : " + retreatHexagon.getName());
        // note: addToRetreatHexagonList() is invoked before retreat move, so
        //  the moveCount is 0 for 1st step and 1 for 2nd step

        $retreatStep = new RetreatStep();
        $unit = $this->units[$id];

        $retreatStep->set($unit->moveCount, $retreatHexagon);

        $this->retreatHexagonList[] = $retreatStep;
    }

    function getFirstRetreatHex($id)
    {
        if (count($this->retreatHexagonList) == 0) {
            throw new Exception("Cannot get reatreat hex");
        }
        return $this->retreatHexagonList[0]->hexagon;
    }

    function getAllFirstRetreatHexes($id)
    {
        if (count($this->retreatHexagonList) == 0) {
            throw new Exception("Cannot get reatreat hex");
        }
        $hexes = array();
        foreach ($this->retreatHexagonList as $hexList) {
            if ($hexList->stepNumber == 0) {
                $hexes[] = $hexList->hexagon;
            }
        }
        return $hexes;
    }

    function getAllRetreatHexes($id)
    {
        if (count($this->retreatHexagonList) == 0) {
            throw new Exception("Cannot get reatreat hex");
        }
        $hexes = array();
        foreach ($this->retreatHexagonList as $hexList) {
                $hexes[] = $hexList->hexagon;
        }
        return $hexes;
    }

    function advanceIsOnRetreatList($id, $hexagon)
    {
        $isOnList = false;
        for ($i = 0; $i < count($this->retreatHexagonList); $i++) {
            $unit = $this->units[$id];

            // note: addToRetreatHexagonList() is invoked before retreat move, so
            //  the moveCount is 0 for 1st step and 1 for 2nd step
            //  when advancing unit.moveCount will be 0 which will match 1st step retreat number

            //alert("function .prototype. checkingt: " + id + " hexagon: " + hexagon.getName() + " with array " + $this->retreatHexagonList[i].hexagon.getName());
            if ($this->retreatHexagonList[$i]->hexagon->equals($hexagon)) {
                $isOnList = true;
            }
        }

        return $isOnList;
    }


    function resetRemainingNonAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }

    function resetRemainingAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_ADVANCING || $unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }


    function resetNonFittingAdvancingUnits($advancedUnit)
    {
        $b = Battle::getBattle();
        $advancingQueue = [];
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_ADVANCING || $unit->status == STATUS_CAN_ADVANCE
                || $unit->status == STATUS_MUST_ADVANCE) {
                $advancingQueue[] = $unit;
                if($unit->status === STATUS_MUST_ADVANCE){
                    $unit->status = STATUS_CAN_ADVANCE;
                }
            }
        }
        $hexNum = $advancedUnit->hexagon->name;
        $mapHex = $b->mapData->getHex($hexNum);

        foreach($advancingQueue as $advancingProspect) {
            $retreatHexes = $this->retreatHexagonList;
            $vacantHex = false;
            foreach($this->retreatHexagonList as $retreatHex){
                $mapHex = $b->mapData->getHex($retreatHex->hexagon->name);
                if (!$mapHex->isOccupied($this->attackingForceId, $b->moveRules->stacking, $advancingProspect)) {
                    $vacantHex = true;
                }
            }
            if(!$vacantHex){
                $advancingProspect->status = STATUS_ATTACKED;
            }
        }
        
    }

    function unitCanAdvance($id)
    {
        $Advance = false;
        $unit = $this->units[$id];

        if ($unit->status == STATUS_CAN_ADVANCE || $unit->status == STATUS_MUST_ADVANCE) {
            $Advance = true;
        }
        return $Advance;
    }

    function unitCanRetreat($id)
    {
        $canRetreat = false;
        $unit = $this->units[$id];

        if ($unit->status == STATUS_CAN_RETREAT) {
            $canRetreat = true;
        }
        return $canRetreat;
    }

    function unitHasMetRetreatCountRequired($id)
    {
        $unitHasMetRetreatCountRequired = false;

        $unit = $this->units[$id];

        if ($unit->moveCount >= $unit->retreatCountRequired) {
            $unitHasMetRetreatCountRequired = true;
        }

        return $unitHasMetRetreatCountRequired;
    }


    /*
     * Force.php Move Rules
     */

    function unitIsZOC($id)
    {
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        /* @var unit $unit */
        $unit = $this->units[$id];

        $mapHex = $mapData->getHex($unit->hexagon->name);
        if ($this->mapHexIsZoc($mapHex, $unit->forceId == $this->attackingForceId ? $this->defendingForceId : $this->attackingForceId)) {
            return true;
        }
        return false;
    }

    function unitIsAdjacent($id)
    {
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        /* @var unit $unit */
        $unit = $this->units[$id];

        $mapHex = $mapData->getHex($unit->hexagon->name);

        if ($mapHex->isAdjacent($unit->forceId == $this->attackingForceId ? $this->defendingForceId : $this->attackingForceId)) {
            return true;
        }
        return false;
    }

    public function getAdjacentUnits($id)
    {
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        /* @var unit $unit */
        $unit = $this->units[$id];

        $mapHex = $mapData->getHex($unit->hexagon->name);
        return $mapHex->getAdjacentUnits($unit->forceId == $this->attackingForceId ? $this->defendingForceId : $this->attackingForceId);
    }


    /*
     * Combat Rule
     */

    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll)
    {
        $battle = Battle::getBattle();
//        $this->clearRetreatHexagonList();

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
        $defUnit = $this->units[$defenderId];
        switch ($combatResults) {
            case EX2:
                $distance = 2;
            case EX:
                $eliminated = $defUnit->damageUnit($this->exchangesKill);
                if (!$eliminated){
                    if($distance) {
                        $defUnit->status = STATUS_CAN_RETREAT;
                    }else{
                        $defUnit->status = STATUS_EXCHANGED;
                    }
                    $defUnit->retreatCountRequired = $distance;
                }else{
                    $defUnit->moveCount = 0;
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
                }
                $this->exchangeAmount += $defUnit->defExchangeAmount * $exchangeMultiplier;
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
                $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
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
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));

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
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));

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
            if ($this->units[$attacker]->status == STATUS_BOMBARDING) {
                $this->units[$attacker]->status = STATUS_ATTACKED;
                $this->units[$attacker]->retreatCountRequired = 0;

                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }

            if ($this->units[$attacker]->status == STATUS_ATTACKING) {
                switch ($combatResults) {
                    case EX2:
                    case EX:
                        $this->units[$attacker]->status = STATUS_CAN_EXCHANGE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case AE:
                        $this->units[$attacker]->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
                        break;

                    case AL:
                    case AL2:
                    case ALF:
                    case BL:
                        $this->units[$attacker]->status = STATUS_CAN_ATTACK_LOSE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        $this->exchangeAmount = 1;
                        if($combatResults === AL2){
                            $this->exchangeAmount = ceil(ceil($this->units[$attacker]->getUnmodifiedStrength()/2) + .1);
                        }
                        break;

                    case DE:
                        if($battle->victory->isUnitProhibitedFromAdvancing($this->units[$attacker])){
                            $this->units[$attacker]->status = STATUS_ATTACKED;
                        }else{
                            $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        }
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case AR:
                        $this->units[$attacker]->status = STATUS_CAN_RETREAT;
                        $this->units[$attacker]->retreatCountRequired = $distance;
                        break;

                    case DRL2:
                    case DR2:
                    case DRL:
                    case DLR:
                    case DR:
                    case DLF:
                        if($this->units[$attacker]->status !== STATUS_NO_RESULT){
                            if($battle->victory->isUnitProhibitedFromAdvancing($this->units[$attacker]) === true){
                                $this->units[$attacker]->status = STATUS_ATTACKED;
                            }else {
                                $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                            }
                        }
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case DL:
                        /* for multi defender combats */
                        if ($vacated || $this->units[$attacker]->status == STATUS_CAN_ADVANCE) {
                            if($battle->victory->isUnitProhibitedFromAdvancing($this->units[$attacker])){
                                $this->units[$attacker]->status = STATUS_ATTACKED;
                            }else {
                                $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                            }
                        } else {
                            $this->units[$attacker]->status = STATUS_NO_RESULT;
                        }
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case NE:
                        $this->units[$attacker]->status = STATUS_NO_RESULT;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }
        }
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$this->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $this->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $this->removeEliminatingUnits();
    }

    function clearRetreatHexagonList()
    {
        $this->retreatHexagonList = array();
    }


    public function groomRetreatList()
    {
        $b = Battle::getBattle();
        $mapData = $b->mapData;
        $newList = [];

        foreach($this->retreatHexagonList as $retreatHexagon){
            $hex = $retreatHexagon->hexagon->name;
            $mapHex = $mapData->getHex($hex);
            if($mapHex->isOccupied($this->defendingForceId)){
                continue;
            }
            $newList[] = $retreatHexagon;
        }
        $this->retreatHexagonList = $newList;
    }

    function mapHexIsZoc(MapHex $mapHex, $defendingForceId = false)
    {
        if ($defendingForceId === false) {
            $defendingForceId = $this->defendingForceId;
        }
        return $mapHex->isZoc($defendingForceId);
        $neighbors = $mapHex->neighbors;

        if ($neighbors) {
            $battle = Battle::getBattle();
            $mapData = $battle->mapData;
            foreach ($neighbors as $neighbor) {
                if ($this->mapHexIsOccupiedEnemy($mapData->getHex($neighbor))) {
                    return true;
                }
            }
        }
        return false;
    }

    function exchangeUnit($unit)
    {
        $this->exchangeAmount -= $unit->exchangeAmount;
    }
    
    function defenderLoseUnit($unit)
    {
        $this->defenderLoseAmount -= $unit->exchangeAmount;
    }

    function clearExchangeAmount()
    {
        $this->exchangeAmount = 0;
    }

    function setupAttacker($id, $range)
    {
        $unit = $this->units[$id];

        if ($range > 1) {
            $unit->status = STATUS_BOMBARDING;

        } else {
            $unit->status = STATUS_ATTACKING;
        }

        if ($this->combatRequired && isset($this->requiredAttacks->$id)) {
            $this->requiredAttacks->$id = false;
        }
    }

    function setupDefender($id)
    {
        $unit = $this->units[$id];

        $unit->status = STATUS_DEFENDING;

        if ($this->combatRequired && isset($this->requiredDefenses->$id)) {
            $this->requiredDefenses->$id = false;
        }
    }


    function undoAttackerSetup($id)
    {
        parent::undoAttackerSetup($id);
        if ($this->combatRequired && isset($this->requiredAttacks->$id)) {
            $this->requiredAttacks->$id = true;
        }
    }

    /*
     * GAME RULES
     */

    function recoverUnits($phase, $moveRules, $mode)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preRecoverUnits();
        $this->anyCombatsPossible = false;
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            $victory->preRecoverUnit($unit);

            switch ($unit->status) {


                case STATUS_ELIMINATED:
                    if($mode === REPLACING_MODE){
                        if ($unit->forceId == $this->attackingForceId){
                            $unit->status = STATUS_CAN_REPLACE;
                        }
                    }
                    break;
                case STATUS_CAN_DEPLOY:
                    if($mode == DEPLOY_MODE){
                        break;
                    }
                    if ($unit->isDeploy()) {
                        break;
                    }

                case STATUS_UNAVAIL_THIS_PHASE:
                case STATUS_STOPPED:
                case STATUS_DEFENDED:
                case STATUS_DEFENDING:
                case STATUS_ATTACKED:
                case STATUS_ATTACKING:
                case STATUS_RETREATED:
                case STATUS_ADVANCED:
                case STATUS_CAN_ADVANCE:
                case STATUS_REPLACED:
                case STATUS_READY:
                case STATUS_REPLACED:
                case STATUS_CAN_UPGRADE:
                case STATUS_NO_RESULT:
                case STATUS_EXCHANGED:
                case STATUS_CAN_ATTACK_LOSE:
                case STATUS_CAN_COMBINE:


                    $status = STATUS_READY;


                    if($mode === COMBINING_MODE){
                        $status = STATUS_UNAVAIL_THIS_PHASE;
                        if($unit->status === STATUS_CAN_COMBINE){
                            $status = STATUS_READY;
                        }
                    }
                    /*
                     * Active Locking Zoc rules
                     */
//                    if($this->unitIsZOC($id)){
//                        $status = STATUS_STOPPED;
//                    }

                    if ($phase == BLUE_MECH_PHASE && $unit->forceId == BLUE_FORCE && $unit->class != "mech") {
                        $status = STATUS_STOPPED;
                    }
                    if ($phase == RED_MECH_PHASE && $unit->forceId == RED_FORCE && $unit->class != "mech") {
                        $status = STATUS_STOPPED;
                    }
                    if ($phase == BLUE_REPLACEMENT_PHASE || $phase == RED_REPLACEMENT_PHASE || $phase == TEAL_REPLACEMENT_PHASE || $phase == PURPLE_REPLACEMENT_PHASE) {
                        $status = STATUS_STOPPED;
                        /* TODO Hack Hack Hack better answer is not isReduced, but canReduce */
                        if ($unit->forceId == $this->attackingForceId &&
                            $unit->isReduced && $unit->class !== "gorilla" && $unit->class !== 'supply' && $unit->class !== 'milita'
                        ) {
                            $status = STATUS_CAN_UPGRADE;
                        }
                    }

                    if ($phase == BLUE_COMBAT_PHASE || $phase == RED_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            /* unitIsZoc has Side Effect */
                            $isZoc = $this->unitIsZoc($id);
                            if ($isZoc) {
                                $this->markRequired($id);
                            }
                            $isAdjacent = $this->unitIsAdjacent($id);
                            if ($unit->forceId == $this->attackingForceId && ($isZoc || $isAdjacent || $this->unitIsInRange($id))) {
                                $status = STATUS_READY;
                                $this->anyCombatsPossible = true;
                            }
                        }
                        if ($mode == COMBAT_RESOLUTION_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($unit->status == STATUS_ATTACKING ||
                                $unit->status == STATUS_DEFENDING
                            ) {
                                $status = $unit->status;
                            }

                        }
                    }

                if ($phase == BLUE_AIR_COMBAT_PHASE || $phase == RED_AIR_COMBAT_PHASE ) {
                    if($unit->class === 'air'){
                    if ($mode == COMBAT_SETUP_MODE) {
                        $status = STATUS_UNAVAIL_THIS_PHASE;

                        $isAdjacent = $this->unitIsAdjacent($id);
                        if ($unit->forceId == $this->attackingForceId && ( $isAdjacent )) {
                            $status = STATUS_READY;
                            $this->anyCombatsPossible = true;
                        }
                    }
                    if ($mode == COMBAT_RESOLUTION_MODE) {
                        $status = STATUS_UNAVAIL_THIS_PHASE;
                        if ($unit->status == STATUS_ATTACKING ||
                            $unit->status == STATUS_DEFENDING
                        ) {
                            $status = $unit->status;
                        }

                    }
                    }
                }

                    if ($mode == MOVING_MODE && $moveRules->stickyZoc) {
                        if ($unit->forceId == $this->attackingForceId &&
                            $this->unitIsZOC($id)
                        ) {
                            $status = STATUS_STOPPED;
                        }
                    }

                    $unit->status = $status;
                    $unit->moveAmountUsed = 0;
                    break;

                default:
                    break;
            }
            if($phase === BLUE_MOVE_PHASE || $phase === RED_MOVE_PHASE || $phase == TEAL_MOVE_PHASE || $phase == PURPLE_MOVE_PHASE){
                $unit = $unit;
                $unit->moveAmountUnused = $unit->getMaxMove();
                if($unit instanceof TransportableUnit){
                    if($unit->canBeTransported()){
                        if($unit->getTransporter()){
                            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                        }
                    }
                }
            }
            $unit->combatIndex = 0;
            $unit->combatNumber = 0;
            $unit->combatResults = NE;
            $victory->postRecoverUnit($unit);

        }
        $victory->postRecoverUnits();
    }


    function markRequired($id)
    {
        $unit = $this->units[$id];

        if ($this->combatRequired) {
            if ($unit->forceId == $this->attackingForceId) {
                $this->markRequiredAttack($id);
            } else {
                $this->markRequiredDefense($id);
            }
        }
    }

    function markRequiredAttack($id)
    {
        $this->requiredAttacks->$id = true;
    }

    function markRequiredDefense($id)
    {
        $this->requiredDefenses->$id = true;
    }


    function replace($id)
    {
        $unit = $this->units[$id];
        if ($unit->isReduced && $unit->status != STATUS_REPLACED) {
            if(method_exists($unit, 'replace')){
                $unit->replace();
            }else{
                $unit->isReduced = false;
            }
            $unit->status = STATUS_REPLACED;
            return true;
        }
        return false;
    }


    function getExchangeAmount()
    {
        return $this->exchangeAmount;
    }
    
    function getDefenderLosingAmount()
    {
        return $this->defenderLoseAmount;
    }

    function clearRequiredCombats()
    {
        $this->requiredAttacks = new stdClass();
        $this->requiredDefenses = new stdClass();
    }


    function requiredCombats()
    {
        foreach ($this->requiredAttacks as $attack) {
            if ($attack === true) {
                return true;
            }
        }
        foreach ($this->requiredDefenses as $defense) {
            if ($defense === true) {
                return true;
            }
        }
        return false;
    }
    function exchangingAreAdvancing()
    {
        $areAdvancing = false;
        $b = Battle::getBattle();
        $this->groomRetreatList();
        /*
         * Todo should not assign to status, should set status
         */
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_EXCHANGE) {
                if(count($this->retreatHexagonList)){
                    if($b->victory->isUnitProhibitedFromAdvancing($unit)){
                        $unit->status = STATUS_ATTACKED;
                    }else{
                        $unit->status = STATUS_CAN_ADVANCE;
                        $areAdvancing = true;
                    }
                }else{
                    $unit->status = STATUS_ATTACKED;
                }
            }
        }
        return $areAdvancing;
    }
    function defendingAreRetreating()
    {
        $areRetreating = false;
        /*
         * Todo should not assign to status, should set status
         */
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_DEFEND_LOSE) {
                if ($unit->retreatCountRequired) {
                    $unit->status = STATUS_CAN_RETREAT;
                    $areRetreating = true;
                } else {
                    $unit->status = STATUS_DEFENDED;
                }
            }
        }
        return $areRetreating;
    }

    function attackingAreRetreating()
    {
        $areRetreating = false;
        /*
         * Todo should not assign to status, should set status
         */
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_ATTACK_LOSE) {
                if ($unit->retreatCountRequired) {
                    $unit->status = STATUS_CAN_RETREAT;
                    $areRetreating = true;
                } else {
                }
            }
        }
        return $areRetreating;
    }

    function attackingAreAdvancing()
    {
        $areAdvancing = false;
        $b = Battle::getBattle();
        /*
         * Todo should not assign to status, should set status
         */
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_ATTACK_LOSE) {
                if (count($this->retreatHexagonList)) {
                    if($b->victory->isUnitProhibitedFromAdvancing($unit)){
                        $unit->status = STATUS_ATTACKED;
                    }else {
                        $unit->status = STATUS_CAN_ADVANCE;
                        $areAdvancing = true;
                    }
                } else {
                $unit->status = STATUS_ATTACKED;
            }
            }
        }
        return $areAdvancing;
    }

    function unitsAreExchanging()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_EXCHANGE) {
                return true;
            }
        }
        return $areAdvancing;
    }

    function unitsAreAttackerLosing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_ATTACK_LOSE) {
                return true;
            }
        }
        return $areAdvancing;
    }

    function unitsAreDefenderLosing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_DEFEND_LOSE) {
                return true;
            }
        }
        return $areAdvancing;
    }
    
    function clearAdvancing()
    {
        return;
        foreach($this->units as $unit){
            if($unit->status == STATUS_CAN_ADVANCE){
                $unit->setStatus(STATUS_ADVANCING);
            }
            if($unit->status == STATUS_ADVANCING){
                $unit->setStatus(STATUS_ADVANCED);
            }
        }
    }

    function unitsAreAdvancing()
    {
        $areAdvancing = false;
        $b = Battle::getBattle();
        $this->groomRetreatList();
        $b->combatRules->groomAdvancing();

        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_ADVANCE
                || $unit->status == STATUS_ADVANCING
                || $unit->status == STATUS_MUST_ADVANCE
            ) {
                $areAdvancing = true;
                break;
            }
        }
        return $areAdvancing;
    }


    function unitsAreRetreating()
    {
        $areRetreating = false;
        for ($id = 0; $id < count($this->units); $id++) {
            $unit = $this->units[$id];

            if ($unit->status == STATUS_CAN_RETREAT
                || $unit->status == STATUS_RETREATING
            ) {
                $areRetreating = true;
                break;
            }
        }
        return $areRetreating;
    }

    public function getCombine()
    {
        $idMap = [];
        $numCombines = 0;
        $fId = $this->attackingForceId;
        $units = $this->units;
        foreach($units as $unit){
            if($unit->forceId === $fId){
                if(!empty($idMap[$unit->id])){
                    continue;
                }
                $inHex = $this->findSimilarInHex($unit);
                if($inHex && count($inHex) > 0){
                    $unit->status = STATUS_CAN_COMBINE;
                    $idMap[$inHex[0]->id] = true;
                    $this->units[$inHex[0]->id]->status = STATUS_CAN_COMBINE;
                    $numCombines++;
                }
            }
        }
        return $numCombines;
    }

    public function findSimilarInHex($unit)
    {
        $b = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $b->mapData;
        if($unit->isReduced !== true){
            return false;
        }
        $units = $mapData->getHex($unit->hexagon->name)->getForces($unit->forceId);
        $similarUnits = [];
        foreach($units as $k => $v){
            if($this->units[$k]->forceId === $unit->forceId){
                if( $this->units[$k]->class === $unit->class && $k != $unit->id){
                    if( $this->units[$k]->isReduced === true){
                        $similarUnits[] = $this->units[$k];
                    }
                }
            }
        }
        return $similarUnits;
    }

}