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
?><body>
<link href='//fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Monsieur+La+Doulaise' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Pinyon+Script' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style>
    <?php include "Wargame/Mollwitz/playAs.css";?>
    body{
        background:#000;
        background:url("<?=url("vendor/wargame/mollwitz/images/Battle_of_Minden_1759.jpeg")?>") #333 no-repeat;
        background-position:center 10%;
        background-size:100%;

    }
</style>
<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>The Battle of Minden&nbsp;&nbsp;&nbsp;</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=url("wargame/enter-hotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a  class="link" href="<?=url("wargame/enter-multi");?>/<?=$wargame?>">Play Multi</a><br>
    <a class="link" href="<?=url("wargame/leave-game");?>">Go to Lobby</a><br>
    <div class="attribution">
        Richard Caton Woodville [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ACaton-Woodville_Battle_of_Minden_1759.jpeg">via Wikimedia Commons</a>
    </div>
</fieldset>
