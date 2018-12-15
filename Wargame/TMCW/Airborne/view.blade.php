@include('wargame::ng-global-header')
@include('wargame::TMCW.Airborne.airborneHeader')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/airborne.css')}}">
<script src="{{mix("vendor/javascripts/medieval/medieval.js")}}"></script>
<script src="{{mix('vendor/javascripts/wargame/airborne.js')}}"></script>
</head>

@section('ng-unit-template')
    <div id="@{{unit.id}}"
         ng-right-click="rightClickMe({id:unit.id})"   ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div ng-if="unit.class !== 'supply'" class="unitSize">lll</div>
        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img ng-if="unit.class !== 'supply' && unit.class !== 'truck'" src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter">
            <i ng-if="unit.class === 'supply'" class="counter-symbol fa fa-adjust"></i>
            <i ng-if="unit.class === 'truck'" class="counter-symbol fa fa-truck"></i>
            <span class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div ng-class="unit.infoLen" class="unit-numbers">@{{ unit.strength }}
            - @{{ unit.maxMove - unit.moveAmountUsed }}</div>
        <div ng-if="unit.class !== 'supply'" class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
    </div>
@endsection

@section('ng-offmap-unit-template')
    <div id="@{{unit.id}}" ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div ng-if="unit.class !== 'supply'" class="unitSize">lll</div>
        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img ng-if="unit.class !== 'supply'  && unit.class !== 'truck'" src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter">
            <i ng-if="unit.class === 'supply'" class="counter-symbol fa fa-adjust"></i>
            <i ng-if="unit.class === 'truck'" class="counter-symbol fa fa-truck"></i>
            <span class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div class="unit-numbers">@{{ unit.strength }} - @{{ unit.maxMove - unit.moveAmountUsed }}</div>
        <div ng-if="unit.class !== 'supply'" class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
    </div>
@endsection

@section('ng-ghost-unit-template')
    <div ng-if="unit.class !== 'supply'" class="unitSize">lll</div>
    <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
         src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
    <div class="counterWrapper">
        <img ng-if="unit.class !== 'supply'  && unit.class !== 'truck'" src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter">
        <i ng-if="unit.class === 'supply'" class="counter-symbol fa fa-adjust"></i>
        <i ng-if="unit.class === 'truck'" class="counter-symbol fa fa-truck"></i>
        <span class="unit-desig">@{{ unit.unitDesig }}</span>
    </div>
    <div class="range">@{{ unit.armorClass }}</div>
    <div class="unit-numbers">@{{ unit.strength }} - @{{ unit.pointsLeft }}</div>
    <div ng-if="unit.class !== 'supply'" class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
@endsection

@section('inner-crt')
    @include('wargame::TMCW.Airborne.airborne-inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\Airborne\Airborne::REBEL_FORCE)])
@endsection
@section('tec')
    @include("wargame::TMCW.Airborne.tec")
@endsection
@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Amph.victoryConditions')
@endsection

@section('zoc-rules')
    @include('wargame::TMCW.Amph.zoc-rules')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.Airborne.obc')
@endsection

@section('outer-deploy-box')
    <div class="clear"></div>
    <div id="deployBox">

        <div class="deploy-zone-wrapper">
            <div>Rebel Zone B</div>
            <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'B'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>

        <div class="deploy-zone-wrapper">
            <div>Airdrop Rebel Zone A</div>
            <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'A'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            <div>Loyalist Zone C</div>
            <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'C'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            <div>Loyalist Zone D</div>
            <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'D'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            <div>Loyalist Zone E</div>
            <div class="a-unit-wrapper" ng-click="clickMe(unit.id, $event)"  ng-repeat="unit in deployUnits | filter:{reinforceZone: 'E'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
    </div>
@endsection



@section('units')
    <div ng-click="clickMe(unit.id, $event)" ng-mouseover="hoverHq(unit)" ng-mouseleave="unHoverHq(unit)"  class="a-unit-wrapper" ng-repeat="unit in mapUnits" ng-style="unit.wrapperstyle">
        <unit right-click-me="rightClickMe(id)" unit="unit"></unit>
    </div>

    <div ng-mouseover="hoverThis(unit)" ng-mouseleave="unHoverThis(unit)" ng-click="clickMe(unit.id, $event)"
         ng-style="unit.style" ng-repeat="unit in moveUnits track by $index" id="@{{unit.id}}" class="unit ghost-unit"
         ng-class="[unit.nationality, unit.class]">
        <ghost-unit unit="unit"></ghost-unit>
    </div>
@endsection


@section('outer-deploy-box')
    <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
    <div id="deployBox">
        <div ng-mouseUp="clickMe(unit.id,  $event)" class="a-unit-wrapper" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
            <offmap-unit unit="unit"></offmap-unit>
        </div>
        <div class="clear"></div>
    </div>
@endsection

@extends('wargame::Medieval.angular-view',['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\Airborne\Airborne::REBEL_FORCE)] )