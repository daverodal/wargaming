<?php
namespace Wargame\TMCW\EastWest;
use \stdClass;
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

class CombatResultsTable extends \Wargame\TMCW\ModernCombatResultsTable
{
    use \Wargame\TMCW\EastWest\DivCombatDoubleMultipleTerrain;
    use CRTResults;
    public $aggressorId;
    public $resultsName;

    function __construct($aggressor){
        global $results_name;
        $this->resultsNames = $results_name;
        $this->aggressorId = $aggressor;
        $results_name[DRL] = "DLR";

        $this->crts = new stdClass();
        $this->crts->normal = new stdClass();
        $this->crts->normal->header = array("1:3", "1:2", "1:1","2:1","3:1","4:1","5:1","6:1", "7:1", "8:1");
        $this->crts->normal->next = '';
        $this->crts->normal->maxCombatIndex = 10;
        $this->rowNum = 1;

        $this->crts->normal->table = array(
            array(DR1, DR1, DR4,  DR4,  DE,    DE,    DE,    DE,    DE, DE),
            array(AR1, DR1, DR4,  DR4,  DR4,    DE,   DE,    DE,   DE, DE),
            array(AR2, AR1, DR1,  DR2,  DR2,   DR3,   DE,  DE,     DE, DE),
            array(AR3, AR2, DR1,  DR1,  DR2,   DR2,   DR3, DE,     DE, DE),
            array(AE, AE, DR1, DR1, DR1, DR2,       DR2, DR3,    DE,   DE),
            array(AE, AE, AR1, DR1, DR1,  DR1,       DR1,   DR2,   DR3,   DE),
        );

        $this->combatIndexCount = 10;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
    }


    function getCombatResults($Die, $index, $combat)
    {
        return $this->crts->normal->table[$Die][$index];
    }
    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $ratio = $attackStrength / $defenseStrength;
        if ($attackStrength >= $defenseStrength) {
            $combatIndex = floor($ratio) + 1;
        } else {
            $combatIndex = 3 - ceil($defenseStrength / $attackStrength);
        }
        return $combatIndex;
    }

}