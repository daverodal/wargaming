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
    <li><span class="lessBig">Terrain: Hedge</span>
        <p>The hedge is treated as a stream hex side.</p>

    </li>
    <li><span class="lessBig">Setup and first player</span>
        <p>Americans: Set up first on and within hexes marked A. They move first.</p>
        <p>British (and Hessians): Set up second on hexes marked B. They move second.</p>
    </li>
    <li><span class="lessBig">
            Optional Rules <i><?= !empty($scenario->americanRevolution) ? "Enabled" : ""?></i>
        </span>
    <ol>
        <li>
            British Are +1 attacking and defending in clear
        </li>
        <li>American are +1 attacking and defending in town or forest</li>
    </ol>
    </li>



</ol>