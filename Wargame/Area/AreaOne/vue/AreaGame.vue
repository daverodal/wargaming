<template>
   <div>
       <header>
           <h1>Area One</h1>
       Welcome {{ $store.state.user }}
       </header>
       <div class="ready-wrapper">
           <div :class="playerOne">one</div>
           <div :class="playerTwo">two</div>
       </div>
        <div  v-for="(resource, index) in $store.state.resources">
            <div v-if="index != 0">
                {{$store.state.combatants[index]}}
                F: {{resource.food}} E: {{resource.energy}} M: {{resource.materials}} Cities: {{$store.getters.getCities[index].length}}
            </div>
        </div>
       <div>{{getPhase}}</div>
       <command-box></command-box>
       <build-box></build-box>

       Turn is turn {{ turn }}
       <button @click="poke">Ready</button>


       <area-status></area-status>

       <div style="width: 1024px"  class="game-wrapper">
           <img style="width: 1024px" :src="mapData.url" alt="">

               <click-box v-for="(box,index) in boxes" :key="index" :box="box"></click-box>
           <div class="command-items" v-for="command in commands">
               love you
               <move-circle :command="command" :amount="command.amount"></move-circle>
           </div>
           <move-command v-if="$store.state.mode === 'move'"></move-command>

       </div>
   </div>
</template>

<script>
    import {Sync} from '../../../wargame-helpers/Sync'
    import {mapGetters} from "vuex";
    export default {
        name: "AreaGame",
        props:['mapData',
        'wargame', 'user'],
        data: () => {
            return {syncObj: null,
            playersReady: [],
                turn: 0
            }
        },
        computed:{
            ...mapGetters(["getPhase"]),

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
          this.syncObj = new Sync('/wargame/fetch/'+this.wargame);
          this.syncObj.register('playerStatus', (obj) => {
          });
          this.syncObj.register('doc', (item, data) => {
              this.playersReady = item.wargame.playersReady;
              console.log(item.wargame.playersReady);
              this.$store.commit('setBoxes', item.wargame.areaModel.areas);
              this.$store.commit('setPlayers', item.wargame.players);
              // this.mapData.boxes = item.wargame.areaModel.areas;
              this.$store.commit('clearTurn');
              this.turn = item.wargame.gameRules.turn;
              this.$store.commit('setResources', item.wargame.gameRules.resources);
              this.$store.commit('setPhase', item.wargame.gameRules.phase);
              this.$store.commit('setCombatants', item.wargame.combatants);
          });
          this.syncObj.fetch(0);
          this.$store.commit('setUser', this.user);
        },
    methods:{
            doCancel(){
              this.$store.commit('doCancel');
            },
            poke(){
                debugger;
                const data = {wargame: this.wargame, event: 1, type: 'area-game'}
                data.commands = this.$store.state.commands;
                data.builds = this.$store.state.builds;
                const x = $.ajax({url:'/wargame/poke',type:'POST',
                data: data,
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
    .move-wrapper{
        position:absolute;
        background:fuchsia;
        width: 900px;
    }
</style>