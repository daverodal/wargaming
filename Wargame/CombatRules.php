<?php
namespace Wargame;
use stdClass;
// combatRules->js

// Copyright (c) 2009-2011 Mark Butler
// Copyright 2012-2015 David Rodal

// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version->

// This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

use Wargame\Battle;

class CombatDefenders{
    function __construct($data = false){
        if($data){
            $this->hasInf = $data->hasInf;
            $this->hasCav = $data->hasCav;
            $this->hasSkrim = $data->hasSkirm;
            $this->hasBow = $data->hasBow;
            $this->hasHq = $data->hasHq ?? false;
        }
    }
    public $hasInf = false;
    public $hasCav = false;
    public $hasSkirm = false;
    public $hasBow = false;
    public $hasHq = false;

    function registerDefender($unit){
        switch($unit->class){
            case 'inf':
                $this->hasInf = true;
                break;
            case 'cavalry':
                $this->hasCav = true;
                break;
            case 'hq':
                $this->hasHq = true;
                break;
        }
        if($unit->bow){
            $this->hasBow = true;
        }
        if($unit->armorClass === 'S'){
            $this->hasSkrim = true;
        }
    }
}
class CombatRules
{
    // Class references
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

    // local publiciables
    /* @var CombatResultsTable */
    public $crt;
    public $currentDefender = false;
    public $defenders;
    public $combats;
    public $combatsToResolve;
    public $attackers;
    public $resolvedCombats;
    public $lastResolvedCombat;
    public $combatDefenders;
    public $unitsBlock = false;

