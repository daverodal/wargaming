<template>
        <div id="vue-crt" :class="crtOptions.playerName">
            <div class="close">X</div>
            <h3>Combat Odds</h3>
            <div v-if="crtData.combatResultsHeader" id="odds">
                <span class="col0">&nbsp;</span>
                <span v-for=" (odds, index) in crtData.combatResultsHeader" :class="'col'+(index-0+1)">{{odds}}</span>
            </div>
            <div v-if="crtData.crts">
            <div v-for="(resultsRow, index) in crtData.crts.normal" :class="index & 1 ? '' : crtOptions.playerName" class="roll">
                <span class="col0">{{index+1}}</span>
                <span  :class="colIndex === crtOptions.index ? index === crtOptions.roll ? 'roll-highlighted': 'highlighted': colIndex === crtOptions.pinIndex ? 'pin-highlighted': ''" v-for="(result, colIndex) in resultsRow">{{ resultsNameData[result] }}</span>
            </div>
            </div>
           THE View CRT Part
         </div>
</template>

<script>
    export default {
        props: ['crt','crtOptions','resultsName'],
        data: ()=>{
            return {crtData:{},
            resultsNameData: [],
            highlightIndex: 5}
        },
        mounted(){
            this.crtData = JSON.parse(this.crt);
            this.resultsNameData = JSON.parse(this.resultsName);
            debugger;
        }
    }
</script>

<style lang="scss" scoped>
     #vue-crt .roll span, #vue-crt #odds span{
         display: inline-block;
         margin-right: 10px;
         width: 28px;
     }
    #vue-crt {
        width: 600px;
        font-size: 16px;

        display: none;
        z-index: 40;
        border-radius: 15px;
        border-width: 10px;
        border-style: solid;
        background: #fff;
        color: black;
        font-weight: bold;
        padding: 1px 5px 10px 15px;
        box-shadow: 10px 10px 10px rgba(30, 30, 30, .85);

     .tableWrapper {
         display: none;
     }

     .crt-table-name {
         color: #aaa;
         font-style: italic;
         margin-top: -15px;
         font-size: 13px;
     }

     #crt-buttons {
         cursor: pointer;

     .switch-crt {
         position: absolute;
         right: 19px;
         top: 3px;
     }

     }
     .crt-odds {
         font-size: 16px;
     }

     #crtDetails {
         text-align: right;
         float: left;
         display: none;
     }

     h3 {
         vertical-align: bottom;
     }

     span {
         width: 32px;
     }

     #crtDetailsButton {
         font-size: 20px;
         cursor: pointer;
     }

     .col1 {
         left: 20px;
     }

     .col2 {
         left: 60px;
     }

     .col3 {
         left: 100px;
     }

     .col4 {
         left: 140px;
     }

     .col5 {
         left: 180px;
     }

     .col6 {
         left: 220px;
     }

     .roll, #odds {
         padding-top: 3px;
         margin-right: 14px
     }

     #odds {
         background: white;
     }

     .even {
         color: black;
     }

     .odd {
         color: black;
     }

     .row1 {
         top: 80px;

     }

     .row2 {
         top: 100px;
         background: white;
     }

     .row3 {
         top: 120px;
     }

     .row4 {
         top: 140px;
         background: white;
     }

     .row5 {
         top: 160px;
     }

     .row6 {
         top: 180px;
         background: white;
     }

     }

    .roll-highlighted{
        background-color:cyan;
    }
    .pin-highlighted{
        background:#ff00ff;
    }
</style>