@include('wargame::export-global-header')
@include('wargame::Vu.header')
<script src="{{mix('vendor/javascripts/wargame/vu.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/vu.css')}}">
</head>

@section('exclusiveRules')
    @include("wargame::Vu.exclusiveRules")
@endsection
@section('victoryConditions')
    @include("wargame::Vu.victoryConditions")
@endsection
@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\VU\CombatResultsTable()])
@endsection
@section('commonRules')
    @include("wargame::Vu.commonRules")

@endsection
@section('tec')
@endsection


@extends('wargame::stdIncludes.view-vue' )
