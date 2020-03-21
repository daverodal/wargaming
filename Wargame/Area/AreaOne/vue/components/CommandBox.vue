<template>
    <div class="command-wrapper">
        Commands
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
    .command-items{
        padding-left:20px;
    }
}
</style>