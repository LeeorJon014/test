<template>
    <Head title="Announcement" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div class="max-w-3xl space-y-6">
                <header>
                    <h1 class="font-dm-sans text-5xl"> {{ title }} </h1>
                </header>

                <div>
                    <img v-if="photo_url !== '/storage/'" :src="photo_url" alt="Preview" class="w-fit h-fit bg-cover bg-no-repeat bg-center">
                </div>

                <div v-html="sanitizeHtml(description)"></div>
        
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import DOMPurify from "dompurify";

export default {
    props: {
        announcement: Object,
        photo_url: String,
    },
    setup(props) {
        const { announcement, photo_url } = props;

        const title = ref(announcement.title);
        const description = ref(announcement.description);
        
        return {
            title,
            photo_url,
            description,
        };
    },
    methods: {
        // render html tags from db
        sanitizeHtml(content) {
            return DOMPurify.sanitize(content);
        },
    },

    components: {
        DashboardLayout,
    }

}

</script>