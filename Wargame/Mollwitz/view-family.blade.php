@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include("$curPath.$clsName"."Header")
<style type="text/css">

<?php  include_once "Wargame/Mollwitz/$clsName/all.css";?>
</style>
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
@include('wargame::Mollwitz.view')
@include('wargame::stdIncludes.view' )
