<template>
    <div class="move-command-wrapper">
        <div class="background">
        </div>
        <div class="move-command">
            <button @click="move">Move</button>
            <div># <button @click="dec">-</button> {{ moveCount}} <button @click="inc">+</button> of {{ armiesHere }} <button @click="all" >all</button></div>
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
        computed: {
          armiesHere(){
              const box = this.$store.getters.selectedBox;
              const playerId = this.$store.state.selectedPlayer;
              return box.armies[playerId];
          }
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
                if(this.moveCount >= this.armiesHere){
                    return;
                }
              this.moveCount++;
            },
            dec(){
                if(this.moveCount <= 1){
                    return;
                }
                this.moveCount--;
            },
            all(){
                this.moveCount = this.armiesHere;
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
            left: 40%;
            padding: 5px;
        }

    }
</style>