import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);


export const store = new Vuex.Store({
    strict: true,
    modules:{
    },
    state: {
        selected: null,
        boxes: {},
        mode: 'select'
    },
    getters:{
        selectedBox(state){
            if(state.selected !== null){
                return state.boxes[state.selected];
            }
            return {};
        },
        selectedNeighbors(state){
            if(state.selected !== null){
                return state.boxes[state.selected].neighbors;
            }
            return [];
        }
    },
    mutations:{
        selected(state, payload){
            state.selected = payload;
        },
        setBoxes(state, payload){
            state.boxes = {...payload};
        },
        doMove(state){
            state.mode = 'move';
        },
        doCancel(state){
            state.mode = 'select';
        }
        },
    actions: {
    }
})