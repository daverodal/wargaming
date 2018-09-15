<? $classOverride = "myGameController";?>
@include('wargame::ng-global-header')
<script src="{{mix("vendor/javascripts/wargame/northvssouth.js")}}"></script>
@extends('wargame::Medieval.angular-view',['topCrt'=> $top_crt = new \Wargame\TMCW\NorthVsSouth\CombatResultsTable(\Wargame\TMCW\NorthVsSouth\NorthVsSouth::SOUTHERN_FORCE)] )
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/northvssouth.css')}}">
</head>
@section('credit')
    @include('wargame::TMCW.NorthVsSouth.credit')
@endsection
@section('victory')

    <span id="victory">
        Victory: <span class='playerSovietFace'><?=$forceName[1]?></span> @{{ vp[1] }} / <span class='playerGermanFace'><?=$forceName[2]?></span> @{{ vp[2] }} @{{ ratio }} @{{ winner }}
    </span>
@endsection
@section('outer-deploy-box')
    <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
    <div class="clear"></div>
    <div id="deployBox">
        <div>
            <h4>free deploy</h4>
        <div ng-click="clickMe(unit.id,  $event)" class="a-unit-wrapper" ng-if="unit.reinforceZone === 'B'" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
            <offmap-unit unit="unit"></offmap-unit>
        </div>
        </div>
        <div class="clear"></div>
        <div>
            <h4>fortified deploy</h4>
            <div ng-click="clickMe(unit.id,  $event)" class="a-unit-wrapper"  ng-if="unit.reinforceZone === 'A'" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div>
            <h4>Reinforce West deploy</h4>
            <div ng-click="clickMe(unit.id,  $event)" class="a-unit-wrapper"  ng-if="unit.reinforceZone === 'C'" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div>
            <h4>Reinforce North deploy</h4>
            <div ng-click="clickMe(unit.id,  $event)" class="a-unit-wrapper"  ng-if="unit.reinforceZone === 'F'" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div>
            <h4>soviet deploy</h4>
            <div ng-click="clickMe(unit.id,  $event)" class="a-unit-wrapper"  ng-if="unit.forceId === 1" ng-repeat="unit in deployUnits"  ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
@endsection

@section('ng-unit-template')
    <div id="@{{unit.id}}"
         ng-right-click="rightClickMe({id:unit.id})"   ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">@{{ unit.name }}</div>

        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div ng-class="unit.infoLen" class="unit-numbers">@{{ unit.strength }} @{{ unit.tried ? '-' : '?' }}
            @{{ unit.maxMove - unit.moveAmountUsed }}</div>
    </div>
@endsection

@section('ng-offmap-unit-template')
    <div id="@{{unit.id}}"  ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">@{{ unit.name }}</div>
        <div class="counterWrapper">
            <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div class="unit-numbers">@{{ unit.strength }}  @{{ unit.tried ? '-' : '?' }} @{{ unit.maxMove - unit.moveAmountUsed }}</div>
    </div>
@endsection

@section('ng-ghost-unit-template')
    <div class="steps">
        <div ng-repeat="i in [0,0,0].slice(3 - unit.steps) track by $index " class="step"></div>
    </div>
    <div class="unitSize">@{{ unit.name }}</div>
    <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
         src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
    <div class="counterWrapper">
        <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                class="unit-desig">@{{ unit.unitDesig }}</span>
    </div>
    <div class="range">@{{ unit.armorClass }}</div>
    <div class="unit-numbers" ng-class="unit.infoLen" >@{{ unit.strength }}  @{{ unit.supplied ? '-' : 'u' }} @{{ unit.pointsLeft }}</div>
@endsection


@section('unitRules.reducedUnits')
    <li>
        The total number of steps a unit has is designated by the number of dots along the left side.
        The number of white dots represents the number of steps lost.
        When a unit is reduced to 0 steps, they are eliminated.
        Units with less then their maximum steps are eligable for replacements.
        <div class="clear"></div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="steps"><div class="white step"></div><div class="step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">6 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="steps"><div class="white step"></div><div class="white step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
                           src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
                           style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="steps"><div class="white step"></div><div class="step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">2 - 4</span></div>

        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             src="<?= url('assets/unit-images/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="steps"><div class="white step"></div><div class="white step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('assets/unit-images/multiInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">1 - 4</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.NorthVsSouth.exclusiveRules')
@endsection

@section('inner-crt')
    @include('wargame::TMCW.NorthVsSouth.inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\NorthVsSouth\CombatResultsTable(\Wargame\TMCW\NorthVsSouth\NorthVsSouth::SOUTHERN_FORCE)])
@endsection
@section('unitRules.unitColors')
    @include('wargame::TMCW.NorthVsSouth.unitColors')
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.NorthVsSouth.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection



@section('obc')
    @include('wargame::TMCW.NorthVsSouth.obc')
@endsection

@section('units')
    <div ng-click="clickMe(unit.id, $event)"  class="a-unit-wrapper" ng-repeat="unit in mapUnits" ng-style="unit.wrapperstyle">
        <unit right-click-me="rightClickMe(id)" unit="unit"></unit>
    </div>

    <div ng-mouseover="hoverThis(unit)" ng-mouseleave="unHoverThis(unit)" ng-click="clickMe(unit.id, $event)"
         ng-style="unit.style" ng-repeat="unit in moveUnits track by $index" class="unit ghost-unit"
         ng-class="[unit.nationality, unit.class]">
        <ghost-unit unit="unit"></ghost-unit>
    </div>
@endsection