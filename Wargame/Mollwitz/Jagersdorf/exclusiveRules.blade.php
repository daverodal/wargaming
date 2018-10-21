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
    <li>
        <h4><?= $forceName[2] ?> Movement Phase </h4>
        <ul>
            <li>
                No <?= $forceName[2] ?> unit may expend more than 2 MP on turn 1 only
            </li>
        </ul>
    </li>
    <li>

        <h4>Terrain Effects on Combat</h4>
        <ul>
            <li>Russian Infantry units are +1 to their combat factor when Attacking into
                or Defending in woods or
                towns, unless they are attacking across a creek or bridge.
            </li>

            <li>Prussian Infantry units are +1 to their combat factor when Attacking into
                or Defending in clear, unless they are attacking across a creek or bridge.
            </li>
        </ul>
    </li>


