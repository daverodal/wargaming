@include('wargame::global-header')
@include('wargame::TMCW.RetreatOne.retreatOneHeader')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/retreatOne.css')}}">
<script src="{{mix('vendor/javascripts/wargame/retreatOne.js')}}"></script>
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
