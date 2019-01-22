<?php
namespace Wargame;
use \stdClass;
// terrain.js

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




class Town
{
    public $name, $hexagon;

    function __construct($townName, $townHexagon)
    {


        $this->name = $townName;
        $this->hexagon = $townHexagon;
    }
}

class Terrain implements \JsonSerializable
{
    public $mapUrl = false;
    public $maxCol = false;
    public $maxRow = false;
    public $maxTerrainX;
    public $maxTerrainY;
    public $terrainArray;
    public $towns;
    public $terrainFeatures;
    public $reinforceZones;
    public $allAreAttackingAcrossRiverCombatEffect;
    public $specialHexes;
    public $thruways;

    function jsonSerialize()
    {
        foreach($this->terrainArray as $topId => $top){
            foreach($top as $id => $value){
                if(count((array)$value) === 1 && isset($value->clear)){
                    unset($this->terrainArray[$topId][$id]);
                }
            }
        }
        return $this;
    }


    function __construct($data = null)
    {

        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "reinforceZones") {
                    $this->reinforceZones = array();
                    foreach ($v as $reinforceZone) {
                        $hexName = $reinforceZone->hex ?? false;
                        if($hexName === false){
                            $hexName = $reinforceZone->hexagon->name;
                        }
                        $this->reinforceZones[] = new ReinforceZone($hexName, $reinforceZone->name);
                    }
                    continue;
                }
                $this->$k = $v;
            }

        } else {
            $this->towns = array();
            $this->terrainFeatures = new stdClass();
            $this->reinforceZones = array();
            $this->thruways = ['road', 'secondaryroad', 'trail', 'ford', 'rr'];

            $this->allAreAttackingAcrossRiverCombatEffect = 1;

            $mapData = MapData::getInstance();


            $x = $mapData->maxX;
            $y = $mapData->maxY;
            $hexName = sprintf("%02d%02d",$x,$y);

            list($x, $y) = Hexagon::getHexPartXY($hexName);
            $this->maxTerrainY = $y + 4;/* add 4 for bottom and even/odd columns */
            $this->maxTerrainX = $x;
            $this->specialHexes = new stdClass();

        }
        $this->additionalRules = [];
        $rule = new stdClass();
        $rule->startHex = "elevation1";
        $rule->endHex = "elevation2";
        $rule->cost = 1;
        $this->additionalRules[] = $rule;

        $rule = new stdClass();
        $rule->startHex = "elevation0";
        $rule->endHex = "elevation1";
        $rule->cost = 1;
        $this->additionalRules[] = $rule;

    }

    public function addSpecialHex($specialHex, $value){
        $this->specialHexes->$specialHex = $value;
    }

    public function addAltEntranceCost($terrain,$altClass,$entranceCost){
        $feature = $this->terrainFeatures->$terrain;
        if($feature){
            $feature->altEntranceCost->$altClass = $entranceCost;
        }
    }

    public function addNatAltEntranceCost($terrain, $nationality,$altClass,$entranceCost){
        if(isset($this->terrainFeatures->$terrain)){
            $feature = $this->terrainFeatures->$terrain;
            if(empty($feature->altEntranceCost->$nationality)){
                $feature->altEntranceCost->$nationality = new stdClass();
            }
            $feature->altEntranceCost->$nationality->$altClass = $entranceCost;
        }
    }

    public function addAltTraverseCost($terrain,$altClass,$traverseCost){
        $feature = $this->terrainFeatures->$terrain;
        if($feature){
            $feature->altTraverseCost->$altClass = $traverseCost;
        }
    }

    public function addNatAltTraverseCost($terrain, $nationality,$altClass,$traverseCost){
        $feature = $this->terrainFeatures->$terrain;
        if($feature){
            if(empty($feature->altTraverseCost->$nationality)){
                $feature->altTraverseCost->$nationality = new stdClass();
            }
            $feature->altTraverseCost->$nationality->$altClass = $traverseCost;
        }
    }

    /* this method will die someday  sooner than later */
    public function setMaxHex(){
        $mapData = MapData::getInstance();


        $x = $mapData->maxX;
        $y = $mapData->maxY;
        $hexName = sprintf("%02d%02d",$x,$y);

        new Hexagon();
        list($x, $y) = Hexagon::getHexPartXY($hexName);
        $this->maxTerrainY = $y + 4;/* for bottom and even odd columns */
        $this->maxTerrainX = $x;

    }

    /*
     * public
     */
    function addTerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive, $blocksRanged = false)
    {

        $this->terrainFeatures->$name = new TerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive, $blocksRanged);
    }

    /*
     * often used!
     */
    function addTerrain($hexagonName, $hexpartType, $terrainName)
    {
        $hexagon = new Hexagon($hexagonName);

        $x = $hexagon->getX();
        $y = $hexagon->getY();
        switch ($hexpartType) {

            case HEXAGON_CENTER:

                break;

            case BOTTOM_HEXSIDE:

                $y = $y + 2;
                break;

            case LOWER_LEFT_HEXSIDE:

                $x = $x - 1;
                $y = $y + 1;
                break;

            case UPPER_LEFT_HEXSIDE:

                $x = $x - 1;
                $y = $y - 1;
                break;

        }
        if(empty($this->terrainArray[$y][$x])){
            $this->terrainArray[$y][$x] = new stdClass();
        }
        if (isset($this->terrainFeatures->$terrainName)) {
            $feature = $this->terrainFeatures->$terrainName;
            /* new feature is exclusive */
            if ($feature->isExclusive === true) {
                $thisHexpart = $this->terrainArray[$y][$x];
                /* reset all exclusive terrains found, leave non exclusive ones */
                foreach($thisHexpart as $thisTerrainName => $thisTerrainValue){
                    if(isset($this->terrainFeatures->$thisTerrainName) && $this->terrainFeatures->$thisTerrainName->isExclusive){
                        unset($this->terrainArray[$y][$x]->$thisTerrainName);
                    }
                }
            }
            $this->terrainArray[$y][$x]->$terrainName = 1;
            if($feature->blocksRanged){
                $this->terrainArray[$y][$x]->blocksRanged = 1;
            }
        }

    }
    function changeTerrain($hexagonName, $hexpartType, $terrainName)
    {
        $hexagon = new Hexagon($hexagonName);

        $x = $hexagon->getX();
        $y = $hexagon->getY();
        switch ($hexpartType) {

            case HEXAGON_CENTER:

                break;

            case BOTTOM_HEXSIDE:

                $y = $y + 2;
                break;

            case LOWER_LEFT_HEXSIDE:

                $x = $x - 1;
                $y = $y + 1;
                break;

            case UPPER_LEFT_HEXSIDE:

                $x = $x - 1;
                $y = $y - 1;
                break;

        }
        if(!$this->terrainArray->$y->$x){
            $this->terrainArray->$y->$x = new stdClass();
        }
        if ($feature = $this->terrainFeatures->$terrainName) {
            /* new feature is exclusive */
            if ($feature->isExclusive === true) {
                $thisHexpart = $this->terrainArray->$y->$x;
                /* reset all exclusive terrains found, leave non exclusive ones */
                foreach($thisHexpart as $thisTerrainName => $thisTerrainValue){
                    if($this->terrainFeatures->$thisTerrainName->isExclusive){
                        unset($this->terrainArray->$y->$x->$thisTerrainName);
                    }
                }
            }
            $this->terrainArray->$y->$x->$terrainName = 1;
            if($feature->blocksRanged){
                $this->terrainArray->$y->$x->blocksRanged = 1;
            }
        }

    }
    /*
     * public init
     */
    function addReinforceZone($hexagonName, $zoneName)
    {
        $reinforceZone = new ReinforceZone($hexagonName, $zoneName);
        array_push($this->reinforceZones, $reinforceZone);
    }

    /*
     * private !
     */
    private function getTerrainCode(Hexpart $hexpart)
    {
        $x = $hexpart->getX();
        $y = $hexpart->getY();
        return $this->getTerrainCodeXY($x, $y);
    }


    /*
     * private !
     */
    private function getTerrainCodeXY($x,$y)
    {
        if (($x >= 0 && $x <= $this->maxTerrainX) && ($y >= 0 && $y <= $this->maxTerrainY)){
            if(isset($this->terrainArray) && isset($this->terrainArray->$y) && isset($this->terrainArray->$y->$x)){
                $terrainCode = $this->terrainArray->$y->$x;
            }else{
                $terrainCode = new stdClass();
                $terrainCode->clear = 1;
            }
        }else{
                $terrainCode = new stdClass();
                $terrainCode->offmap = 1;
        }

        return $terrainCode;
    }

    /*
     * public
     */
    function terrainIs($hexpart, $terrainName)
    {
        $terrainCode = $this->getTerrainCode($hexpart);
        $found = false;
        if (!empty($terrainCode->$terrainName)) {
            return true;
        }
        return false;
    }

    /*
     * public
     * Check the hexside OR destination Hex
     */
    function terrainIsHexSide($startHex,$endHex, $terrainName)
    {
        list($startX, $startY) = Hexagon::getHexPartXY($startHex);
        list($endX, $endY) = Hexagon::getHexPartXY($endHex);
        $hexsideX = ($startX + $endX) / 2;
        $hexsideY = ($startY + $endY) / 2;

        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        if (isset($terrainCode->$terrainName)) {
            return true;
        }
        $terrainCode = $this->getTerrainCodeXY($endX, $endY);
        if(isset($terrainCode->$terrainName)){
            return true;
        }
        return false;
    }

    /*
     * public
     * Check ONLY the hexside
     */
    function terrainIsHexSideOnly($startHex,$endHex, $terrainName)
    {
        list($startX, $startY) = Hexagon::getHexPartXY($startHex);
        list($endX, $endY) = Hexagon::getHexPartXY($endHex);
        $hexsideX = ($startX + $endX) / 2;
        $hexsideY = ($startY + $endY) / 2;

        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        if (isset($terrainCode->$terrainName)) {
            return true;
        }
        return false;
    }

    /*
     * public
     * Check the hexside AND destination Hex
     */
    function    terrainIsHex($hex, $terrainName)
    {
        list($endX, $endY) = Hexagon::getHexPartXY($hex);

        $terrainCode = $this->getTerrainCodeXY($endX, $endY);
        if(!empty($terrainCode->$terrainName)){
            return true;
        }
        return false;
    }
    /*
     * public
     */
    function terrainIsXY($x,$y, $terrainName)
    {
        $terrainCode = $this->getTerrainCodeXY($x,$y);
        if (!empty($terrainCode->$terrainName)) {
            return true;
        }
        return false;
    }
    /*
     * move rules
     */
    function moveIsInto($hexagon, $name)
    {
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        $moveIsInto = false;
        if (($this->terrainIs($hexpart, $name) == true)) {
            $moveIsInto = true;
        }

        return $moveIsInto;
    }


    private function  getTerrainEntranceMoveCostXY($endX, $endY, MovableUnit $unit)
    {
        $terrains = $this->getTerrainCodeXY($endX, $endY);
        $entranceCost = 0;

        foreach ($terrains as $terrainFeature => $val) {
            /* @var TerrainFeature $feature */
            if (in_array($terrainFeature, $this->thruways)) {
                continue;
            }

            /*
             * blocksRanged is an exmample of one that breaks here.
             */
            if(isset($this->terrainFeatures->$terrainFeature)) {
                $feature = $this->terrainFeatures->$terrainFeature;
                if (!empty($unit->nationality) && $unit->class && !empty($feature->altEntranceCost->{$unit->nationality}->{$unit->class})) {
                    $cost = $feature->altEntranceCost->{$unit->nationality}->{$unit->class};
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                } else if ($unit->class && !empty($feature->altEntranceCost->{$unit->class})) {
                    $cost = $feature->altEntranceCost->{$unit->class};
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                } else {
                    $cost = $feature->entranceCost;
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                }
                $entranceCost += $cost;
            }
        }
        return $entranceCost;
    }

    private function getTerrainTraverseCost($terrainCode, $unit){
        $traverseCost = 0;
        foreach ($terrainCode as $code => $val) {
            if(isset($this->terrainFeatures->$code)) {

                $feature = $this->terrainFeatures->$code;
                if (!empty($unit->nationality) && $unit->class && !empty($feature->altTraverseCost->{$unit->nationality}->{$unit->class})) {
                    $cost = $feature->altTraverseCost->{$unit->nationality}->{$unit->class};
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                } else if ($unit->class && !empty($feature->altTraverseCost->{$unit->class})) {
                    $cost = $feature->altTraverseCost->{$unit->class};
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                } else {
                    $cost = $this->terrainFeatures->$code->traverseCost;
                    if ($cost === "blocked") {
                        return "blocked";
                    }
                }
                $traverseCost += $cost;
            }
        }
        return $traverseCost;

    }

    private function getTerrainCodeUnitCost($terrainCode, $unit){
        if(empty($this->terrainFeatures->$terrainCode)){
            return 0;
        }
        $feature = $this->terrainFeatures->$terrainCode;
        if(!empty($unit->nationality) && $unit->class && !empty($feature->altEntranceCost->{$unit->nationality}->{$unit->class})){
            $moveCost = $feature->altEntranceCost->{$unit->nationality}->{$unit->class};
        }else if($unit->class && !empty($feature->altEntranceCost->{$unit->class})){
            $moveCost = $feature->altEntranceCost->{$unit->class};
        }else{
            $moveCost = $feature->entranceCost;

        }
        return $moveCost;

    }

    function getTerrainAdditionalCostXY($startX, $startY, $endX, $endY, MovableUnit $unit){
        $moveCost = 0;
        $startTerrainCode = $this->getTerrainCodeXY($startX,$startY);
        $endTerrainCode = $this->getTerrainCodeXY($endX,$endY);
        $rules = $this->additionalRules;

        foreach($rules as $rule){
            if(!empty($startTerrainCode->{$rule->startHex}) && !empty($endTerrainCode->{$rule->endHex})){
                $moveCost += $rule->cost;
            }
        }
        return $moveCost;
    }


    function getTerrainAdditionalCost($startHexagon, $endHexagon, MovableUnit $unit){
        $moveCost = 0;
        list($startX, $startY) = Hexagon::getHexPartXY($startHexagon);
        list($endX, $endY) = Hexagon::getHexPartXY($endHexagon);
        return $this->getTerrainAdditionalCostXY($startX, $startY, $endX, $endY, $unit);
    }
    function getTerrainMoveCost($startHexagon, $endHexagon, $railMove, MovableUnit $unit)
    {
        if(is_object($startHexagon)){
            $startHexagon = $startHexagon->name;
        }
        if(is_object($endHexagon)){
            $endHexagon = $endHexagon->name;
        }
        $moveCost = 0;
        list($startX, $startY) = Hexagon::getHexPartXY($startHexagon);
        list($endX, $endY) = Hexagon::getHexPartXY($endHexagon);
        return $this->getTerrainMoveCostXY($startX, $startY, $endX, $endY,$railMove, $unit);
    }
    /*
      * very public
      * used in moveRules
      */
    function getTerrainMoveCostXY($startX, $startY, $endX, $endY, $railMove, MovableUnit $unit)
    {
        $hexsideX = ($startX + $endX) / 2;
        $hexsideY = ($startY + $endY) / 2;

        // if road, or trail,override terrain,  add fordCost where needed
        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        if($railMove && (!empty($terrainCode->road) || !empty($terrainCode->trail) || !empty($terrainCode->ford) || !empty($terrainCode->rrp) || !empty($terrainCode->secondaryroad))){
            $roadCost = $this->getTerrainCodeUnitCost('road',$unit);
            $trailCost = $this->getTerrainCodeUnitCost('trail',$unit);
            $fordCost = $this->getTerrainCodeUnitCost('ford',$unit);
            $secondaryroad = $this->getTerrainCodeUnitCost('secondaryroad',$unit);
            $rrCost = $this->getTerrainCodeUnitCost('rrp',$unit);

            $moveCost = $roadCost;
            if(!empty($terrainCode->rrp)){
                $moveCost = $rrCost;
            }
            if(!empty($terrainCode->trail)){
                $moveCost = $trailCost;
            }
            if(!empty($terrainCode->secondaryroad)){
                $moveCost = $secondaryroad;
            }
            if(!empty($terrainCode->ford)){
                $moveCost = $fordCost;
            }
        }
        else
         {

            //  get entrance cost
            $moveCost = $this->getTerrainEntranceMoveCostXY($endX, $endY, $unit);
             if($moveCost === "blocked"){
                 return "blocked";
             }
            $cost = $this->getTerrainTraverseCost($terrainCode, $unit);
             if($cost === "blocked"){
                 return "blocked";
             }
             $moveCost += $cost;
             $cost = $this->getTerrainAdditionalCostXY($startX, $startY, $endX, $endY, $unit);
             $moveCost += $cost;
         }
        return $moveCost;
    }

    /*
     * public used in combatRules
     */
    function getDefenderTerrainCombatEffect($hexagon)
    {
        $combatEffect = 0;

        $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());


        $terrains = $this->getTerrainCodeXY($hexagon->getx(),$hexagon->getY());
        foreach ($terrains as $terrainFeature => $val) {
            $combatEffect += $this->terrainFeatures->$terrainFeature->combatEffect;
        }
        return $combatEffect;

    }

    /*
     * public used in combatRules
     */
    function getDefenderTraverseCombatEffect($startHexagon, $endHexagon, $attackingForceId)
    {
        $combatEffect = 0;
        $hexsideX = ($startHexagon->getX() + $endHexagon->getX()) / 2;
        $hexsideY = ($startHexagon->getY() + $endHexagon->getY()) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);

        $terrains = $this->getTerrainCodeXY($hexpart->getX(),$hexpart->getY());
        foreach ($terrains as $terrainFeature => $val) {

            $combatEffect += $this->terrainFeatures->$terrainFeature->combatEffect;
        }
        return $combatEffect;

    }

    /*
     * public used in combatRules
     */
    function getAllAreAttackingAcrossRiverCombatEffect()
    {
        return $this->allAreAttackingAcrossRiverCombatEffect;
    }

    /*
     * public used lots
     */
    function isExit($hexagon)
    {
        $isExit = false;

        if (is_object($hexagon)) {
            $X = $hexagon->getX();
            $Y = $hexagon->getY();
        } else {
            list($X, $Y) = Hexagon::getHexPartXY($hexagon);
        }
        $hexpart = new Hexpart($X, $Y);

        $terrainCode = $this->getTerrainCode($hexpart);

        if ($this->terrainIs($hexpart, "offmap") == true) {
            $isExit = true;
        }
        return $isExit;
    }

    /*
     * public moveRules
     */
    function getReinforceZoneList($hexagon)
    {
        $zoneName = [];
        for ($i = 0; $i < count($this->reinforceZones); $i++) {
            //alert("" + i + " " + $this->reinforceZones[$i]->hexagon->getName() + " : " + hexagon->getName());
            if ($this->reinforceZones[$i]->hexagon->equals($hexagon) == true) {
                $zoneName[] = $this->reinforceZones[$i]->name;
            }
        }

        return $zoneName;
    }

    /*
    * public moveRules
    */
    function getReinforceZonesByName($name)
    {
        $zones = array();
        for ($i = 0; $i < count($this->reinforceZones); $i++) {
            //alert("" + i + " " + $this->reinforceZones[$i]->hexagon->getName() + " : " + hexagon->getName());
            if ($this->reinforceZones[$i]->name == $name) {
                $zones[] = $this->reinforceZones[$i];
            }
        }

        return $zones;
    }

}
