<template>
    <div  @mouseover="setHovered(box.id)"  @mouseout="unsetHovered" @click="select" class="label-box" :class="{ 'is-beacon': beacon && beacon == box.id ? true  : false, blue: box.owner == 1, red: box.owner == 2, neighbor: isNeighbor, selected: isSelected}" :style="{top: boxY, left: boxX}">
        {{box.name}}
        <div v-if="armies" class="inf">{{armies}}</div>
        <div v-if="casualities[this.box.id]" class="cross">{{casualities[this.box.id]}}</div>
    </div>
</template>

<script>

    import {mapGetters, mapMutations} from "vuex";
    export default {
        name: "ClickBox",
        props: ['box'],
        data: () => {
            return {open: false}
        },
        computed: {
            ...mapGetters(['casualities','beacon', 'isSmallMap']),
            boxX(){
                debugger;
              return this.box.x * (this.isSmallMap ? .7 : 1);
            },
            boxY(){
                debugger;
                return this.box.y * (this.isSmallMap ? .7 : 1);
            },
          isSelected(){
              if(this.box.id === this.$store.state.selected){
                  return true;
              }
              return false;
            },
            isNeighbor(){
              const neighbors = this.$store.getters.selectedNeighbors;
              const boxId = this.box.id - 0;
              if(neighbors.includes(boxId)){
                  return true;
              }
              return false;
            },
            armies(){
                return this.box.armies[1] || 0 +  this.box.armies[2] || 0
            }
        },
        methods:{
            ...mapMutations(['setHovered', 'unsetHovered']),
            hover(){
                this.$store.commit('setHovered', this.box.id);

            },
            unhover(){
                this.$store.commit('unSetHovered');
            },
            select(){
                if(this.isNeighbor){
                    this.$store.commit('doMove', this.box.id);
                    return;
                }
                if(this.isSelected){
                    this.$store.commit('selected', {id: null, playerId: null});
                }else{
                    const user = this.$store.state.user;
                    const players = this.$store.state.players;

                    const armies = Object.keys(this.box.armies)
                    .filter(armyId => players[armyId] === user)
                    .map(id => id - 0);
                    if(armies.length === 0){
                        this.$store.commit('selected',{id: this.box.id, playerId: null});
                    }
                    if(armies.length === 1){
                        const armyId = armies[0];
                        this.$store.commit('selected',{id: this.box.id, playerId: armyId});
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .inf{
        background-image: url('./InfBPB-1.jpg');
        height:16px;
        background-size:8px;
        background-repeat: no-repeat;
        padding-left: 10px;
    }
    .cross{
        background-image: url('./cross.svg');
        height:16px;
        background-size:16px;
        background-repeat: no-repeat;
        padding-left: 20px;
    }
    div{
        border: 3px solid transparent;
    }
    .blue{
        background:#00e7ff;
    }
    .red{
        background: orangered;
    }
    .selected{
        border-color: yellow;
    }
    .neighbor{
        border-color: orange;
    }
    @keyframes bigger{
        0%{
            border-width: 1px;
        }
        65%{
        }
        100%{
            border-width:30px;
            margin-top:-29px;
            margin-left:-29px;

        }
    }
    .is-beacon{
        animation: 1.5s infinite forwards bigger;
        }
</style>