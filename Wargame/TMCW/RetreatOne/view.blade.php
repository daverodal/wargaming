@include('wargame::global-header')
@include('wargame::TMCW.RetreatOne.retreatOneHeader')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/retreatone/css/all.css')}}">
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/RetreatOne/all.css";
?>
</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
{{--    @include('wargame::TMCW.Amph.victoryConditions')--}}
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

@include('wargame::stdIncludes.view' )
