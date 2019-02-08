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
?>
<body xmlns="http://www.w3.org/1999/html">
<div class="bug-report"  style="display:none;">
    <form id="bug-report-form">
        <textarea id="bug-report-message" rows="10" cols="50"></textarea><br>
        <button id="submit-bug-report">Submit Report</button>
        <button id="cancel-bug-report">Cancel</button>
    </form>
</div>
<div id="main-viewer">
    <header id="header">
        <div id="preHeaderContent"></div>
        <div id="headerContent">

            <div @click="menu = !menu" class=" btn-group"  :class="{open: menu}" @click="menu = !menu">
                <button class="WrapperLabel" title="Game Menu"><i class="tablet fa fa-bars"></i><span class="desktop">Menu</span></button>

                    <ul class="dropdown-menu">
                        @section('inner-menu')
                        <li><a id="mute-button">mute</a></li>
                        <li><a href="<?= url("wargame/leave-game"); ?>">Go To Lobby</a></li>
                        <li><a href="<?= url("logout"); ?>">logout</a></li>
                        <li><a id="arrow-button" @click="showArrows()">show arrows</a></li>
                        <li><a href="#" onclick="seeUnits();return false;">See Units</a></li>
                        <li><a href="#" onclick="seeBoth();return false;">See Both</a></li>
                        <li><a href="#" onclick="seeMap();return false;">See Map</a></li>
                        @show
                        <li class="closer"></li>
                    </ul>
            </div>
            <div class="btn-group info-dropdown"  :class="{open: info}" @click="info = !info">
                <button class="WrapperLabel" title="Game Information"><i class="fa fa-info-circle"></i></button>
                <ul class="dropdown-menu">
                    <li> Welcome {{$user}}</li>

                    @if($playersData[0] != '')
                        <li>
                            {{ $playersData[0] }} as {{ $playDat["forceName"][0] }}
                        </li>
                    @endif
                    <li>
                        {{ $playersData[1] }} as {{ $playDat["forceName"][1] }}
                    </li>
                    <li>
                        {{ $playersData[2] }} as {{ $playDat["forceName"][2] }}
                    </li>

                    <li>
                        in <span class="game-name">{{$gameName}}-{{$arg}}</span></li>
                    <li>
                        name is {{ $docName }}
                    </li>

                    <li class="game-id">
                        {{ $wargame }}
                    </li>
                    <li class="closer"></li>
                </ul>
            </div>
            <div :class="{open: crt}" class="btn-group" id="crt-wrapper">
                <button @click="changeCrt()"  class="wrapper-label" title='Combat Results Table'>
                    <span>CRT</span></button>
                <vue-draggable-resizable v-show="crt" style="z-index: 100;">
                    <vue-crt :crt-options="crtOptions" :crt="'{{ json_encode(new \Wargame\Vu\CombatResultsTable()) }}'"></vue-crt>
                </vue-draggable-resizable>
            </div>
            <?php //include "timeTravel.php"; ?>
            <div id="nextPhaseWrapper">
                @section('innerNextPhaseWrapper')
                <button @click="fullScreen()" id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
                <button :class="{'inline-show': dynamicButtons.determined}" class="dynamicButton combatButton" id="determinedAttackEvent">d</button>
                <button :class="{'inline-show': dynamicButtons.move}" class="dynamicButton movementButton" id="forceMarchEvent">m</button>
                <button :class="{'inline-show': dynamicButtons.move}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
                <button :class="{'inline-show': dynamicButtons.move}" class="dynamicButton combatButton" id="shiftKey">+</button>
                <button :class="{'inline-show': dynamicButtons.showHexes}" class="dynamicButton hexButton" id="showHexes">H</button>
                <button class="debugButton" id="debug"><i class="fa fa-bug"></i></button>
                <button @click="nextPhase" id="nextPhaseButton">Next Phase</button>
                <div id="comlinkWrapper">
                    <div id="comlink"></div>
                </div>
                @show

            </div>
            <div id="statusWrapper">

                <div id="topStatus"></div>
                <div>
                    <span id="status"></span>
                    <span id="victory"></span>
                </div>
            </div>
            <div @click="zoom()" class="btn-group" id="zoomWrapper">
                    <button id="zoom">
                        <span class="defaultZoom">1.0</span>
                    </button>
            </div>
            <div class="btn-group"  :class="{open: rules}" @click="rules = !rules">
                <button class=""><span class="tablet">?</span><span class="desktop">Rules</span></button>
                <div class="dropdown-menu">


                    @section('commonRules')
                    @show
                    @section('exclusiveRulesWrapper')
                        @include('wargame::TMCW.exclusiveRulesWrapper')
                    @show
                    @section('obc')
                    @show
                    @section('tec')
                        @include("wargame::Mollwitz.tec")
                    @show
                </div>
            </div>

            <div class="btn-group" :class="{open: log}" @click="log = !log" >
                <button class=""><span class="tablet">Log</span><span class="desktop">Log</span></button>
                <div class="dropdown-menu">
                    <ol id="logWrapper">logs man</ol>
                </div>
            </div>

            @section('tec')
                @include("wargame::Mollwitz.tec")
            @show

            @section('casualty')
            @show

            @section('outer-units-menu')
            <div class=" btn-group" :class="{open: submenu}" @click="submenu = !submenu" id="unitsWrapper">
                <button class="" title="Offmap Units">Units</button>
                <ul  id="units" class="dropdown-menu sub-menu">
                    <li><a @click="menuClick('all')" id="closeAllUnits">Close All</a></li>
                    <li><a @click="menuClick('deadpile')" id="hideShow">Retired Units</a></li>
                    <li><a @click="menuClick('deployBox')" id="showDeploy">Deploy/Staging Box</a></li>
                    <li><a @click="menuClick('exitBox')" id="showExited">Exited Units</a></li>
                </ul>
            </div>
            @show
            @section('outer-aux-menu')
            @show



            <div style="clear:both;"></div>

        </div>
        <div id="secondHeader">
            <div id="boxes-wrapper">
                    <div class="unit-wrapper" v-show="show.units.deployBox">
                        <div @click="show.units.deployBox = false" class="close">X</div>
                        <div style="margin-right:24px;" class="left">Deploy/Staging area</div>
                        <div id="deployBox">
                            <units-component :myunits="deployBox"></units-component>
                            <div class="clear"></div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>

            <div class="unit-wrapper" id="deadpile-wrapper" v-show="show.units.deadpile">
                <div class="close">X</div>
                <div style="font-size:50px;font-family:sans-serif;float:right;color:#666;">
                    Retired Units
                </div>
                <units-component :myunits="deadpile"></units-component>
                <div class="clear"></div>

            </div>
            <div class="unit-wrapper" v-show="show.units.exitBox" id="exitWrapper">
                <div class="close">X</div>
                <div style="margin-right:3px;" class="left">Exited Units</div>
                <div id="exitBox">
                    <units-component :myunits="exitBox"></units-component>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
            <div class="clear"></div>
        </div>


    </header>

    <div id="content">
        <div id="rightCol">

            <div id="gameViewer">
                <div id="floaters" style="position:absolute; width:100%; height:100%;">
                    <float-message  :x="x" :y="y" :header="header" id="myFloater" :message="message">
                    </float-message>
                </div>

                <div id="gameContainer" >
                    <div id="gameImages">
                        @section('game-images')
                        <div id="svgWrapper">
                            <svg id="arrow-svg" style="opacity:.6;position:absolute;" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <marker id="comar" markerWidth="10" markerHeight="10" refX="0" refY="3" orient="auto" markerUnits="strokeWidth">
                                        <path d="M0,0 L0,6 L9,3 z" fill="#f00" />
                                    </marker>
                                    <marker id='heead' orient="auto" fill="red"
                                            markerWidth='2.5' markerHeight='5'
                                            refX='0.1' refY='2.5'>
                                        <!-- triangle pointing right (+x) -->
                                        <path d='M0,0 V5 L2.5,2.5 Z' />
                                    </marker>


                                    <marker
                                        inkscape:stockid="Arrow1Lend"
                                        orient="auto"
                                        refY="0.0"
                                        refX="0.0"
                                        id="head"
                                        fill="red"
                                        style="overflow:visible;">
                                        <path
                                            id="path3762"
                                            d="M 0.0,0.0 L 5.0,-5.0 L -12.5,0.0 L 5.0,5.0 L 0.0,0.0 z "
                                            style="fill-rule:evenodd;stroke:#000000;stroke-width:1.0pt;"
                                            transform="scale(0.15) rotate(180) translate(12.5,0)" ></path>
                                    </marker>
                                </defs>
                            </svg>
                        </div>
                        <img id="map" alt="map" src="<?php preg_match("/http/",$mapUrl) ?   $pre = '': $pre = url('.');echo "$pre$mapUrl";?>">

                        <?php $id = 0; ?>
                        <units-component :myghosts="moveUnits" :myunits="units"></units-component>

                        <map-symbol v-for="mapSymbol in mapSymbols"  :mapsymbol="mapSymbol"></map-symbol>
                        <transition-group name="social-events" appear>
                            <special-event  v-for="(specialEvent,key) in specialEvents" :key="specialEvent.id" :special-event="specialEvent"></special-event>
                        </transition-group>

                        @section('units')
                        @show
                    </div>
                    @show
                </div>
            </div>

            <audio class="pop" src="{{asset('assets/audio/pop.m4a')}}"></audio>
            <audio class="poop" src="{{asset('assets/audio/lowpop.m4a')}}"></audio>
            <audio class="buzz" src="{{asset('assets/audio/buzz.m4a')}}"></audio>

        </div>
    </div>
    <div id="display"></div>
</div>
</body></html>
