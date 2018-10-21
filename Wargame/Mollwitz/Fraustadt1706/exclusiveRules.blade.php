<?php
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>

<li><span class="lessBig">Deploy Phase</span>
    <p class="indent">The <?= $deployName[1] ?> player deploys first. The <?= $deployName[2] ?> player deploys
        Second</p>

</li>
<li><span class="lessBig">First Player</span>
    <p class="indent">The <?= $forceName[1] ?> player moves first. The <?= $forceName[2] ?> player moves second.</p>
</li>
<li><span class="lessBig">Movement</span>
    <p class="indent">The <?= $forceName[1] ?> units receive a 1 MP bonus on turn 1 only.</p>
    <p class="indent"> The <?= $forceName[2] ?> Units movement rate are halved on turn 1, normal thereafter.</p>
</li>
<li><span class="lessBig">Terrain</span>
    <p class="indent">Swamps, Rivers and Ponds: Are all frozen and have no effect on movement or combat.</p>
    <p class="indent"> Fortifications: The Fortifications had no impact on the Swedish infantry assault so have no
        effect on infantry and cost 1 MP for cavalry to cross halving cavalry attacking over them.</p>
</li>
