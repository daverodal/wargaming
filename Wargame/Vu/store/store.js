import Vue from "vue";
import Vuex from "vuex";
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
        timeTravel:{
           currentClick: ''
        },
        crtData: {...combatResultsTable
        }
    },
    getters:{
        currentTableName: (state) => {
            console.log("CurrentTableName");
            console.log(state.crt.selectedTable);
            return state.crt.selectedTable;
        },
        currentTable: (state) => {
            return state.crtData.crts[state.crt.selectedTable];
        }
    }
})