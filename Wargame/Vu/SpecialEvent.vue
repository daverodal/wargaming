<template>
        <div :style="{top:specialEvent.y + 'px', left: specialEvent.x + 'px'}" class="special-events specialHexesVP">
            <div v-if="specialEvent.text"><span v-html="specialEvent.text"></span></div>
            <i class="fa fa-adjust" v-else></i>
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


    .social-events-move{
        transition: all 1s;
        display:block !important;
    }
    .social-events-enter-active{
        transition: all 1.5s;
        opacity: 0;
        margin-top:-40px;
        display: block !important;
    }


    .special-events {
        display:none;
        font-size:60px;
        position: absolute;
        color: white;
        background-color: transparent;
        background-color: #59cb5e;
        pointer-events:none;
        i {
            font-size: 25px;
            transform: rotate(180deg);
            z-index: 1000;
        }

    }
    .social-events-enter{
        opacity: 1;
        margin-top:0px;
        display:block !important;
    }


</style>