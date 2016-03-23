<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?><style type="text/css">
    #header {
        /*display:none;*/
    }

    .exclusive {
        color: green;
    }

    .game-rules {
        font-family: sans-serif;
    }

    .game-rules table, .game-rules th, .game-rules td {
        border: 1px solid black;
    }

    .game-rules h1 {
        color: #338833;
        font-size: 60px;

    }


</style>
<div class="dropDown" id="GRWrapper" style="font-weight:normal">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div class="game-rules">
            <?php $playerOne = $forceName[1];
            $playerTwo = $forceName[2]; ?>
            <h1>    <?= $name ?>    </h1>

            <h2>Rules of Play</h2>

            <h2>Design Context</h2>

            <p><?= $name ?> is a continuation of the gaming framework first pioneered by the game The Martian Civil War.
                We hope you enjoy playing our game.</p>


            <ol class="topNumbers">
                <li id="contentsRules">
                    @include("wargame::SPI.commonContents")
                </li>
                <li id="unitsRules">
                    @section('commonUnitsRules')
                        @include("wargame::SPI.commonUnitsRules")
                    @show
                </li>
                <li id="sopRules">
                    @include("wargame::SPI.commonSequenceOfPlay")
                </li>
                <li id="stackingRules">
                    @include("wargame::SPI.commonStacking")
                </li>
                <li id="moveRules">
                    @include("wargame::SPI.commonMoveRules")
                </li>
                <li id="zocRules">
                    @include("wargame::SPI.commonZocRules")
                </li>
                <li id="combatRules">
                    @include("wargame::SPI.commonCombatRules")
                </li>

                <li class="exclusive" id="victoryConditions">
                    @section('victoryConditions')
                        @include("wargame::SPI.victoryConditions")
                    @show
                </li>

            </ol>
        </div>
    </div>
</div>

