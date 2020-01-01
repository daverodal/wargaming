<template>
    <div  @click="open = !open" class="label-box" :style="{top: box.y, left: box.x}">
        {{box.name}}
        <div v-if="open">
            This is the clickedy part
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClickBox",
        props: ['box'],
        data: () => {
            return {open: false}
        },
        methods:{
            clicked(){
                debugger;
            }
        }
    }
</script>

<style scoped>

</style>