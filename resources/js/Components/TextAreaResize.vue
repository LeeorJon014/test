<script>
export default {
    props: {
        value: String, // Step 1: Define a prop named 'value' to receive the initial value (database).
    },
    data() {
        return {
            text: this.value, // Step 4: Initialize 'text' with the prop value
        };
    },
    watch: {
        value(newValue) {
            this.text = newValue; // Step 5: Watch for changes in the 'value' prop and update 'text'
        },
    },
    methods: {
        resizeTextarea() {
            this.$refs.textarea.style.height = "auto";
            this.$refs.textarea.style.height = this.$refs.textarea.scrollHeight + "px";
        },
    },
};
</script>

<template>
    <!-- Step 2: Use v-model to bind the input value to 'text' -->
    <!-- Step 3 (@input="resizeTextarea"): Listen for input events to adjust the textarea -->
    <textarea v-model="text" ref="textarea" @input="resizeTextArea"
        class="bg-white rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
    </textarea>
</template>