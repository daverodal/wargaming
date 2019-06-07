@include('wargame::export-global-header',['topCrt'=> $top_crt = new \Wargame\TMCW\EastWest\CombatResultsTable(\Wargame\TMCW\EastWest\EastWest::GERMAN_FORCE)])
<script src="{{mix('vendor/javascripts/wargame/east-west.js')}}"></script>
@extends('wargame::stdIncludes.view-vue'  )
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/eastwest.css')}}">
</head>
@section('credit')
    @include('wargame::TMCW.EastWest.credit')
@endsection
