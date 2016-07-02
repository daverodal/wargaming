@include('wargame::global-header')
@include('wargame::TMCW.Moskow.moskowHeader')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/moskow/css/all.css')}}">
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Moskow.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.Moskow.obc')
@endsection

@include('wargame::stdIncludes.view' )
