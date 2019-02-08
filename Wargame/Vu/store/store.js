debugger;
import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);
debugger;
export const store = new Vuex.Store({
    state: {
        crt:{
            index: false,
            row: false,
            pinned: false,
            player: "",
            details: "",
            combatResult: "",
            selectedTable: 'normal'
        },
        crtData: {...combatResultsTable
        }
    },
    getters:{
        currentTableHame: (state) => {
            console.log("CurrentTableName");
            console.log(state.crt.selectedTable);
            return state.crt.selectedTable;
        },
        currentTable: (state) => {
            return combatResultsTable.crts[state.crt.selectedTable];
        }
    }
})