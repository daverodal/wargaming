import Vue from "vue";
import WargameVueComponents from "@markarian/wargame-vue-components";
// import VueDraggableResizable from 'vue-draggable-resizable'
Vue.use(WargameVueComponents);
import UnitComponent from './UnitComponent';



// Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);

import {TinCansSyncController} from "./tin-cans-sync-controller";
const syncController = new TinCansSyncController();
import SpecialEvent from './SpecialEvent';
Vue.component('special-event', SpecialEvent);
import '../../wargame-helpers/Vue/vue-hookup';
