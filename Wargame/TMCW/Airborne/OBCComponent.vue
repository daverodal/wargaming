<template>
    <div class="obc">
        <div class="game-turn" v-for="turn in maxTurn">

            <div :class="{'turn-counter': turn ==   currentTurn}">Turn {{ turn }}</div>
            <div v-for="(myObc, key) in obc" v-if="myObc.length > 0 && key.match(new RegExp('^gameTurn'+turn))" >
                {{ key.replace(new RegExp('^gameTurn'+turn), '') }}
                <units-component :myunits="myObc"></units-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OBCComponent",
        props:['obc'],
        mounted(){
        },
        methods:{
          getObcs(turn){

          }
        },
        computed:{
            currentTurn(){
                return this.$store.state.headerData.turn;
            },
            maxTurn(){
              return this.$store.state.headerData.maxTurn;
            },
            gameTurns(){
                let turns = _.filter(this.obc, (item, key) =>{
                    if(key.match(/^gameTurn/)){
                        return true;
                    }
                    return false;
                });
            }
        }
    }
</script>

<style lang="scss">
    .obc {
        display:flex;
        position: absolute;
        z-index: 10;
        background: white;
        margin-top: 3em;
        padding:10px;

        .game-turn{
            width: 90px;
            margin: 0 10px;
        }
        .turn-counter{
            background-color:mediumaquamarine;
        }
        .unit {
            position: static !important;
        }
    }
</style>