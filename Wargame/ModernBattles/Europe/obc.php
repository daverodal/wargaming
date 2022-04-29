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
        <fieldset ng-repeat="x in [].constructor(maxTurn) track by $index">
            <legend>turn {{ $index + 1 }} </legend>
            <div id="gameTurn{{$index + 1}}">
                <div class="a-unit-wrapper" ng-repeat="unit in reinforcements['gameTurn' + ($index+1)]"  ng-style="unit.wrapperstyle">
                    <offmap-unit unit="unit"></offmap-unit>
                </div>
                <div ng-if="turn - 1 == $index" id="gameTurnCounter">Game Turn</div>
            </div>
        </fieldset>
        <div class="clear"></div>
    </div>
</div>