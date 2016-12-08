<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Wargame\Mollwitz;
use \Wargame\Battle;
class victoryCore extends \Wargame\VictoryCore
{
    public $victoryPoints = [0,0,0];
    public $movementCache;
    public $headQuarters = [];
    public $histogram = [[0,0,0]];
    public $casualties = [0,0,0];

    function __construct($data)
    {
        parent::__construct($data);
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->headQuarters = $data->victory->headQuarters;
            $this->casualties = $data->victory->casualties;

            if(isset($data->victory->histogram)) {
                $this->histogram = $data->victory->histogram;
                $turn = $data->gameRules->turn;
                if (!isset($this->histogram[$turn])) {
                    $this->histogram[$turn] = [0, 0, 0];
                }
            }
        } else {
            $this->movementCache = new \stdClass();
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->headQuarters = $this->headQuarters;
        $ret->histogram = $this->histogram;
        $ret->casualties = $this->casualties;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $this->scoreKills($unit, 1);
    }

    public function scoreKills($unit, $mult = 1){

        $force_name = \Wargame\Battle::$forceName;
        $b = \Wargame\Battle::getBattle();
        $turn = $b->gameRules->turn;



        if ($unit->forceId == 1) {
            $victorId = 2;
        } else {
            $victorId = 1;
        }

        $victorName = $force_name[$victorId];
        $vp = $unit->damage * $mult;
        $this->histogram[$turn][$victorId] += $vp;
        $this->victoryPoints[$victorId] += $vp;
        $this->casualties[$unit->forceId] += $vp;
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $class = "${victorName} victory-points";
        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$class'>+$vp VP's</span>";
    }


    public function phaseChange()
    {
    }

    protected function checkVictory( $battle){
        return false;
    }


    public function gameEnded(){
        if($this->gameOver){
            return true;
        }
        $battle = Battle::getBattle();
        $this->checkVictory($battle);
    }

    public function playerTurnChange($arg){

        $force_name = \Wargame\Battle::$forceName;

        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $gameRules->flashMessages[] = "@hide crt";

        if($this->checkVictory($battle)){
            return;
        }

        $gameRules->flashMessages[] = $force_name[$attackingId]." Player Turn";
    }

