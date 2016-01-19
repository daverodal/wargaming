@include('wargame::global-header')
@include('wargame::TMCW.Manchuria1976.Manchuria1976Header')
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/Manchuria1976/all.css";
    ?>
</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
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

@section('obc')
    @include('wargame::TMCW.obc')
@endsection

@include('wargame::stdIncludes.view' )
