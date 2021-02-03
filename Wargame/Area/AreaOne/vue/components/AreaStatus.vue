<template>
    <div class="area-status-wrapper container" v-if="isSelected">
        <h2>{{selectedBox.name}}</h2>
      <div class="row">
        <div class="col">Terrain Type</div>
        <div class="col">{{selectedBox.terrainType}}</div>
      </div>
      <div class="row">
        <div class="col">Resources</div>
        <div class="col">
          <resource :resource="resourcesHere"></resource>
        </div>
      </div>
      <div class="row">
        <div class="col">Area ID</div>
        <div class="col">{{selectedBox.id}}</div>
      </div>
      <div class="row">
        <div class="col">Owner</div>
        <div class="col">{{$store.state.combatants[selectedBox.owner]}}</div>
      </div>

        <h3>Armies Here</h3>
        <div class="armies-box">
            <div>blue
                {{selectedBox.armies[1] || 0}}
            </div>
            <div>red
                {{selectedBox.armies[2] || 0}}
            </div>
        </div>
        </div>
</template>

<script>
    import {mapGetters} from "vuex";
    export default {
        name: "AreaStatus",
        computed:{
            ...mapGetters(['selectedBox', 'isSelected', 'isProduction', 'pfAvailable', 'resourcesHere'])
        },
        methods:{
            move(){
            }
        }
    }
</script>

<style lang="scss" scoped>
    h2{
        margin-top: 0px;
    }
    .area-status-wrapper{

        margin: 5px 0;
        background: #eee;

    }
    .armies-box{
        display:flex;
        justify-content: space-between;
    }
</style>