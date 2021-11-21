@include('wargame::export-global-header',['topCrt'=> $top_crt = new \Wargame\Additional\EastWest\CombatResultsTable(\Wargame\Additional\EastWest\EastWest::GERMAN_FORCE)])
<script src="{{mix('vendor/javascripts/wargame/east-west.js')}}"></script>
@extends('wargame::stdIncludes.view-vue'  )
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/eastwest.css')}}">
</head>
@section('credit')
    @include('wargame::Additional.EastWest.credit')
@endsection
@section('full-status')
    <east-west-header id="topStatus" :scenario="'<?php echo $scenario->scenarioName ?>'":message="headerTopStatus"></east-west-header>
    <div>
        <span id="status" v-html="headerStatus"></span>
        <span id="combatStatus" v-html="combatStatus"></span>
        @section('victory')
            <span id="victory" v-html="headerVictory"></span>
        @show
    </div>
@endsection
@section('deploy-box')
    <div id="deployBox">
        <div>

            <deploy-units-component style="float:right" :myfilter="1" :myunits="allMyBoxes.deployBox"></deploy-units-component>
            <deploy-units-component style="float:left" :myfilter="2" :myunits="allMyBoxes.deployBox"></deploy-units-component>
        </div>

        <div class="clear"></div>
    </div>
@endsection
@section('unitRules')
    @include ("wargame::TMCW.Amph.commonUnitsRules")
@endsection
@section('tec')
    @include("wargame::TMCW.Amph.tec")
@endsection
@section('SOP')
    @include('wargame::TMCW.Amph.commonSequenceOfPlay')
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.Amph.exclusiveRules')
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

@section('obc')
    <li><a @click="menuClick('showObc')" id="showObc">Show OBC</a></li>
@endsection