@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\Kiev1941\CombatResultsTable(\Wargame\TMCW\Kiev1941\Kiev1941::GERMAN_FORCE)])


<script src="{{mix('vendor/javascripts/wargame/airborne.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/airborne.css')}}">
</head>

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
@extends('wargame::stdIncludes.view-vue' )



@section('deploy-box')
    <div class="clear"></div>
    <div id="deployBox">

        <div class="deploy-zone-wrapper">
            <div>Rebel Zone B</div>

            <deploy-units-component class="big" :myunits="allMyBoxes.northeast"></deploy-units-component>

            <div class="clear"></div>
        </div>

        <div class="deploy-zone-wrapper">
            <div>Airdrop Rebel Zone A</div>
            <deploy-units-component  :myunits="allMyBoxes.airdrop"></deploy-units-component>

            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper"  v-if="allMyBoxes.C && allMyBoxes.C.length > 0" >
            <div>Loyalist Zone C</div>
            <deploy-units-component :myunits="allMyBoxes.C"></deploy-units-component>

            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper" v-if="allMyBoxes.D && allMyBoxes.D.length > 0" >
            <div>Loyalist Zone D</div>
            <deploy-units-component  :myunits="allMyBoxes.D"></deploy-units-component>

        </div>
        <div class="deploy-zone-wrapper" v-if="allMyBoxes.D && allMyBoxes.D.length > 0" >
            <div>Loyalist Zone E</div>
            <deploy-units-component  :myunits="allMyBoxes.E"></deploy-units-component>

            <div class="clear"></div>
        </div>
    </div>
@endsection

