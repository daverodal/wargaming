<template>
    <div class="area-status-wrapper" v-if="isSelected">
        <h2>{{selectedBox.name}}</h2>

        {{$store.state.buildLocations[selectedBox.id]}}
        <h3>Armies Here</h3>
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
    h2{
        margin-top: 0px;
    }
    .area-status-wrapper{

        margin: 5px 0;
        background: #eee;

    }
    .armies-box{
        display:flex;
        justify-content: space-between;
    }
</style>