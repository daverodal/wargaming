@include('wargame::export-global-header', ['topCrt'=> new \Wargame\Vu\CombatResultsTable()])


<script src="{{mix('vendor/javascripts/wargame/vu.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/vu.css')}}">
</head>

@section('tec')
@endsection

@section('commonRules')
@endsection
@section('exclusiveRulesWrapper')
@endsection
@section('obc')
@endsection

@extends('wargame::stdIncludes.view-vue' )
