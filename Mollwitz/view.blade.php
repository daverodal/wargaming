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

@section('crt')
    <h1> War </h1>
    <?php $topCrt = new \Mollwitz\CombatResultsTable();?>
@endsection

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Mollwitz\CombatResultsTable()]);
@endsection



@section('obc')
    @include('wargame::Mollwitz.obc')
@endsection

@section('commonRules')
    @include('wargame::Mollwitz.commonRules')
@endsection