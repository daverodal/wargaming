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

        The <?= $deployName[1] ?> player deploys first. The <?= $deployName[2] ?> player deploys Second

    </li>
    <li>
        <span class="lessBig">First Player</span>
        The <?= $forceName[1] ?> player moves first. The <?= $forceName[2] ?> player moves second.
    </li>
    <li><span class="lessBig">Movement</span>
        The movement rate of all <?= $forceName[2] ?> units is halved on turn one, drop fractions.
    </li>

</ol>
