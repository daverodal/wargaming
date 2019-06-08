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
        <ol>
            <li>
                <?= $deployName[2] ?> deploy first move first. Deploy on L and LD hexes
                Units deployed on LD hexes will not be able to move until turn 4.
                    Units deployed on the L hexes will not be able to move on turn 1.
                Stackable units may (and in fact must) deploy stacked.
            </li>
            <li>
                <?= $deployName[1] ?> Deploy second move second deploy on L hexes.
            </li>
        </ol>
    </li>
    <li><span class="lessBig">Exclusion Zones</span>
        <ol>
            <li>
                Only Loyalist units may enter Red E hexes

            </li>
            <li>
                Only Rebel units may enter Blue E hexes
            </li>
        </ol>
    </li>
    <li>
        <span class="lessBig">Stackable Units</span>
        <ol>
            <li>
                Stackable unit are marked by an S between Combat and move factors. Add their factors too units stacked with on defense and Attack;
                except arty not doubled on defense when stacked. Stacked units all share the same fate in combat results.
                Two stackable units may stack with each other but there may never be more than a total of two units in a hex.
            </li>
        </ol>
    </li>
<li>
    <span class="lessBig">Charismatic leader Benedict Arnold</span>
    <ol>
        <li>
            Arnold may stack with another unit even if it has another stackable unit with it,
            adds Combat factor to stack. Has no combat factor when not stacked.
        </li>
        <li>
            When attacking He shifts a column one to right.
        </li>
        <li>
            He is destroyed if the unit he is stacked with is destroyed. He is destroyed if a hostile moves into his hex and he is not stacked with a friendly.
        </li>
    </ol>
</li>
<li>
    <span class="lessBig">Morgan’s Rifles (Rebel 6-4 with range of 2)</span>
    <ol>
        <li>Has a range of 2 may not fire over other units, Nor may it shoot along the hexside between two units. It MAY shoot along the hexside of just one unit.
            It follows other artillery line of site rules.Morgan like Artillery is not halved attacking across streams, rivers.
        </li>
        <li>
            It provides a column shift to the right when attacking.
        </li>
    </ol>
</li>

