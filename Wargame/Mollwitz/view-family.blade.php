@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include("$curPath.$clsName"."Header")
<link rel="stylesheet" type="text/css" href="{{elixir('vendor/wargame/mollwitz/css/'.$clsName.'.css')}}">
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

@section('casualty')
    <div class="dropDown"  id="CASWrapper">
        <h4 class="WrapperLabel" title='Casualities'>Cas</h4>
        <div id="TEC" style="display:none;">
            <div class="close">X</div>

            <div class="cas-container"></div>
        </div>
    </div>
@endsection
@if(view()->exists("$curPath.tec"))
@section('tec')
    @include("$curPath.tec")
@endsection
@endif
