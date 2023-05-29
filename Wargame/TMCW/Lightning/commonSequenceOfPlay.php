<?php
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
?><span class="big">Sequence of Play</span>

<p>The game is made up of 7 Game turns, each Game turn consists of two
    player turns, Each player turn has
    several phases. These are described below in the sequence of play.</p>
<ol>
    <li>
        <?= $forceName[1] ?> Player Turn
        <ol>
            <li>
                Movement Phase
                <p>The phasing player may move any or all of their units. Movement is voluntary.</p>
            </li>
            <li>
                Combat Phase
                <p>The phasing player may attack any and all enemy units adjacent to their units. Combat is
                voluntary.</p>
            </li>
        </ol>
    </li>
    <li>
        <?= $forceName[2] ?> Player Turn
        <ol>
            <li>
                Movement Phase
                The phasing player may move any or all of their units. Movement is voluntary.
            </li>
            <li>
                Combat Phase
                <p>The phasing player may attack any and all enemy units adjacent to their units. Combat is
                    voluntary.</p>
            </li>
            <li>
                Second Movement Phase
                The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second movement phase.
                <p class="ruleComment">
                    Only the Loyalist player has a second movement phase, and they do no have any units
                    that can move in this phase till later in the game.
                </p>
            </li>
        </ol>
    </li>

</ol>
<p>At the end of 7 game turns the game is over and victory is
    determined.
</p>
