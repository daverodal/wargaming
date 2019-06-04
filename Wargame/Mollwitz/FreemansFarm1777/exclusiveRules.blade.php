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
            <li> On turn one the <?= $forceName[1] ?> may not move.</li>
            <li>The Hessian units deployed along the Hudson river may not move till turn 4.</li>
        </ol>
    </li>
    <li>
        <span class="lessBig">Special Units</span>
        <ol>
            <li><span class="lessBig">Morgan</span>
                The <?= $forceName[2] ?> has a special unit, Morgan. This unit represents the sharp
                shooter associated with Morgans troops. To this end, they have a range of two, and can
                hit units 2 hexes away. The only restriction is they may NOT shoot over units friendly or enemy, Nor
                may shoot along the hexside between two units. They MAY shoot along the hexside of just one unit.
            </li>
            <li> <span class="lessBig">Arnold</span>
                The HQ units represents Arnold, arnold may stack with, attack with and defend with Another unit.
                adding 3 to any attack.
            </li>
            <li>
                <span class="lessBig">
                    Combat Bonus
                </span>
                If Morgan or Arnold participate in an attack there is a +1 shift for the attacker.
                These cannot be combined for greater than +1 shift, but you may have two seperate attacks with
                a +1 shift.
            </li>
            <li>
                <span class="lessBig">Small units</span>
                Several of the <?= $forceName[1] ?> are considered Small units and have an S on them between the two numbers
                These units MAY stack with other units, they DO NOT contribute to a combat shift for artillery UNLESS there
                are 3 attack strength point of artiller.
            </li>
        </ol>
    </li>

