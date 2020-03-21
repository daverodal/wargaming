<template>
    <div  @click="select" class="label-box" :class="{green: box.owner == 1, red: box.owner == 2, neighbor: isNeighbor, selected: isSelected}" :style="{top: box.y, left: box.x}">
        {{box.name}}
         <span> {{box.armies[1] || 0}} {{box.armies[2] || 0}}
         </span>
        <div v-if="open">
            This is the clickedy part
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClickBox",
        props: ['box'],
        data: () => {
            return {open: false}
        },
        computed: {
          isSelected(){
              if(this.box.id === this.$store.state.selected){
                  return true;
              }
              return false;
            },
            isNeighbor(){
              const neighbors = this.$store.getters.selectedNeighbors;
              if(this.$store.getters.selectedNeighbors.includes(this.box.id)){
                  return true;
              }
              return false;
            }
        },
        methods:{
            clicked(){
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
    div{
        border: 3px solid transparent;
    }
    .green{
        background:lime;
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
</style>