<template>
    <div class="command-wrapper" v-if="commands && commands.length > 0">
        <h3>Commands</h3>
        <div class="command-items" v-for="command in commands">
            from {{$store.state.boxes[command.from].name}} to {{$store.state.boxes[command.to].name}} num {{command.amount}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "CommandBox",
        computed: {
            commands() {
                return this.$store.state.commands;
            }
        }
    }
</script>

<style lang="scss" scoped>
.command-wrapper{
        margin-left: 15px;

        padding-left: 10px;
        border-left: 2px solid #999;
    }
    h3{
        margin-top:0px;
    }


</style>