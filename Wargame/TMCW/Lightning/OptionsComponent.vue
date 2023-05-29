<template>
    <div v-if="showOptions" class="options-modal">
        <div class="options-container">
            <p>
                Pick you poison
            </p>
            <div class="flex-container">
                <div class="flex-item">
                    <h2 class="big">Nuclear Plant </h2>
                    <h3>+35</h3>
                   Taking this objective may be the hardest one of all. It is far back from the beach and there is no cover of forest to
                    slow the defenders. It is woth 35 points.
                    <img src="/vendor/wargame/tmcw/lightning/images/left-goal.png" alt="">
            <button @click="ididit()">Choose</button>
                </div>
                <div class="flex-item">
                    <h2 class="big">Castle</h2>
                    <h3> +25</h3>
                    Thaking this objective is easier than the Nuclear plant to the left. But the Mountains give
                    good cover for the derfenders. It is worth 25 points.
                    <img src="/vendor/wargame/tmcw/lightning/images/middle-goal.png" alt="">
            <button @click="ididit()">Choose</button>

                </div>
                <div class="flex-item">
                    <h2 class="big">Science Facility</h2>
                    <h3> +20</h3>
                    This may be the easiest object to capture. It is fairly close and there is no terrain to defend in.
                    It is worth 20 points.
                    <img src="/vendor/wargame/tmcw/lightning/images/right-goal.png" alt="">
            <button @click="ididit()">Choose</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OptionsComponent",
        methods:{
            ididit(){
                let myState = this.$store.state.optionsPane;
                myState.showOptions = false;
            }
        },
        computed:{
            showOptions(){

                return this.$store.state.optionsPane.showOptions;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .options-modal{
        position:fixed;
        left:0px;
        right:0px;
        top:0px;
        bottom:0px;
        background-color:rgba(0,0,0,.5);
        z-index: 100;
        .options-container{
            background:white;
            width: 90vw;
            margin:30px auto;
            padding:30px;
            border:solid #333 3px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            .flex-container{
                display:flex;
                flex-wrap: wrap;
                justify-content: space-between;
                .flex-item{
                    width: 250px;
                    text-align: center;

                }
            }
        }
    }
</style>