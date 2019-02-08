<template>
        <div id="vue-crt" :class="crtOptions.playerName">
            <div class="close">X</div>
            <button class="next-table-button btn btn-sm" @click="showNext">Show Next Table</button>
            {{ currentTableName }}
            <h3>Combat Odds <span v-if="highlightIndex">{{currentTable.header[highlightIndex]}}</span> <span>{{combatResult}}</span></h3>
            <div v-if="crtData.crts"  v-for="(table,tableName) in crtData.crts">
                <div v-if="tableName === currentTableName">
                {{ tableName }} table
                <div id="odds">
                    <span class="col0">&nbsp;</span>
                    <span :class="headerHighlight(index)" v-for=" (odds, index) in table.header">{{odds}}</span>
                </div>
                <div v-if="crtData.crts">
                <div v-for="(resultsRow, index) in table.table" :class="index & 1 ? '' : crtOptions.playerName" class="roll">
                    <span class="col0">{{index+1}}</span>
                    <span  :class="rowHighlight(index, colIndex)" v-for="(result, colIndex) in resultsRow">{{ resultsNameData[result] }}</span>
                </div>
                </div>
                </div>
            </div>
            <button class="btn" @click="showDetails = !showDetails">Details</button>
            <div v-if="showDetails" v-html="details"></div>
         </div>
</template>

<script>
    import {store} from "./store/store";
    export default {
        props: ['crt','crtOptions'],
        computed: {
            highlightIndex(){
                return this.$store.state.crt.index;
            },
            highlightPinned(){
                return this.$store.state.crt.pinned
            },
            highlightRoll(){
                return this.$store.state.crt.roll;
            },
            details(){
                return this.$store.state.crt.details;
            },
            combatResult(){
                return this.$store.state.crt.combatResult;
            },
            currentTable(){
                return this.crtData.crts[this.$store.state.crt.selectedTable];
            },
            currentTableName(){
               return this.$store.state.crt.selectedTable;
            }
        },
        data: ()=>{
            return {
                crtData:{},
                resultsNameData: [],
                showDetails: false,
            }
        },
        mounted(){
            this.crtData = this.$store.state.crtData;
            // this.crtData = JSON.parse(this.crt);
            // this.$store.state.crtData = this.crtData;
            /* global constant resultsNames */
            this.resultsNameData = resultsNames;
        },
        methods:{
            showNext(){
              if(this.$store.state.crt.selectedTable === 'normal'){
                  this.$store.state.crt.selectedTable = 'cavalry';
                  return;
              }
              if(this.$store.state.crt.selectedTable === 'cavalry'){
                  this.$store.state.crt.selectedTable = 'determined';
                  return;
              }
              if(this.$store.state.crt.selectedTable === 'determined'){
                  this.$store.state.crt.selectedTable = 'normal';
                  return;
              }
            },
            headerHighlight(index){
                return index === this.highlightIndex ? 'highlighted': index === this.highlightPinned ? 'pin-highlighted': ''
            },
            rowHighlight(row, col){
                return col === this.highlightIndex ? row === this.highlightRoll ? 'roll-highlighted': 'highlighted': col === this.highlightPinned ? 'pin-highlighted': ''
            }
        }
    }
</script>

<style lang="scss" scoped>
    .next-table-button{
        margin-top: 20px;
        margin-right: 10px;
        float:right;
        font-size: 12px;
    }

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
    .right{
        float: right;
    }
</style>