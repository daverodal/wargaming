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
    <li><span class="lessBig">Demoralization</span>
        <ol>
            <li>
                Once the Rebels have lost 30 combat strength points their units my not enter voluntarily enter hostile
                ZOC’s or attack.
            </li>
        </ol>
    </li>
    <li><span class="lessBig">Combat</span>
        <ol>
            <li><?= $forceName[1] ?>: Units: Add 1 to their combat factor when defending in or attacking into clear
                terrain.
            </li>
            <li><?= $forceName[2] ?>: Units: Add 1 to their combat factor when defending in Hexes or all opponents are
                attacking across (hex sides) other than clear.

            </li>
            <li>
                Attacks across Fords: The Hessians were able to force the fords with little difficulty. Do not half
                units attacking across the fords (Trail Bridges).
            </li>

        </ol>
    </li>
