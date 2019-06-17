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
    <li>
        <?= $forceName[1] ?> units are this color.
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;">
            <div class="unit-size">lll</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 5</div>
        </div>
    </li>
    <li>
        <?= $forceName[2] ?> units are this color.
            <div class="clear"></div>

            <div class="unit loyalist" alt="0"
             style="float:left;border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
            <div class="unit loyalGuards" alt="0"
                 style="float:left; border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
                <div class="unit-size">xx</div>
                <div class="counter-wrapper">
                    <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
                </div>
                <div class="unit-numbers">7 - 5</div>
            </div>
            <div class="clear"></div>

    </li>
    <li>
        The symbol above the numbers represents the unit type.
        This is Armor (tanks).
        <div class="unit loyalGuards" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">

            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Armor.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">13 - 8</div>
        </div>
    </li>
    <li>
        This is Mechinized Infantry (soldiers in half tracks, with small arms).
        <div class="unit rebel" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/MechInf.svg'); ?>" class="counter">
            </div>
            <div class="unit-numbers">10 - 8</div>
        </div>
    </li>
    <li>
        This is Infantry. (soldiers on foot, with small arms).
        <div class="unit rebel" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 5</div>
        </div>
    </li>
    <li>
        This is Airborne Infantry. (soldiers on foot, dropped from the sky, with small arms).
        <div class="unit rebel" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Para.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">8 - 5</div>
        </div>
    </li>
    <li>
        This is Mountain Infantry. (soldiers on foot, with mountain gear, with small arms).
        These troops have a +1 shift when attacking into mountain hexes. Only one +1 shift per attack.
        <div class="unit loyalGuards" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Mountain.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">7 - 5</div>
        </div>
    </li>
    <li>
        This is Shock Infantry. (soldiers on foot, with heavy weapons).
        These troops have a +1 shift when attacking. Only one +1 shift per attack.
        <div class="unit loyalGuards" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Shock.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">9 - 5</div>
        </div>
    </li>
    <li>
        This is Heavy Weasons Infantry. (soldiers on foot, with heavy weapons).
        These troops have no special abilities in this game.
        <div class="unit loyalGuards" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Heavy.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">10 - 5</div>
        </div>
    </li>
    <li>
        The number on the left is the combat strength. The number on the right is the movement allowance
        <div class="unit loyalGuards" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/MechInf.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers">12 - 8</div>
        </div>
        <p class="ruleComment">
            The above unit has a combat strength of 12 and a movenent allowance of 8.</p>
    </li>

    <li>
        If a units numbers are in white, that means this unit is at reduced strength.
        <div class="clear"></div>
        <div class="unit loyalist" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">2 - 4</span></div>
        </div>
        <div class="unit rebel" alt="0"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="unit-size">xx</div>
            <div class="counter-wrapper">
                <img src="<?= url('assets/unit-images/Infantry.svg'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">4 - 5</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
