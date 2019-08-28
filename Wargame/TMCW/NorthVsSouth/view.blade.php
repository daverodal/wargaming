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