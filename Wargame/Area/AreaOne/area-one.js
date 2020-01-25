import AreaGame from "./vue/AreaGame";

require('../area-js/bootstrap');

import Vue from "vue";
import VueResource from 'vue-resource';
import {store} from "./store";
import ClickBox from "./vue/components/ClickBox";
import AreaStatus from "./vue/components/AreaStatus";
import MoveCommand from "./vue/components/MoveCommand";
window.vueStore = store;
Vue.use(VueResource);
Vue.component('area-game', AreaGame);
Vue.component('click-box', ClickBox);
Vue.component('area-status', AreaStatus);
Vue.component('move-command', MoveCommand);

document.addEventListener("DOMContentLoaded",() => {
    window.world = new Vue({
        el: '.world',
        store: store,
        computed: {
            currentClick() {
            },
            showCrt() {
            }
        },
        methods: {
            myFirstOne(evt) {
                console.log(evt.target.id);
            }
        },
        data: {
            crtOptions: {}
        }
    });
});