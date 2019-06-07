@include('wargame::export-global-header', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(\Wargame\TMCW\KievCorps\KievCorps::GERMAN_FORCE)])


<script src="{{mix('vendor/javascripts/wargame/kievCoprs.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/kievCorps.css')}}">
</head>


@section('credit')
    @include('wargame::TMCW.KievCorps.credit')
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
@endsection
