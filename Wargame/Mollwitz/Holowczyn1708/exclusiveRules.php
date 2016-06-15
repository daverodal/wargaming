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

/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>

<ol>
    <li><span class="lessBig">Deploy Phase</span>
        <p class="indent">The <?= $deployName[1] ?> player deploys first. The <?= $deployName[2] ?> player deploys
            Second</p>

    </li>
    <li><span class="lessBig">First Player</span>
        <p class="indent">The <?= $forceName[1] ?> player moves first. The <?= $forceName[2] ?> player moves second.</p>
    </li>
    <li><span class="lessBig">Movement</span>
        <p class="indent">The <?= $forceName[1] ?> units receive a 1 MP bonus on turn 1 only.</p>
        <p class="indent"> The <?= $forceName[2] ?> Units movement rate are halved on turn 1, normal thereafter.</p>
    </li>
    <li>
        <span class="lessBig">Reserve Division</span>
        <p>Sheremetiev’s Division, Did not participate in the battle but could have done.
            At the beginning of each Russian Movement Phase starting with turn 2 the Russian player rolls a die.
            No unit deployed on one of the R6 hexes may move until a six is scored or until any Unit deployed on an R6 Hex has been attacked by the Swedes.
            In either case those Russian units may subsequently move normally


        </p>
    </li>
    <li><span class="lessBig">Pontoons</span>
        <p class="indent">
            <img id="pontoon-image" src="<?= url("js/SwePontoon.png") ?>">
            The Swedes had pontoons available. They were unable to use them.
            As an optional rule allow the Swedes to deploy 2 pontoon markers within 2 hexes of any Swedish unit.
            This has the effect of creating clear hex in that hex for the remainder of the game and nullifying any stream hex side between the two pontoons
        </p>

    </li>
</ol>