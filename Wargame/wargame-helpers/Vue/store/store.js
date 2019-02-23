import Vue from "vue";
import Vuex from "vuex";
import {DR} from "../global-vue-header";
Vue.use(Vuex);
export const store = new Vuex.Store({
    state: {
        crt:{
            index: false,
            row: false,
            pinned: false,
            player: "",
            details: "",
            combatResult: "",
            selectedTable: 'normal',
            roll: null
        },
        headerData:{
            victory: '',
            status: '',
            topStatus: '',
            log: ''
        },
        timeTravel:{
           currentClick: ''
        },
        crtData: {...combatResultsTable
        },
        mapData:{
            unitsMap:{},
            hexesMap:{}
        }
    },
    getters:{
        currentTableName: (state) => {

            return state.crt.selectedTable;
        },
        currentTable: (state) => {
            return state.crtData.crts[state.crt.selectedTable];
        }
    }
})