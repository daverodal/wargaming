@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)])


<script src="{{mix('vendor/javascripts/wargame/kievCoprs.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/kievCorps.css')}}">
</head>
@section('dead-pile')
    German Units: <units-component :myfilter="1" :myunits="allMyBoxes.deadpile"></units-component>
    <div class="clear"></div>
    Sovier Units: <units-component :myfilter="2" :myunits="allMyBoxes.deadpile"></units-component>
    <div class="clear"></div>
    <div class="clear"></div>
@endsection
@section('credit')
    @include('wargame::TMCW.KievCorps.credit')
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.KievCorps.exclusiveRules')
@endsection
@section('victoryConditions')
    @include('wargame::TMCW.KievCorps.victoryConditions')
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
