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
        <span class="lessBig">Setting Up</span>
        <ol>
            <li>The <?= $forceName[2] ?> player sets up first. The <?= $forceName[1] ?> Setup second.</li>
            <?php if (empty($scenario->redux) && empty($scenario->hastenbeck2)) { ?>
                <li>When the <?= $forceName[1] ?> player starts deploying their units. There is a %50 chance they can
                    deploy in the F2 hexes, and %50 they
                    have to deploy in the F1 Hexes.
                </li>
            <?php } ?>
            <li> <?= $forceName[1] ?> moves first. <?= $forceName[2] ?> moves second.</li>

            </li>
        </ol>
    <li><span class="lessBig">Terrain</span>
        <ol>
            <li>
                Streams are +2 MP's to cross
            </li>
            <li>
                Major Rivers (dark thick blue) are impassable
            </li>
            <li>
                HAMELN Is a Major Fortification Garrisoned by the Allies No French unit may ever enter it.
            </li>
        </ol>

    </li>
