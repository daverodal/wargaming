<template>
    <div class="build-wrapper" v-if="builds && builds.length > 0">
        <h3>Builds</h3>
        <div class="build-items" v-for="(build, index) in builds">
            {{$store.state.boxes[build.selected].name}} 1 army <button @click="$store.commit('deleteProduction',index)">delete</button>
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
       margin: 5px 0;
    background: #eee;
    }
    h3{
        margin-top:0px;
    }

</style>