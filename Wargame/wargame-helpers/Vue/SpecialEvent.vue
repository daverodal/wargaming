<template>
        <div :style="{top:specialEvent.y + 'px', left: specialEvent.x + 'px'}" class="special-events specialHexesVP">
            <div class="s-e-container" v-if="specialEvent.text" v-html="specialEvent.text"></div>
        </div>
</template>

<script type="text/javascript">
    export default {
        props: ['specialEvent'],
        data: () => {
            return {}
        },
        mounted() {
        }
    }
</script>
<style  lang="scss">


    .social-events-enter-active{
        transition: all 9.5s;
        margin-top:-40px;
        opacity: 0;
    }


    .special-events {
        opacity: 0;
        z-index: 100;
        font-size:60px;
        position: absolute;
        color: white;
        background-color: transparent;
        pointer-events:none;
        i {
            font-size: 25px;
            transform: rotate(180deg);
            z-index: 1000;
        }
        .s-e-container{
            display:block;
            border:10px solid black;
            border-radius: 40px;
            span{
                padding: 5px;
                display:block;
                border-radius: 30px;
            }
        }
        &.specialHexesVP{
            .s-e-container{
                border:none;
            }

        }

    }
    .social-events-enter{
        opacity: 1;
        margin-top:0px;
    }


</style>