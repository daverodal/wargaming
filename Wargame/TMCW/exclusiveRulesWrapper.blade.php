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

/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="dropDown" id="GRWrapper">

    <div id="GR">
        <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

        <div @click="showExRules = false" class="close">X</div>
        <div class="game-rules">
            <H1>
                <?=$name?>
            </H1>

            @section('exclusiveRules')
                <h2 class="exclusive"> EXCLUSIVE RULES
                </h2>
                @include('wargame::TMCW.exclusiveRules')
            @show
            @section('victoryConditions')
                @include('wargame::TMCW.victoryConditions')
            @show
            <h2 class="exclusive">Design Credits</h2>
            @section('credit')
                @include('wargame::TMCW.credit')
            @show
        </div>
    </div>
</div>