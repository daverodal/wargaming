<div class="game-rules">
    <h1>    {{ $gameName }}   </h1>

    <h2>Rules of Play</h2>

    <h2>Design Context</h2>

    <p><?= $name ?> is a continuation of the gaming framework first pioneered by the game The Martian Civil War.
        We hope you enjoy playing our game.</p>


    <ol class="topNumbers">
        <li id="contentsRules">
            @include('wargame::TMCW.commonContents')
        </li>
        <li id="unitsRules">
            <span class="big">UNITS</span>

            <ol>
                @section('unitRules')
                    @include('wargame::TMCW.commonUnitsRules')
                @show
            </ol>

            <?php //include "commonUnitsRules.php" ?>
        </li>
        <li id="sopRules">
            @section('SOP')
                @include('wargame::TMCW.commonSequenceOfPlay')
            @show
        </li>
        <li id="stackingRules">
            @section('commonStacking')
                @include('wargame::TMCW.commonStacking')
            @show
            <?php //include "commonStacking.php" ?>
        </li>
        <li id="moveRules">
            @include('wargame::TMCW.commonMoveRules')

            <?php //include "commonMoveRules.php" ?>
        </li>
        <li id="zocRules">
            @section('zoc-rules')
                @include('wargame::TMCW.commonZocRules')
            @show
        </li>
        <li id="supply-rules">
            @section('supply-rules')
            @show
        </li>
        <li id="combatRules">
            @include('wargame::TMCW.commonCombatRules')
        </li>

        <li id="exclusiveRules" class="exclusive">
            @section('exclusiveRules')
                @include('wargame::TMCW.exclusiveRules')
            @show
        </li>
        <li class="exclusive" id="victoryConditions">
            @section('victoryConditions')
                @include('wargame::TMCW.victoryConditions')
            @show

        </li>
        <li id="designCredits">
            <span class="big">Design Credits</span>
            @section('credit')
                @include('wargame::TMCW.credit')
            @show
        </li>
    </ol>
</div>
