@include('wargame::global-header')
@include('wargame::TMCW.Amph.amph-header')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/amph/css/all.css')}}">
</head>
@section('tec')
    @include("wargame::TMCW.Amph.tec")
@endsection

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
<div id="options-pane">
    There are 3 major facilities on the map. The nuclear facility, on the left, Chateau sur mer, in the center, and the marine science center, on the right.
    You must choose which facility to make your primary goal. Taking and holding your primary goal at the end of the game will give you extra victory points.
    Not taking your primary goal will deduct points. Your opponents will NOT know what your goal is, they may suspect what it is after you start playing,
    you may of course try to decieve your opponent into thinking your goal is really another. Your opponent will know you have chosen a goal and will try to prevent you
    from holding it. Good luck.
<div id="options-box">
    these are options

</div>
<button id="choose-option-button">Choose Your Option</button>
</div>
@include('wargame::stdIncludes.view' )
