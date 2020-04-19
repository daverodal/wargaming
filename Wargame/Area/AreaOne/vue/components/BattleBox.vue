<template>
    <div class="build-wrapper" v-if="battles && battles.length > 0">
        <h3>Battles</h3>
        <div class="build-items" v-for="(battle, index) in battles">
            {{battle}}
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    export default {
        name: "BattleBox",
        computed: {
            ...mapGetters(['battles'])
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

</style>