<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
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
?><div class="dropDown" id="OBCWrapper">
    <h4 class="WrapperLabel" title='Order of Battle Chart'>OBC</h4>
    <div id="OBC" style="display:none;"><div class="close">X</div>
        <fieldset>
            <legend>turn 1</legend>
            <div id="gameTurn1">
                <div id="turnCounter">Game Turn</div>
            </div>
        </fieldset>
        <fieldset>
            <legend>turn 2</legend>
            <div id="gameTurn2"></div>
            A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2C"  ng-style="unit.wrapperstyle">
                 <offmap-unit unit="unit"></offmap-unit>
                </div>
            D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2D"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2E"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
        </fieldset>
        <fieldset>
            <legend>turn 3</legend>
            <div id="gameTurn3"></div>
            A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3C"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3D"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3E"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
        </fieldset>
        <fieldset>
            <legend>turn 4</legend>
            <div id="gameTurn4"></div>
            A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3C"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3D"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3E"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>

        </fieldset>
        <fieldset>
            <legend>turn 5</legend>
            <div id="gameTurn5">
                A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
                B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
                C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3C"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
                D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3D"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
                E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3E"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
        </fieldset>
        <fieldset>
            <legend>turn 6</legend>
            A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3C"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3D"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3E"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
        </fieldset>
        <fieldset>
            <legend>turn 7</legend>
            <div id="gameTurn7">
            </div>
            A: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2A"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            B: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn2B"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            C: <div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3C"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            D:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3D"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            E:<div class="a-unit-wrapper" ng-repeat="unit in reinforcements.gameTurn3E"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
        </fieldset>
        <div style="clear:both"></div>
    </div>
</div>