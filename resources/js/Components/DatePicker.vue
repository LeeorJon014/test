<script>
export default {
    data() {
        return {
            selectedDate: '' || this.initialDate,
        };
    },
    props: {
        initialDate: String,
        disabled: Boolean,
    },
    methods: {
        submitDate() {
            this.$emit('selectDate', this.selectedDate);
        },
    },
};
</script>

<template>
    <div class="flex items-center space-x-4">
        <input v-model="selectedDate" @input="submitDate"
            class="mt-1 block w-full bg-white rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
            type="date" :disabled="disabled" />
    </div>
</template>