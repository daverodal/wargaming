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
<style type="text/css">

        /*#GR ol.ExclusiveRules{*/
            /*counter-reset: item 6;*/
       /*}*/
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div class="game-rules">
            <H1>
                <?= $name ?>
            </H1>

            <h2 class="exclusive"> EXCLUSIVE RULES </h2>
            <ol>
                @section('inner-units')
                    @parent

                    <li class="exclusive">
                    <span class="lessBig">Aditional Units</span><p>Wagons look like this.</p>
                        <div class="unit Swedish wagon" style="top: 0px; left: 0px; position: relative; border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); border-style: solid; box-shadow: none;">
                            <div class="counterWrapper">
                                <div class="counter"></div>
                            </div>
                            <p class="range"></p>

                            <p class="forceMarch" style="display: none;">M</p>

                            <div class="unit-numbers"><span class="unit-info reduced infoLen7">(2) - 2</span></div>

                        </div>

                        <p>Wagons are supply wagons, and are highly valueable. Wagons have no attack strength, defend with a strength of 2 and may stack with one other non wagon unit. If
                    a hex containing a wagon and another unit is attacked, the combined strength of the units are used to defend. If the result is a DR, the wagon is destroyed. Wagons look like this.
                        Wagons are worth 5 victory points if destroyed. See victory below.</p>
                </li>
                @show
                <li><span class="lessBig">Deploy Phase</span>
                    <p class="indent">The <?= $deployName[1]?> player deploys first. The <?= $deployName[2]?> player deploys Second</p>

                </li>
                <li><span class="lessBig">First Player</span>
                    <p class="indent">The <?= $forceName[1]?> player moves first. The  <?= $forceName[2]?>  player moves second.</p>
                </li>

                <li><span class="lessBig">Terrain</span>
                    <p class="indent">Wagons may only move through clear terrain or on roads (in road mode).</p>
                </li>
                    @section('inner-movement')
                        @parent
                <li class="exclusive"><span class="lessBig">Wagons</span>
                    <p class="indent">Wagons may stack with one other non-wagon unit, they may not stack with another wagon. The stacking limit is one wagon and one non-wagon unit.</p>
                </li>
                    @show
                <li><span class="lessBig">Combat</span>
                    <p class="indent">Wagons may not attack. If stacked with another unit they add 2 to the defense of the stack.</p>
                    <p class="indent">Wagons may not retreat and are eleminated if forced to do so.</p>
                </li>
            </ol>
            <ol class="ExclusiveRules topNumbers">
                @section('victoryConditions')
                    @show
            </ol>
            <div id="credits">
                <h2><cite><?= $name ?></cite></h2>
                <h4>Design Credits</h4>

                <h4>Game Design:</h4>
                Lance Runolfsson
                <h4>Graphics and Rules:</h4>
                <site>Lance Runolfsson</site>
                <h4>HTML 5 Version:</h4>
                David M. Rodal
            </div>
        </div>
    </div>
</div>