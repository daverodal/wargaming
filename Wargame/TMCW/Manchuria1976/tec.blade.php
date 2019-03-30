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
?><div class="dropDown"  id="TECWrapper">
    <h4 class="WrapperLabel" title='Terrain Effects Chart'>TEC</h4>
    <DIV id="TEC"><div class="close">X</div>
        @if(isset($scenario->newmap) && $scenario->newmap)

                <DIV>
                    <div class="close">X</div>
                    <div class="left">
                        <ul>
                            <li class="row-one">
                                <div class="column-image"></div>
                                <div class="column-one">Clear</div>
                                <div class="column-two">1 Movement Point</div>
                                <div class="column-three">No Effect</div>
                            </li>
                            <li class="row-two">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    Mountain
                                </div>
                                <div class="column-two">Mech units 6 MP's, PRC infantry 1.5 MP's</div>
                                <div class="column-three">Soviet attacks Shift two, PRC attacks shift one</div>
                            </li>
                            <li class="row-three">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    City
                                </div>
                                <div class="column-two">1 MP number is VP's awarded</div>
                                <div class="column-three">Shift two</div>
                            </li>
                            <li class="row-four">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    Road Hexside
                                </div>
                                <div class="column-two">.5 Movement Points</div>
                                <div class="column-three">No Effect</div>
                            </li>
                            <li class="row-five">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    Rail Hexside
                                </div>
                                <div class="column-two">.5 Movement Point. PRC can use rail movement</div>
                                <div class="column-three">No Effect</div>
                            </li>
                            <li class="row-six">
                                <div class="column-image"></div>
                                <div class="column-one">
                                    <span>River Hexside</span>
                                </div>
                                <div class="column-two">+1 MP</div>
                                <div class="column-three">Shift One if all attacks cross river</div>
                            </li>
                            <li class="row-seven">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    <span>Border Hexside</span>
                                </div>
                                <div class="column-two">Units may not enter Mongolia</div>
                                <div class="column-three">No Effect</div>
                            </li>
                            <li class="row-eight">
                                <div class="column-image"></div>

                                <div class="column-one">
                                    <span>Ocean/Lake</span>
                                </div>
                                <div class="column-two">Movement Prohibited</div>
                                <div class="column-three">Combat Prohibited</div>
                            </li>
                            <!--    Empty one for the bottom border -->
                            <li class="closer"></li>
                        </ul>
                    </div>
                </div>
    @else
            <img id="tecImage" src="<?=asset('vendor/wargame/tmcw/manchuria1976/images/Manchuria1976TEC.jpg')?>">

        @endif
    </div>
</div>