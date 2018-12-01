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
    @section('unitRules.unitColors')
    <li>
        <?= $forceName[1] ?> units are this color.
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 6</div>
        </div>
    </li>
    <li>
        <?= $forceName[2] ?> units are this color.
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiInf.png'); ?>" class="counter">
            </div>
            <div class="unit-numbers">3 - 3</div>
        </div>
    </li>
    <li>
        The symbol above the numbers represents the unit type.
        This is Armor (tanks).
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">

            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 6</div>
        </div>
    </li>
    <li>
        This is Mechinized Infantry (soldiers in half tracks, with small arms).
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiMech.png'); ?>" class="counter">
            </div>
            <div class="unit-numbers">6 - 6</div>
        </div>
    </li>
    <li>
        This is Moterized Infantry. (soldiers in trucks that fight on foot, with small arms).
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiMotInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">4 - 6</div>
        </div>
    </li>
    <li>
        This is Artillery. (Big guns that fire from the rear, they have a range of 2 hexes).
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArt.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">3 - 6</div>
        </div>
    </li>
    <li>
        This is a supply unit. (Trucks for carrying food and ammo).
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiMotMt.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">1 - 3</span></div>
        </div>
    </li>
    <li>
        This is Infantry. (soldiers on foot, with small arms).
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">3 - 3</div>
        </div>
    </li>
    <li>
        The number on the left is the combat strength. The number on the right is the movement allowance
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiMech.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 6</div>
        </div>
        <p class="ruleComment">
            The above unit has a combat strength of 9 and a movenent allowance of 6.</p>
    </li>
    @endsection

@section('unitRules.reducedUnits')
    <li>
        If a units numbers are in white, that means this unit is at reduced strength and can receive
        replacements
        during the replacement phase.
        <div class="clear"></div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiMech.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">4 - 6</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
    @endsection
