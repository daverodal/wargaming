@include('wargame::global-header')
@include('wargame::TMCW.Manchuria1976.Manchuria1976Header')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/manchuria1976.css')}}">
<link href="https://fonts.googleapis.com/css?family=Archivo+Narrow:600|Arimo:700|Oswald|Poppins:500|Roboto+Slab:700|Work+Sans:500" rel="stylesheet">
</head>


@include('wargame::TMCW.Manchuria1976.unitsRules')

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt',['topCrt'=> $top_crt = new \Wargame\TMCW\Manchuria1976\CombatResultsTable()])
@endsection

@section('victoryConditions')
{{--    @include('wargame::TMCW.Manchuria1976.victoryConditions')--}}
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('tec')
    @include("wargame::TMCW.Manchuria1976.tec")
@endsection

@section('obc')
    @include('wargame::TMCW.Manchuria1976.obc')
@endsection

@extends('wargame::stdIncludes.view' )
