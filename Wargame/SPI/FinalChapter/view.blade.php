@include('wargame::global-header')
@include('wargame::SPI.FinalChapter.finalChapterHeader')
<style type="text/css">
    <?php
         include_once "Wargame/SPI/FinalChapter/all.css";
?>
</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::SPI.FinalChapter.victoryConditions')
@endsection

@section('commonRules')
    {{--@include('wargame::SPI.commonRules')--}}
@endsection

@section('exclusiveRules')
    @include('wargame::SPI.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::SPI.FinalChapter.obc')
@endsection

@include('wargame::stdIncludes.view' )
