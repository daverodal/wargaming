@include('wargame::export-global-header', ['topCrt'=> new \Wargame\ModernBattles\Europe\CombatResultsTable(1)])


<script src="{{mix('vendor/javascripts/wargame/europe.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/europe.css')}}">
</head>

@section('tec')
    @include("wargame::TMCW.Amph.tec")
@endsection

@section('unitRules')
    @include ("wargame::TMCW.NorthVsSouth.commonUnitsRules")
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('airpower-boxx')
    <div class="unit-wrapper" id="airpower-wrapper" v-show="show.units.airpowerWrapper">
        <div @click="show.units.deadpile = false" class="close">X</div>
        <div style="font-size:50px;font-family:sans-serif;float:right;color:#666;">
            Airpower Available
        </div>
        @section('airpower-wrapper')
            <airpower-units-component :myfilter="1" :myunits="allMyBoxes.airpowerWrapper"></airpower-units-component>
            <div class="clear"></div>
            <airpower-units-component :myfilter="2" :myunits="allMyBoxes.airpowerWrapper"></airpower-units-component>
            <div class="clear"></div>
            <div class="clear"></div>
        @show

    </div>
@endsection
@section('SOP')
    @include('wargame::TMCW.Amph.commonSequenceOfPlay')
@endsection
@section('obc')
    <b-dropdown-item @click="menuClick('showObc')" id="showObc">Show OBC</b-dropdown-item>
@endsection
@section('victoryConditions')
    @include('wargame::TMCW.Amph.victoryConditions')
@endsection
@section('commonRules')
    <div class="dropDown" id="GRWrapper">
        <div id="GR">
            <div @click="commonRules = false" class="close">X</div>
            @include("wargame::TMCW.commonRulesCore")
        </div>
    </div>
@endsection
@extends('wargame::stdIncludes.view-vue' )
@section('dynamic-buttons')
    <button :class="{'inline-show': dynamic.combat}" @click="clearCombat" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button :class="{'inline-show': dynamic.combat, dark: dynamic.shiftKey}" @click="shiftClick"  class="dynamicButton combatButton" id="shiftKey" >+</button>
@endsection

@section('outer-units-menux')
    <b-dropdown id="dropdown-2" text="Units" class="" size="sm" no-caret variant="xyzzy">
        <b-dropdown-item @click="menuClick('all')" id="closeAllUnits">Close All</b-dropdown-item>
        <b-dropdown-item @click="menuClick('deadpile')" id="hideShow">Retired Units</b-dropdown-item>
        <b-dropdown-item @click="menuClick('deployWrapper')" id="showDeploy">Deploy/Staging Box</b-dropdown-item>
        <b-dropdown-item @click="menuClick('airpowerWrapper')" id="showAirpower">Airpower</b-dropdown-item>
        <b-dropdown-item @click="menuClick('exitBox')" id="showDeploy">Exiited Units</b-dropdown-item>
    </b-dropdown>
@endsection
@section('deploy-box')
    <div id="deployBox">
        <div>

            <deploy-units-component style="float:left" :myfilter="1" :myunits="allMyBoxes.deployBox"></deploy-units-component>
            <deploy-units-component style="float:left" :myfilter="2" :myunits="allMyBoxes.deployBox"></deploy-units-component>
        </div>

        <div class="clear"></div>
    </div>
@endsection