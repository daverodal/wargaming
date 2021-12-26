import AreaGame from "./vue/AreaGame";

require('../area-js/bootstrap');

import Vue from "vue";
import VueResource from 'vue-resource';
import {store} from "./store";
import ClickBox from "./vue/components/ClickBox";
import AreaStatus from "./vue/components/AreaStatus";
import MoveCommand from "./vue/components/MoveCommand";
import CommandBox from "./vue/components/CommandBox";
import MoveCircle from "./vue/components/MoveCircle";
import BuildBox from "./vue/components/BuildBox";
import BattleBox from "./vue/components/BattleBox";
import ProductionStatus from "./vue/components/ProductionStatus";
import Resource from "./vue/components/Resource";
window.vueStore = store;
Vue.use(VueResource);
import LogView from "./vue/components/LogView";
// import vue-panzoom
import panZoom from 'vue-panzoom'
import CasualityCircle from "./vue/components/CasualityCircle";
import "bootstrap-sass";
// install plugin
Vue.use(panZoom, {compoentName: 'pan-zoom',
    zoomDoubleClickSpeed: 1});
Vue.component('area-game', AreaGame);
Vue.component('click-box', ClickBox);
Vue.component('area-status', AreaStatus);
Vue.component('move-command', MoveCommand);
Vue.component('command-box', CommandBox);
Vue.component('build-box', BuildBox);
Vue.component('battle-box', BattleBox);
Vue.component('move-circle', MoveCircle );
Vue.component('production-status', ProductionStatus );
Vue.component('log-view', LogView);
Vue.component('casuality-circle', CasualityCircle);
Vue.component('resource', Resource);

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
            }
        },
        data: {
            crtOptions: {}
        }
    });
});