@include('wargame::global-header')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/medieval/grunwald1410/css/all.css')}}">
<style>
    .semi-circle {
        width: 48px;
        height: 24px;
        /* background: #eee; */
        border-color: red;
        border-style: solid;
        border-width: 25px 0px 0px 0px;
        border-radius: 100%;
        position: absolute;
        top:-5px;
        left:-5px;

    }



    .rel-unit{
        position:relative;
    }

</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('inner-crt')
    @include('wargame::Medieval.medieval-inner-crt', ['topCrt'=> new \Wargame\Medieval\MedievalCombatResultsTable()])
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




@section('nounits')



    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('assets/unit-images/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                <img src="{{asset("js/".$unit['image'])}}" class="counter"><span class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@endsection

@include('wargame::Medieval.angular-view',['topCrt'=> new \Wargame\Medieval\MedievalCombatResultsTable()] )
