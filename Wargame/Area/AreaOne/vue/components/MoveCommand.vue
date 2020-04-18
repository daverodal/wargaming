<template>
    <div class="move-command-wrapper">
        <div class="background">
        </div>
        <div class="move-command">
            <button @click="move">Move</button>
            <div># <button @click="dec">-</button> {{ moveCount}} <button @click="inc">+</button></div>
            <button @click="cancel">Cancel</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MoveCommand",
        data: () => {
            return {moveCount: 0};
        },
        mounted(){
            const box = this.$store.getters.selectedBox;
            const playerId = this.$store.state.selectedPlayer;
            // this.moveCount = box.armies[playerId];
            this.moveCount = 1;
        },
        methods:{
            cancel(){
                this.$store.commit('doCancel');
            },
            move(){
              this.$store.commit('moveCommand', {amount: this.moveCount})
            },
            inc(){
                const state = this.$store.state;
                if(this.moveCount >= this.$store.getters.selectedBox.armies[state.selectedPlayer]){
                    return;
                }
              this.moveCount++;
            },
            dec(){
                if(this.moveCount <= 0){
                    return;
                }
                this.moveCount--;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .move-command-wrapper{
        .background{
            background: #000;
            opacity: .3;
            position:absolute;
            left:0;
            right:0;
            top:0;
            bottom:0;
        }
        .move-command{
            top: 250px;
            opacity: 1;
            background: white;
            border: 10px solid #999;
            border-radius: 15px;
            width: 200px;
            position: absolute;
        }

    }
</style>