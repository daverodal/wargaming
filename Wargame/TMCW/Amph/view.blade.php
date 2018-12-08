   @include('wargame::global-header')
@include('wargame::TMCW.Amph.amph-header')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/amph.css')}}">
</head>
@extends('wargame::stdIncludes.view')

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt',['topCrt'=> $top_crt = new \Wargame\TMCW\CombatResultsTable()])
@endsection
@section('tec')
    @include("wargame::TMCW.Amph.tec")
@endsection
@section('zoc-rules')
    @include('wargame::TMCW.Amph.zoc-rules')
@endsection

@section('deploy-box')
       <div id="deployBox">

           <div id="beach-landing" class="deploy-zone-wrapper">
               <div>Rebel Beach Landing</div>
               <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'B'}"
                    ng-style="unit.wrapperstyle">
                   <offmap-unit unit="unit"></offmap-unit>
               </div>
               <div class="clear"></div>
           </div>

           <div id="airdrop" class="deploy-zone-wrapper">
               <div>Rebel Airdrop Zone</div>
               <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'A'}"
                    ng-style="unit.wrapperstyle">
                   <offmap-unit unit="unit"></offmap-unit>
               </div>
               <div class="clear"></div>
           </div>
           <div id="west" class="deploy-zone-wrapper">
               <div>Loyalist West</div>
               <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'C'}"
                    ng-style="unit.wrapperstyle">
                   <offmap-unit unit="unit"></offmap-unit>
               </div>
               <div class="clear"></div>
           </div>
           <div id="south" class="deploy-zone-wrapper">
               <div>Loyalist South</div>
               <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'D'}"
                    ng-style="unit.wrapperstyle">
                   <offmap-unit unit="unit"></offmap-unit>
               </div>
               <div class="clear"></div>
           </div>
           <div id="east" class="deploy-zone-wrapper">
               <div>Loyalist East</div>
               <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'E'}"
                    ng-style="unit.wrapperstyle">
                   <offmap-unit unit="unit"></offmap-unit>
               </div>
               <div class="clear"></div>
           </div>
       </div>
   @endsection

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Amph.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.obc')
@endsection

