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
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <?php
    $oClass = new ReflectionClass('Wargame\Cnst');
    $constants = $oClass->getConstants();
    global $results_name;
    ?>
    <script>

        <?php foreach($constants as $k => $v){
            echo "const $k = $v;\n";
        }?>
@isset($topCrt)
        const combatResultsTable = JSON.parse('<?=json_encode($topCrt)?>');
@endif

        const resultsNames = JSON.parse('<?=json_encode($results_name)?>');
        const addUrl = "<?=url("wargame/add/");?>";
        const pokeUrl = "<?=url("wargame/poke/");?>";
        const fetchUrl = "<?=url("wargame/fetch/$wargame");?>";
        const wargame = "<?=$wargame?>";
        const rowSvg = "<?php echo asset('vendor/wargame/genre/images/rowHex.svg');?>";
        const imagesBase = "<?php echo asset('vendor/wargame/genre/images/');?>";

        const mapUrl = '{{ $mapUrl }}';
        const mapSymbolsBefore = "<?php echo asset('assets/map-symbols/');?>/";

        if (!window.PHP_INIT_VARS) {
            window.PHP_INIT_VARS = {};
        }


        window.legacy = {};
        window.PHP_INIT_VARS.playerOne = "{{$forceName[1]}}";
        window.PHP_INIT_VARS.playerTwo = "{{$forceName[2]}}";
        window.PHP_INIT_VARS.playerThree = "{{$forceName[3] or ''}}";
        window.PHP_INIT_VARS.playerFour = "{{$forceName[4] or ''}}";
    </script>
        <link href='//fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/icon">
    {{--<script src="{{mix("vendor/javascripts/wargame/wargame.js")}}"></script>--}}


    <script type="text/javascript">
//        $.ajaxSetup({
//            headers: {
//                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//            }
//        });
    </script>


