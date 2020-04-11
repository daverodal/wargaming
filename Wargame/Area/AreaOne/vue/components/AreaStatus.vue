<template>
    <div v-if="isSelected">
        <h2>Name: {{selectedBox.name}}</h2>
        <div v-if="isProduction && selectedBox.isCity">
            <h3>production!
                {{pfAvailable}}</h3>
            <button v-if="pfAvailable  > 0" @click="$store.commit('produceUnit')">Add</button>
        </div>
        <h2>Armies</h2>
        <div class="armies-box">
            <div>blue
                {{selectedBox.armies[1] || 0}}
            </div>
            <div>red
                {{selectedBox.armies[2] || 0}}
            </div>
        </div>
        </div>
</template>

<script>
    import {mapGetters} from "vuex";
    export default {
        name: "AreaStatus",
        computed:{
            ...mapGetters(['selectedBox', 'isSelected', 'isProduction', 'pfAvailable'])
        },
        methods:{
            move(){
            }
        }
    }
</script>

<style lang="scss" scoped>
    .armies-box{
        width:200px;
        display:flex;
        justify-content: space-between;
    }
</style>