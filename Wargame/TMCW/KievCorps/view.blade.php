@extends('wargame::stdIncludes.view' )

@include('wargame::global-header')
@include('wargame::TMCW.KievCorps.kievHeader')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/kievcorps/css/all.css')}}">
</head>
@section('credit')
    @include('wargame::TMCW.KievCorps.credit')
@endsection
@section('unitRules.reducedUnits')
    <li>
        The total number of steps a unit has is designated by the number of dots along the left side.
        The number of white dots represents the number of steps lost.
        When a unit is reduced to 0 steps, they are eliminated.
        Units with less then their maximum steps are eligable for replacements.
        <div class="clear"></div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('js/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="steps"><div class="white step"></div><div class="step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('js/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">6 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[1]) ?>" alt="0"
             src="<?= url('js/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="steps"><div class="white step"></div><div class="white step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('js/multiArmor.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
                           src="<?= url('js/short-red-arrow-md.png'); ?>"
                           style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="steps"><div class="white step"></div><div class="step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('js/multiInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">2 - 4</span></div>

        </div>
        <div class="unit <?= strtolower($forceName[2]) ?>" alt="0"
             src="<?= url('js/short-red-arrow-md.png'); ?>"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="steps"><div class="white step"></div><div class="white step"></div><div class="step"></div></div>
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= url('js/multiInf.png'); ?>" class="counter">
            </div>

            <div class="unit-numbers"><span class="">1 - 4</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.KievCorps.exclusiveRules')
@endsection

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(GERMAN_FORCE)])
@endsection
@section('unitRules.unitColors')
    @include('wargame::TMCW.KievCorps.unitColors')
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.KievCorps.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection



@section('obc')
    @include('wargame::TMCW.obc')
@endsection
@section('units')
    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="steps">
                <div class="step"></div>
                <div class="step"></div>
            </div>
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                <img src="{{asset("js/".$unit['image'])}}" class="counter"><span
                        class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@endsection
