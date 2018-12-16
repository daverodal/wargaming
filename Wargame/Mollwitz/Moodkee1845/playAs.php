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
    body{
        background:#000;
        background:url("<?=url("vendor/wargame/mollwitz/images/British_Indian_Empire_1909_Imperial_Gazetteer_of_India.png")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;

    }
    h2{
        color:#f66;
        text-shadow: 0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,
        0 0 3px black,0 0 3px black;
    }
    h1{
        text-align:center;
        font-size:90px;
        font-family:'Pinyon Scrip';
        color:#f66;
        margin-top:0px;
        text-shadow: 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black, 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
        0 0 5px black,0 0 5px black,0 0 5px black;
    }
    .link{
        font-size:40px;
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
    legend   {
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
   fieldset{
        text-align: center;
       width:30%;
       margin:0px;
       position:absolute;
       top:300px;
       left:50%;
       margin-left:-15%;
       background-color: rgba(255,255,255,.4);
   }

</style>
<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>Moodkee 1845</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=url("wargame/enter-hotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a  class="link" href="<?=url("wargame/enter-multi");?>/<?=$wargame?>">Play Multi</a><br>
    <a class="link" href="<?=url("wargame/leave-game");?>">Go to Lobby</a><br>
</fieldset>
