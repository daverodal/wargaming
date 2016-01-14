@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include('wargame::Mollwitz.Golymin1806.Golymin1806Header')
<style type="text/css">
    <?php
         include_once "Mollwitz/Golymin1806/all.css";
?>
</style>
</head>
<?php
//$playerNum = !empty($playerNum) ? $playerNum : 0;
//$player = $forceName[$playerNum];
//$youAre = $forceName[$playerNum];
//$playerOne = $forceName[1];
//$playerTwo = $forceName[2];
//
//$deployTwo = $deployName[1];
//$deployOne = $deployName[2]
//    //@include_once "view.php";
//
?>

@section('victoryConditions')
    @include('wargame::Mollwitz.Golymin1806.victoryConditions')
@endsection


@section('exclusiveRules')
    @include('wargame::Mollwitz.Golymin1806.exclusiveRules')
@endsection

@include('wargame::Mollwitz.view')
@include('wargame::stdIncludes.view' )
