@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\CombatResultsTable()])


<script src="{{mix('vendor/javascripts/wargame/amph.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/amph.css')}}">
</head>
@section('unitRules')
@include ("wargame::TMCW.amph.commonUnitsRules")
@endsection
@section('tec')
    @include("wargame::TMCW.Amph.tec")
@endsection
@section('commonRules')

    <div class="dropDown" id="GRWrapper" style="font-weight:normal">

        <div id="GR">
            <div @click="commonRules = false" class="close">X</div>
            @include("wargame::TMCW.commonRulesCore")
        </div>
    </div>
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('obc')
    <li><a @click="menuClick('showObc')" id="showObc">Show OBC</a></li>
@endsection

@extends('wargame::stdIncludes.view-vue' )

@section('innerNextPhaseWrapper')
    <button @click="fullScreen()" id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
    <button @click="clearCombat" :class="{'inline-show': dynamicButtons.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button @click="shiftClick" :class="{'inline-show': dynamicButtons.combat, dark: dynamicButtons.shiftKey }" class="dynamicButton combatButton" id="shiftKey">+</button>
    <button @click="bugReport" class="debugButton" id="debug"><i class="fa fa-bug"></i></button>
    <button @click="nextPhase" id="nextPhaseButton">Next Phase</button>
    <div id="comlinkWrapper">
        <div id="comlink"></div>
    </div>
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
