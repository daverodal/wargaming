<?php
namespace Wargame\Vu;
use stdClass;
use Wargame\Battle;
use Wargame\Hexpart;
// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

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

class CombatResultsTable extends \Wargame\CombatResultsTable
{
    	public $combatIndexCount;
    	public $maxCombatIndex;
    	public $dieSideCount;
    	public $dieMaxValue;

    	public $combatResultsTable;
        public $combatResultsHeader;
    	public $combatOddsTable;
    	//     combatIndexeCount is 6; maxCombatIndex = 5
	//     index is 0 to 5;  dieSidesCount = 6
    
    function __construct(){
	    $this->crts = new stdClass();

        $this->crts->normal = new \Wargame\CRT(array("1:5","1:4","1:3","1:2","1:1","2:1","3:1","4:1","5:1","6:1"),
            '', 10, 1);
        $this->crts->normal->table = array(
            array(AE, AR, AR, DR, DR, DR, DE, DE, DE, DE),
            array(AE, AE, AR, AR, DR, DR, DR, DE, DE, DE),
            array(AE, AE, AE, AR, DR, DR, DR, DR, DE, DE),
            array(AE, AE, AE, AR, AR, DR, DR, DR, DE, DE),
            array(AE, AE, AE, AR, AR, EX, DR, EX, EX, DE),
            array(AE, AE, AE, AE, AR, AR, EX, EX, EX, DE),
        );
        $this->rowNum = 0;

        $this->combatIndexCount = 10;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;

        $this->crts->cavalry = new \Wargame\CRT(array("1:5","1:4","1:3","1:2","1:1","2:1","3:1","4:1","5:1","6:1"),
            '', 10, 1);


        $this->crts->cavalry->table = array(
            array(AE, AR, AR, DR, DR, DR, DR, DR, DR, DR),
            array(AE, AE, AR, AR, DR, DR, DR, DR, DR, DR),
            array(AE, AE, AE, AR, DR, DR, DR, DR, DR, DR),
            array(AE, AE, AE, AR, AR, DR, DR, DR, DR, DR),
            array(AE, AE, AE, AR, AR, DR, DR, DR, DR, DR),
            array(AE, AE, AE, AE, AR, AR, DR, DR, DR, DR)
        );

        $this->crts->determined = new \Wargame\CRT(array("1:5","1:4","1:3","1:2","1:1","2:1","3:1","4:1","5:1","6:1"),
            '', 10, 1);


        $this->crts->determined->table = [
            array(AE, AR, AR, DR, DR, DR, DE, DE, DE, DE),
            array(AE, AE, AR, AR, DR, DR, DR, DE, DE, DE),
            array(AE, AE, AE, AR, EX, DR, DR, DR, DE, DE),
            array(AE, AE, AE, AR, AR, EX, DR, EX, DE, DE),
            array(AE, AE, AE, AR, AR, EX, EX, EX, EX, DE),
            array(AE, AE, AE, AE, AR, AR, EX, EX, EX, DE),
          ];

      }

        function getCombatResults($Die, $index, $combat)
        {
            if($combat->useAlt){
                return $this->crts->cavalry->table[$Die][$index];
            }else{
                if($combat->useDetermined){
                    return $this->crts->determined->table[$Die][$index];
                }
                return $this->crts->normal->table[$Die][$index];
            }
        }

    function getCombatDisplay(){
        return $this->combatResultsHeader;
    }

    public function setCombatIndex($defenderId){

        $combatLog = "";
        /* @var Jagersdorf $battle */
        $battle = Battle::getBattle();
        $combats = $battle->combatRules->combats->$defenderId;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $isClear = $battle->terrain->terrainIs($hexpart,'clear');
        $isTown = $battle->terrain->terrainIs($hexpart,'town');
        $isHill = $battle->terrain->terrainIs($hexpart,'hill');
        $isForest = $battle->terrain->terrainIs($hexpart,'forest');

        $defenders = $combats->defenders;

        $attackers = $combats->attackers;
        $attackStrength = 0;
        $attackersCav = false;
        $combinedArms = ['infantry'=>0, 'artillery'=>0, 'cavalry'=>0];

        foreach($attackers as $attackerId => $attacker){
            $unit = $battle->force->units[$attackerId];
            $unitStrength = $unit->strength;

            if($unit->class == "infantry"){
                if($battle->combatRules->thisAttackAcrossRiver($defenderId,$attackerId)){
                    $unitStrength /= 2;
                }
            }

            if($unit->class == "cavalry"){
                $attackersCav = true;
                if($battle->combatRules->thisAttackAcrossRiver($defenderId,$attackerId) || !$isClear){
                    $unitStrength /= 2;
                }
            }
            $attackStrength += $unitStrength;
        }
        $defenseStrength = 0;
        $defendersAllCav = true;
        foreach($defenders as $defId => $defender){
            $unit = $battle->force->units[$defId];
            $class = $unit->class;
            $unitDefense = $unit->defStrength;
            if($unit->class != 'cavalry'){
                $defendersAllCav = false;
            }
            $defenseStrength += $unitDefense * (($isTown && $class != 'cavalry') || $isHill ? 2 : 1);
        }


        if($attackStrength >= $defenseStrength){
            foreach($combats->attackers as $attackerId => $attacker){
                $combinedArms[$battle->force->units[$attackerId]->class]++;
            }
            if(!$isClear){
                unset($combinedArms['cavalry']);
            }
        }

        $armsShift = 0;
        $armsTypes = "";
        if ($attackStrength >= $defenseStrength) {
            foreach($combinedArms as $k => $arms){
                if($arms > 0){
                    $armsTypes  .= "$k ";
                    $armsShift++;
                }
            }
            $armsShift--;
        }

        if ($armsShift < 0) {
            $armsShift = 0;
        }
        if($armsShift > 0){
            $combatLog .= "Combined Arms Shift: +$armsShift ".$armsTypes;
        }

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        $combatIndex += $armsShift;

        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }



        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $armsShift;
        $combats->index = $combatIndex;
        $combats->useAlt = false;
        if($defendersAllCav && !$attackersCav){
            $combats->useAlt = true;
        }
        $combats->combatLog = $combatLog;
    }

    function getCombatIndex($attackStrength, $defenseStrength){
        $ratio = $attackStrength / $defenseStrength;
        if($attackStrength >= $defenseStrength){
            $combatIndex = floor($ratio)+3;
        }else{
            $combatIndex = 5 - ceil($defenseStrength /$attackStrength );
        }
        return $combatIndex;
    }

}
