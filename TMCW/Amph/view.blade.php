<?php
global $force_name;?>
@include('wargame::TMCW.Amph.amph-header')
<style type="text/css">
    <?php
         include_once "TMCW/Amph/all.css";
?>
</style>
</head>
<?php
global $force_name;
$playerNum = !empty($playerNum) ? $playerNum : 0;
$player = $force_name[$playerNum];
$youAre = $force_name[$playerNum];
$deployTwo = $playerOne = $force_name[1];
$deployOne = $playerTwo = $force_name[2];
    //@include_once "view.php";
        ?>
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
