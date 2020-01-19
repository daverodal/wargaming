<template>
    <div v-if="$store.state.selected !== null"> Name: {{$store.getters.selectedBox.name}}
        Armies: {{$store.getters.selectedBox.armies[1]}} {{$store.getters.selectedBox.armies[2]}}
        </div>
</template>

<script>
    export default {
        name: "AreaStatus"
    }
</script>

<style scoped>

</style>