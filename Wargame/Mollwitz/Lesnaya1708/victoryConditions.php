<?php
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
?><li><span class="lessBig">Victory Conditions</span>
    <ol>

        <li>
        Each side is awarded one victory point for each hostile combat factor destroyed. Swedish Wagons are worth 3 Victory point if destoryed.
            </li>
        <li>Victory points are also awarded for locations occupied (for the <?= $player[1]?> player).
            These locations are marked with numbers in red. Two locations worth 5 and 15 points are in the City of Lesnaya.
            If the <?= $player[1]?> player enters either of those location they will be awarded victory points. If the <?= $player[2]?> player retakes the
            location the <?= $player[1]?> will lose those victory points.

            <p class="ruleComment">
                Note: objectives start in the possession of the enemy, so they will have a label of the opposite color
                of their number at the beginning of the game. It will switch back and forth depending upon whoever last occupied the objective.
            </p>
            </li>

        <li><?= $player[1]?>: win at the end of any Game turn that they have 35 points by turn 7 inclusive.
        </li>

        <li> <?= $player[2]?>: win at the end of any Game turn that they have 30 points by turn 7 inclusive, or if the <?= $player[1]?> fail to win
        by turn 7.</li>

        <li>A draw occurs if both sides meet the above victory conditions on same turn.</li>
    </ol>
</li>