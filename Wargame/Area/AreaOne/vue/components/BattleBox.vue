<template>
    <div class="build-wrapper" v-if="battles && battles.length > 0">
        <h3>Battles</h3>
        <div class="build-items" @mouseover="setBeacon(battle.location)" @mouseout="unsetBeacon()" :class="{yellow: hovered == battle.location}" v-for="(battle, index) in battles">
            {{battle.report}}
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";
    export default {
        name: "BattleBox",
        computed: {
            ...mapGetters(['battles', 'hovered'])
        },
        methods:{
            ...mapMutations(['setBeacon', 'unsetBeacon'])
        }
    }
</script>

<style lang="scss" scoped>
.build-wrapper{
     margin: 5px 0;
    background: #eee;
    }
    h3{
        margin-top:0px;
    }
    .yellow{
        background: #ffff6c;
    }

</style>