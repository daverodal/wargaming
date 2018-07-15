<?php global $force_name;
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
?><!doctype html>
<html>

<head>
    <?php
    $oClass = new ReflectionClass('Wargame\Cnst');
    $constants = $oClass->getConstants();

    ?>
    <script>
        const GAME_NAME_DUDE = "<?=$classOverride ?? '' ?>" || "SubGameController";
        <?php foreach($constants as $k => $v){
            echo "const $k = $v;\n";
        }?>


        const fetchUrl = "<?=url("wargame/fetch/$wargame");?>";


        var DR = window.DR;

        if (!DR) {
            DR = {};
        }


        DR.playerOne = "{{$forceName[1]}}";
        DR.playerTwo = "{{$forceName[2]}}";
        DR.playerThree = "{{$forceName[3] or ''}}";
        DR.playerFour = "{{$forceName[4] or ''}}";
    </script>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/icon">


    <script type="text/javascript">



    </script>
    <link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>

