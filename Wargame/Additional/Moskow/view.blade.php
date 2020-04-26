@include('wargame::global-header')
@include('wargame::Additional.Moskow.moskowHeader')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/moskow.css')}}">
<script src="{{mix('vendor/javascripts/wargame/moskow.js')}}"></script>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::Additional.Moskow.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::Additional.Moskow.obc')
@endsection

@include('wargame::stdIncludes.view' )
