<template>
    <div v-if="$store.state.selected !== null">
        Name: {{$store.getters.selectedBox.name}}
        <h2>Armies</h2>
        <div class="armies-box">
            <div>blue
                {{$store.getters.selectedBox.armies[1] || 0}}
            </div>
            <div>red
                {{$store.getters.selectedBox.armies[2] || 0}}
            </div>
        </div>
        <button @click="move">Move</button>
        </div>
</template>

<script>
    export default {
        name: "AreaStatus",
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