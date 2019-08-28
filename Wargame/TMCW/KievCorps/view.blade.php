@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)])


<script src="{{mix('vendor/javascripts/wargame/kievCoprs.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/kievCorps.css')}}">
</head>


@section('credit')
    @include('wargame::TMCW.KievCorps.credit')
@endsection

@extends('wargame::stdIncludes.view-vue' )
@section('dynamic-buttons')
    <button @click="clearCombat" :class="{'inline-show': dynamic.combat}" class="dynamicButton combatButton" id="clearCombatEvent">c</button>
    <button @click="shiftClick" :class="{'inline-show': dynamic.combat, dark: dynamic.shiftKey }" class="dynamicButton combatButton" id="shiftKey">+</button>
@endsection
@section('options')
@endsection
