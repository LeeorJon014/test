<script>
import { inject } from 'vue'

export default {
    props: {
        title: String,
    },
    setup() {
        const selectedTitle = inject('selectedTitle')

        return {
            selectedTitle,
        }
    },
}

</script>

<template>
    <div class="tab-content" v-show="title == selectedTitle">
        <slot />
    </div>
</template>

<style>
.tab-content{
    margin-top: 0 !important;
}

</style>