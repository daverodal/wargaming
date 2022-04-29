<?php
namespace Wargame\ModernBattles\Europe;
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

class CombatResultsTable extends \Wargame\CombatResultsTable
{
    use CRTResults;
    use \Wargame\ModernBattles\DiffCombatShiftTerrain;
        

    public $aggressorId = 1;

    function __construct(){

        $this->crts = new \stdClass();

        global $results_name;
        $this->resultsNames = $results_name;
        $results_name[EX] = "DX";
        $results_name[DR1] = "D1";
        $results_name[DR2] = "D2";
        $results_name[DR3] = "D3";
        $results_name[DR4] = "D4";
        $results_name[AR1] = "A1";
        $this->crts->mobile = new \Wargame\CRT(array("-7","-6,5","-4,3","-2","-1","0","+1", "+2,3","+4,5","+6,8","+9,11", "+12"),
            '', 12, 1);
        $this->crts->mobile->table = array(
            array(AR1, AR1, AR1, BR, BR, DR1, DR2, DR2, DR3, DR3, DR4, DE),
            array(AR1, AR1, AR1, AR1, BR, DR1, DR1, DR2, DR2, DR3, DR3, DR4),
            array(AR1, AR1, AR1, AR1, AR1, BR, DR1, DR1, DR2, DR2, DR3, DR3),
            array(AR1, AR1, AR1, AR1, AR1, BR, BR, DR1, DR1, DR2, DR2, DR3),
            array(AE, AR1, AR1, AR1, AR1, AR1, BR, BR, DR1, DR1, DR1, DR2),
            array(AE, AE, AR1, AR1, AR1, AR1, AR1, BR, BR, BR, DR1, DR1),
        );
        $this->rowNum = 1;


        $this->combatIndexCount = 12;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;

    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $difference = $attackStrength - $defenseStrength;

        if($difference < -7 ){
            $difference = -7;
        }
        if($difference  > 12){
            $difference = 12;
        }
        if($difference == -7 ){
            return 0;
        }
        if($difference >= -6 && $difference <= -5){
            return 1;
        }
        if($difference >= -4 && $difference <= -3){
            return 2;
        }
        if($difference == -2){
            return 3;
        }
        if($difference == -1){
            return 4;
        }
        if($difference == 0){
            return 5;
        }
        if($difference == 1){
            return 6;
        }
        if($difference >= 2 && $difference <= 3){
            return 7;
        }
        if($difference >= 4 && $difference <= 5){
            return 8;
        }
        if($difference >= 6 && $difference <= 8){
            return 9;
        }
        if($difference >= 9 && $difference <= 11){
            return 10;
        }
        if($difference == 12){
            return 11;
        }
        // should probably throw an exception
        return 0;
    }
    function getCombatResults($Die, $index, $combat)
    {
        return $this->crts->mobile->table[(int)$Die][$index];
    }

}
