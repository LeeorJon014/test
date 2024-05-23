<template>
    <Head title="Create Announcement Form" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div class="max-w-3xl space-y-6">
                <header>
                    <h1 class="font-dm-sans text-5xl">Create Announcement Form</h1>
                </header>

                <form @submit.prevent="submit" enctype="multipart/form-data" class="mt-4">
                    <div class="px-5 py-3 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
                        <p class="text-2xl font-semibold text-white">Create Announcement</p>
                        <p class="text-sm italic text-ronchi">Please accomplish all fields and submit.</p>
                    </div>

                    <div class="px-5 pt-3 pb-5 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
                        <div>
                            <InputLabel for="full_name">Title</InputLabel>

                            <TextInput
                                v-model="form.title"
                                type="text"
                                name="full_name"
                                id="full_name"
                                class="mt-1 block w-full"
                                placeholder="Type title here"
                            />

                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="mt-4">
                            <!-- Announcement Picture -->
                            <InputLabel for="photo_url" value="Announcement Picture" />
                            <input 
                                class=" relative w-full h-10.5 leading-9 overflow-hidden 
                                text-base text-midnight-express border border-midnight-express border-opacity-50 bg-white rounded-l-md rounded-r-md 
                                focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow cursor-pointer
                                file:mr-5 file:py-2 file:px-6 file:border-0
                                file:text-base file:font-medium file:bg-carrot-orange file:text-white
                                hover:file:cursor-pointer hover:file:bg-orange hover:file:bg-opacity-70" 
                                type="file" name="photo_url" id="photo_url" 
                                accept=".jpg, .jpeg, .png"
                                @change="handleFileChange"
                            />

                            <div class="w-48 h-48 mx-auto mt-5 shadow-md">
                                <img v-if="form.photo_preview" :src="form.photo_preview" alt="Preview" class="w-full h-full bg-cover bg-no-repeat bg-center">
                            </div>

                            <InputError class="mt-2" :message="form.errors.photo_url" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="about" value="Description" />
                            <div class="mt-2">
                                <ckeditor 
                                    v-model="form.description" 
                                    :editor="editor" 
                                    id="about" 
                                    name="about" 
                                    rows="3" 
                                    class="block w-full rounded-lg text-base border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
                                </ckeditor>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        </div>


                        <div class="flex items-center justify-center mt-10">
                            <PrimaryButton>
                                Save
                            </PrimaryButton>

                            <SecondaryButton class="ml-2" @click="clearFields">
                                Clear Fields
                            </SecondaryButton>

                            <SecondaryButton
                                id="backButton"
                                class="ml-2"
                                @click="redirectToAnnouncement"
                            >
                                Back
                            </SecondaryButton>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { useToast } from "vue-toast-notification";
import InputError from '@/Components/InputError.vue';
import {watch,ref, onMounted} from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';


const form = useForm({
    title: '',
    description: '',
    photo_url: '',
});

// clear selection
const clearFields = () => {
  form.title = '';
  form.description = '';
  form.photo_url = ''; // or null, depending on your requirements
  form.photo_preview = '';
};

const redirectToAnnouncement = () => {
    window.location.href = route(`${usePage().props.role}.announcement`);
};


const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.photo_url = file;

    if(file) {
        
        const reader = new FileReader();
        reader.onload = (e) => {
            form.photo_preview = e.target.result;
        };
        reader.readAsDataURL(file);

    }
};

const editorInstance = ref(null);

onMounted(() => {
  ClassicEditor
    .create(document.querySelector('#about'), {
//       plugins: [SourceEditing], // Add SourceEditing to the plugins list
        toolbar: {
            items: [
                'undo', 'redo',
//                '|', 'sourceEditing',
                '|', 'heading',
                '|', 'bold', 'italic',
                '|', 'link', 'insertTable', 'mediaEmbed',
                '|', 'bulletedList', 'numberedList', 'outdent', 'indent'
            ]
        },
    })
    .then(editor => {
        editor.setData(form.description);
        editorInstance.value = editor;

        editor.model.document.on('change:data', () => {
            form.description = editor.getData();
        });
    })
    .catch(error => {
        console.error(error);
    });
});

const toast = useToast()

watch(() => usePage().props.status, flash => {
    let toastId = null;
    if (flash) {
        form.reset('title', 'description', 'photo_preview')
        toastId = toast.info(flash)
    }
    // if (toastId !== null) {
    //     setTimeout(() => toast.dismiss(toastId), 5000)
    // }
}, {deep: true})
  
const submit = async () => {
  try {
    await form.post(route(`${usePage().props.role}.store.announcement`));
  } catch (error) {
    // Handle any network or unexpected errors here
    console.error('An error occurred:', error);
  }
};
</script>
