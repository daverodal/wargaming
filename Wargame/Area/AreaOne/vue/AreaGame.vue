<template>
   <div>
       <header><h1>my game all mighty</h1>
       This is a turn.
       </header>
       <div :class="playerOne">one</div>
       <div :class="playerTwo">two</div>

       Turn is >>>. {{ turn }} <<<<<
       <button @click="poke">Ready</button>

       <div v-if="playersReady && playersReady[0]"> p {{playersReady[0].ready }} </div>
       <div class="game-wrapper">
           <img style="position:absolute;left:0;top:0;width: 1024px" :src="mapData.url" alt="">

               <click-box v-for="(box,index) in mapData.boxes" :key="index" :box="box"></click-box>
       </div>
   </div>
</template>

<script>
    import {Sync} from '../../../wargame-helpers/Sync'
    export default {
        name: "AreaGame",
        props:['mapData',
        'wargame'],
        data: () => {
            return {syncObj: null,
            playersReady: [],
                turn: 0
            }
        },
        computed:{
            playerOne(){
                debugger;
                if(this.playersReady && this.playersReady[0] && this.playersReady[0].ready){
                    return 'blue';
                }
                return 'red'
            },
            playerTwo(){
                debugger;
                if(this.playersReady && this.playersReady[1] && this.playersReady[1].ready){
                    return 'blue';
                }
                return 'red'
            }
        },
        mounted() {
          this.syncObj = new Sync('/wargame/fetch/'+this.wargame);
          this.syncObj.register('playerStatus', (obj) => {
             debugger
          });
          this.syncObj.register('doc', (item, data) => {
              this.playersReady = item.wargame.playersReady;
              console.log(item.wargame.playersReady);
              debugger;
              this.turn = item.wargame.gameRules.turn;
          });
          this.syncObj.fetch(0);
            debugger;
        },
    methods:{
            poke(){
                const x = $.ajax({url:'http://localhost:8888/wargame/poke',type:'POST',
                data: {wargame: this.wargame, event: 1, type: 'area-game'},
                error: (err) => {
                    debugger;
                },
                success: (res) => {
                    debugger
                }});
            }
    },
    }
</script>

<style lang="scss" scoped>
    .game-wrapper{
        position: relative;
    }
    .red {
        background-color: palevioletred;
    }
    .blue {
        background-color: #00b0ff;
    }
    .label-box{
        background: white;
        padding: 3px;
        position:absolute;
    }
</style>