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
<style>
    body{
        background:url("<?=url("vendor/wargame/tmcw/manchuria1976/images/ChineseType59Small.png")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
    h1{
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    h2{
        text-align:center;
        font-size:38px;
        font-family:'Great Vibes';
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    .rebel{
        font-size:40px;
        color:red;
    }
    .loyalist{
        font-size:40px;
        color:blue;

    }
    .link{
        font-size:40px;
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
    legend   {
        color:white;
    }
    fieldset{
        background:rgba(0,0,0,.3);
        text-align:center;width:30%;
        margin:60px auto;
        border-radius:15px;
    }
    .clear{
        clear:both;
    }
    a:hover{
        text-decoration: underline;
    }
</style>
<link href='https://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>

<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
<h1 style="text-align:center;font-size:90px;font-family:'Nosifer'">Manchuria 1976</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=url("wargame/enter-hotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=url("wargame/enter-multi");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=url("wargame/leave-game");?>">Go to Lobby</a>
    <div class="attribution">
        By Staff Sgt. D. Myles Cullen (USAF) [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3AChinese_MBTs_070324-F-0193C-040.JPEG">via Wikimedia Commons</a>
    </div>
</fieldset>
@extends("wargame::playAs")