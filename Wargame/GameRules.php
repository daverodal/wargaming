<?php
namespace Wargame;
use \stdClass;
// gameRules.js

// Copyright (c) 2009-2011 Mark Butler
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

class PhaseChange
{

    public $currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
    }
    function set($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn)
    {
        $this->currentPhase = $currentPhase;
        $this->nextPhase = $nextPhase;
        $this->nextMode = $nextMode;
        $this->nextAttackerId = $nextAttackerId;
        $this->nextDefenderId = $nextDefenderId;
        $this->phaseWillIncrementTurn = $phaseWillIncrementTurn;
    }
}

class GameRules extends GameRulesAbs
{
    // class references
    /* @var MoveRules $moveRules */
    public $moveRules;
    /* @var CombatRules */
    public $combatRules;
    /* @var Force */
    public $force;
    /* @var PhaseChange */
    public $phaseChanges;
    public $flashMessages;
    public $flashLog;

    public $turn;
    public $maxTurn;
    public $phase;
    public $mode;
    public $combatModeType;
    public $gameHasCombatResolutionMode;
    public $attackingForceId;
    public $defendingForceId;
    public $interactions;
    public $replacementsAvail;
    public $currentReplacement;
    public $phaseClicks;
    public $phaseClickNames;
    public $playTurnClicks;
    public $legacyExchangeRule;
    public $options = false;
    public $option;

    function save()
    {
        array_splice($this->flashLog, count($this->flashLog),0, $this->flashMessages);
        $data = new stdClass();
        foreach ($this as $k => $v) {
            if (is_object($v)) {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    public function inject(MoveRules $MoveRules,CombatRules $CombatRules,Force $Force){
        $this->moveRules = $MoveRules;
        $this->combatRules = $CombatRules;
        $this->force = $Force;
    }

    function __construct(MoveRules $MoveRules, CombatRules $CombatRules, Force $Force, $data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "phaseChanges") {
                    $this->phaseChanges = array();
                    foreach ($v as $phaseChange) {
                        $this->phaseChanges[] = new PhaseChange($phaseChange);
                    }
                    continue;
                }
                $this->$k = $v;
            }
            $this->moveRules = $MoveRules;
            $this->combatRules = $CombatRules;
            $this->force = $Force;
        } else {
            $this->moveRules = $MoveRules;
            $this->combatRules = $CombatRules;
            $this->force = $Force;
            $this->phaseChanges = array();
            $this->currentReplacement = false;

            $this->turn = 1;
            $this->legacyExchangeRule = false;
            $this->combatModeType = COMBAT_SETUP_MODE;
            $this->gameHasCombatResolutionMode = true;
            $this->trayX = 0;
            $this->trayY = 0;
            $this->attackingForceId = BLUE_FORCE;
            $this->defendingForceId = RED_FORCE;
            $this->interactions = array();
            $this->phaseClicks = array();
            $this->playTurnClicks = array();
            $this->options = false;

            $this->force->setAttackingForceId($this->attackingForceId);
            $this->flashLog = [];
            $this->flashMessages = [];
        }
        if(!isset($this->flashLog)){
            $this->flashLog = [];
        }
        if($this->legacyExchangeRule !== false){
            $this->legacyExchangeRule = true;
        }
    }

    function setMaxTurn($max_Turn)
    {

        $this->maxTurn = $max_Turn;
    }

    function setInitialPhaseMode($phase, $mode)
    {
        global $phase_name;
        $this->phase = $phase;
        $this->mode = $mode;
        $this->phaseClickNames[] = $phase_name[$phase];

    }
    function addPhaseChange($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn)
    {

        $phaseChange = new PhaseChange();
        $phaseChange->set($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn);
        array_push($this->phaseChanges, $phaseChange);
    }

