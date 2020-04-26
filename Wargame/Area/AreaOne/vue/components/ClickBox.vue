<template>
    <div  @mouseover="setHovered(box.id)"  @mouseout="unsetHovered" @click="select" class="label-box" :class="{ 'is-beacon': beacon && beacon == box.id ? true  : false, blue: box.owner == 1, red: box.owner == 2, neighbor: isNeighbor, selected: isSelected}" :style="{top: box.y, left: box.x}">
        {{box.name}}
         <span> {{box.armies[1] || 0}} {{box.armies[2] || 0}}
         </span>
        <div v-if="casualities[this.box.id]" class="cross">{{casualities[this.box.id]}}</div>
        <div v-if="open">
            This is the clickedy part
        </div>
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
            ...mapGetters(['casualities','beacon']),
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