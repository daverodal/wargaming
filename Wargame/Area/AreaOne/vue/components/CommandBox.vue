<template>
    <div class="command-wrapper" v-if="commands && commands.length > 0">
        <h3>Commands</h3>
        <div class="command-items" v-for="(command, index) in commands">
            from {{$store.state.boxes[command.from].name}} to {{$store.state.boxes[command.to].name}}<br> num {{command.amount}}<button class="btn btn-sm btn-secondary" @click="$store.commit('deleteCommand', index)">delete</button>
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
    margin: 5px 0;
    background: #eee;
    }
    h3{
        margin-top:0px;
    }


</style>