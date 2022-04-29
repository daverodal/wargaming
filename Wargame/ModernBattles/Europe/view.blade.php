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
@section('deploy-box')
    <div id="deployBox">
        <div>

            <deploy-units-component style="float:left" :myfilter="1" :myunits="allMyBoxes.deployBox"></deploy-units-component>
            <deploy-units-component style="float:left" :myfilter="2" :myunits="allMyBoxes.deployBox"></deploy-units-component>
        </div>

        <div class="clear"></div>
    </div>
@endsection