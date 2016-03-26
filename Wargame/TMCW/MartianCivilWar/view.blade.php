@include('wargame::global-header')
@include('wargame::TMCW.MartianCivilWar.tmcwHeader')
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/MartianCivilWar/all.css";
?>
</style>
</head>

@section('tec')
    @include("wargame::TMCW.MartianCivilWar.tec")
@endsection

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.MartianCivilWar.victoryConditions')
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
