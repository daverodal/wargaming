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
?><div class="btn-group dropDown" :class="{open: undo}" id="time-wrapper">
    <button @click="undo = !undo" class="WrapperLabel" title='Time Travel'>U<small>ndo</small></button>
    <vue-draggable-resizable v-show="undo" style="z-index: 100;">
        <undo class=""></undo>
    </vue-draggable-resizable>
</div>



<?php
/*
 *    <div id="Time"><div class="close">X</div>
        Time you are viewing:
        <div id="clickCnt"></div>
        <div id="phaseClicks"></div><br>
        <div class="cool-box go-buttons">
            <div class="time-left col-xs-6">
                Cancel Undo<br>
                <div class="fancy-time-button " id="timeLive">Go to present - cancel</div>

            </div>
            <div class="time-right col-xs-6">
                Perform Undo<br>
                <div class="fancy-time-button right" id="timeBranch">Branch viewed time to present</div><br>
            </div>
            <div class="clear"></div>
        </div>


        <div class="cool-box">
            <div class="time-button-wrapper alpha col-xs-6">
                Back 1<br>
                <div class="fancy-time-button" id="click-back">click</div>
                <div class="fancy-time-button" id="phase-back">phase</div>
                <div class="fancy-time-button" id="player-turn-back">player turn</div>

            </div>
            <div class="time-button-wrapper col-xs-6">
                Forward 1<br>
                <div class="fancy-time-button" id="click-surge">click</div>
                <div class="fancy-time-button" id="phase-surge">phase</div>
                <div class="fancy-time-button" id="player-turn-surge">player turn</div>

            </div>
            <div class="clear"></div>

        </div>


    </div>
 */