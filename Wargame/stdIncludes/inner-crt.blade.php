<?php
/* not happy about this part :( */
global $results_name;

/*
 * The template passes $topCrt
 * that is how a templates tells us which crt to render
 */
$crts = $topCrt->crts;
?>
<div id="crt-buttons">
    @foreach($crts as $crtName => $crt)
        <div class="switch-crt" id="{{$crtName}}Table">show {{$crtName}} table</div>
    @endforeach
</div>

@foreach($crts as $crtName => $crt)
    <div class="tableWrapper {{$crtName}}Table">
        <h4 class="crt-table-name">{{$crtName}} combat table.</h4>

        <div id="odds">
            <span class="col0">&nbsp;</span>
            <?php  $i = 1;
            $header = $topCrt->combatResultsHeader;
            $headerName = $crtName."ResultsHeader";
            if(isset($topCrt->$headerName)){
                $header = $topCrt->$headerName;
            }?>
            @foreach ($header as $odds)
                <span class="col{{$i++}}">{{$odds}}</span>
            @endforeach
            <div class="clear"></div>
        </div>
        <?php
        $rowNum = 1;
        $odd = ($rowNum & 1) ? "odd" : "even";?>
        @foreach ($crt as $row)
            <div class="roll {{"row$rowNum $odd"}}">
                <span class="col0">{{$rowNum++}}</span>
                <?php $col = 1;?>
                @foreach ($row as $cell)
                    <span class="col{{$col++}}">{{$results_name[$cell]}}</span>
                @endforeach
                <div class="clear"></div>
            </div>
        @endforeach
    </div>

@endforeach