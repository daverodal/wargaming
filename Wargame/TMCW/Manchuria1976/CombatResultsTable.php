<?php
namespace Wargame\TMCW\Manchuria1976;
use stdClass;
// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 
/**
 *
 * Copyright 2012-2015 David Rodal
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
    use DivMCWCombatShiftTerrain;

    use CRTResults;
    public $combatIndexCount;
    public $maxCombatIndex;
    public $dieSideCount;
    public $dieMaxValue;

    public $combatResultsTable;
    public $combatResultsHeader;
    public $aggressorId = false;


    //     combatIndexeCount is 6; maxCombatIndex = 5
    //     index is 0 to 5;  dieSidesCount = 6

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1");
        $this->crts = new stdClass();
        $this->crts->normal = array(
            array(DRL, DRL,DE,  DE, DE, DE),
            array(DRL, EX, DRL, DE, DE, DE),
            array(EX, EX, DRL, DRL, DE, DE),
            array(EX, DRL, EX, EX, DE, DE),
            array(AL,  DRL, DRL, DRL, DRL, DE),
            array(AL,  AL,  DRL, DRL, EX, DE),
        );

        $this->crts->determined = array(
            array(DR, DRL,DE,  DE, DE, DE),
            array(EX, EX, DRL, DE, DE, DE),
            array(EX, EX, DRL, EX, DE, DE),
            array(EX, EX, EX, EX, DE, DE),
            array(AL,  DR, EX, DR, EX, DE),
            array(AL,  AL,  DR, DR, EX, DE),
        );
        
        $this->combatIndexCount = 6;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        /* TODO: object oriented :( */
        global $results_name;
        $results_name[DRL2] = "DRL";

    }

    function getCombatResults($Die, $index, $combat)
    {
        if($combat->useDetermined){
            return $this->crts->determined[$Die][$index];
        }
        return $this->crts->normal[$Die][$index];
    }

    function getCombatIndex($attackStrength, $defenseStrength){
        $combatIndex = floor($attackStrength / $defenseStrength)-1;

        return $combatIndex;
    }
}
