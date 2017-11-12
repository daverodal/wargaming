@section('units')
    <?php $id = 0;?>
    <?php foreach($units as $unit){?>
    <div class="unit <?=$unit['class']?> <?=$unit['type']?>" id="<?=$unit['id']?>" alt="0">
        <div class="shadow-mask"></div>
        <div class="counterWrapper">
            <div class="guard-unit">GD</div>
            <div class="counter"></div>
            <div class="fa fa-flag"></div>
        </div>

        <p class="forceMarch">M</p>
        <p class="range"></p>
        <img class="arrow" src="{{url('assets/unit-images/short-red-arrow-md.png')}}" class="counter">

        <div class="unit-numbers">5 - 4</div>

    </div>
    <?php } ?>
@endsection

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\Mollwitz\CombatResultsTable()])
@endsection

@section('inner-menu')
    @parent
    <li><a id="vintageButton">vintage</a></li>
@endsection

@section('obc')
    @include('wargame::Mollwitz.obc')
@endsection

@section('commonRules')
    @include('wargame::Mollwitz.commonRules')
@endsection