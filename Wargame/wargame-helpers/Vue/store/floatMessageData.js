import Vue from "vue";

export const floatMessageData = {
    namespaced: true,
    state: {
        header: "",
        message: "",
        advisory: "",
        x: 0,
        y:0
    },
    mutations:{
        setHeader(state, header){
            state.header = header;
        },
        setMessage(state, message){
            state.message = message;
        },
        setAdvisory(state, advisory){
            state.advisory = advisory;
        },
        setX(state, x){
            state.x = x;
        },
        setY(state, y){
            state.y = y;
        },
        clear(state){
            state.advisory = state.message = state.header = '';
            state.x = state.y = 0;
            return state;
        }
    },
    getters:{

    }
}