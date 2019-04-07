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
?>
<li class="dropDown" id="GRWrapper" style="font-weight:normal">
    <a class="WrapperLabel" title="Game Rules">Rules</a>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div class="game-rules">
            <h1>    {{ $gameName }}   </h1>

            <h2>Rules of Play</h2>

            <h2>Design Context</h2>

            <p><?= $name ?> is a continuation of the gaming framework first pioneered by the game The Martian Civil War.
                We hope you enjoy playing our game.</p>


            <ol class="topNumbers">
                <li id="contentsRules">
                    @include('wargame::TMCW.commonContents')
                </li>
                <li id="unitsRules">
                    <span class="big">UNITS</span>

                    <ol>
                        @section('unitRules')
                            @include('wargame::TMCW.commonUnitsRules')
                        @show
                    </ol>

                <?php //include "commonUnitsRules.php" ?>
                </li>
                <li id="sopRules">
                    @section('SOP')
                        @include('wargame::TMCW.commonSequenceOfPlay')
                    @show
                </li>
                <li id="stackingRules">
                    @section('commonStacking')
                    @include('wargame::TMCW.commonStacking')
                    @show
                <?php //include "commonStacking.php" ?>
                </li>
                <li id="moveRules">
                    @include('wargame::TMCW.commonMoveRules')

                <?php //include "commonMoveRules.php" ?>
                </li>
                <li id="zocRules">
                    @section('zoc-rules')
                        @include('wargame::TMCW.commonZocRules')
                    @show
                </li>
                <li id="supply-rules">
                    @section('supply-rules')
                    @show
                </li>
                <li id="combatRules">
                    @include('wargame::TMCW.commonCombatRules')
                </li>

                <li id="exclusiveRules" class="exclusive">
                    @section('exclusiveRules')
                        @include('wargame::TMCW.exclusiveRules')
                    @show
                </li>
                <li class="exclusive" id="victoryConditions">
                    @section('victoryConditions')
                        @include('wargame::TMCW.victoryConditions')
                    @show

                </li>
                <li id="designCredits">
                    <span class="big">Design Credits</span>
                    @section('credit')
                        @include('wargame::TMCW.credit')
                    @show
                </li>
            </ol>
        </div>
    </div>
</li>

