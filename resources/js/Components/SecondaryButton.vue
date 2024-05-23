<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-5 py-2 bg-white border border-midnight-express border-opacity-40 rounded-xl font-semibold text-sm text-midnight-express hover:bg-mountain-meadow transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
