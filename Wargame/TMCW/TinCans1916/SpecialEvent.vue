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
        transition: all 1s;
        opacity: 1;
    }


    .special-events {
        opacity: 1;
        z-index: 100;
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
        opacity: 0;
        margin-top:0px;
    }


</style>