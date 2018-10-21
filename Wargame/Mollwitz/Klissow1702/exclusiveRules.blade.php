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
    <li><span class="lessBig">Terrain</span>
        <p class="indent">Marsh: The Swedes experienced very little difficulty attacking across the marshes, therefore
            marshes are only +2 MP's to enter.
            In addition, during combat only attacks into a marsh are halved. Not attacks out of them, like the TEC
            states.</p>
    </li>
    <li><span class="lessBig">Pontoons</span><br><br>
        <div class="specialHexes pontoon Swedish clear" style="margin:0 !important;"></div><br><br>
        <div class="specialHexes pontoon SaxonPolish " style="margin:0 !important;"></div><br>
        <p class="indent">
            Each side during their deployment may place one Pontoon marker on any swamp hex within 3 hexes of a friendly
            deploy hex.
            This changes the hex to clear terrain for the remainder of the game. Pontoons may be used by either side.
            And may not be destroyed.
        </p>

    </li>
