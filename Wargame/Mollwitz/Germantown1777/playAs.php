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
?><head>
    <meta charset="UTF-8">
    <link href='//fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Monsieur+La+Doulaise' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Pinyon+Script' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
    <style>
        body{
            background:#000;
            background:url("<?=url("vendor/wargame/mollwitz/images/Germantown_battle,_Chew's_house_-_NARA_-_532863.jpg")?>") #333 no-repeat;
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
        .attribution{
            background: rgba(255,255,255,.6);
        }
        .attribution a{
            color:red;
            text-shadow: 1px 1px 1px black;
        }

    </style>
</head>
<body>

<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>Germantown 1777</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=url("wargame/enter-hotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a  class="link" href="<?=url("wargame/enter-multi");?>/<?=$wargame?>">Play Multi</a><br>
    <a class="link" href="<?=url("wargame/leave-game");?>">Go to Lobby</a><br>
    <div class="attribution">
        By Unknown or not provided (U.S. National Archives and Records Administration) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3AGermantown_battle%2C_Chew&#039;s_house_-_NARA_-_532863.jpg">via Wikimedia Commons</a>    </div>
</fieldset>
