@include('wargame::global-header')
@include('wargame::TMCW.Manchuria1976.Manchuria1976Header')
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/manchuria1976.css')}}">
<link href="https://fonts.googleapis.com/css?family=Archivo+Narrow:600|Arimo:700|Oswald|Poppins:500|Roboto+Slab:700|Work+Sans:500" rel="stylesheet">
</head>


@section('commonRules')
    @include('wargame::TMCW.Manchuria1976.manchuriaRules')
@endsection
@extends('wargame::stdIncludes.view' )
