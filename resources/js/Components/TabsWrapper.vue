<script>
import { ref, provide } from 'vue';

export default {
    props: {
        id: {
            type: Number,
            required: true,
        },
        role: String,
    },
    setup(props, { slots }) {
        const tabTitles = ref(slots.default()
            .map((tab) => tab.props && tab.props.title)
            .filter((title) => title !== null)
        );
        const selectedTitle = ref(tabTitles.value[0])

        provide('selectedTitle', selectedTitle)

        const handleSelectedTitle = (title) => {
            if (title === 'Miscellaneous') {
                window.location.href = `/${props.role}/cultural-property/forms/${props.id}`;
            }
            else {
                selectedTitle.value = title;
            }
        }

        return {
            tabTitles,
            selectedTitle,
            handleSelectedTitle,
        }
    },
}

</script>

<template>
    <div class="tabs sm:w-7xl mx-auto pt-10 space-y-6">
        <div class="tabs-header text-lg flex flex-wrap justify-center sm:justify-stretch">
            <div class="tabs-title w-50 text-center px-4 py-2 bg-hawkes-blue rounded-tr-2xl hover:bg-mountain-meadow hover:text-white cursor-pointer transition duration-400 ease-out"
                v-for="title in tabTitles" :key="title" :class="{ selected: title == selectedTitle }"
                @click="handleSelectedTitle(title)">
                {{ title }}
            </div>
        </div>
        <slot />
    </div>
</template>

<style>
.tabs-header div.selected {
    background-color: #15223D;
    color: white;
    text-decoration: underline;
    text-underline-offset: 4px;
}
</style>