    @include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\CombatResultsTable()])


<script src="{{mix('vendor/javascripts/wargame/amph.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/amph.css')}}">
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
@section('zoc-rules')
    @include('wargame::TMCW.Amph.zoc-rules')
@endsection
@section('supply-sources')
    @include('wargame::TMCW.Amph.supply-sources')
@endsection
@section('supply-rules')
    @include('wargame::TMCW.supply-rules')
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
    <b-dropdown-item @click="menuClick('showObc')" id="showObc">Show OBC</b-dropdown-item>
@endsection

@extends('wargame::stdIncludes.view-vue' )

@section('dynamic-buttons')
    <button @click="clearCombat" :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button @click="shiftClick" :class="{'inline-show': dynamic.combat, dark: dynamic.shiftKey }" class="dynamicButton combatButton" id="shiftKey">+</button>
@endsection
@section('options')
    <options-component>
    </options-component>
@show
@section('deploy-box')
       <div id="deployBox">

           <div id="beach-landing" class="deploy-zone-wrapper">
               <div>Rebel Beach Landing</div>
               <units-component  :myunits="allMyBoxes.beachlanding"></units-component>
               <div class="clear"></div>
           </div>

           <div id="airdrop" class="deploy-zone-wrapper">
               <div>Rebel Airdrop Zone</div>
               <units-component :myunits="allMyBoxes.airdrop"></units-component>
               <div class="clear"></div>
           </div>
           <div id="west" class="deploy-zone-wrapper">
               <div>Loyalist West</div>
               <units-component :myunits="allMyBoxes.west"></units-component>

               <div class="clear"></div>
           </div>
           <div id="south" class="deploy-zone-wrapper">
               <div>Loyalist South</div>
               <units-component :myunits="allMyBoxes.south"></units-component>
               <div class="clear"></div>
           </div>
           <div id="east" class="deploy-zone-wrapper">
               <div>Loyalist East</div>
               <units-component :myunits="allMyBoxes.east"></units-component>
               <div class="clear"></div>
           </div>
       </div>
   @endsection
