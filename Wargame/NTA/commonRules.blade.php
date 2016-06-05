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

/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">
    .game-rules {
        font-family: sans-serif;
        font-size: 16px;
        font-weight: normal;
    }

    .game-rules table, .game-rules th, .game-rules td {
        border: 1px solid black;
    }

    .game-rules h1 {
        color: #338833;
        font-size: 60px;

    }

    #GR #credits h2 {
        color: #338833;
    }

    #GR li {
        margin: 3px 0;
    }

    #GR h4 {
        margin-bottom: 5px;
    }

    #GR #credits h4 {
        margin-bottom: 0px;
    }

    .game-rules h4:hover {
        text-decoration: none;
    }
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>
    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div class="game-rules">
            <H1>
                Napoleons Training Academy
            </H1>

            <h3> Quick Start Guide #3</h3>
            <header>

            </header>
            <h2></h2>
            <ol>

                <li> Sequence of Play</li>
                <ol>
                    <li>Red Movement Phase (Alpha)</li>

                    <li>Red Combat Setup Phase (Alpha)</li>

                    <li>Red Combat Resolution Phase (Alpha)</li>

                    <li>Blue Movement Phase (Bravo)</li>

                    <li>Blue Combat Setup Phase (Bravo)</li>

                    <li>Blue Combat Resolution Phase (Bravo)</li>

                    <li>Turn End</li>
                </ol>
                </li>
            </ol>
            <li>
                <span class="big">Movement</span>

                <p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.</p>

                <p>All hexes in the game are considered clear, even the lone tree in the center of the game. 1 Movement
                    point per hex required for all hexes.</p>

            </li>
            <li><span class="big">Zones of Control. </span>
                <ol>
                    <li>
                        The 6 hexs surrounding a unit constitute it's zone of control (ZOC).
                    </li>
                    <li>
                        When a unit enters a Hostile <abbr title="Zone Of Control">ZOC</abbr> it must
                        stop
                        and move no
                        further that turn.
                    </li>
                    <li>
                        Units that start a turn in an enemy ZOC may not move that phase, and may only
                        escape a zoc via combat.
                    </li>
                    <li>
                        Any hex may have both friendly and enemy ZOC's in it. They do no affect each other in any way.
                    </li>
                    <li>
                        All friendly units that start the combat setup phase in a zoc must attack that phase.
                    </li>
                    <li>
                        All enemy units that start the combat setup phase in a zoc must be attacked in that phase.
                    </li>
                </ol>

            </li>
            <li>
                <span class="big">Combat (Attacks)</span>

                <p>The first number on a unit is it's combat factor.</p>
                <ol>
                    <li> <span class="big">General Rules
                        </span>
                        <ol>
                            <li>A single unit may only participate in single attack in the friendly attack phase.</li>
                            <li>Attacks may always be made at lower than odds than those gained by calculation.</li>
                            <li>All attacks are voluntary. Except that all hostile units adjacent to an attacking unit
                                must
                                them
                                selves be
                                attacked even if only by artillery bombardment.
                            </li>
                            <li>All combat is between adjacent units except that artillery may attack units up to two
                                hexes
                                away
                                (Bombardment).
                                Including into but not over town, hill or woods. Artillery units may not participate in
                                bombardment
                                attacks if they adjacent to an enemy unit,
                                they must attack an adjacent unit if they attack at all.
                            </li>
                        </ol>
                    </li>

                    <li>
                        <span class="big">Multi Hex Multi Unit Combat</span>
                        <ol>
                            <li>A single unit may attack any or all hostile units that it is adjacent to so long as the
                                odds are not
                                worse than 1-4.
                            </li>
                            <li>
                                If multiple attackers wish to attack multiple defenders, all attackers must be able to
                                attack all defenders.
                            </li>
                            <li>
                                To setup a multi hex combat, first click on one of the defenders, then shift-click on
                                the second defender.
                            </li>
                        </ol>

                    </li>

                    <li>
                        <span class="big">Retreats</span>
                        <ol>
                            <li>
                        Whenever obligated by combat result retreats his units obeying the following requirements.
                                </li>
                            <li>Units may not retreat off board, or into
                        enemy
                        zones
                        of control.
                                </li>
                            <li>
                                Units
                                that cannot
                                retreat are eliminated.
                            </li>

                        </ol>
                    </li>
<li>
                    <span class="big">Cavalry Combat</span>
    <ol>
        <li>
                        Any cavalry unit that is attacked solely by infantry will use the cavalry combat results table to resolve combat.
            </li>
        <li>
            This combat results table has only defender retreat as it's worst result.
        </li>
        <li>
                     This reflects the fact
    that cavalry could run away from situations that were disadvantageous.
        </li>
        </ol>
</li>

                    <h4>Advance after combat</h4> If a defending hex is left vacant any adjacent attacker that
                    participated
                    in
                    the attack my
                    be moved into that hex. This must be done before the next attack is resolved. Artillery units may
                    NOT
                    advance after combat.

                    <h2>Combat Results</h2>

                    <h4>Combat Results Table (CRT) (No Attack at less than 1-4 is allowed)</h4>

                    @section('inner-crt')
                    @show

                    <ul>

                        <li>
                            <h4>Combined Arms Bonus</h4>
                            <ul>
                                <li>Any attack starting at 1-1 odds or better against clear terrain hex, that includes
                                    attacking
                                    units from
                                    two different branches of service is receives 1 favorable column shift.
                                </li>
                                <li>Any attack against a clear terrain hex that includes attacking units from all three
                                    different branches
                                    of service receives 2 favorable column shifts.
                                </li>
                                <li>Any attack against a non clear terrain hex that includes both Infantry and artillery
                                    enjoys
                                    a 1 column
                                    favorable odds shift.
                                </li>
                            </ul>
                        </li>

                        <li>
                            <h4>Combat Result Explanation</h4>
                            <ul>
                                <li>A-E all attacking units eliminated</li>

                                <li>A-R All attacking units must retreat 1 hex (See Retreats page 2)</li>

                                <li>D-R All defending units must retreat 1 hex (See Retreats page 2)</li>

                                <li>EX all defending units are eliminated. Attacking units of the attackers choice = to
                                    or
                                    greater than
                                    eliminated defenders by unmodified combat strength are also eliminated.
                                </li>

                                <li>DE all defending units are eliminated,</li>
                                </li>
                            </ul>

                    </ul>
                </ol>
            </li>
            @section('victoryConditions')
            @show

            <div id="credits">
                <h2><cite>Napoleons Training Academy</cite></h2>
                <h3>Design Credits</h3>

                <h4>Game Design:</h4>
                David M. Rodal
                <h4>Graphics:</h4>
                David M. Rodal
                <h4>Rules:</h4>
                <site>David M. Rodal, based off of a game created by Lance Runolfsson</site>
                <h4>HTML 5 Version:</h4>
                David M. Rodal
            </div>
        </div>
    </div>
</div>