    public function postCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        foreach ($attackers as $attackerId => $val) {
            $unit = $b->force->units[$attackerId];
            if ($unit->class == "artillery" && $unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }

    public function calcFromAttackers(){
        $mapData = \Wargame\MapData::getInstance();

        $battle = Battle::getBattle();
        /* @var CombatRules $cR */
        $cR = $battle->combatRules;
        /* @var Force $force */
        $force = $battle->force;
        $force->clearRequiredCombats();
        $defenderForceId = $force->defendingForceId;
        foreach($cR->attackers as $attackId => $combatId){
            $mapHex = $mapData->getHex($force->getUnitHexagon($attackId)->name);
            $neighbors = $mapHex->getZocUnits($defenderForceId);
            foreach($neighbors as $neighbor){
                /* @var MapHex $hex */
                $hex = $mapData->getHex($force->getUnit($neighbor)->hexagon->name);
                if($hex->isOccupied($defenderForceId)){
                    $units = $hex->forces[$defenderForceId];
                    foreach($units as $unitId=>$unitVal){
                        $requiredVal = true;
                        $combatId = isset($cR->defenders->$unitId) ? $cR->defenders->$unitId : null ;
                        if($combatId !== null){
                            $attackers = $cR->combats->$combatId->attackers;
                            if($attackers){
                                if(count((array)$attackers) > 0){
                                    $requiredVal = false;
                                }
                            }

                        }

                        $force->requiredDefenses->$unitId = $requiredVal;
                    }
                }
            }
        }
    }
    public function postUnsetAttacker($args){
        $this->calcFromAttackers();
        list($unit) = $args;
        $id = $unit->id;
    }
    public function postUnsetDefender($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetAttacker($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }

    public function postSetDefender($args){
        $this->calcFromAttackers();

    }

    public function preUnsetAttacker($args){
        list($cd, $id) = $args;

//        $this->combats->$oldCd->removeAttacker($id);
        $b = Battle::getBattle();
        $unit = $b->force->units[$id];
//dd($id);
//        $b->combatRules->addAttacker($cd, $id, $dId, $bearing);
        $hexNum = $unit->hexagon->name;
        $mapHex = $b->mapData->getHex($hexNum);
        $forces = $mapHex->getForces($unit->forceId);
        $numForces = count((array)$forces);


        if($unit->class === "hq"){
            /*
             * hq by itself may not attack
             */
            if($numForces == 1){
                return;
            }
            foreach($forces as $force){
                $aUnit = $b->force->units[$force];
                if($aUnit->id == $id){
                    continue;
                }
                if($aUnit->name === "smallunit"){
                    continue;
                }

                $b->combatRules->removeAttacker($cd, $aUnit->id);
                return;
            }
            return;

        }else{
            $hexNum = $unit->hexagon->name;
            $mapHex = $b->mapData->getHex($hexNum);
            $forces = $mapHex->getForces($unit->forceId);
            if($unit->name === "smallunit"){
                return;
            }
            foreach($forces as $force){
                $aUnit = $b->force->units[$force];
                if($aUnit->id == $id){
                    continue;
                }

                if($aUnit->class === "hq"){
                    /*
                     * blah, for now, only 1 unit required to attack with hq.
                     */
                    $b->combatRules->removeAttacker($cd, $aUnit->id);
                    return;
                }
            }
        }

    }

    public function preSetAttacker($args){
        list($cd, $id, $dId, $bearing) = $args;
        $b = Battle::getBattle();
        $unit = $b->force->units[$id];
//dd($id);
//        $b->combatRules->addAttacker($cd, $id, $dId, $bearing);
        $hexNum = $unit->hexagon->name;
        $mapHex = $b->mapData->getHex($hexNum);
        $forces = $mapHex->getForces($unit->forceId);
        $numForces = count((array)$forces);
        if($unit->class === "hq"){
            /*
             * hq by itself may not attack
             */
            if($numForces == 1){
                $b->combatRules->removeAttacker($cd, $id);
                return;
            }
            foreach($forces as $force){
                $aUnit = $b->force->units[$force];
                if($aUnit->id == $id){
                    continue;
                }
                if($aUnit->name === "smallunit"){
                    continue;
                }
                if($aUnit->class === "hq"){
                    continue;
                }
                /*
                 * blah, for now, only 1 non hq or smallunit unit required to attack with hq.
                 */
                $b->combatRules->addAttacker($cd, $aUnit->id, $dId, $bearing);
                return;
            }
            $b->combatRules->removeAttacker($cd, $id);
//            $b->combatRules->addAttacker($cd, 20, $dId, $bearing);

//            dd($id);
//            dd($cd);
        }else{
            $hexNum = $unit->hexagon->name;
            $mapHex = $b->mapData->getHex($hexNum);
            $forces = $mapHex->getForces($unit->forceId);
            if($unit->name === "smallunit"){
                return;
            }
            foreach($forces as $force){
                $aUnit = $b->force->units[$force];
                if($aUnit->id == $id){
                    continue;
                }
                if($aUnit->class === "hq"){
                    /*
                     * blah, for now, only 1 unit required to attack with hq.
                     */
                    $b->combatRules->addAttacker($cd, $aUnit->id, $dId, $bearing);
                    return;
                }
            }
        }
    }

    public function preRecoverUnits(){
        $this->headQuarters = [];
        $b = Battle::getBattle();
        $units = $b->force->units;
        foreach($units as $unit){
            if($unit->class == 'hq' && $unit->hexagon->name && $unit->forceId == $b->force->attackingForceId){
                $this->headQuarters[] = $unit->id;
            }
        }

    }

    public function preRecoverUnit($arg){

    }

    public function checkCommand($unit){
        $id = $unit->id;
        $b = Battle::getBattle();
        $unit->removeAdjustment('movement');

        if(($b->gameRules->phase == RED_MOVE_PHASE || $b->gameRules->phase == BLUE_MOVE_PHASE)) {
            if ($unit->forceId === $b->gameRules->attackingForceId) {
                foreach ($this->headQuarters as $hq) {

                    $cmdRange = $b->force->units[$hq]->cmdRange;
                    if ($id == $hq) {
                        return;
                    }
                    $los = new \Wargame\Los();

                    $los->setOrigin($b->force->getUnitHexagon($id));
                    $los->setEndPoint($b->force->getUnitHexagon($hq));
                    $range = $los->getRange();
                    if ($range <= $cmdRange) {
                        return;
                    }
                }
                $unit->addAdjustment('movement', 'halfMovement');
                return;
            }
        }

    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();

        /* Deal with Forced March */
        if(($b->gameRules->phase == RED_MOVE_PHASE || $b->gameRules->phase == BLUE_MOVE_PHASE) && $unit->forceMarch){
            $unit->forceMarch = false;
        }
        if(($b->gameRules->phase == RED_COMBAT_PHASE || $b->gameRules->phase == BLUE_COMBAT_PHASE) && $unit->forceMarch){
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
        if(!empty($b->scenario->commandControl)){
            $this->checkCommand($unit);
        }
        if($unit->hexagon->parent === 'deployBox' && $b->gameRules->mode !== DEPLOY_MODE){
            $unit->hexagon->parent = "not-used";
        }
    }
}