import Vue from "vue";
window._ = require('lodash');
import VueResource from 'vue-resource';
import {store} from "./store";
window.vueStore = store;
Vue.use(VueResource);

document.addEventListener("DOMContentLoaded",() => {
    window.world = new Vue({
        el: '.world',
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