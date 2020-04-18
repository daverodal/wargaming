<template>
    <div class="prod-status-wrapper" v-if="isProduction && selectedBox.isCity">
        <h3>production!
            {{pfAvailable}}</h3>
        <button v-if="pfAvailable  > 0" @click="$store.commit('produceUnit')">Add</button>
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
        margin-left: 15px;

        padding-left: 10px;
        border-left: 2px solid #999;
    }
    h3{
        margin-top:0px;
    }
</style>