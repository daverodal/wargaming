<template>
   <div>
       <header><h1>my game all mighty</h1>
       This is a turn.
       </header>
       <div class="ready-wrapper">
           <div :class="playerOne">one</div>
           <div :class="playerTwo">two</div>
       </div>


       Turn is >>>. {{ turn }} <<<<<
       <area-status></area-status>
       <button @click="poke">Ready</button>

       <div v-if="playersReady && playersReady[0]"> p {{playersReady[0].ready }} </div>
       <div class="game-wrapper">
           <img style="position:absolute;left:0;top:0;width: 1024px" :src="mapData.url" alt="">

               <click-box v-for="(box,index) in boxes" :key="index" :box="box"></click-box>
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
                if(this.playersReady && this.playersReady[0] && this.playersReady[0].ready){
                    return 'blue';
                }
                return 'red'
            },
            playerTwo(){
                if(this.playersReady && this.playersReady[1] && this.playersReady[1].ready){
                    return 'blue';
                }
                return 'red'
            },
            boxes(){
                return this.$store.state.boxes;
            }
        },
        mounted() {
          this.syncObj = new Sync('/wargame/fetch/'+this.wargame);
          this.syncObj.register('playerStatus', (obj) => {
          });
          this.syncObj.register('doc', (item, data) => {
              this.playersReady = item.wargame.playersReady;
              console.log(item.wargame.playersReady);
              debugger;
              this.$store.commit('setBoxes', item.wargame.areaModel.areas);
              // this.mapData.boxes = item.wargame.areaModel.areas;
              this.turn = item.wargame.gameRules.turn;
          });
          this.syncObj.fetch(0);
        },
    methods:{
            poke(){
                const x = $.ajax({url:'https://alpha.davidrodal.com/wargame/poke',type:'POST',
                data: {wargame: this.wargame, event: 1, type: 'area-game'},
                error: (err) => {
                    debugger;
                },
                success: (res) => {
                }});
            }
    },
    }
</script>

<style lang="scss" scoped>
    .ready-wrapper{
        display:flex;
        justify-content: space-around;
    }
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