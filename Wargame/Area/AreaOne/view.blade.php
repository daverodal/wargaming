<html>
<head>

</head>
<body>
    <area-game class="world" :wargame="'{{ $wargame }}'" :map-data="{{json_encode($mapData)}}"></area-game>
</body>
<script type="text/javascript" src="{{mix('vendor/javascripts/wargame/area-one.js')}}"></script>

</html>
