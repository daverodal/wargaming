@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include('wargame::Mollwitz.Fraustadt1706.Fraustadt1706Header')
<style type="text/css">
    <?php
         include_once "Mollwitz/Fraustadt1706/all.css";
?>
</style>
</head>
<?php
$playerNum = !empty($playerNum) ? $playerNum : 0;
$player = $forceName[$playerNum];
$youAre = $forceName[$playerNum];
$playerOne = $forceName[1];
$playerTwo = $forceName[2];

$deployTwo = $deployName[1];
$deployOne = $deployName[2]
    //@include_once "view.php";
        ?>
@section('units')
    <?php $id = 0;?>
    <?php foreach($units as $unit){?>
    <div class="unit <?=$unit['class']?> <?=$unit['type']?>" id="<?=$unit['id']?>" alt="0">
        <div class="shadow-mask"></div>
        <div class="counterWrapper">
            <div class="guard-unit">GD</div>
            <div class="counter"></div>
        </div>
        <p class="range"><?=$unit['range']?></p>

        <p class="forceMarch">M</p>
        <img class="arrow" src="{{url('js/short-red-arrow-md.png')}}" class="counter">

        <div class="unit-numbers">5 - 4</div>

    </div>
    <?php } ?>
@endsection
@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection
@section('victoryConditions')
    @include('wargame::Mollwitz.Fraustadt1706.victoryConditions')
@endsection
@section('commonRules')
    @include('wargame::Mollwitz.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::Mollwitz.Fraustadt1706.exclusiveRules')
@endsection
@section('obc')
    @include('wargame::Mollwitz.obc')
@endsection
@include('wargame::stdIncludes.view' )
