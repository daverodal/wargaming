@include('wargame::export-global-header', ['topCrt'=> new \Wargame\SimpleBBCombatResultsTable(1)])


<script src="{{mix('vendor/javascripts/wargame/tin-cans.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/tin-cans.css')}}">
</head>
@section('dead-pile')
    German Units: <units-component :myfilter="1" :myunits="allMyBoxes.deadpile"></units-component>
    <div class="clear"></div>
    Sovier Units: <units-component :myfilter="2" :myunits="allMyBoxes.deadpile"></units-component>
    <div class="clear"></div>
    <div class="clear"></div>
@endsection
@section('credit')
    @include('wargame::TMCW.TinCans1916.credit')
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.TinCans1916.exclusiveRules')
@endsection
@section('victoryConditions')
    @include('wargame::TMCW.TinCans1916.victoryConditions')
@endsection
@section('full-status')
    <div id="topStatus" v-html="(headerTurn <= 5 ? 'Jun ' + ((headerTurn - 1) * 2 + 22) : 'Jul ' + ((headerTurn - 6) * 2 + 2)) + headerTopStatus"></div>
    <div>
        <span id="status" v-html="headerStatus"></span>
        <span id="combatStatus" v-html="combatStatus"></span>
        @section('victory')
            <span id="victory" v-html="headerVictory"></span>
        @show
    </div>
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
    <button @click="clearCombat" :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button @click="shiftClick" :class="{'inline-show': dynamic.combat, dark: dynamic.shiftKey }" class="dynamicButton combatButton" id="shiftKey">+</button>
@endsection
@section('options')
@endsection
