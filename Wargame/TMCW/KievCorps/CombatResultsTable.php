<?php
namespace Wargame\TMCW\KievCorps;
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
    use \Wargame\DivMCWCombatShiftTerrain;
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
        $this->crts->normal->header = array("1:1","2:1","3:1","4:1","5:1","6:1", "7:1", "8:1", "9:1", "10:1", "11:1");
        $this->crts->normal->next = '';
        $this->crts->normal->maxCombatIndex = 10;
        $this->rowNum = 1;

        $this->crts->normal->table = array(
            array(AL, AL, NE,  BL,  BL,    BL,    DRL,    DRL,    DL2AL, DL2AL, DL2AL),
            array(AL, AL, BL,  BL,  BL,    DRL,   DRL,    DL2AL2, DL2AL, DL2AL, DL2R),
            array(AL, AR, DR,  BL,  DRL,   DRL,   DL2AL,  DL2AL2, DL2R, DL2R, DE),
            array(AR, AR, DR,  DR,  DRL,   DL2AL, DL2AL2, DL2AL,  DL2R, DE,   DE),
            array(AR, NE, DRL, DRL, DL2AL, DL2AL, DL2AL2, DL2AL,  DEAL,   DE,   DE),
            array(AR, DR, DRL, DRL, DL2R,  DL2R,  DL2R,   DEAL,   DE,   DE,   DE),
        );

        $this->combatIndexCount = 11;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
    }


    function getCombatResults($Die, $index, $combat)
    {
        return $this->crts->normal->table[$Die][$index];
    }

}