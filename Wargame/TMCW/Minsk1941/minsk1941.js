import Vue from "vue";
import WargameVueComponents from "@markarian/wargame-vue-components";

// import VueDraggableResizable from 'vue-draggable-resizable'
Vue.use(WargameVueComponents);
import UnitComponent from './UnitComponent';


// Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);

import {Minsk1941SyncController} from "./minsk-1941-sync-controller";
const syncController = new Minsk1941SyncController();

import '../../wargame-helpers/Vue/vue-hookup';
