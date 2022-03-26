<?php
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
?><span class="big">Sequence of Play</span>

<p>The game is made up of <span class="gameLength">7</span> Game turns, each Game turn consists of two
    player turns, Each player turn has
    several phases. These are described below in the sequence of play.</p>
<ol>
    <li>
        <?= $forceName[1] ?> Player Turn
        <ol>
            <li>
                Replacement Phase
                <p>The phasing player may allocate as many replacements as they
                received. <?= $forceName[1] ?>
                forces receive <span class="player-one-replacements"></span> replacement per turn. (There is no replacement phase
                for the <?= $forceName[1] ?> player on turn one).</p>
            </li>
            <li>
                Movement Phase
                <p>The phasing player may move any or all of their units. Movement is voluntary.</p>
            </li>
            <li>
                Combat Phase
                <p>The phasing player may any and all units that adjacent to their units. Combat is
                voluntary.</p>
            </li>
            <li>
                Second Movement Phase
                <p>The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second
                movement phase.</p>
            </li>
        </ol>
    </li>
    <li>
        <?= $forceName[2] ?> Player Turn
        <ol>
            <li>
                Replacement Phase
                The phasing player may receive as many replacements as they are
                allocated. <?= $forceName[2] ?>
                s receive <span class="player-two-replacements"></span> replacements per turn.
            </li>
            <li>
                Movement Phase
                The phasing player may move any or all of their units. Movement is voluntary.
            </li>
            <li>
                Combat Phase
                The phasing player may any and all units that adjacent to their units. Combat is
                voluntary.
            </li>
            <li>
                Second Movement Phase
                The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second
                movement phase.
            </li>
        </ol>
    </li>

</ol>
<p>At the end of <span class="gameLength">7</span> game turns the game is over and victory is
    determined.
</p>
