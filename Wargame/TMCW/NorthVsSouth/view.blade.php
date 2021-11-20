@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\NorthVsSouth\CombatResultsTable(1)])


<script src="{{mix('vendor/javascripts/wargame/northvssouth.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/northvssouth.css')}}">
</head>

@section('tec')
@endsection

@section('commonRules')
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('obc')
@endsection

@extends('wargame::stdIncludes.view-vue' )
@section('dynamic-buttons')
    <button :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="shiftKey">+</button>
@endsection
@section('deploy-box')
    <div id="deployBox">
        <div>

            <deploy-units-component style="float:left" :myfilter="1" :myunits="allMyBoxes.deployBox"></deploy-units-component>
            <deploy-units-component style="float:left" :myfilter="2" :myunits="allMyBoxes.deployBox"></deploy-units-component>
        </div>

        <div class="clear"></div>
    </div>
@endsection