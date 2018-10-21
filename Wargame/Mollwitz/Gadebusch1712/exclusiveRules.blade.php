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
    <li><?= $deployName[1] ?>: player deploys first.</li>
    <li><?= $deployName[2] ?>: player deploys second.
    </li>
    </li>
    <li><span class="lessBig">Movement</span>
        <ol>
            <li><?= $forceName[1] ?>: player moves first.</li>
            <li><?= $forceName[2] ?>: player moves second.
            </li>

        </ol>
    </li>
    <li><span class="lessBig">Terrain</span>
        <ol>
            <li>Swamps: The Swedes were able to get their guns over these swamps with little difficulty.
                In this game they will cause only -1 MP to enter and -1 Combat factor to any unit attacking into or out
                of.
            </li>
            <li>
                Sunken Road / Ravine. These were actually narrow ravines that these roads ran through in this game
                deduct 1 strength point from units attacking out.
                Artillery can only attack in or out to adjacent hexes.

            </li>
        </ol>
    </li>
    <li><span class="lessBig">Danish Indecision</span>
        <ol>
            <li>Historically the Danes allowed the Swedes to advance over the swamps and on to their right flank with no
                intervention.
            </li>
            <li>
                At the beginning of the Danish movement phase turns 1-3 inclusive, the Danish player will roll 1d6.
                If the roll is 2 or less the Danish player may move no units that turn. They can stop rolling once the
                effect has occurred.
                If this effect does not happen by turn 4 the Danes may not move any units on turn 4. (the computer does
                this for you)


            </li>
        </ol>
    </li>
