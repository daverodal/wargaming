@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)])


<script src="{{mix('vendor/javascripts/wargame/airborne.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/airborne.css')}}">
</head>

@extends('wargame::stdIncludes.view-vue' )


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

