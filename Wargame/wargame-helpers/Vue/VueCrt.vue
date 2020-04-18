<template>
        <div id="vue-crt" :class="crtOptions.playerName">
            <div class="close" @click="closeMe">X</div>
            <button v-if="numTables > 1" class="next-table-button btn btn-sm" @click="showNext">Show {{ nextTableName }} Table</button>
            <h3>Combat Odds <span v-if="highlightIndex">{{currentTable.header[highlightIndex]}}</span> <span>{{combatResult}}</span> <span>{{dieRoll}}</span></h3>
            <div v-if="crtData.crts"  v-for="(table,tableName) in crtData.crts">
                <div v-if="tableName === currentTableName">
                {{ tableName }} table
                <div id="odds">
                    <span class="col0">&nbsp;</span>
                    <span @click="pinCombat(index)" :id="'crt-col-'+index" :class="headerHighlight(index)" v-for=" (odds, index) in table.header">{{odds}}</span>
                </div>
                <div v-if="crtData.crts">
                <div v-for="(resultsRow, index) in table.table" :class="index & 1 ? '' : crtOptions.playerName" class="roll">
                    <span class="col0">{{index - table.dieOffsetHelper}}</span>
                    <span   :id="'crt-col-'+colIndex" :class="rowHighlight(index - table.dieOffsetHelper, colIndex)" @click="pinCombat(colIndex)" v-for="(result, colIndex) in resultsRow">{{ resultsNameData[result] }}</span>
                </div>
                </div>
                </div>
            </div>
            <button id="crt-details-button" class="btn">Details</button>
            <div v-if="showDetails" v-html="details"></div>
         </div>
</template>

<script>
    import {store} from "./store/store";
    import {mapMutations} from "vuex";
    import {DR} from '@markarian/wargame-helpers'
    import {doitCRT} from "@markarian/wargame-helpers";
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
                return this.$store.getters.currentTable;
            },
            currentTableName(){
               return this.$store.getters.currentTableName;
            },
            dieRoll(){
                return this.$store.state.crt.roll;
            },
            nextTableName(){
                return this.$store.getters.currentTable.next;
            },
            numTables(){
                return this.$store.state.crtData.crts.length;
            },
            showDetails(){
                return this.$store.state.crt.showDetails
            }
        },
        data: ()=>{
            return {
                crtData:{},
                resultsNameData: []
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
            ...mapMutations(['toggleShowDetails']),
            closeMe(){
                this.$store.commit('setCrt', {showCrt: false});
                // this.$store.state.crt.showCrt = false;
            },
            showNext(){
                this.$store.commit('setCrt', {selectedTable: this.$store.getters.currentTable.next})
                // this.$store.state.crt.selectedTable = this.$store.getters.currentTable.next;
            },
            headerHighlight(index){
                return index === this.highlightIndex ? 'highlighted': index === this.highlightPinned ? 'pin-highlighted': ''
            },
            rowHighlight(row, col){
                return col === this.highlightIndex ? row === this.highlightRoll ? 'roll-highlighted': 'highlighted': col === this.highlightPinned ? 'pin-highlighted': ''
            },
            pinCombat(index){
                const x = DR.dragged;
                if(DR.dragged){
                    return;
                }
                doitCRT(index+1);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>