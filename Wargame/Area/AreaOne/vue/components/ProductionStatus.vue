<template>
    <div class="prod-status-wrapper" v-if="isProduction && selectedBox.isCity">
        <h3>Production
            {{pfAvailable}}</h3>
        <button class="btn btn-secondary btn-sm" v-if="pfAvailable  > 0" @click="$store.commit('produceUnit')">Add</button>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "ProductrionStatus",
        computed:{
            ...mapGetters(['selectedBox', 'isSelected', 'isProduction', 'pfAvailable'])
        }
    }
</script>

<style scoped lang="scss">
    .prod-status-wrapper{
        margin: 5px 0;
        background: #eee;

    }
    h3{
        margin-top:0px;
    }
</style>