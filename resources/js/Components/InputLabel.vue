<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block my-2 text-midnight-express font-normal text-base leading-4">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
