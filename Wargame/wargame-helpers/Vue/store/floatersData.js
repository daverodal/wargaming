import Vue from "vue";

export const floatersData = {
    namespaced: true,
    state: {
       isVis: false
    },
    mutations:{
        show(state){

            state.isVis = true;
        },
        hide(state){
            state.isVis = false;
        },
        toggle(state){
            state.isVis = !state.isVis;
        }
    },
    getters:{
        allBoxes(state){
            return state.allBoxes;
        }
    }
}