<template>
   <div>
       <h1>Area One</h1>

       <header class="display-wrapper">
           <div class="display-item">
               <h2>Welcome {{ $store.state.user }}</h2>
           Turn is turn {{ turn }}
           {{getPhase}}
           </div>
            <area-status></area-status>
           <production-status></production-status>
           <command-box></command-box>
           <build-box></build-box>
       </header>
       <div class="ready-wrapper">
           <div :class="playerOne">one</div>
           <div :class="playerTwo">two</div>
       </div>
       <div>
           <h2>Resources</h2>
        <div  v-for="(resource, index) in $store.state.resources">
            <div v-if="index != 0">
                {{$store.state.combatants[index]}}
                F: {{resource.food}} E: {{resource.energy}} M: {{resource.materials}} Cities: {{$store.getters.getCities[index].length}}
            </div>
        </div>
       </div>
       <div class="ready-wrapper">



       </div>

       <button @click="poke">Ready</button>



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
    import {syncObj} from '@markarian/wargame-helpers'
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
          syncObj.register('playerStatus', (obj) => {
          });
          syncObj.register('doc', (item, data) => {
              this.playersReady = item.wargame.playersReady;
              console.log(item.wargame.playersReady);
              this.$store.commit('setPlayers', item.wargame.players);
              // this.mapData.boxes = item.wargame.areaModel.areas;
              if(this.$store.state.phase != item.wargame.gameRules.phase){
                  this.$store.commit('setBoxes', item.wargame.areaModel.areas);
                  this.$store.commit('clearTurn');
                  this.turn = item.wargame.gameRules.turn;
                  this.$store.commit('setResources', item.wargame.gameRules.resources);
                  this.$store.commit('setPhase', item.wargame.gameRules.phase);
              }

              this.$store.commit('setCombatants', item.wargame.combatants);
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
                // const x = $.ajax({url:'/wargame/poke',type:'POST',
                // data: data,
                // error: (err) => {
                //     debugger;
                // },
                // success: (res) => {
                // }});
                window.axios.post('/wargame/poke', data).then(response => {

                }).catch(error => {

                })
            }
    },
    }
</script>

<style lang="scss" scoped>
    .display-wrapper{
       display: flex;
        min-height: 120px;
        border: 2px solid #999;
        padding: 5px;

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