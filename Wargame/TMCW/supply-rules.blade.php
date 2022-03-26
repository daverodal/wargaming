<?php
/**
 *
 * Copyright 2012-2022 David Rodal
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

?>
<span class="big">Supply</span>
<p>
    Units are said to be either in supply or unsuppied. Supply affects both movement and combat.
</p>
<ol>
    <li><span class="big">Effects on Movement</span>
        <p>
            Unsupplied units have their movement allowance halved.
        </p>
        <p>For the purposes of movement supply is determined at the start of the movement phase.
           </p>

    <li><span class="big">Effects on Combat</span>

        <p>Units that are not in supply have their combat factor halved when attacking and remains normal when defending.</p>

        <p>For purposes of combat supply is determined at the instant of combat, units that started the combat
        phase supplied may be unsupplied by the time their turn comes to attack. </p>

    </li>
    <li><span class="big">Tracing Supply</span>

        <p>For a unit to be in supply it must be able to trace a line of contigous hexes from the unit to a supply source.
        </p><p>
            This must be no more than 12 hexes in length.</p>
        <p>ZOC's DO block supply lines, friendly units will negate this effect.</p>
    </li>
    <li><span class="big">Supply Sources</span>
        @section("supply-sources")
        @show
    </li>
</ol>

