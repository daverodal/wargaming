<?php
namespace Wargame\TMCW;
use Wargame\CRT;
use Wargame\SimpleBBCombatTrait;

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

class SimpleBBCombatResultsTable extends \Wargame\CombatResultsTable
{
    use SimpleBBCombatTrait;
    public $aggressorId = BLUE_FORCE;
    public $crt;

    function __construct(){
        $this->crts = new \stdClass();
        $this->crts->normal = new \Wargame\CRT(array( "1:2", "1:1", "2:1", "3:1", "4:1"),
            '', 5, 1);

        $this->crts->normal->table = array(
            array(NE, S,  S,   S,  S),
            array(NE, NE, S,   S,  S),
            array(NE, NE, NE,  NE, S),
            array(NE, NE, NE,  NE, NE),
            array(NE, NE, NE,  NE, NE),
            array(NE, NE, NE,  NE, NE),
        );
        $this->dieSideCount = 6;
    }

    function getCombatResults($Die, $index, $combat)
    {
        return $this->crts->normal->table[$Die][$index];
    }
    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $ratio = $attackStrength / $defenseStrength;
        $combatIndex = floor($ratio);
        return $combatIndex;
    }

}