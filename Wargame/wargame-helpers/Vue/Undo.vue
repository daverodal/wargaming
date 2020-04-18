<template>
    <div id="VueTime">
        Time you are viewing:
        <div id="clickCnt">{{ currentClick }}</div>
        <div class="cool-box go-buttons">
            <div class="time-left col-xs-6">
                Cancel Undo<br>
                <div class="fancy-time-button " id="time-live">Go to present - cancel</div>

            </div>
            <div class="time-right col-xs-6">
                Perform Undo<br>
                <div class="fancy-time-button right" id="time-branch">Branch viewed time to present</div><br>
            </div>
            <div class="clear"></div>
        </div>


        <div class="cool-box">
            <div class="time-button-wrapper alpha col-xs-6">
                Back 1<br>
                <div class="fancy-time-button" id="click-back">click</div>
                <div class="fancy-time-button"  id="phase-back">phase</div>
                <div class="fancy-time-button"  id="player-turn-back">player turn</div>

            </div>
            <div class="time-button-wrapper col-xs-6">
                Forward 1<br>
                <div class="fancy-time-button" id="click-surge">click</div>
                <div class="fancy-time-button"  id="phase-surge">phase</div>
                <div class="fancy-time-button" id="player-turn-surge">player turn</div>

            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
    import {clickBack,phaseBack,playerTurnBack, clickSurge, phaseSurge, playerTurnSurge, timeBranch, timeLive} from "@markarian/wargame-helpers";

    export default {
        name: "Undo",
        methods: {
            clickBack(){
                clickBack();
            },
            clickSurge(){
                clickSurge();
            },
            phaseBack(){
                phaseBack();
            },
            phaseSurge(){
                phaseSurge();
            },
            playerTurnBack(){
                playerTurnBack();
            },
            playerTurnSurge(){
                playerTurnSurge();
            },
            timeBranch(){
                timeBranch();
                this.$root.undo = false;
            },
            timeLive(){
                timeLive();
                this.$root.undo = false;
            }
        },
        computed:{
            currentClick(){
                return this.$store.state.timeTravel.currentClick;
            }
        }
    }
</script>

<style lang="scss" scoped>

.text-center{
    text-align: center;
}

#VueTime {
    ul {
        margin: 0;
        padding: 0;
    }
    padding: 10px;
    font-weight: 500;
    .go-buttons{
        margin-bottom:20px;
    }
    .timetime-button-wrapper{
        &.alpha{
            border-right: 1px solid #333;
        }
        @extend .text-center;
    }
    background: white;
    width: 660px;
    cursor: move;
    background: rgb(255, 255, 255);
    border-radius: 10px;
    border: 2px solid black;
    box-shadow: 10px 10px 10px rgba(30, 30, 30, .85);
    position: absolute !important;
    z-index: 30;
    #phaseClicks {
        display: none;
        cursor: pointer;
        .newPhase {
            min-width: 10px;
            height: 40px;
            border-left: 1px solid red;
            display: inline-block;
            margin-bottom: 10px;
        }
        .newTick {
            text-align: center;
            margin-top: 10px;
            width: 15px;
            height: 10px;
            border-left: 1px solid black;
            display: inline-block;
            font-size: 10px;
            &.tickShim {
                width: 0px;
            }
            &:hover {
                background: #999;
                color: #fff;
            }
            &.activeTick {
                background-color: #666;
                a {
                    background-color: #666;
                    color: white;
                }
            }

        }
    }
    .time-button{
        min-width:50px;
    }
    .fancy-time-button{
        font-size:16px;
        display: inline-block;
        border: 5px solid gray;
        padding: 3px 5px;
        margin: 0 5px;
        border-radius: 10px;
        cursor: pointer;
    }
}

</style>