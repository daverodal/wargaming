@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include('wargame::Mollwitz.Brandywine1777.Brandywine1777Header')
<style type="text/css">
    <?php
         include_once "Mollwitz/Brandywine1777/all.css";
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
//        ?>

@section('victoryConditions')
    @include('wargame::Mollwitz.Brandywine1777.victoryConditions')
@endsection


@section('exclusiveRules')
    @include('wargame::Mollwitz.Brandywine1777.exclusiveRules')
@endsection

@include('wargame::Mollwitz.view')
@include('wargame::stdIncludes.view' )
