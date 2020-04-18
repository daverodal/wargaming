<template>
    <div class="build-wrapper" v-if="builds && builds.length > 0">
        <h3>Builds</h3>
        <div class="build-items" v-for="build in builds">
            At {{$store.state.boxes[build.selected].name}} one Army for {{$store.state.combatants[build.playerId]}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "BuildBox",
        computed: {
            builds() {
                return this.$store.state.builds;
            }
        }
    }
</script>

<style lang="scss" scoped>
.build-wrapper{
        margin-left: 15px;

        padding-left: 10px;
        border-left: 2px solid #999;
    }
    h3{
        margin-top:0px;
    }

</style>