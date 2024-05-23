<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-left text-sm text-midnight-express leading-5 cursor-pointer hover:bg-mountain-meadow transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
