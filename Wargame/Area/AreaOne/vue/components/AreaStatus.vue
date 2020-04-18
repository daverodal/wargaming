<template>
    <div class="area-status-wrapper" v-if="isSelected">
        <h2>Name: {{selectedBox.name}}</h2>

        <h2>Armies Here</h2>
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
        margin-left: 15px;

            padding-left: 10px;
            border-left: 2px solid #999;

    }
    .armies-box{
        display:flex;
        justify-content: space-between;
    }
</style>