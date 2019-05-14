import Vue from "vue";

export const boxesData = {
    namespaced: true,
    state: {
        allBoxes:{
            deployBox: [],
            deadpile: [],
            exitBox: [],
            notUsed: [],
            beachlanding: [],
            airdrop:[],
            south: [],
            west: [],
            east: [],
        },
    },
    mutations:{
        putUnit(state, {slot, unit}){
            if(!Array.isArray(state.allBoxes[slot])){
                Vue.set(state.allBoxes, slot, []);
            }
            state.allBoxes[slot].push(unit);
        },
        clearBoxes(state){
            Object.keys(state.allBoxes).forEach(function(key,index) {
                state.allBoxes[key] = [];
            })
        }
    },
    getters:{
        allBoxes(state){
            return state.allBoxes;
        }
    }
}