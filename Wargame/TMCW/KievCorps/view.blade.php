@include('wargame::ng-global-header')
@extends('wargame::Medieval.angular-view',['topCrt'=> $top_crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)] )
@include('wargame::TMCW.KievCorps.kievHeader')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/kievCorps.css')}}">

<script src="{{mix("vendor/javascripts/medieval/medieval.js")}}"></script>

<script src="{{mix('vendor/javascripts/wargame/kievCoprs.js')}}">

</script>
</head>
@section('credit')
    @include('wargame::TMCW.KievCorps.credit')
@endsection



@section('ng-unit-template')
    <div id="@{{unit.id}}"
         ng-right-click="rightClickMe({id:unit.id})"   ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="steps">
            <div ng-repeat="i in [0,0,0].slice(3 - unit.steps) track by $index " class="step"></div>
        </div>
        <div ng-if="unit.integrity" class="integrity"><i class="fa fa-star"></i></div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">@{{ unit.unitSize }}</div>

        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div ng-style="{background: unit.integrityColor }" ng-class="unit.infoLen" class="unit-numbers">@{{ unit.strength }}
            <span ng-class="{'white-color':!unit.supplied}">@{{ unit.supplied ? '-':'u' }}</span> @{{ unit.maxMove - unit.moveAmountUsed }}</div>
    </div>
@endsection

@section('ng-offmap-unit-template')
    <div id="@{{unit.id}}"  ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="steps">
            <div ng-repeat="i in [0,0,0].slice(3 - unit.steps) track by $index " class="step"></div>
        </div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">@{{ unit.unitSize }}</div>
        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div class="unit-numbers">@{{ unit.strength }} - @{{ unit.maxMove - unit.moveAmountUsed }}</div>
    </div>
@endsection

@section('ng-ghost-unit-template')
    <div class="steps">
        <div ng-repeat="i in [0,0,0].slice(3 - unit.steps) track by $index " class="step"></div>
    </div>
    <div class="unitSize">@{{ unit.unitSize }}</div>
    <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
         src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
    <div class="counterWrapper">
        <img src="{{asset("assets/unit-images/")}}/@{{ unit.image }}" class="counter"><span
                class="unit-desig">@{{ unit.unitDesig }}</span>
    </div>
    <div class="range">@{{ unit.armorClass }}</div>
    <div class="unit-numbers">@{{ unit.strength }} <span ng-class="{'white-color':!unit.supplied}">@{{ unit.supplied ? '-':'u' }}</span> @{{ unit.pointsLeft }}</div>
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
    @include('wargame::TMCW.KievCorps.exclusiveRules')
@endsection

@section('inner-crt')
    @include('wargame::TMCW.KievCorps.kievCorps-inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)])
@endsection
@section('unitRules.unitColors')
    @include('wargame::TMCW.KievCorps.unitColors')
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.KievCorps.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection



@section('obc')
    @include('wargame::TMCW.obc')
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

@section('unitsz')
    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="steps">
                <div class="step"></div>
                <div class="step"></div>
            </div>
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                <img src="{{asset("assets/unit-images/".$unit['image'])}}" class="counter"><span
                        class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@endsection
