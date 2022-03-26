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

?>
<span class="big">Zones of Control</span>
<img class="zoc-images" style="width:96px" src="/assets/images/zocs.svg">
<p>
    The six hexes surrounding a unit constitute it's Zone of Control or <abbr
        title="Zone Of Control">ZOC</abbr>.
    <abbr title="Zone Of Control">ZOC</abbr>'s affect the movement, supply tracing and combat during retreats.
</p>
<ol>
    <li><span class="big">Effects on Movement</span>

        <p>When a unit enters a hostile <abbr title="Zone Of Control">ZOC</abbr> it must stop
            and move no further that turn. If a units starts the
            turn in a <abbr title="Zone Of Control">ZOC</abbr>, it may
            leave the hex. If it enters another ZOC it has to stop again. A unit MAY move directly from one ZOC to another
            but most stop after doing so.</p>

    <li><span class="big">Effects on Supply</span>

        <p>Supply lines may NOT be traced through enemey ZOC's. If a friendly unit in the hex it will negate this effect and allow
        supply to be traced through the hex.</p>

        </li>
    <li><span class="big">Effects on Retreat</span>
        <p>Units may not retreat through enemy ZOC's If they are forced to do so they are eliminated instead.
            This effect is negated by friendly units in the hex, allowing the retreating units to move through the hex.
        </p>
    </li>
</ol>
