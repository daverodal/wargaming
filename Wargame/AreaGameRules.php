<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12/7/19
 * Time: 12:40 PM
 *
 * /*
 * Copyright 2012-2019 David Rodal
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

namespace Wargame;
use stdClass;

class AreaGameRules
{
    // class references
    /* @var MoveRules $moveRules */
    public $moveRules;
    /* @var CombatRules */
    public $combatRules;
    /* @var Force */
    public $force;
    /* @var AreaPhaseChange[] */
    public $phaseChanges;
    public $flashMessages;
    public $flashLog;

    public $turn;
    public $maxTurn;
    public $phase;
    public $currentPhaseIndex;
    public $mode;
    public $interactions;
    public $phaseClicks;
    public $phaseClickNames;
    public $playTurnClicks;
    public $options = false;
    public $option;
    public $commands;

    function save()
    {
        unset($this->moveRules);
        unset($this->force);
        unset($this->combatRules);
        $data = new stdClass();
        foreach ($this as $k => $v) {
            if (is_object($v) && $k !== 'commands') {
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

    function __construct( $data = null)
    {

        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "phaseChanges") {
                    $this->phaseChanges = array();
                    foreach ($v as $phaseChange) {
                        $this->phaseChanges[] = new AreaPhaseChange($phaseChange);
                    }
                    continue;
                }
                $this->$k = $v;
            }

        } else {

            $this->phaseChanges = array();

            $this->turn = 1;
            $this->trayX = 0;
            $this->trayY = 0;

            $this->interactions = array();
            $this->phaseClicks = array();
            $this->playTurnClicks = array();
            $this->options = false;

            $this->flashLog = [];
            $this->flashMessages = [];
            $this->currentPhaseIndex = 0;
            $this->commands = new \stdClass();
        }
        if(!isset($this->flashLog)){
            $this->flashLog = [];
        }
        array_splice($this->flashLog, count($this->flashLog),0, $this->flashMessages);
    }

    function setMaxTurn($max_Turn)
    {

        $this->maxTurn = $max_Turn;
    }

    function setInitialPhaseMode()
    {
        $indx = $this->currentPhaseIndex = 0;
        $this->phase = $this->phaseChanges[$indx]->phase;
        $this->mode = $this->phaseChanges[$indx]->mode;
    }
    function addPhaseChange($currentPhase, $currentMode,bool $phaseWillIncrementTurn)
    {

        $phaseChange = new AreaPhaseChange();
        $phaseChange->set($currentPhase, $currentMode, $phaseWillIncrementTurn);
        $this->phaseChanges[] = $phaseChange;
    }

    function determineOwnership(){
        $areas = Battle::getBattle()->areaModel->areas;
        foreach($areas as $key => $area){
            if(count((array)$area->armies) === 0){
                continue;
            }
            if(count((array)$area->armies) === 1){
                $area->owner = array_keys(get_object_vars($area->armies));
                continue;
            }
            if(count((array)$area->armies) > 1){
                $area->owner = 0;
                continue;
            }
        }
    }

    function runCommands(){
        $areas = Battle::getBattle()->areaModel->areas;

        foreach($this->commands as $user => $commands){
            foreach($commands as $command ) {
                $from = $command['from'];
                $to = $command['to'];
                $playerId = $command['playerId'];
                $amount = $command['amount'];
                $prevAmount = $areas->$from->armies->$playerId ?? 0;
                $areas->$from->armies->$playerId = $prevAmount - $amount;
                $prevAmount = $areas->$to->armies->$playerId ?? 0;
                $areas->$to->armies->$playerId = $prevAmount + $amount;
            }
        }
        $this->commands = new \stdClass();
        $this->determineOwnership();
    }
    function processEvent($event,  $commands, $user,$location, $click)
    {

        $battle = Battle::getBattle();
        $players = $battle->players;


        foreach($players as $id => $player){
            if($user === $player){
                $this->commands->$user = $commands;
                $battle->playersReady->toggleReady($id);
            }
        }
        if($battle->playersReady->allReady()){
            $this->runCommands();
            $this->turn++;
            $battle->playersReady->clearAllReady();
        }
//        foreach($players as $id => $player) {
//            if ($user === $player) {
//                $battle->playersReady->toggleReady($id);
//            }
//        }

        return true;
        switch ($this->mode) {
            case Cnst::COMMAND_PHASE:

        }

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
        if ((($this->gameHasCombatResolutionMode  == false) ||  ($this->force->moreCombatToResolve() == false)) && $this->moveRules->anyUnitIsMoving == false) {
            $this->currentPhaseIndex++;
            if($this->currentPhaseIndex >= count($this->phaseChanges)){
                $this->currentPhaseIndex = 0;
            }
            $currentPhase = $this->phaseChanges[$this->currentPhaseIndex];
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
}