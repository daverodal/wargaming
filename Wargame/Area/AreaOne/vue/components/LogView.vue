<template>
    <div>
        View Log: <button @click="open = !open">+-</button>
        <div v-if="open">
            <ol>
                <li v-for="turn in getLog">
                    <ol>
                        <li v-for="line in turn">
                            {{line}}
                        </li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "LogView",
        data: ( ) => {
            return {
                open: false
            }
        },
        computed: {
            ...mapGetters(['getLog'])
        }

    }
</script>

<style lang="scss" scoped>
    ol{
        padding-inline-start: 20px;
    }
</style>