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
            selectedTable: 'normal'
        },
        crtData: {

        }
    }
})