@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include('wargame::NTA.header')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/nta/css/all.css')}}">
</head>

@section('exclusiveRules')
    @include("wargame::NTA.exclusiveRules")
@endsection
@section('victoryConditions')
    @include("wargame::NTA.victoryConditions")
@endsection
@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\NTA\CombatResultsTable()])
@endsection
@section('commonRules')
    @include("wargame::NTA.commonRules")

@endsection
@section('tec')
@endsection


@extends('wargame::stdIncludes.view' )
