<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <area-game class="world" :wargame="'{{ $wargame }}'" :user="'{{$user}}'" :map-data="{{ json_encode($mapData) }}"></area-game>
</body>
<?php
$oClass = new ReflectionClass('Wargame\Cnst');
$constants = $oClass->getConstants();
?>
<script type="text/javascript">
    <?php foreach($constants as $k => $v){
        echo "const $k = $v;\n";
    }
    ?>
        window.legacy = {};
        const fetchUrl = "<?=url("wargame/fetch/$wargame");?>";

</script>
<script type="text/javascript" src="{{mix('vendor/javascripts/wargame/area-one.js')}}"></script>
</html>