    /*
     * TODO
     * This is how we serialized data in the ancient days...
     */
    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if ((is_object($v) && $k != 'combatDefenders' && $k != "defenders" && $k != "lastResolvedCombat" && $k != "resolvedCombats" && $k != "combats" && $k != "attackers" && $k != "combatsToResolve") || $k == "crt") {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    function __construct(Force $Force,Terrain $Terrain, $data = null)
    {
        $this->force = $Force;
        $this->terrain = $Terrain;

        if ($data) {
            foreach ($data as $k => $v) {
                if($k === "dieRoll"){
                    continue;
                }
                if($k === 'combatDefenders'){
                    $this->combatDefenders = new CombatDefenders($data->combatDefenders);
                }
                if($k === "combats" || $k === "combatsToResolve"){
                    $this->$k = new stdClass();
                    if(is_object($v)) {
                        foreach ($v as $kv => $vv) {
                            $this->$k->$kv = new Combat($vv);
                        }
                    }
                }else{
                    $this->$k = $v;
                }
            }
        } else {
            /*
             * TODO all this sucks and needs to be initialized
             */
            $this->combats = new stdClass();
            $this->attackers = new stdClass();
            $this->defenders = new stdClass();
            $this->currentDefender = false;
            $this->combatDefenders = new CombatDefenders();
        }
    }

    public function injectCrt(CombatResultsTable $crt){
        $this->crt = $crt;
    }

    public function removeAttacker($cd, $aId){
        $this->force->undoAttackerSetup($aId);

        unset($this->attackers->$aId);
        $this->combats->$cd->removeAttacker($aId);
        if($this->multiDefender() && !$this->adjacentAttacker()){
            $this->clearCurrentCombat();
        }
    }
    public function addFpf($cd, $id, $defenderId){
        $los = new Los();

        $unit = $this->force->units[$id];
        $los->setOrigin($this->force->getUnitHexagon($id));
        $los->setEndPoint($this->force->getUnitHexagon($cd));
        $range = $los->getRange();
        $bearing = $los->getBearing();
        if ($range <= $unit->getRange($id)) {
            $this->force->setupFpf($id, $range);
            $this->defenders->$id = $cd;
            $this->combatsToResolve->$cd->addFpf($id, $cd, $bearing);
            $this->crt->setCombatIndex($cd);
        }
    }
    public function removeFpf($cd, $id, $defenderId){

        unset($this->defenders->$id);

        $this->combatsToResolve->$cd->removeFpf($id);
        $this->force->removeFpf($id);

        $this->combatsToResolve->$cd->removeFpf($id, $cd);
        $this->crt->setCombatIndex($cd);
    }
    public function addAttacker($cd, $id, $defenderId, $bearing){
        $los = new Los();

        $unit = $this->force->units[$id];
        $los->setOrigin($this->force->getUnitHexagon($id));
        $los->setEndPoint($this->force->getUnitHexagon($defenderId));
        $range = $los->getRange();
        $bearing = $los->getBearing();
        if ($range <= $unit->getRange($id)) {
            $this->force->setupAttacker($id, $range);
            $this->attackers->$id = $cd;
            $this->combats->$cd->addAttacker($id, $defenderId, $bearing);
        }
    }

    function recalcCombat($id){
        $attackers = $this->attackers;
        $combatId = false;
        $defenders = $this->defenders;

        if(isset($attackers->$id)){
            $combatId = $attackers->$id;
        }elseif(isset($this->defenders->$id)){
            $combatId = $this->defenders->$id;
        }
        if($combatId !== false){
            $this->crt->setCombatIndex($combatId);
        }
    }

    function recalcCurrentCombat(){
        $cd = $this->currentDefender;

        if($cd !== false){
            $this->crt->setCombatIndex($cd);
        }
    }
    function pinCombat($pinVal)
    {
        $pinVal--; /* make 1 based 0 based */
        $cd = $this->currentDefender;
        if ($cd !== false) {
            if ($pinVal >= $this->combats->$cd->index || $this->combats->$cd->pinCRT === $pinVal) {
                $this->combats->$cd->pinCRT = false;
            } else {
                $this->combats->$cd->pinCRT = $pinVal;
            }
            $this->crt->setCombatIndex($cd);
        }
    }

    function noMoreDefenders(){

        $cd = $this->currentDefender;
        if ($cd !== false) {
            $attackers = $this->resolvedCombats->$cd->defenders;
            $battle = Battle::getBattle();
            foreach($attackers as $attackId => $val){
                if( $battle->force->units[$attackId]->status === STATUS_CAN_DEFEND_LOSE){
                    return false;
                }
            }
        }
        return true;
    }

    function noMoreAttackers(){

        $cd = $this->currentDefender;
        if ($cd !== false) {
            $attackers = $this->resolvedCombats->$cd->attackers;
            $battle = Battle::getBattle();
            foreach($attackers as $attackId => $val){
                if($battle->force->units[$attackId]->status === STATUS_CAN_ATTACK_LOSE || $battle->force->units[$attackId]->status === STATUS_CAN_DEFEND_LOSE || $battle->force->units[$attackId]->status === STATUS_CAN_EXCHANGE){
                    return false;
                }
            }
        }
        return true;
    }

    function clearCurrentCombat(){

        $cd = $this->currentDefender;
        if($cd === false){
            return false;
        }

        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $noCombats = true;

        foreach ($this->combats->{$this->currentDefender}->attackers as $attackerId => $attacker) {
            $noCombats = false;
            $this->force->undoAttackerSetup($attackerId);
            $victory->preUnsetAttacker($cd, $attackerId);
            $this->removeAttacker($cd, $attackerId);
            $victory->postUnsetAttacker($this->force->units[$attackerId]);
        }
        $this->combats->$cd->useDetermined = false;
        $this->crt->setCombatIndex($cd);
        if($noCombats === true){
            return false;
        }
        return true;

    }

    function multiDefender(){
        if($this->currentDefender === false){
            return false;
        }
        if(!$this->combats->{$this->currentDefender}){
            return false;
        }
        if(!$this->combats->{$this->currentDefender}->defenders){
            return false;
        }
        $defenders = $this->combats->{$this->currentDefender}->defenders;
        $hexes = [];
        foreach($defenders as $defenderId=>$defenderVal){
            $hex = $this->force->getUnitHexagon($defenderId);
            $hexes[$hex->name] = true;
        }
        return count($hexes) > 1;
    }

    function adjacentAttacker(){
        if($this->currentDefender === false){
            return false;
        }
        if(!$this->combats->{$this->currentDefender}){
            return false;
        }
        if(!$this->combats->{$this->currentDefender}->attackers){
            return false;
        }

        $attackers = $this->combats->{$this->currentDefender}->attackers;
        $los = new Los();
        foreach($attackers as $aId => $attacker){
            $los->setOrigin($this->force->getUnitHexagon($aId));
            $los->setEndPoint($this->force->getUnitHexagon($this->currentDefender));
            $range = $los->getRange();
            if($range === 1.0){
                return true;
            }
        }
        return false;
    }

    function setupCombat($id, $shift = false)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $unit = $battle->force->units[$id];
        $id = "$id";

        $cd = $this->currentDefender;
        if ($this->force->unitIsEnemy($id) == true) {
            // defender is already in combatRules, so make it currently selected
//            if(isset($this->defenders->$id)){
//                $id = $this->defenders->$id;
//            }

            $combats = $combatId = false;

            if (isset($this->defenders->$id)) {
                $combatId = $this->defenders->$id;
//                $cd = $this->defenders->$id;
                if(isset($this->combats->$combatId)) {
                    $combats = $this->combats->$combatId;
                }
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
                            $victory->preUnsetAttacker($cd, $attackerId);
                            $this->removeAttacker($cd, $attackerId);
//                            unset($this->attackers->$attackerId);
//                            $this->combats->$cd->removeAttacker($attackerId);
//                            unset($this->combats->$cd->attackers->$attackerId);
//                            unset($this->combats->$cd->thetas->$attackerId);
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
                        if($this->force->units[$force]->class !== "air" &&  ($battle->gameRules->phase == RED_AIR_COMBAT_PHASE || $battle->gameRules->phase == BLUE_AIR_COMBAT_PHASE )) {
                            continue;
                        }
                        $this->defenders->$force = $id;
                        if($force != $id){
                            $cd = $this->currentDefender;
                            $this->force->setupDefender($force);
                            if (!$this->combats) {
                                $this->combats = new  stdClass();
                            }
                            if (empty($this->combats->$cd)) {
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
            $unit = $this->force->units[$id];

            if($this->force->units[$id]->class === "supply" && $this instanceof CombatSupply){
                $this->selectSupply($unit);
            }
            if ($this->currentDefender !== false && $this->force->units[$id]->status != STATUS_UNAVAIL_THIS_PHASE) {
                if (isset($this->combats->$cd->attackers->$id) && $this->combats->$cd->attackers->$id !== false && $this->attackers->$id === $cd) {
                    $this->force->undoAttackerSetup($id);
                    $victory->preUnsetAttacker($cd, $id);
                    $this->removeAttacker($cd, $id);
//                    unset($this->attackers->$id);
//                    $this->combats->$cd->removeAttacker($id);
//                    unset($this->combats->$cd->attackers->$id);
//                    unset($this->combats->$cd->thetas->$id);
                    $victory->postUnsetAttacker($this->force->units[$id]);
                    $this->crt->setCombatIndex($cd);
                } else {
                    $good = true;
                    foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                        $los = new Los();

                        $los->setOrigin($this->force->getUnitHexagon($id));
                        $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                        $range = $los->getRange();
                        if ($range > $unit->getRange($id)) {
                            $good = false;
                            break;
                        }
                        if ($range > 1) {
                            if($this->checkBlocked($los,$id) === false){
                                $good = false;
                            }
                            if($this->multiDefender()){
                                $adjacentAttacker = $this->adjacentAttacker();
                                if(!$adjacentAttacker){
                                    $good = false;
                                }
                            }
                        }
                        if ($range == 1) {
                            if ($this->terrain->terrainIsHexSide($this->force->getUnitHexagon($id)->name, $this->force->getUnitHexagon($defenderId)->name, "blocked" )
                            && !($unit->class === "artillery" || $unit->class === "horseartillery") ) {
                                $good = false;
                            }
                        }
                        if(method_exists($unit, "checkLos")){
                            if($unit->checkLos($los, $defenderId) === false){
                                $good = false;
                            }
                        }
                        if($victory->isCombatVetoed($unit, $this->currentDefender) === true){
                            $good = false;
                        }

                    }
                    if ($good) {
                        foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                            $los = new Los();

                            $los->setOrigin($this->force->getUnitHexagon($id));
                            $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                            $range = $los->getRange();
                            $bearing = $los->getBearing();
                            if ($range <= $unit->getRange($id)) {
                                $this->force->setupAttacker($id, $range);
                                if (isset($this->attackers->$id) && $this->attackers->$id !== $cd) {
                                    /* move unit to other attack */
                                    $oldCd = $this->attackers->$id;
                                    $victory->preUnsetAttacker($oldCd, $id);
                                    $this->combats->$oldCd->removeAttacker($id);
//                                    unset($this->combats->$oldCd->attackers->$id);
//                                    unset($this->combats->$oldCd->thetas->$id);
                                    $this->crt->setCombatIndex($oldCd);
                                    $this->checkBombardment($oldCd);

                                }

                                $this->addAttacker($cd, $id, $defenderId, $bearing);
                                $victory->preSetAttacker($cd, $id, $defenderId, $bearing);
//                                $this->attackers->$id = $cd;
//                                $this->combats->$cd->addAttacker($id, $defenderId, $bearing);
//                                $this->combats->$cd->attackers->$id = $bearing;
//                                $this->combats->$cd->defenders->$defenderId = $bearing;
//                                if (empty($this->combats->$cd->thetas->$id)) {
//                                    $this->combats->$cd->thetas->$id = new stdClass();
//                                }
//                                $this->combats->$cd->thetas->$id->$defenderId = $bearing;
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
        $unit = $this->force->units[$id];
        $good = true;
        $hexParts = $los->getlosList();
        // remove first and last hexPart

        $src = array_shift($hexParts);
        $target = array_pop($hexParts);
        $srcElevated = $targetElevated = $srcElevated2 = $targetElevated2 = false;

        if ($this->terrain->terrainIs($src, "elevation2")) {
            $srcElevated2 = true;
        }
        if ($this->terrain->terrainIs($target, "elevation2")) {
            $targetElevated2 = true;
        }
        if ($this->terrain->terrainIs($src, "elevation1")) {
            $srcElevated = true;
        }
        if ($this->terrain->terrainIs($target, "elevation1")) {
            $targetElevated = true;
        }
        $hasElevated1 = $hasElevated2 = false;
        foreach ($hexParts as $hexPart) {

            if($this->unitsBlock || ($unit->unitsBlock ?? false)){
                $hexPart->calculateHexpartType();
                $hexPart->calculateHexpartName();
                if($hexPart->getHexpartType() == HEXAGON_CENTER){
                    $hexName =  $hexPart->getName();
                    $hexName = preg_replace("/hexpart:/","", $hexName);
                    $isIt = $mapData->hexagonIsOccupiedForce(new Hexagon($hexName), $unit->forceId);
                    $isIt |= $mapData->hexagonIsOccupiedEnemy(new Hexagon($hexName), $unit->forceId);
                    if($isIt){
                        return false;
                    }
                }else{
                    $neighbors = $hexPart->neighbors;
                    /*
                     * if both neighbors block, than the hexside blocks.
                     */
                    $both = 0;
                    foreach($neighbors as $neighbor){
                        $isIt = $mapData->hexagonIsOccupiedForce(new Hexagon($neighbor), $unit->forceId);
                        $isIt |= $mapData->hexagonIsOccupiedEnemy(new Hexagon($neighbor), $unit->forceId);
                        if($isIt){
                           $both++;
                        }
                    }
                    if($both === 2){
                        return false;
                    }
                }
            }
            $plusElevation = 0;
            if ($this->terrain->terrainIs($hexPart, "blocksRanged")) {
                $plusElevation = 1;
            }
            if ($this->terrain->terrainIs($hexPart, "elevation2")) {
                /*
                 * can't shoot over woods on a hill
                 */
                if($plusElevation){
                    return false;
                }
                $hasElevated2 = true;
                continue;
            }

            if ($this->terrain->terrainIs($hexPart, "elevation1")) {
                if($plusElevation){
                    $hasElevated2 = true;

                }else{
                    $hasElevated1 = true;
                }
                continue;
            }
            if($plusElevation){
                $hasElevated1 = true;
                continue;
            }
        }
        /* don't do elevation check if non elevation (1) set. This deals with case of coming up
         * back side of not circular hill
         */
        if($hasElevated1 || $targetElevated || $srcElevated){

            /*
             * Ugly if statement. If elevation1 both src and target MUST be elevation1 OR either src or target can be elevation2.
             * otherwise, blocked.
             */
            if($hasElevated1 && !(($srcElevated && $targetElevated) || ($targetElevated2 || $srcElevated2))){
                $good = false;
            }
            if ($hasElevated2 && (!$srcElevated2 || !$targetElevated2)) {
                $good = false;
            }


        }

        if ($good === false) {
            return false;
        }
        $mapHex = $mapData->getHex($this->force->getUnitHexagon($id)->name);
        if ($this->force->mapHexIsZOC($mapHex)) {
                return false;
        }
        return $good;
    }
    function checkBombardment($cd = false)
    {
        if($cd === false){
            $cd = $this->currentDefender;
        }
        $attackers = $this->combats->{$cd}->attackers;
        $defenders = $this->combats->{$cd}->defenders;
        foreach ($defenders as $defenderId => $defender) {
            foreach ($this->combats->{$cd}->attackers as $attackerId => $attacker) {
                $attacker = $this->force->getUnit($attackerId);
                if($attacker->class !== 'artillery' && $attacker->class !== 'horseartillery') {
                    $this->combats->{$cd}->isArtilleryOnly = false;
                    return;
                }
            }
        }
        $this->combats->{$cd}->isArtilleryOnly = true;
        $this->combats->{$cd}->useDetermined = false;
    }

    function useDetermined(){
        $cd = $this->currentDefender;
        $b = Battle::getBattle();
        $v = $b->victory;
        if($cd !== false){
            if($v->isDeterminedAble($cd, $this->combats->$cd)){
                $this->combats->$cd->useDetermined = $this->combats->$cd->useDetermined ? false : true;
                $this->recalcCurrentCombat();
                return true;
            }
        }
        return false;
    }
    function cleanUpAttacklessDefenders()
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        if (!$this->combats) {
            $this->combats = new stdClass();
        }
        foreach ($this->combats as $id => $combat) {
            if ($id === $this->currentDefender) {
                continue;
            }
            if (count((array)$combat->attackers) == 0) {
                foreach ($combat->defenders as $defenderId => $defender) {
                    $unit = $this->force->getUnit($defenderId);
                    $unit->setStatus( STATUS_READY);
                    unset($this->defenders->$defenderId);
                    $victory->postUnsetDefender($unit);
                }
                unset($this->combats->$id);
            }
        }
    }

    function setupFireCombat($id)
    {
    }

    function getDefenderTerrainCombatEffect($defenderId)
    {
        if(empty($this->combats->$defenderId->defenders)){
            $defenders = [];
        }else{
            $defenders = $this->combats->$defenderId->defenders;
        }
        $bestDefenderTerrainEffect = 0;
        foreach ($defenders as $defId => $def) {
            $unit = $this->force->getUnit($defId);
            $terrainCombatEffect = $this->terrain->getDefenderTerrainCombatEffect($unit->getUnitHexagon());
            if ($this->allAreAttackingAcrossRiver($defId)) {
                $riverCombatEffect = $this->terrain->getAllAreAttackingAcrossRiverCombatEffect();
                if ($riverCombatEffect > $terrainCombatEffect) {
                    $terrainCombatEffect = $riverCombatEffect;
                }
            }
            if ($terrainCombatEffect > $bestDefenderTerrainEffect) {
                $bestDefenderTerrainEffect = $terrainCombatEffect;
            }
        }
        return $bestDefenderTerrainEffect;
    }

    function cleanUp()
    {
        unset($this->combats);
        unset($this->resolvedCombats);
        unset($this->lastResolvedCombat);
        unset($this->combatsToResolve);
        $this->currentDefender = false;
        $this->attackers = new stdClass();
        $this->defenders = new stdClass();
    }

    function allAttackersArtillery($combatId){
        if($this->combatsToResolve->$combatId && $this->combatsToResolve->$combatId->attackers){
            $attackers = $this->combatsToResolve->$combatId->attackers;
            foreach($attackers as $attId => $attacker){
                if($this->force->units[$attId]->range == 1){
                    return false;
                }
            }
            return true;
        }
    }

    function anyArtilleryInRange($defId){
        $defenders = $this->combatsToResolve->$defId->defenders;
        $ret = false;
        foreach($defenders as $defId => $def){
            $defUnit = $this->force->units[$defId];
            foreach($this->force->units as $fpfId => $fpf){
                $fpf->fpfInRange = false;
                if($fpf->isOnMap() && $fpf->status === STATUS_READY && $defUnit->id !== $fpf->id && $defUnit->forceId === $fpf->forceId && $fpf->range > 1){
                    if($fpf->fpf > 0){
                        $los = new Los();
                        $los->setOrigin($this->force->getUnitHexagon($defId));
                        $los->setEndPoint($this->force->getUnitHexagon($fpfId));
                        $range = $los->getRange();
                        if($range > 0 && $range <= $fpf->range){
                            $fpf->fpfInRange = true;
                            $ret = true;
                        }
                    }
                }
            }
        }
        return $ret;
    }

    function resolveCombat($id)
    {
        $battle = Battle::getBattle();
        global $results_name;
        $this->combatDefenders = new CombatDefenders();

        if ($this->force->unitIsEnemy($id) && !isset($this->combatsToResolve->$id)) {
            if (isset($this->defenders->$id)) {
                $id = $this->defenders->$id;
            } else {
                return false;
            }
        }
        if ($this->force->unitIsFriendly($id)) {
            if (isset($this->attackers->$id)) {
                $id = $this->attackers->$id;
            } else {
                return false;
            }
        }
        if (!isset($this->combatsToResolve->$id)) {
            return false;
        }
        $this->currentDefender = $id;
        // Math->random yields number between 0 and 1
        //  6 * Math->random yields number between 0 and 6
        //  Math->floor gives lower integer, which is now 0,1,2,3,4,5

        $Die = $battle->dieRolls->getEvent($this->crt->dieSideCount);
//        $Die = 4;
        $index = $this->combatsToResolve->$id->index;
        if ($this->combatsToResolve->$id->pinCRT !== false) {
            if ($index > ($this->combatsToResolve->$id->pinCRT)) {
                $index = $this->combatsToResolve->$id->pinCRT;
            }
        }
        $combatResults = $this->crt->getCombatResults($Die, $index, $this->combatsToResolve->$id);
        $this->combatsToResolve->$id->Die = $Die + 1;
        $this->combatsToResolve->$id->combatResult = $results_name[$combatResults];
        $this->force->clearRetreatHexagonList();
        $this->force->clearExchangeAmount();

        /* determine which units are defending */
        $defenders = $this->combatsToResolve->{$id}->defenders;
        $defendingHexes = [];
        /* others is units not in combat, but in hex, combat results apply to them too */
        $others = [];
        $phase = $battle->gameRules->phase;

        foreach($defenders as $defenderId => $defender){
            $unit = $this->force->units[$defenderId];
            $this->combatDefenders->registerDefender($unit);
            $hex = $unit->hexagon;
            if($this->force->units[$defenderId]->class === "air" &&  ($phase == RED_COMBAT_PHASE || $phase == BLUE_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE)) {
//                unset($defenders->$defenderId);
                continue;
            }
            if(!isset($defendingHexes[$hex->name])){
                $mapHex = $battle->mapData->getHex($hex->getName());
                $hexDefenders = $mapHex->getForces($unit->forceId);
                foreach($hexDefenders as $hexDefender){
                    $hexUnit = $this->force->units[$hexDefender];
                    if($hexUnit->class !== "air" &&  ($battle->gameRules->phase == RED_AIR_COMBAT_PHASE || $battle->gameRules->phase == BLUE_AIR_COMBAT_PHASE )) {
                        continue;
                    }
                    if($hexUnit->class === "air" &&  ($phase == RED_COMBAT_PHASE || $phase == BLUE_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE)) {
                        continue;
                    }
                    if(isset($defenders->$hexDefender)){
                        continue;
                    }
                    $others[] = $hexDefender;
                }
            }
        }
        /* apply combat results to defenders */
        foreach ($defenders as $defenderId => $defender) {
            if(method_exists($this->crt, 'applyCRTResults')){
                $this->crt->applyCRTResults($defenderId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die,$this->force);

            }else{
                $this->force->applyCRTResults($defenderId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
            }
        }
        /* apply combat results to other units in defending hexes */
        foreach ($others as $otherId) {
            if(method_exists($this->crt, 'applyCRTResults')) {
                $this->crt->applyCRTResults($otherId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die, $this->force);
            }else{
                $this->force->applyCRTResults($otherId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
            }
        }
        $this->lastResolvedCombat = $this->combatsToResolve->$id;
        if (!$this->resolvedCombats) {
            $this->resolvedCombats = new stdClass();
        }
        $this->resolvedCombats->$id = $this->combatsToResolve->$id;
        unset($this->combatsToResolve->$id);
        foreach ($this->lastResolvedCombat->attackers as $attacker => $v) {
            unset($this->attackers->$attacker);
        }
        /* remove retreat list hexes that are still occupied */

        $this->force->groomRetreatList();
        return $Die;
    }

    function groomAdvancing(){
        $this->force->groomRetreatList();
        if(count($this->force->retreatHexagonList) === 0){
            $attackers = $this->lastResolvedCombat->attackers;
            foreach($attackers as $uId => $ignore){
                $unit = $this->force->getUnit($uId);
                if ($unit->status == STATUS_CAN_ADVANCE
                    || $unit->status == STATUS_ADVANCING
                    || $unit->status == STATUS_MUST_ADVANCE) {
                    $unit->status = STATUS_ATTACKED;
                }
            }
        }
    }


    function resolveFireCombat($id)
    {
    }

    function allAreAttackingAcrossRiver($defenderId)
    {

        $defenderId = $this->defenders->$defenderId;
        $allAttackingAcrossRiver = true;
        $attackerHexagonList = $this->combats->$defenderId->attackers;
        /* @var Hexagon $defenderHexagon */
        $unit = $this->force->getUnit($defenderId);
        $defenderHexagon = $unit->getUnitHexagon();
        foreach ($attackerHexagonList as $attackerHexagonId => $val) {
            /* @var Hexagon $attackerHexagon */
            $attackerUnit = $this->force->getUnit($attackerHexagonId);
            $attackerHexagon = $attackerUnit->getUnitHexagon();

            $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
            $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

            $hexside = new Hexpart($hexsideX, $hexsideY);

            if ($this->terrain->terrainIs($hexside, "river") === false && $this->terrain->terrainIs($hexside, "wadi") === false) {

                $allAttackingAcrossRiver = false;
            }
        }

        return $allAttackingAcrossRiver;
    }


    function allAreAttackingThisAcrossRiver($defenderId)
    {

        $combatId = $this->defenders->$defenderId;
        $allAttackingAcrossRiver = true;
        $attackerHexagonList = $this->combats->$combatId->attackers;
        /* @var Hexagon $defenderHexagon */
        $unit = $this->force->getUnit($defenderId);
        $defenderHexagon = $unit->getUnitHexagon();
        foreach ($attackerHexagonList as $attackerHexagonId => $val) {
            /* @var Hexagon $attackerHexagon */
            $attackerUnit = $this->force->getUnit($attackerHexagonId);
            $attackerHexagon = $attackerUnit->getUnitHexagon();

            $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
            $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

            $hexside = new Hexpart($hexsideX, $hexsideY);

            if ($this->terrain->terrainIs($hexside, "river") === false && $this->terrain->terrainIs($hexside, "wadi") === false) {

                $allAttackingAcrossRiver = false;
            }
        }

        return $allAttackingAcrossRiver;
    }

    function numDefenders($defenderId)
    {
        if(!isset($this->defenders->$defenderId)){
            return 0;
        }
        $defenderId = $this->defenders->$defenderId;
        if(!isset($this->combatsToResolve->$defenderId)){
            return 0;
        }
        return  count(get_object_vars($this->combatsToResolve->$defenderId->defenders));
    }

    function thisAttackAcrossRiver($defenderId, $attackerId, &$reason = "")
    {

        $attackerUnit = $this->force->getUnit($attackerId);
        $attackerHexagon = $attackerUnit->getUnitHexagon();
        /* @var Hexagon $defenderHexagon */
        $defenderUnit = $this->force->getUnit($defenderId);
        $defenderHexagon = $defenderUnit->getUnitHexagon();


        $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
        $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

        $hexside = new Hexpart($hexsideX, $hexsideY);

        if ($this->terrain->terrainIs($hexside, "river") === true) {
            $reason = "river";
            return true;
        }
        if ($this->terrain->terrainIs($hexside, "wadi") === true) {
            $reason = "wadi";
            return true;
        }
        if ($this->terrain->terrainIs($hexside, "blocksnonroad") === true) {
            $reason = "bridge";
            return true;
        }
        return false;
    }

    function thisAttackAcrossType($defenderId, $attackerId, $type){
        $los = new Los();

        $los->setOrigin($this->force->getUnitHexagon($defenderId));
        $los->setEndPoint($this->force->getUnitHexagon($attackerId));

        $hexParts = $los->getlosList();
        /*
         *  defender is located in hexParts[0],
         * x first hexside adjacent to defender is in $hexParts[1]
         */
        return ($this->terrain->terrainIs($hexParts[1], $type));

    }

    function thisAttackAcrossTwoType($defenderId, $attackerId, $type){
        $los = new Los();

        $los->setOrigin($this->force->getUnitHexagon($defenderId));
        $los->setEndPoint($this->force->getUnitHexagon($attackerId));

        $hexParts = $los->getlosList();
        /*
         *  defender is located in hexParts[0],
         * x first hexside adjacent to defender is in $hexParts[1] need to check second hexside for elevations checks from behind.
         */
        return ($this->terrain->terrainIs($hexParts[1], $type) || $this->terrain->terrainIs($hexParts[2], $type) );

    }


    function getCombatOddsList($combatIndex)
    {
        return $this->crt->getCombatOddsList($combatIndex);
    }

    function undoDefendersWithoutAttackers()
    {
        $this->currentDefender = false;
        if ($this->combats) {
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            foreach ($this->combats as $defenderId => $combat) {
                if (count((array)$combat->attackers) == 0) {
                    foreach ($combat->defenders as $defId => $def) {
                        $unit = $this->force->getUnit($defId);
                        $unit->setStatus(STATUS_READY);
                        $victory->postUnsetDefender($this->force->units[$defId]);
                    }
                    unset($this->combats->$defenderId);
                    continue;
                }
                if ($combat->index < 0) {
                    if ($combat->attackers) {
                        foreach ($combat->attackers as $attackerId => $attacker) {
                            $unit = $this->force->getUnit($attackerId);
                            unset($this->attackers->$attackerId);
                            $unit->setStatus( STATUS_READY);
                            $victory->postUnsetAttacker($unit);
                        }
                    }
                    foreach ($combat->defenders as $defId => $def) {
                        $unit = $this->force->getUnit($defId);
                        $unit->setStatus( STATUS_READY);
                        $victory->postUnsetDefender($unit);
                    }
                    unset($this->combats->$defenderId);
                    continue;
                }
            }
        }
    }

    function combatResolutionMode()
    {
        $b = Battle::getBattle();
        $b->victory->combatResolutionMode();
        $this->combatsToResolve = $this->combats;
        unset($this->combats);
    }

}
