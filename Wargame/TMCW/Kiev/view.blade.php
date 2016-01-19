@include('wargame::global-header')
@include('wargame::TMCW.Kiev.kievHeader')
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/Kiev/all.css";
?>
</style>
</head>

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

@include('wargame::stdIncludes.view' )