    function processEvent($event, $id, $location, $click)
    {

        /* @var Hexagon $location */

        $now = time();
        $interaction = new stdClass();

        $interaction->event = $event;
        $interaction->id = $id;
        $interaction->hexagon = $location;
        $interaction->time = $now;

        /* @var $battle Battle */
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        //TODO Ugly Ugly Ugly Ugly
        $mapData->specialHexesChanges = new stdClass();
        $mapData->specialHexesVictory = new stdClass();
        //TODO Ugly Ugly Ugly Ugly

        $this->flashMessages = [];



        if($event === SELECT_ALT_COUNTER_EVENT || $event === SELECT_ALT_MAP_EVENT){
            if($location !== null){
                $this->flashMessages[] = "@hex ".$location->getName();
            }else{
                $hex = $battle->force->units[$id]->hexagon;
                if($hex->parent == "gameImages"){
                    $this->flashMessages[] = "@hex ".$hex->name;
                }
            }
            return true;
        }


        if($event === SURRENDER_EVENT){
            if($id == $battle->players[$this->attackingForceId]){
                $player = $this->attackingForceId;
            }else{
                $player = $this->defendingForceId;
            }
            $battle->victory->surrender($player);
            return true;
        }

        switch ($this->mode) {



            case OPTION_MODE:

                switch ($event) {

                    case SELECT_BUTTON_EVENT:

                        $this->option = $id;
                        $this->options = false;
                        return $this->selectNextPhase($click);
                        break;
                }
                break;















            case REPLACING_MODE:
                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($this->replacementsAvail <= 0) {
                            break;
                        }

                        if($this->currentReplacement !== false && $location){
                            $unit = $this->force->getUnit($this->currentReplacement);

                            if ($unit->getReplacing($location) !== false) {
                                $this->moveRules->stopReplacing();
                                $this->currentReplacement = false;
                                $this->replacementsAvail--;
                            }
                        }


                        if ($this->force->attackingForceId == $this->force->units[$id]->forceId) {
                            $unit = $this->force->getUnit($id);
                            if ($unit->setStatus(STATUS_CAN_REPLACE)) {
                                $this->currentReplacement = false;
                                $this->moveRules->stopReplacing();
                                break;
                            }

                            if ($this->force->units[$id]->status == STATUS_CAN_REPLACE) {
                                if ($this->currentReplacement !== false && $this->currentReplacement != $id) {
                                    $unit = $this->force->getUnit($this->currentReplacement);
                                    $unit->setStatus(STATUS_CAN_REPLACE);
                                }
//                                $this->force->units[$id]->status = STATUS_CAN_REPLACE;
                                $this->currentReplacement = $id;
                                $this->moveRules->startReplacing($id);
                                break;
                            }
                            if (isset($this->force->landForce) && $this->force->landForce && $this->force->units[$id]->status != STATUS_REPLACING && $this->force->units[$id]->status != STATUS_CAN_REINFORCE && $this->force->replace($id)) {
                                $this->replacementsAvail--;
                                if ($this->currentReplacement !== false) {
                                    $this->force->units[$this->currentReplacement]->status = STATUS_REPLACED;
                                    $this->moveRules->stopReplacing();

                                    $this->currentReplacement = false;
                                }
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        if ($this->selectNextPhase($click)) {
                            $this->replacementsAvail = false;
                        }
                        break;
                }
                break;
            case DEPLOY_MODE:
                switch ($event) {
                    case KEYPRESS_EVENT:
                        $bad = true;
                        $c = chr($id);

                        if($c == 'i' || $c == 'I'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->enterImproved(true);
                            $bad = false;
                        }

                        if($c == 'u' || $c == 'U'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->exitImproved(true);
                            $bad = false;

                        }
                        if($c == 'b' || $c == 'B'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            if(method_exists($unit, 'battleReadyOrgStatus')){
                                if (!$unit->unitHasNotMoved()) {
                                    return false;
                                }
                                if($unit->battleReadyOrgStatus() === false){
                                    return false;
                                }
                                return true;
                            }
                            return false;
                        }
                        if($c == 'h' || $c == 'H'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            if(method_exists($unit, 'hedgeHogOrgStatus')){
                                if (!$unit->unitHasNotMoved()) {
                                    return false;
                                }
                                if($unit->hedgeHogOrgStatus() === false){
                                    return false;
                                }
                                return true;
                            }
                            return false;
                        }
                        if($c == 's' || $c == 'S'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);


                            if(method_exists($unit, 'split')){
                                if($unit->split() === false){
                                    return false;
                                }
                                $bad = false;
                            }
                            if(method_exists($unit, 'standOrgStatus')){
                                if($unit->standOrgStatus() === false){
                                    return false;
                                }
                                return true;
                            }
                            if($bad){
                                return false;
                            }

                        }
                        if($c == 'c' || $c == 'C'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $ret = $this->force->findSimilarInHex($unit);

                            if(is_array($ret) && count($ret) > 0){
                                if($unit->combine($ret[0]) === false){
                                    return false;
                                }
                            }else{
                                return false;

                            }
                            $bad = false;
                        }

                        if($id == 37){
                            if(method_exists($this->moveRules, 'turnLeft')){
                                $ret = $this->moveRules->turnLeft(true);
                                return $ret;
                            }

                        }

                        if($id == 40){
                            if(method_exists($this->moveRules, 'turnAbout')){
                                $ret = $this->moveRules->turnAbout(true);
                                return $ret;
                            }

                        }

                        if($id == 39){
                            if(method_exists($this->moveRules, 'turnRight')){
                                $ret = $this->moveRules->turnRight(true);
                                return $ret;
                            }
                        }
                        if($bad === true){
                            return false;
                        }

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:



                    return $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;


            case REBASE_MODE:
                switch ($event) {
                    case KEYPRESS_EVENT:
                        $c = chr($id);
                        $bad = true;

                        if($c == 'i' || $c == 'I'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->enterImproved(true);
                            $bad = false;

                        }

                        if($c == 'u' || $c == 'U'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->exitImproved(true);
                            $bad = false;

                        }
                        if($c == 's' || $c == 'S'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);


                            if($unit->split() === false){
                                return false;
                            }
                            $bad = false;

                        }
                        if($c == 'c' || $c == 'C'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $ret = $this->force->findSimilarInHex($unit);

                            if(is_array($ret) && count($ret) > 0){
                                if($unit->combine($ret[0]) === false){
                                    return false;
                                }
                            }else{
                                return false;

                            }
                            $bad = false;

                        }
                        if($bad === true){
                            return false;
                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:



                        return $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;

            case SUPPLY_MODE:
                switch ($event) {
                    case KEYPRESS_EVENT:
                        $c = chr($id);
                        $bad = true;

                        if($c == 'i' || $c == 'I'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->enterImproved(true);
                            $bad = false;
                        }

                        if($c == 'u' || $c == 'U'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->exitImproved(true);
                            $bad = false;
                        }
                        if($c == 's' || $c == 'S'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);


                            if($unit->split() === false){
                                return false;
                            }
                            $bad = false;

                        }
                        if($c == 'c' || $c == 'C'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $ret = $this->force->findSimilarInHex($unit);

                            if(is_array($ret) && count($ret) > 0){
                                if($unit->combine($ret[0]) === false){
                                    return false;
                                }
                            }else{
                                return false;

                            }
                            $bad = false;

                        }
                        if($bad === true){
                            return false;
                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:



                        return $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;


            case COMBINING_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {
                            $c = chr($id);

                            if($c == 'c' || $c == 'C'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                $ret = $this->force->findSimilarInHex($unit);

                                if(is_array($ret) && count($ret) > 0){
                                    if($unit->combine($ret[0]) === false){
                                        return false;
                                    }else{
                                        $this->moveRules->stopMove($unit, true);
                                        return true;
                                    }
                                }else{
                                    return false;

                                }
                            }

                        }
                        return false;

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $ret = $this->moveRules->selectUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

//                        $this->force->getCombine();

                        return $this->selectNextPhase($click);
                        break;
                }
                break;
            case MOVING_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {
                            $bad = true;
                            $c = chr($id);
                            if ($c == 'm' || $c == 'M') {
                                /* @var Unit $unit */
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);
                                if (!$unit->unitHasNotMoved()) {
                                    return false;
                                }
                                if ($unit->forceMarch === true) {
                                    $unit->forceMarch = false;
                                    $unit->railMove(false);
                                } else {
                                    $unit->forceMarch = true;
                                    $unit->railMove(true);
                                }
                                $bad = false;
                            }

                            if($c == 'x' || $c == 'X'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                if ($unit->hexagon->parent == "gameImages") {
                                    return $this->moveRules->exitUnit($unit->id);

                                }
                                return false;
                            }

                            if($c == 'i' || $c == 'I'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);
                                $ret = $unit->enterImproved();
                                if($ret) {
                                    $hexName = $unit->hexagon->name;
                                    if ($unit->isImproved) {
                                        $battle->mapData->specialHexesVictory->$hexName = "IP!";
                                    } else {
                                        $battle->mapData->specialHexesVictory->$hexName = "No IP";
                                    }
                                }
                                return $ret;
                            }

                            if($c == 'u' || $c == 'U'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                return $unit->exitImproved();
                            }

                            if($c == 'l' || $c == 'L'){
                                if($this->moveRules InstanceOf TransportMoveRules){
                                     return $this->moveRules->loadUnit();
                                }else {
                                    return false;
                                }
                                /* is this finished? */
//                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);
                            }

                            if($id == 37){
                                if(method_exists($this->moveRules, 'turnLeft')){
                                    $ret = $this->moveRules->turnLeft();
                                    return $ret;
                                }
                                return false;
                            }

                            if($id == 40){
                                if(method_exists($this->moveRules, 'turnAbout')){
                                    $ret = $this->moveRules->turnAbout();
                                    return $ret;
                                }
                                return false;
                            }

                            if($id == 39){
                                if(method_exists($this->moveRules, 'turnRight')){
                                    $ret = $this->moveRules->turnRight();
                                    return $ret;
                                }
                                return false;
                            }

                            if($c == 'b' || $c == 'B'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                if(method_exists($unit, 'battleReadyOrgStatus')){
                                    if (!$unit->unitHasNotMoved()) {
                                        return false;
                                    }
                                    if($unit->battleReadyOrgStatus() === false){
                                        return false;
                                    }
                                    return true;
                                }
                                return false;
                            }

                            if($c == 'h' || $c == 'H'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                if(method_exists($unit, 'hedgeHogOrgStatus')){
                                    if (!$unit->unitHasNotMoved()) {
                                        return false;
                                    }
                                    if($unit->hedgeHogOrgStatus() === false){
                                        return false;
                                    }
                                    return true;
                                }
                                return false;
                            }

                            if($c == 's' || $c == 'S'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                if(method_exists($unit, 'split')){
                                    if($unit->split() === false){
                                        return false;
                                    }
                                    $bad = false;
                                }
                                if(method_exists($unit, 'standOrgStatus')){
                                    if (!$unit->unitHasNotMoved()) {
                                        return false;
                                    }
                                    if($unit->standOrgStatus() === false){
                                        return false;
                                    }
                                    return true;
                                    $bad = false;
                                }
                                if($bad){
                                    return false;
                                }
                            }
                            if($c == 'c' || $c == 'C'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                $ret = $this->force->findSimilarInHex($unit);

                                if(is_array($ret) && count($ret) > 0){
                                    if($unit->combine($ret[0]) === false){
                                        return false;
                                    }
                                }else{
                                    return false;

                                }
                                $bad = false;
                            }
                            if($bad === true){
                                return false;
                            }
                        }else{
                            return false;
                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $ret = $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

                        $numCombines = 0;
                        if(method_exists($this->force, 'getCombine')){
                            $numCombines = $this->force->getCombine();
                        }

                        $ret =  $this->selectNextPhase($click);

                        return $ret;
                        break;
                }
                break;


            case SPEED_MODE:

                switch ($event) {

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {

                            if($id == 37){
                                if(method_exists($this->moveRules, 'slower')){
                                    $ret = $this->moveRules->slower();
                                    return $ret;
                                }
                            }


                            if($id == 39){
                                if(method_exists($this->moveRules, 'faster')){
                                    $ret = $this->moveRules->faster();
                                    return $ret;
                                }
                            }

                        }
                    // Fall-through
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $ret = $this->moveRules->selectUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;

            case COMBAT_SETUP_MODE:
            case FIRE_COMBAT_SETUP_MODE:
                $shift = false;
                switch ($event) {

                    case KEYPRESS_EVENT:
                        $c = chr($id);
                        if ($c == 'd' || $c == 'D') {
                            return $this->combatRules->useDetermined();
                            return true;
                        }
                        if ($c == 'c' || $c == 'C') {
                            return $this->combatRules->clearCurrentCombat();
                        }
                        return false;
                        break;

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case SELECT_SHIFT_COUNTER_EVENT:
                        $shift = true;
                    /* fall through */
                    case SELECT_COUNTER_EVENT:
                        $this->combatRules->setupCombat($id, $shift);

                        break;
                    case COMBAT_PIN_EVENT:
                        $this->combatRules->pinCombat($id);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->combatRules->undoDefendersWithoutAttackers();
                        if ($this->gameHasCombatResolutionMode == true) {
                            if (!(isset($this->force->landForce) && $this->force->landForce && $this->force->requiredCombats())) {
                                $this->combatRules->combatResolutionMode();

                                if($this->mode == FIRE_COMBAT_SETUP_MODE){
                                    $this->mode = FIRE_COMBAT_RESOLUTION_MODE;

                                }else{
                                    $this->mode = COMBAT_RESOLUTION_MODE;
                                }
                                if(isset($this->force->landForce) && $this->force->landForce){
                                    $this->force->clearRequiredCombats();
                                }
                                $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);
                                $this->phaseClicks[] = $click + 1;
                                $this->phaseClickNames[] = "Combat Resolution ";
                                if($this->force->moreCombatToResolve() === false){
                                    $this->flashMessages[] = "No Combats to Resolve";
                                    $this->combatRules->cleanUp();
                                    $this->selectNextPhase($click);
                                }
                            } else {
                                $this->flashMessages[] = "Required Combats Remain";
                            }
                        } else {
                            $this->selectNextPhase($click);
                            if($this->phase == BLUE_COMBAT_RES_PHASE || $this->phase == RED_COMBAT_RES_PHASE){
                                $this->combatRules->combatResolutionMode();
                                $defender = $this->force->defendingForceId;
                                $attacker = $this->force->attackingForceId;
                                if($this->combatRules->combatsToResolve) {
                                    foreach ($this->combatRules->combatsToResolve as $key => $val) {
                                        if ($this->force->units[$key]->forceId === $defender) {
                                            $this->force->defendingForceId = $defender;
                                            $this->force->attackingForceId = $attacker;
                                        } else {
                                            $this->force->defendingForceId = $attacker;
                                            $this->force->attackingForceId = $defender;
                                        }
                                        $interaction->dieRoll = $this->combatRules->resolveCombat($key);
                                    }
                                    $this->force->defendingForceId = $defender;
                                    $this->force->attackingForceId = $attacker;
                                }
                            }
                        }
                        break;
                    default:
                        return 0;
                }
                break;

            case COMBAT_RESOLUTION_MODE:
            case FIRE_COMBAT_RESOLUTION_MODE:

                switch ($event) {
                    case KEYPRESS_EVENT:
                        return false;

                    case SELECT_COUNTER_EVENT:

                        $interaction->dieRoll = $this->combatRules->resolveCombat($id);
                        if ($this->force->unitsAreBeingEliminated() == true) {
                            $this->force->removeEliminatingUnits();
                        }
                        if(isset($this->force->landForce) && $this->force->landForce === true) {
                            if ($this->force->unitsAreExchanging() == true) {
                                $this->mode = EXCHANGING_MODE;
                            }

                            if ($this->force->unitsAreAttackerLosing() == true) {
                                $this->mode = ATTACKER_LOSING_MODE;
                            }

                            /* Defender losses are taken before attacker losses */

                            if ($this->force->unitsAreDefenderLosing() == true) {
                                $this->mode = DEFENDER_LOSING_MODE;
                                break;
                            }

                            if ($this->force->unitsAreRetreating() == true) {
                                $this->force->clearRetreatHexagonList();
                                $this->mode = RETREATING_MODE;
                            } else { // check if advancing after eliminated unit
                                if ($this->force->unitsAreAdvancing() == true) {
                                    $this->mode = ADVANCING_MODE;
                                }
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        if ($this->force->moreCombatToResolve() == false) {
                            $this->combatRules->cleanUp();
                            $this->selectNextPhase($click);
                        }
                        break;
                }
                break;


            case RETREATING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        $this->moveRules->retreatUnit($event, $id, $location);
                        if ($this->force->unitsAreRetreating() == false) {
                            if ($this->force->unitsAreExchanging() == true) {
                                $this->mode = EXCHANGING_MODE;
                            } else {
                                if($this->force->unitsAreAttackerLosing()){
                                    $this->mode = ATTACKER_LOSING_MODE;

                                }else {
                                    if($this->force->unitsAreDefenderLosing()) {
                                        $this->mode = DEFENDER_LOSING_MODE;
                                    }else{
                                        if ($this->force->unitsAreAdvancing() == true) {
                                            $this->mode = ADVANCING_MODE;
                                        } else { // melee

                                            if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                                if ($this->gameHasCombatResolutionMode == true) {
                                                    $this->mode = COMBAT_RESOLUTION_MODE;
                                                } else {
                                                    $this->mode = COMBAT_SETUP_MODE;
                                                }
                                            } else { // fire
                                                if ($this->gameHasCombatResolutionMode == true) {
                                                    $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                                } else {
                                                    $this->mode = FIRE_COMBAT_SETUP_MODE;
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
                break;

            case ADVANCING_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($id == 27) {
                            if($this->moveRules->anyUnitIsMoving == false) {
                                $this->force->resetRemainingAdvancingUnits();
                                if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                    if ($this->gameHasCombatResolutionMode == true) {
                                        $this->mode = COMBAT_RESOLUTION_MODE;
                                    } else {
                                        $this->mode = COMBAT_SETUP_MODE;
                                    }
                                } else {
                                    if ($this->gameHasCombatResolutionMode == true) {
                                        $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                    } else {
                                        $this->mode = FIRE_COMBAT_SETUP_MODE;
                                    }
                                }
                            }else{
                                $this->moveRules->endAdvancing($this->moveRules->movingUnitId);
                                if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                    if ($this->gameHasCombatResolutionMode == true) {
                                        $this->mode = COMBAT_RESOLUTION_MODE;
                                    } else {
                                        $this->mode = COMBAT_SETUP_MODE;
                                    }
                                } else {
                                    if ($this->gameHasCombatResolutionMode == true) {
                                        $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                    } else {
                                        $this->mode = FIRE_COMBAT_SETUP_MODE;
                                    }
                                }
                            }
                            return true;
                        }
                        return false;
                        break;

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        $this->moveRules->advanceUnit($event, $id, $location);

                        if ($this->force->unitsAreAdvancing() == false) { // melee
                            if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                if ($this->gameHasCombatResolutionMode == true) {
                                    $this->mode = COMBAT_RESOLUTION_MODE;
                                } else {
                                    $this->mode = COMBAT_SETUP_MODE;
                                }
                            } else {
                                if ($this->gameHasCombatResolutionMode == true) {
                                    $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                } else {
                                    $this->mode = FIRE_COMBAT_SETUP_MODE;
                                }
                            }
                        }
                        break;
                }
                break;
            case EXCHANGING_MODE:
            case ATTACKER_LOSING_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        $unit = $this->force->getUnit($id);
                        if ($unit->setStatus(STATUS_EXCHANGED)) {
                            $this->force->exchangeUnit($unit);
                            if ($this->force->unitsAreBeingEliminated() == true) {
                                $this->force->removeEliminatingUnits();
                            }
                            $outOfMen = false;

                            if(($this->mode === ATTACKER_LOSING_MODE || $this->mode === EXCHANGING_MODE) && $this->combatRules->noMoreAttackers()){
                                $outOfMen = true;
                            }
                            if($this->legacyExchangeRule || $this->force->getExchangeAmount() <= 0 || $outOfMen === true) {
                                if ($this->force->exchangingAreAdvancing() == true ) { // melee
                                    $this->mode = ADVANCING_MODE;
                                } else {
                                    if($this->force->defendingAreRetreating() === true){
                                        $this->mode = RETREATING_MODE;
                                    }else{
                                        if($this->force->attackingAreRetreating() === true) {
                                            $this->mode = RETREATING_MODE;
                                        }else {
                                            if ($this->force->attackingAreAdvancing() === true) {

                                                $this->mode = ADVANCING_MODE;

                                            } else {
                                                if ($this->force->unitsAreRetreating() == true) {
                                                    $this->force->clearRetreatHexagonList();
                                                    $this->mode = RETREATING_MODE;
                                                } else { // check if advancing after eliminated unit
                                                    if ($this->force->unitsAreAdvancing() == true) {
                                                        $this->mode = ADVANCING_MODE;
                                                    } else {
                                                        $this->mode = COMBAT_RESOLUTION_MODE;

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }
                break;
            case DEFENDER_LOSING_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        $unit = $this->force->getUnit($id);
                        if ($unit->setStatus(STATUS_EXCHANGED)) {
                            $this->force->defenderLoseUnit($unit);
                            if ($this->force->unitsAreBeingEliminated() == true) {
                                $this->force->removeEliminatingUnits();
                            }
                            $outOfMen = false;
                            if($this->combatRules->noMoreDefenders()){
                                $outOfMen = true;
                            }

                            if($this->force->getDefenderLosingAmount() <= 0 || $outOfMen === true) {
                                    if($this->force->defendingAreRetreating() === true){
                                        $this->mode = RETREATING_MODE;
                                    }else{
                                        if($this->force->attackingAreRetreating() === true) {
                                            $this->mode = RETREATING_MODE;
                                        }else {
                                            if ($this->force->unitsAreAttackerLosing() == true) {
                                                $this->mode = ATTACKER_LOSING_MODE;
                                            }else{
                                            if ($this->force->attackingAreAdvancing() === true) {
                                                $this->mode = ADVANCING_MODE;

                                            } else {
                                                if ($this->force->unitsAreRetreating() == true) {
                                                    $this->force->clearRetreatHexagonList();
                                                    $this->mode = RETREATING_MODE;
                                                } else { // check if advancing after eliminated unit
                                                    if ($this->force->unitsAreAdvancing() == true) {
                                                        $this->mode = ADVANCING_MODE;
                                                    } else {
                                                        $this->mode = COMBAT_RESOLUTION_MODE;

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }
                break;        }

//        $this->interactions[] = $interaction;

        return true;

    }

    function currentPhase($phase = false){
        if($phase === false){
            $phase = $this->phase;
        }
        foreach($this->phaseChanges as $phaseChange){
            if($phaseChange->currentPhase === $phase){
                return $phaseChange;
            }
        }
        return false;
    }

    function phaseJump($attackingId, $defendingId, $phase = false, $incTurn = false, $mode= false){

        if($phase){
            $this->phase = $phase;
            if($mode){
                $this->mode = $mode;
            }else{
                $this->mode = REPLACING_MODE;
            }
        }
        if($incTurn){
            $this->incrementTurn();
        }
        $this->attackingForceId = $attackingId;
        $this->defendingForceId = $defendingId;
        $this->force->setAttackingForceId($attackingId, $defendingId);
        $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);
    }

    function selectNextPhase($click){
        $ret = $this->endPhase($click);

        $numCombines = 0;
        if (method_exists($this->force, 'getCombine')) {
            $numCombines = $this->force->getCombine();
        }

        if ($ret === true && $this->mode === COMBINING_MODE && $numCombines === 0) {
            $this->flashMessages[] = "No Combines Possible. Skipping to Next Phase.";
            $ret = $this->endPhase($click);
        }
        do {
            $didOne = false;

            if ($ret === true &&
                (($this->mode === COMBAT_SETUP_MODE &&
                        ($this->phase === BLUE_COMBAT_PHASE || $this->phase === RED_COMBAT_PHASE))
                    || ($this->mode === FIRE_COMBAT_SETUP_MODE)
                ) &&
                $this->force->anyCombatsPossible === false) {
                $didOne = true;
                $this->flashMessages[] = "No Combats Possible.";
                $ret = $this->endPhase($click);
            }
        }while($didOne === true);
        return $ret;

    }
    function endPhase($click)
    {
        global $phase_name;
        /* @var Battle $battle */
        $battle = Battle::getBattle();
        $victory = $battle->victory;

        if($victory->vetoPhaseChange()){
            return true;
        }
        if($this->mode == MOVING_MODE && $this->moveRules->movesLeft()){
            return false;
        }
        if ($this->moveRules->anyUnitIsMoving) {
            $this->moveRules->stopMove($this->force->units[$this->moveRules->movingUnitId]);
        }
        if ((($this->gameHasCombatResolutionMode  == false) ||  ($this->force->moreCombatToResolve() == false)) && $this->moveRules->anyUnitIsMoving == false) {
            $currentPhase = $this->currentPhase();
            if ($currentPhase) {
                $victory->nextPhase();
                $this->phase = $currentPhase->nextPhase;
//                    $prevMode = $this->mode;
                $this->mode = $currentPhase->nextMode;
//                    if($this->gameHasCombatResolutionMode === false && $this->mode == COMBAT_RESOLUTION_MODE && $prevMode == COMBAT_SETUP_MODE){
//                        $this->combatRules->combatsToResolve = $this->combatRules->combats;
//                    }

                $this->replacementsAvail = false;
                $this->phaseClicks[] = $click + 1;
                $this->phaseClickNames[] = $phase_name[$this->phase];

                if ($currentPhase->phaseWillIncrementTurn == true) {
                    $this->incrementTurn();
                }

                if ($this->attackingForceId != $currentPhase->nextAttackerId) {
                    $battle = Battle::getBattle();
                    $players = $battle->players;
                    $this->playTurnClicks[] = $click + 1;
                    if ($players[1] != $players[2]) {
                        Battle::pokePlayer($players[$currentPhase->nextAttackerId]);
                    }
                    $victory->playerTurnChange($currentPhase->nextAttackerId);
                }

                $this->attackingForceId = $currentPhase->nextAttackerId;
                $this->defendingForceId = $currentPhase->nextDefenderId;

                $this->force->setAttackingForceId($this->attackingForceId, $this->defendingForceId);

                $victory->phaseChange();
                $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);

                if ($this->turn > $this->maxTurn) {
                    $victory->gameEnded();
                    $this->flashMessages[] = "@gameover";
                }


                return true;
            }

        }
        return false;
    }

    function incrementTurn()
    {
        $this->turn++;
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->incrementTurn();
    }

    function xxxgetInfo()
    {

        //	var info;
        global $phase_name, $force_name, $mode_name;
        $info = "turn: " . $this->turn;
        $info .= " " . $phase_name[$this->phase];
        $info .= " ( " . $force_name[$this->force->getVictorId()];
        if ($this->turn < $this->maxTurn) {
            $info .= " is winning )";
        } else {
            $info .= " wins! )";
        }
        $info .= "<br />&nbsp; " . $mode_name[$this->mode];
        $info .= "<br />last force to occupy Marysville wins";

        return $info;
    }
}
