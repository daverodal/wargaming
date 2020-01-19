import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);


export const store = new Vuex.Store({
    strict: true,
    modules:{
    },
    state: {
        selected: null,
        boxes: {}
    },
    getters:{
        selectedBox(state){
            if(state.selected !== null){
                return state.boxes[state.selected];
            }
            return {};
        }
    },
    mutations:{
        selected(state, payload){
            state.selected = payload;
        },
        setBoxes(state, payload){
            debugger;
            state.boxes = {...payload};
        }
    },
    actions: {
    }
})