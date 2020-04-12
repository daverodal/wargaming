<template>
    <div class="build-wrapper">
        Builds
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
    .build-items{
        padding-left:20px;
    }
}
</style>