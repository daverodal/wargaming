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
    <p class="indent">The <?= $deployName[1] ?> move first. The <?= $deployName[2] ?> player deploys Second</p>

</li>
<li><span class="lessBig">Movement</span>
    <ol>
        <li><?= $forceName[1] ?>: player moves first.</li>
        <li><?= $forceName[2] ?>: player moves second. May not move (But may attack) on turn 1.
            <?= $forceName[2] ?> units south of the River Kinzig may not move turn 2.
        </li>
        <li>Towns: It is critically important in this game to remember that Town hexes are NOT road hexes.
        </li>
        <li>Depending upon the scenario, on turn two
            the <?= $forceName[2] ?> may not move any units south of the River Kinzig.
        </li>
        <li><img src="<?= url("js/orchard.png") ?>">Orchard Parkland:
            Orchard Parkland hexes do not effect movement and are treated as clear for purposes of movement.
        </li>
        <li><img src="<?= url("js/PermFort.png") ?>">Permanent Fortifications:
            The fortifications of Hanau may not be moved over except at roads.
        </li>
    </ol>
</li>
<li><span class="lessBig">Combat</span>
    <ol>
        <li>
            Orchard/Parkland: Orchard Parkland hexes do not affect combat except that they may not be fired over by
            artillery.
        </li>
        <li>
            Permanent Fortifications:
            The fortifications of Hanau may not be attacked over by cavalry or infantry except at roads. Otherwise
            treat as normal fortifications.
        </li>
    </ol>
</li>
<li><span class="lessBig">Balancing</span>
    The Standard scenario produces a very historical result of French victory. In the play balance
    scenario the Allies north of the river may move on turn 1. But the French may deploy adjacent to the “F” line
    as well as on it and within it.
</li>
