import Vue from "vue";
import Vuex from "vuex";
import {DR} from "../global-vue-header";
Vue.use(Vuex);

import {mapData} from "./mapData";
import {headerData} from "./headerData";

export const store = new Vuex.Store({
    modules:{
      mD: mapData,
      headerData: headerData
    },
    state: {
        crt:{
            index: false,
            row: false,
            pinned: false,
            player: "",
            details: "",
            combatResult: "",
            selectedTable: 'normal',
            roll: null,
            showCrt: false
        },
        timeTravel:{
           currentClick: '',
            showUndo: false
        },
        crtData: {...combatResultsTable
        },

        gameRules:{
            prevPhase: null
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