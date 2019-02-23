<?php
namespace Wargame\TMCW\Collapse;
use stdClass;
use Wargame\Battle;
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
    use CRTResults;
    use CombatHalfDoubleTerrain;
    public $aggressorId;
    public $resultsName;

    function __construct($aggressor){
        global $results_name;
        $this->resultsNames = $results_name;
        $this->aggressorId = $aggressor;
        $results_name[EX2] = "½EX";

        $this->crts = new stdClass();
        $this->crts->normal = new \Wargame\CRT(array("1:3", "1:2", "1:1","2:1","3:1","4:1","5:1","6:1", "7:1", "8:1", "9:1"),
            '', 11, 0);
        $this->crts->normal->table = array(
            array(AE, AE, AE, AE, AR, AR, BR, BR, BR, DR, DR),
            array(AE, AE, AE, AE, AR, AR, BR, BR, EX, EX, EX2),
            array(AE, AE, AE, AR, AR, BR, DR, EX, EX, EX2, EX2),
            array(AE, AE, AR, AR, BR, DR, EX, EX, EX2, EX2, DE),
            array(AE, AE, AR, BR, BR, DR, EX, EX2, EX2, DE, DE),
            array(AE, DR, BR, BR, DR, EX, EX2, EX2, DE, DE, DE),
            array(AE, AR, BR, DR, DR, EX, EX2, DE, DE, DR, DE),
        );
        $this->rowNum = 0;
        $this->maxCombatIndex = 10;
        $this->dieSideCount = 6;
    }


    function getCombatResults($Die, $index, $combat)
    {
        return $this->crts->normal->table[(int)$Die + 1 + $combat->dieOffset][$index];
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