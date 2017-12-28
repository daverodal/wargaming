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
        <div id="headerContent">
            <div id="mouseMove">mouse</div>

            <div class="dropDown alpha" id="menuWrapper">
                <h4 class="WrapperLabel" title="Game Menu"><i class="tablet fa fa-bars"></i><span class="desktop">Menu</span></h4>

                <div id="menu">
                    <div class="close">X</div>
                    <ul>
                        @section('inner-menu')
                        <li><a id="muteButton">mute</a></li>
                        <li><a href="<?= url("wargame/leave-game"); ?>">Go To Lobby</a></li>
                        <li><a href="<?= url("logout"); ?>">logout</a></li>
                        <li><a id="arrowButton">show arrows</a></li>
                        <li><a href="#" onclick="seeUnits();return false;">See Units</a></li>
                        <li><a href="#" onclick="seeBoth();return false;">See Both</a></li>
                        <li><a href="#" onclick="seeMap();return false;">See Map</a></li>
                        @show
                        <li class="closer"></li>
                    </ul>
                </div>
            </div>
            <div class="dropDown" id="infoWrapper">
                <h4 class="WrapperLabel" title="Game Information"><i class="tablet">i</i><span class="desktop">Info</span></h4>
                <div id="info">
                    <div class="close">X</div>
                    <ul>
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
            </div>
            <?php global $results_name; ?>

            <div id="crtWrapper">
                <h4 class="WrapperLabel" title='Combat Results Table'>
                    <span>CRT</span></h4>

                <div id="crt">
                    <div class="close">X</div>
                    <h3>Combat Odds</h3>

                    @section('inner-crt')
                        @include('wargame::stdIncludes.inner-crt',['topCrt'=> $top_crt = new \Wargame\TMCW\CombatResultsTable()])
                    @show

                    <div id="crtDetailsButton">details</div>
                    <div id="crtOddsExp"></div>
                </div>
            </div>
            @include("wargame::stdIncludes.timeTravel")
            <?php //include "timeTravel.php"; ?>
            <div id="nextPhaseWrapper">
                @section('innerNextPhaseWrapper')
                <button id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
                <button class="dynamicButton combatButton" id="determinedAttackEvent">d</button>
                <button class="dynamicButton movementButton" id="forceMarchEvent">m</button>
                <button class="dynamicButton combatButton" id="clearCombatEvent">c</button>
                <button class="dynamicButton combatButton" id="shiftKey">+</button>
                <button class="dynamicButton hexButton" id="showHexes">H</button>
                <button class="debugButton" id="debug"><i class="fa fa-bug"></i></button>
                <button id="nextPhaseButton">Next Phase</button>
                <div id="comlinkWrapper">
                    <div id="comlink"></div>
                </div>
                @show

            </div>
            <div id="statusWrapper">

                <div id="topStatus"></div>
                <div class="clear">
                    <span id="status"></span>
                    <span id="victory"></span>
                </div>
            </div>
            <div id="zoomWrapper">
                    <span id="zoom">
                        <span class="defaultZoom">1.0</span>
                    </span>
            </div>
            <div class="dropDown">
                <h4 class="WrapperLabel"><span class="tablet">?</span><span class="desktop">Rules</span></h4>
                <div class="subMenu">

                    @section('commonRules')
                    @show
                    @section('exclusiveRulesWrapper')
                        @include('wargame::TMCW.exclusiveRulesWrapper')
                    @show
                    @section('obc')
                    @show
                </div>
            </div>

            <div class="dropDown">
                <h4 class="WrapperLabel"><span class="tablet">Log</span><span class="desktop">Log</span></h4>
                <div class="subMenu">
                    <ol id="logWrapper"></ol>
                </div>
            </div>

            @section('tec')
                @include("wargame::Mollwitz.tec")
            @show

            @section('casualty')
            @show

            @section('outer-units-menu')
            <div class="dropDown" id="unitsWrapper">
                <h4 class="WrapperLabel" title="Offmap Units">Units</h4>

                <div id="units" class="subMenu">
                        <div class="dropDown" id="closeAllUnits">Close All</div>
                        <div class="dropDown" id="hideShow">Retired Units</div>
                        <div class="dropDown" id="showDeploy">Deploy/Staging Box</div>
                        <div class="dropDown" id="showExited">Exited Units</div>
                        <div class="dropDown" id="showNotUsed">Not Used</div>

                </div>
            </div>
            @show
            @section('outer-aux-menu')
            @show



            <div style="clear:both;"></div>

        </div>
    </header>
    <div id="content">
        <div id="rightCol">
            @section('unit-boxes')
            <div class="unit-wrapper" id="deployWrapper">
                <div class="close">X</div>
                <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
                <div id="deployBox"></div>
                <div style="clear:both;"></div>
            </div>
            <div class="unit-wrapper" style="display:none;" id="deadpile">
                <div class="close">X</div>
                <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                    Retired Units
                </div>
            </div>
            <div class="unit-wrapper" style="display:none;" id="exitWrapper">
                <div class="close">X</div>
                <div style="margin-right:3px;" class="left">Exited Units</div>
                <div id="exitBox">
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="unit-wrapper" style="display:none;" id="notUsedWrapper">
                <div class="close">X</div>
                <div style="margin-right:3px;" class="left">Units not used.</div>
                <div id="notUsed"></div>
                <div style="clear:both;"></div>
            </div>

            <div class="unit-wrapper" style="display:none;" id="undeadpile"></div>
            <div id="vue-app">
            </div>
            @show
            <div id="gameViewer">
                <div id="gameContainer">
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

                        @section('units')
                        @foreach ($units as $unit)
                            <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
                                <div class="shadow-mask"></div>
                                <div class="unitSize">{{$unit['unitSize']}}</div>
                                <img class="arrow" src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
                                <div class="counterWrapper">
                                    <img src="{{asset("assets/unit-images/".$unit['image'])}}" class="counter"><span class="unit-desig"><?=$unit['unitDesig']?></span>
                                </div>
                                <div class="unit-numbers">5 - 4</div>
                            </div>
                            @endforeach
                        @show

                        <div id="floatMessage">
                            <header></header>
                            <p></p>
                        </div>
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
