import Vue from "vue";

import WargameVueComponents,  {SyncController}  from "@markarian/wargame-vue-components";
Vue.use(WargameVueComponents);
const syncController = new SyncController();

import '../wargame-helpers/Vue/vue-hookup';