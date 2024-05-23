<script>
import vSelect from 'vue-select';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';


export default {
    components: {
        vSelect,
        InputLabel,
        TextInput,
    },
    methods: {
        emitSelectedValue() {
            this.$emit('input', this.selectedValue);
        },
    },
    props: {
        label: String,
        options: {
            type: Array,
            required: true,
        },
        value: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            selectedValue: this.value || [], // Initialize selectedValue with the provided value prop
        };
    },
};
</script>

<template>
    <v-select v-model="selectedValue" :options="options" :label="label" :value="value" :searchable="false" :multiple="true"
        @update:modelValue="emitSelectedValue"
        class="mt-1 block w-full text-base border-gray-300 dark:border-gray-700 dark:bg-white dark:text-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
    </v-select>
</template>

<style> /* main.scss */
 @import 'vue-select/dist/vue-select.css';
</style>
