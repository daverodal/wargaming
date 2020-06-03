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
    public $builds;
    public $battles = [];
    public $resources;
    public $cities = [];
    public $casualities;
    public $log = [];
    public $collectedThisTurn = [null,null,null];

    function save()
    {
        unset($this->moveRules);
        unset($this->force);
        unset($this->combatRules);
        $data = new stdClass();
        foreach ($this as $k => $v) {
            if (is_object($v) && ($k !== 'builds' && $k !== 'commands' && $k !== 'casualities' && $k !== 'log' && $k !== 'collectedThisTurn' && $k !== 'resources')) {
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
            $this->casualities = new \stdClass();
            $this->builds = new \stdClass();
            $this->battles = [];

            $this->resources = [];
            $amount = new \stdClass();;
            $amount->energy = $amount->materials = $amount->food = 3;

            $this->resources = [clone $amount,clone $amount ,clone $amount];
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
        $this->cities = [0,0,0];
        $areas = Battle::getBattle()->areaModel->areas;
        foreach($areas as $key => $area){
            if(count((array)$area->armies) === 0){
                continue;
            }
            if(count((array)$area->armies) === 1){
                $prevOwner = $area->owner ?? null;
                $newOwner = $area->owner = array_keys(get_object_vars($area->armies))[0];
                if($prevOwner !== $newOwner){
                    $report = "Change Ownership ". $area->name. " new owner ".$area->owner;
                    $battleReport = new \stdClass();
                    $battleReport->report = $report;
                    $battleReport->location = $key;
                    $this->battles[] = $battleReport;

                }
                if($area->isCity ?? false){
                    $this->cities[$area->owner]++;
                }
                continue;
            }
            if(count((array)$area->armies) > 1){
                $cas = $this->casualities->$key ?? 0;

                $p1 = "1";
                $p2 = "2";
                $report = "battle at ".$area->name;
                if($area->armies->$p1 == $area->armies->$p2){
                    $cas += $area->armies->$p1;
                    $report .= " both sides lost ".$area->armies->$p1. " nobody controls the area";
                    unset($area->armies->$p1);
                     unset($area->armies->$p2);
                    $area->owner = 0;

                }else{
                    if($area->armies->$p1 > $area->armies->$p2){
                        $report .= " both sides lost ".$area->armies->$p2 . " 1 controls the area";
                        $cas += $area->armies->$p2;
                        $area->armies->$p1 -= $area->armies->$p2;
                        $area->owner = $p1;
                        unset($area->armies->$p2);


                    } else {
                        $report .= " both sides lost ".$area->armies->$p1 . " 2 controls the area";
                        $cas += $area->armies->$p1;
                        $area->armies->$p2 -= $area->armies->$p1;
                        $area->owner = $p2;
                        unset($area->armies->$p1);
                    }
                }
                $this->casualities->$key = $cas;
                $battleReport = new \stdClass();
                $battleReport->report = $report;
                $battleReport->location = $key;
                $this->battles[] = $battleReport;
                continue;
            }
            $areas->{$area->id} = $area;
        }
    }

    function collectResources($area){
        $amount = $this->resources[$area->owner];
        $collected = $this->collectedThisTurn[$area->owner];
        $log = "";
//        $this->log[] = ["In collectResources ".$area->name];
        switch($area->terrainType){
            case 'desert':
                $amount->energy += 2;
                $collected->energy += 2;
                $log = "e +2";
                break;
            case 'field':
                $amount->food += 2;
                $collected->food += 2;

                $log = "f +2";
                break;
            case 'pasture':
                $amount->food += 1;
                $collected->food += 1;

                $log = "f +1";
                break;
            case 'forest':
                $amount->materials += 1;
                $collected->materials += 1;
                $log = "m +1";
                break;
            case 'mountain':
                $amount->materials += 2;
                $collected->materials += 2;
                $log = "m +2";
                break;
            case 'water':
                $log = "e +1";
                $amount->energy += 1;
                $collected->energy += 1;
                break;
        }
        $this->resources[$area->owner] = $amount;
        $this->collectedThisTurn[$area->owner] = $collected;
        return $log;
    }
    function gatherResources(){
        $areas = Battle::getBattle()->areaModel->areas;

        $turnLog = [];
        $taxed = new stdClass();
        $taxed->food = $taxed->materials =  $taxed->energy = 0;

        $this->collectedThisTurn = [clone $taxed, clone $taxed , clone $taxed];
        $turnLog[] = "Turn ". $this->turn;
        $this->en = $this->fo = $this->ma = 0;
        foreach($areas as $key => $area){
            if($area->isCity ?? false){
                if($area->owner ?? 0 > 0){
                    $line =  $area->owner. " " . $area->name;
                    $res = $this->collectResources($area);
                    $turnLog[] = $line . " $res";

                    foreach($area->neighbors as $neighbor){
                        $neiOwner = $areas->{$neighbor}->owner ?? 0;
                        if($neiOwner ==  $area->owner){
                            $line =  "neighbor " . $areas->{$neighbor}->name;
                            $res = $this->collectResources($areas->{$neighbor});
                            $turnLog[] = $line . " $res";
                        }
                    }
                }
            }
        }
        $p1 = $this->collectedThisTurn[1];
        $p2 = $this->collectedThisTurn[2];
        $turnLog[] = "f ". $p1->food ." e " . $p1->energy . " m ". $p1->materials;
        $turnLog[] = "f ". $p2->food ." e " . $p2->energy . " m ". $p2->materials;
        $p1 = $this->resources[1];
        $p2 = $this->resources[2];
        $turnLog[] = "tot f ". $p1->food ." e " . $p1->energy . " m ". $p1->materials;
        $turnLog[] = "tot f ". $p2->food ." e " . $p2->energy . " m ". $p2->materials;


        $this->log[] = $turnLog;
//        $this->log[] = $turnLog;
    }
    function executeBuilds(){
        $areas = Battle::getBattle()->areaModel->areas;

        $p1 = $this->resources[1];
        $p2 = $this->resources[2];
        $turnLog[] = "before f ". $p1->food ." e " . $p1->energy . " m ". $p1->materials;
        $turnLog[] = "before f ". $p2->food ." e " . $p2->energy . " m ". $p2->materials;


        $this->log[] = $turnLog;

        foreach($this->builds as $user => $builds){
            foreach($builds as $build ) {
                if(is_array($build)){
                    $selected = $build['selected'];
                    $playerId = $build['playerId'];
                }else{
                    $selected = $build->selected;
                    $playerId = $build->playerId;
                }

                $resourcePlayer = $this->resources[$playerId];
                $resourcePlayer->food--;
                $resourcePlayer->energy--;
                $resourcePlayer->materials--;
                $areas->$selected->armies->$playerId += 1;
            }
        }
        $turnLog = [];
        $p1 = $this->resources[1];
        $p2 = $this->resources[2];
        $turnLog[] = "aft f ". $p1->food ." e " . $p1->energy . " m ". $p1->materials;
        $turnLog[] = "aft f ". $p2->food ." e " . $p2->energy . " m ". $p2->materials;


        $this->log[] = $turnLog;

        $this->builds = new \stdClass();
    }
    function executeMoves($player){
        $areas = Battle::getBattle()->areaModel->areas;

        foreach($this->commands as $user => $commands){
            foreach($commands as $command ) {
                if(is_array($command)){
                    $from = $command['from'];
                    $to = $command['to'];
                    $playerId = $command['playerId'];
                    $amount = $command['amount'];
                }else{
                    $from = $command->from;
                    $to = $command->to;
                    $playerId = $command->playerId;
                    $amount = $command->amount;
                }

                if($playerId !== $player){
                    continue;
                }
                $prevAmount = $areas->$from->armies->$playerId ?? 0;
                if($amount > $prevAmount){
                    $amount = $prevAmount;
                    if($amount == 0){
                        continue;
                    }
                }
                $areas->$from->armies->$playerId = $prevAmount - $amount;
                $prevAmount = $areas->$to->armies->$playerId ?? 0;
                $areas->$to->armies->$playerId = $prevAmount + $amount;
            }
        }
    }
    function executeAllMoves(){
        $areas = Battle::getBattle()->areaModel->areas;
        $sortedCommands = [];
        foreach($this->commands as $user => $commands){
            foreach($commands as $command ) {
                if(is_array($command)){
                    $from = $command['from'];
                    $to = $command['to'];
                    $playerId = $command['playerId'];
                    $amount = $command['amount'];
                    $command['user'] = $user;
                }else{
                    $from = $command->from;
                    $to = $command->to;
                    $playerId = $command->playerId;
                    $amount = $command->amount;
                    $command->user = $user;
                }
                if(!isset($sortedCommands[$amount])){
                    $sortedCommands[$amount] = [];
                }
                $sortedCommands[$amount][] = $command;

            }
        }
        ksort($sortedCommands, SORT_NUMERIC);
        foreach($sortedCommands as $commands){
            foreach($commands as $command){
            if(is_array($command)){
                $from = $command['from'];
                $to = $command['to'];
                $playerId = $command['playerId'];
                $amount = $command['amount'];
            }else{
                $from = $command->from;
                $to = $command->to;
                $playerId = $command->playerId;
                $amount = $command->amount;
            }

            $prevAmount = $areas->$from->armies->$playerId ?? 0;
            if($amount > $prevAmount){
                $amount = $prevAmount;
                if($amount == 0){
                    continue;
                }
            }
            $areas->$from->armies->$playerId = $prevAmount - $amount;
            $prevAmount = $areas->$to->armies->$playerId ?? 0;
            $areas->$to->armies->$playerId = $prevAmount + $amount;
            $this->determineOwnership();
            }
        }
    }

    function runCommands(){

        $this->casualities = new \stdClass();
        $this->determineOwnership();
        switch($this->phase){
            case RESULTS_PHASE:
                $this->determineOwnership();
                $this->gatherResources();
                $this->phase = PRODUCTION_PHASE;
                $this->battles = [];
                $this->turn++;
                break;
            case PRODUCTION_PHASE:
                $this->determineOwnership();
                $this->executeBuilds();
                $this->phase = COMMAND_PHASE;
                break;
            case COMMAND_PHASE:
                $this->battles = [];
//                $this->executeMoves(2);
//                $this->determineOwnership();
//                $this->executeMoves(1);
//                $this->determineOwnership();
            $this->executeAllMoves();
                $this->commands = new \stdClass();
                $this->phase = RESULTS_PHASE;
        }

    }
    function processEvent($event,  $commands, $builds, $user,$location, $click)
    {

        $battle = Battle::getBattle();
        $players = $battle->players;

        foreach($players as $id => $player){
            if($user === $player){

                $this->commands->$user = $commands;
                $this->builds->$user = $builds;
                $battle->playersReady->toggleReady($id);
            }
        }
        if($battle->playersReady->allReady()){
            $this->runCommands();
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