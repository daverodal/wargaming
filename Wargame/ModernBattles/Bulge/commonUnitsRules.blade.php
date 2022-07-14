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


        <?= $forceName[1] ?> units are this color dude.
            <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 8, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'us', maxMove:6, moveAmountUsed: 0}"></unit-component>

    </li>
    <li>
        <?= $forceName[2] ?> units are this color.
            <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 8, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'german', maxMove:8, moveAmountUsed: 0}"></unit-component>

    </li>
    <li>
        The symbol above the numbers represents the unit type.
        This is Armor (tanks).
        <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 8, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'us', maxMove:6, moveAmountUsed: 0}"></unit-component>

    </li>
    <li>
        This is Mechinized Infantry (soldiers in half tracks, with small arms).
        <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 5, shadowy: false, supplied: true, forceId: 2, image: 'MechInf.svg', nationality: 'us', maxMove:6, moveAmountUsed: 0}"></unit-component>

    </li>
    <li>
        This is Infantry. (soldiers on foot, with small arms).
        <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 4, shadowy: false, supplied: true, forceId: 2, image: 'Infantry.svg', nationality: 'us', maxMove:8, moveAmountUsed: 0}"></unit-component>
    </li>
    <li>
        In all the examples above there has been a question mark between the two numbers.
        The number to the right of the question mark is the units movement allowance.
        The number to the left of the question mark is the units <i>untried</i> combat strength.
        <unit-component class="big" style="{position: relative}" :unit="{name: 'xxx', strength: 8, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'german', maxMove:8, moveAmountUsed: 0}"></unit-component>

        Until a unit has been involved in combat, it's untried strength and a question mark will be visible.
        Once involved in combat, it's true strength will be revealed and the question mark turns into a '-' symbol.
        <unit-component class="big" style="{position: relative}" :unit="{tried: true, name: 'xxx', strength: 10, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'german', maxMove:8, moveAmountUsed: 0}"></unit-component>

        <p class="ruleComment">
            The above unit has a combat strength of 10 and a movenent allowance of 8. A units actual strength
        will be close to but vary from it's untried strength. Some units will be stronger, some the same, and some weaker.</p>
        <unit-component class="big" style="{position: relative}" :unit="{tried: true, name: 'xxx', strength: 6, shadowy: false, supplied: true, forceId: 2, image: 'Armor.svg', nationality: 'german', maxMove:8, moveAmountUsed: 0}"></unit-component>
    </li>
    <li>
        If a unit is unsupplied it's strength and movement allowance are halved, and a 'U' appears next to the unit type.
        <unit-component class="big" style="{position: relative}" :unit="{tried: true, name: 'xxx', strength: 4, shadowy: false, supplied: false, forceId: 2, image: 'Armor.svg', nationality: 'german', maxMove:4, moveAmountUsed: 0}"></unit-component>

    </li>
    @show

@section('unitRules.reducedUnits')
    <li>
        If a units numbers are in white, that means this unit is at reduced strength and can receive
        replacements
        during the replacement phase.
        <div class="clear"></div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Armor.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/MechInf.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">4 - 6</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
    @show
