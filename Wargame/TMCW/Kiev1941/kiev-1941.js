import Vue from "vue";
import WargameVueComponents from "@markarian/wargame-vue-components";
Vue.use(WargameVueComponents);
import UnitComponent from './UnitComponent';

// Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);

import {Kiev1941SyncController} from "./kiev-1941-sync-controller";
const syncController = new Kiev1941SyncController();

import '../../wargame-helpers/Vue/vue-hookup';
