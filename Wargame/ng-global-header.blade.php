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
?><!doctype html>
<html>

<head>
    <?php
        global $phase_name;
    $oClass = new ReflectionClass('Wargame\Cnst');
    $constants = $oClass->getConstants();
    global $results_name, $phase_name, $mode_name;

    ?>
    <script>
        <?php foreach($constants as $k => $v){
            echo "const $k = $v;\n";
        }?>

        const mode_name = JSON.parse('<?=json_encode($mode_name)?>');
                    const phase_name = []
            <?php foreach($phase_name as $k => $v){
                echo "phase_name[$k] = \"$v\";\n";
            }?>

        const fetchUrl = "<?=url("wargame/fetch/$wargame");?>";


        if (!window.PHP_INIT_VARS) {
            window.PHP_INIT_VARS = {};
        }


        window.legacy = {};
        window.PHP_INIT_VARS.playerOne = "{{$forceName[1]}}";
        window.PHP_INIT_VARS.playerTwo = "{{$forceName[2]}}";
        window.PHP_INIT_VARS.playerThree = "{{$forceName[3] ?? ''}}";
        window.PHP_INIT_VARS.playerFour = "{{$forceName[4] ?? ''}}";

        @isset($topCrt)
            const combatResultsTable = JSON.parse('<?=json_encode($topCrt)?>');
            const topCrtJson = '{!!json_encode($topCrt)!!}';
            const unitsJson = '{!!json_encode($units)!!}';
        @endif

        const addUrl = "<?=url("wargame/add/");?>";
        const pokeUrl = "<?=url("wargame/poke/");?>";
        if(!fetchUrl){
            const fetchUrl = "<?=url("wargame/fetch/$wargame");?>";
        }
        const wargame = "<?=$wargame?>";
        const rowSvg = "<?php echo asset('vendor/wargame/genre/images/rowHex.svg');?>";
        const imagesBase = "<?php echo asset('vendor/wargame/genre/images/');?>";

        const mapSymbolsBefore = "<?php echo asset('js');?>/";
        const mapSymbolsSpotted = "<?php echo asset('vendor/wargame/tactical/images/spotted.svg');?>";


    </script>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/icon">


    <script type="text/javascript">



    </script>
    <link href='https://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>

