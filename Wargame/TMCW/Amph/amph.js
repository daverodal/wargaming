import Vue from "vue";
import WargameVueComponents, {SyncController} from "@markarian/wargame-vue-components";
import { globalFuncs } from "@markarian/wargame-helpers";
import VueDraggableResizable from 'vue-draggable-resizable'
import UnitComponent from './UnitComponent';
Vue.use(WargameVueComponents);
Vue.component('vue-draggable-resizable', VueDraggableResizable)
Vue.component('unit-component', UnitComponent);
const syncController = new SyncController();
const xyzzy = globalFuncs;
import '../../wargame-helpers/Vue/vue-hookup';
