@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include("$curPath.$clsName"."Header")
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/mollwitz/'.$clsName.'/css/all.css')}}">
</head>
@extends('wargame::stdIncludes.view' )
@extends('wargame::Mollwitz.view')
@section('credit')
    @include('wargame::Mollwitz.credit')
@endsection
@if(view()->exists("$curPath.victoryConditions"))
    @section('victoryConditions')
        @include("$curPath.victoryConditions")
    @endsection
@endif

@if(view()->exists("$curPath.exclusiveRules"))
    @section('exclusiveRules')
        @include("$curPath.exclusiveRules")
    @endsection
@endif

@if(view()->exists("$curPath.tec"))
@section('tec')
    @include("$curPath.tec")
@endsection
@endif
