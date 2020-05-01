<template>
   <div>
        <div class="super-wrapper">
       <header class="display-wrapper">
           <a href="/wargame/leave-game">Go To Lobby</a>

           <div class="display-item">
               <h2>Welcome {{ $store.state.user }}</h2>
           Turn is turn {{ turn }}
           {{getPhase}}
               Small Map?:  Try Zooming with scroll wheel
<!--               <input type="checkbox" v-model="smallMap">-->
               <div class="ready-wrapper">
                   <div :class="playerOne">one</div>
                   <div :class="playerTwo">two</div>
               </div>
               <button v-if="!showWait" class="geaux-button" @click="poke">GO</button>
               <button v-if="showWait" class="wait-button" @click="poke">Wait</button>
               <div>
                   <h2>Assets</h2>
                   <div  v-for="(resource, index) in getPF">
                       <div class="resource-wrapper" v-if="index != 0">
                           <div class="big">{{$store.state.combatants[index]}} PF: {{resource.pf }} Cities: {{$store.getters.getCities[index].length}}</div>
                           <div>Armies {{totalArmies[index]}}</div>
                           <div> F: {{resource.food}} E: {{resource.energy}} M: {{resource.materials}}</div>
                       </div>
                   </div>
               </div>
           </div>
           <battle-box></battle-box>
            <area-status></area-status>
           <production-status></production-status>
           <command-box></command-box>
           <build-box></build-box>
           <button v-if="!showWait" class="geaux-button" @click="poke">GO</button>
           <button v-if="showWait" class="wait-button" @click="poke">Wait</button>

       </header>

       <div class="game-wrapper" >
           <pan-zoom :options="{zoomDoubleClickSpeed: 1}">
           <div :class="{'small-game': smallMap}">
           <img :style="{width: !smallMap ? mapData.width : mapData.width * .7 + 'px'}" :src="mapData.url" alt="">

               <click-box v-for="(box,index) in boxes" :key="index" :box="box"></click-box>
           <div class="command-items" v-for="command in commands">
               <move-circle :command="command" :amount="command.amount"></move-circle>
           </div>
           <move-command v-if="$store.state.mode === 'move'"></move-command>
           </div>
           </pan-zoom>
       </div>
        </div>
   </div>
</template>

<script>
    import {syncObj} from '@markarian/wargame-helpers'
    import {mapGetters} from "vuex";
    export default {
        name: "AreaGame",
        props:['mapData',
        'wargame', 'user'],
        data: () => {
            return {
                syncObj: null,
                turn: 0
            }
        },
        computed:{
            ...mapGetters(["getPhase", 'getPF', 'playersReady', 'playerIds', 'showWait', 'totalArmies', 'isSmallMap']),
            smallMap:{
                get(){
                    return  this.isSmallMap;
                },
                set(value){
                    this.$store.commit('setSmallMap', value)
                }
            },
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
            },
            commands() {
                return this.$store.state.commands;
            }
        },
        mounted() {
          syncObj.register('playerStatus', (obj) => {
          });
          syncObj.register('doc', (item, data) => {
              this.$store.commit('setPlayersReady', item.wargame.playersReady)
              this.$store.commit('setPlayers', item.wargame.players);
              if(item.wargame.gameRules.battles && item.wargame.gameRules.battles.length > 0){
                  this.$store.commit('setBattles', item.wargame.gameRules.battles);
              }else{
                  this.$store.commit('setBattles', []);
              }
              // this.mapData.boxes = item.wargame.areaModel.areas;
              if(this.$store.state.phase != item.wargame.gameRules.phase){
                  this.$store.commit('setBoxes', item.wargame.areaModel.areas);
                  this.$store.commit('clearTurn');
                  this.turn = item.wargame.gameRules.turn;
                  this.$store.commit('setResources', item.wargame.gameRules.resources);
                  this.$store.commit('setPhase', item.wargame.gameRules.phase);
              }

              this.$store.commit('setCombatants', item.wargame.combatants);
              this.$store.commit('setCaualities', item.wargame.gameRules.casualities);
          });
          syncObj.fetch(0);
          this.$store.commit('setUser', this.user);
        },
    methods:{
            doCancel(){
              this.$store.commit('doCancel');
            },
            poke(){
                const data = {wargame: this.wargame, event: 1, type: 'area-game'}
                data.commands = this.$store.state.commands;
                data.builds = this.$store.state.builds;
                window.axios.post('/wargame/poke', data).then(response => {

                }).catch(error => {

                })
            }
    },
    }
</script>

<style lang="scss">
    .vue-pan-zoom-scene{
        outline: none !important;
    }
</style>
<style lang="scss" scoped>
    .big{
        font-size: 22px;
    }
    .resource-wrapper{
        margin: 5px 0;
        background: #eee;
    }
    .super-wrapper{
        display:flex;
        min-width:240px;
    }
    .geaux-button{
        background: #35cd04;
        width: 40px;
        height: 40px;
        border: 3px solid black;
        border-radius: 100%;
        font-size:15px;
    }
    .wait-button{
        background: red;
        width: 40px;
        height: 40px;
        border: 3px solid black;
        border-radius: 100%;
        font-size:11px;
    }
    .display-wrapper{
        min-width:200px;
       display: flex;
        flex-direction: column;
        min-height: 120px;
        border: 2px solid #999;
        padding: 5px;
        z-index: 5;
        background: white;

    }
    h2{
        margin-top:0px;
    }
    .ready-wrapper{
        display:flex;
        justify-content: flex-start;
        div{
            margin: 10px;
        }
    }
    .game-wrapper{
        .small-game{
            font-size:12px;
        }
        position: relative;
        margin-bottom:20px;
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
    .move-wrapper{
        position:absolute;
        background:fuchsia;
        width: 900px;
    }
</style>