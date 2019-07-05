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

<div id="main-viewer">
    <header id="header" :class="headerPlayer">
        <div id="preHeaderContent"></div>
        <div id="headerContent">
            <div :class="{open: debug}" class="bug-report">
                <form id="bug-report-form">
                    <textarea v-model="bugMessage" id="bug-report-message" rows="10" cols="50"></textarea><br>
                    <button @click.stop.prevent="saveBugReport" id="submit-bug-report">Submit Report</button>
                    <button @click.stop.prevent="bugReport">Cancel</button>
                </form>
            </div>
            <div class="left-header">
                <div @click="menu = !menu" class=" btn-group"  :class="{open: menu}" @click="menu = !menu">
                    <button class="WrapperLabel" title="Game Menu"><i class="tablet fa fa-bars"></i></button>

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
                <div :class="{open: crtOpen}" class="btn-group" id="crt-wrapper">
                    <button @click="changeCrt()"  class="wrapper-label" title='Combat Results Table'>
                        <span>CRT</span></button>
                </div>
                <div class="btn-group dropDown" :class="{open: undo}" id="time-wrapper">
                    <button @click="toggleUndo()" class="wrapper-label" title='Time Travel'>U<small>ndo</small></button>
                </div>
                <div @click="zoom()" class="btn-group" id="zoomWrapper">
                    <button id="zoom">
                        <span class="defaultZoom">1.0</span>
                    </button>
                </div>
                <div class="btn-group"  :class="{open: rules}" >
                    <button @click="rules = !rules" class=""><span class="tablet">?</span></button>
                    <ul class="dropdown-menu">
                        <li><a @click="menuClick('rules')" id="rules">Rules</a></li>
                        <li><a @click="menuClick('showTec')" id="hideShow">TEC</a></li>
                        @section('exclusiveRulesWrapper')
                            @include('wargame::TMCW.exclusiveRulesWrapper')
                        @show
                        @section('obc')
                            <li><a @click="menuClick('showObc')" id="showObc">Show OBC</a></li>
                        @show
                    </ul>
                </div>
                <div class="rules-wrapper" style="position:relative">
                    <div style="position:absolute;z-index:10;background:white;margin-top:3em;" v-if="commonRules">
                        @section('commonRules')
                        @show
                    </div>

                    <template v-if="showTec">
                        <div style="position:absolute;z-index:10;background:white;margin-top:3em;">
                        @section('tec')
                        @show
                        </div>
                    </template>

                    <template v-if="showObc">
                        <obc-component :obc="allMyBoxes"></obc-component>
                    </template>
                </div>



                <div class="btn-group" :class="{open: log}" @click="log = !log" >
                    <button class=""><span>Log</span></button>
                    <div class="dropdown-menu">
                        <ol id="log-wrapper" v-html="headerLog"></ol>
                    </div>
                </div>

                @section('outer-units-menu')
                    <div class=" btn-group" :class="{open: submenu}" @click="submenu = !submenu" id="units-wrapper">
                        <button class="" title="Offmap Units">Units</button>
                        <ul  id="units" class="dropdown-menu sub-menu">
                            <li><a @click="menuClick('all')" id="closeAllUnits">Close All</a></li>
                            <li><a @click="menuClick('deadpile')" id="hideShow">Retired Units</a></li>
                            <li><a @click="menuClick('deployBox')" id="showDeploy">Deploy/Staging Box</a></li>
                            <li><a @click="menuClick('exitBox')" id="showExited">Exited Units</a></li>
                        </ul>
                    </div>
                @show
            </div>
            <div class="right-header">
                <div id="statusWrapper">

                    <div id="topStatus" v-html="headerTopStatus"></div>
                    <div>
                        <span id="status" v-html="headerStatus"></span>
                        <span id="combatStatus" v-html="combatStatus"></span>
                        @section('victory')
                            <span id="victory" v-html="headerVictory"></span>
                        @show
                    </div>
                </div>
                <div id="nextPhaseWrapper">
                    @section('innerNextPhaseWrapper')
                        <button @click="fullScreen()" id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
                        <button :class="{'inline-show': dynamic.determined}" class="dynamicButton combatButton" id="determinedAttackEvent">d</button>
                        <button :class="{'inline-show': dynamic.move}" class="dynamicButton movementButton" id="forceMarchEvent">m</button>
                        <button @click="clearCombat" :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
                        <button :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="shiftKey">+</button>
                        <button :class="{'inline-show': dynamic.showHexes}" class="dynamicButton hexButton" id="showHexes">H</button>
                        <button @click="bugReport" class="debugButton" id="debug"><i class="fa fa-bug"></i></button>
                        <button @click="nextPhase" id="nextPhaseButton">Next Phase</button>

                    @show
                        <div id="comlinkWrapper">
                            <div id="comlink"></div>
                        </div>
                </div>

            </div>




            @section('casualty')
            @show


            @section('outer-aux-menu')
            @show

        </div>
        <div id="secondHeader">
            <div id="boxes-wrapper">
                @section('unit-boxes')
                    <div class="unit-wrapper" v-show="show.units.deployBox">
                        <div @click="show.units.deployBox = false" class="close">X</div>
                        <div style="margin-right:24px;" class="left">Deploy/Staging area</div>
                        @section('deploy-box')
                            <div id="deployBox">
                                <vue-draggable-resizable @dragging="didDrag" :h="60" :w="3000" axis="x">
                                    <units-component :myfilter="1" :myunits="allMyBoxes.deployBox"></units-component>
                                    <div class="clear"></div>
                                    <units-component :myfilter="2" :myunits="allMyBoxes.deployBox"></units-component>
                                    <div class="clear"></div>
                                </vue-draggable-resizable>
                                <div class="clear"></div>
                            </div>
                        @show
                        <div style="clear:both;"></div>
                    </div>

                    <div class="unit-wrapper" id="deadpile-wrapper" v-show="show.units.deadpile">
                        <div class="close">X</div>
                        <div style="font-size:50px;font-family:sans-serif;float:right;color:#666;">
                            Retired Units
                        </div>
                        <vue-draggable-resizable @dragging="didDrag" :h="60" :w="3000" axis="x">
                            <units-component :myfilter="1" :myunits="allMyBoxes.deadpile"></units-component>
                            <div class="clear"></div>
                            <units-component :myfilter="2" :myunits="allMyBoxes.deadpile"></units-component>
                            <div class="clear"></div>
                        </vue-draggable-resizable>
                        <div class="clear"></div>

                    </div>
                    <div class="unit-wrapper" v-show="show.units.exitBox" id="exitWrapper">
                        <div class="close">X</div>
                        <div style="margin-right:3px;" class="left">Exited Units</div>
                        <div id="exitBox">
                            <units-component :myunits="allMyBoxes.exitBox"></units-component>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                @show
            </div>
            <div class="clear"></div>
        </div>


    </header>

    <div class='doody' id="content">
        <div  id="crt-drag-wrapper" style="position:absolute;z-index:1000;">
            <div v-show="showCrt" class="vue-wrapper">
                <vue-crt :crt-options="crtOptions" :crt="'{{ json_encode(new \Wargame\Vu\CombatResultsTable()) }}'"></vue-crt>
            </div>
        </div>
        <div  id="undo-drag-wrapper">
            <div v-show="showUndo" class="vue-wrapper">
                <undo></undo>
            </div>
        </div>
        <div id="gameViewer">
            <div id="floaters" style="position:absolute; width:100%; height:100%;">
                <float-message  :x="x" :y="y" :header="header" id="myFloater" :message="message">
                </float-message>
            </div>

            <div id="gameContainer" >
                <div id="gameImages" @keyup.native="pushedKey" @click="mapClick">
                    <float-message  :x="x" :y="y" :header="header" id="myFloater" :message="message">
                    </float-message>
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

                    <img id="map" alt="map" src="{{$mapUrl}}">

                    <?php $id = 0; ?>
                    <units-component :myghosts="moveUnits" :myunits="units"></units-component>

                        <map-symbol v-for="(mapSymbol, index) in mapSymbols"  :key="index":mapsymbol="mapSymbol"></map-symbol>

                    <special-hex v-for="(specialHex, index) in specialHexes"  :key="'A' + index" :specialhex="specialHex"></special-hex>
                    <transition-group name="social-events" appear>
                        <special-event  v-for="(specialEvent,key) in specialEvents" :key="specialEvent.id" :special-event="specialEvent"></special-event>
                    </transition-group>

                    @section('units')
                    @show
                        <flash-hexagon :position="rowSvg"></flash-hexagon>

                </div>
                @show
            </div>
        </div>

        <audio class="pop" src="{{asset('assets/audio/pop.m4a')}}"></audio>
        <audio class="poop" src="{{asset('assets/audio/lowpop.m4a')}}"></audio>
        <audio class="buzz" src="{{asset('assets/audio/buzz.m4a')}}"></audio>
    </div>
    <div id="floatMessageContainer">
        <flash-messages :messages="messages"></flash-messages>
        @section('options')
        @show
    </div>
</div>
</body></html>
