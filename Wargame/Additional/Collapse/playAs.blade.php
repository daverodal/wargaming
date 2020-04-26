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
?><head>
    <meta charset="UTF-8">
</head><body>
<style>
    body{
        background:url("<?=url("vendor/wargame/Additional/collapse/images/19440628_destroyed_panzer_iv_20._panzer_division_bobruisk.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
</style>

<h2 id="welcome" style="text-align:center;font-size:30px;">Welcome to</h2>
<h1 id='playastitle' style="text-align:center;font-size:90px;"><span class="guard">Collapse in the east</span></h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=url("wargame/enter-hotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=url("wargame/enter-multi");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=url("wargame/leave-game");?>">Go to Lobby</a>
    <div class="attribution">
        See page for author [Public domain], <a target="_blank" href="https://commons.wikimedia.org/wiki/File:19440628_destroyed_panzer_iv_20._panzer_division_bobruisk.jpg">via Wikimedia Commons</a></footer>    </div>
</fieldset>
@extends("wargame::playAs")