@include('wargame::global-header')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/wargame/tmcw/css/kiev.css')}}">
</head>

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\Kiev\CombatResultsTable()])
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

@extends('wargame::stdIncludes.view' )
