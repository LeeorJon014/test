<template>
    <Head title="Edit Announcement Form" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div class="max-w-3xl space-y-6">
                <header>
                    <h1 class="font-dm-sans text-5xl">Edit Announcement Form</h1>
                </header>

                <form @submit.prevent="submit" enctype="multipart/form-data" class="mt-4">
                    <div class="px-5 py-3 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
                        <p class="text-2xl font-semibold text-white">Edit Announcement</p>
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
                                <img v-if="form.photo_url !== '/storage/'" :src="form.photo_url" alt="Preview" class="w-full h-full bg-cover bg-no-repeat bg-center">
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="about" value="Description" />
                            <div class="mt-2">
                                <ckeditor 
                                    v-model="form.description" 
                                    :editor="editorInstance" 
                                    id="about" 
                                    name="about" 
                                    rows="3" 
                                    class="block w-full rounded-lg text-base border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
                                </ckeditor>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        </div>
                        <input type="hidden" name="fileName" v-model="form.fileName">
                        <input type="hidden" name="fileType" v-model="form.fileType">


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

import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

//import  ClassicEditor  from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
//import { Essentials } from '@ckeditor/ckeditor5-essentials';
//import { Bold, Italic } from '@ckeditor/ckeditor5-basic-styles';
//import { Link } from '@ckeditor/ckeditor5-link';
//import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
//import { SourceEditing } from '@ckeditor/ckeditor5-source-editing';

const form = useForm({
    title: usePage().props.announcement.title,
    description: usePage().props.announcement.description,
    photo_url: usePage().props.photo_url,
    fileName: '',
});

//clear selection
const clearFields = () => {
  form.reset();
};

const redirectToAnnouncement = () => {
    window.location.href = route(`${usePage().props.role}.announcement`);
};

const handleFileChange = (event) => {
    const file = event.target.files[0];

    if (file) {
        // Extract file name and file type
        const fileName = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.photo_url = e.target.result;
            form.fileName = fileName; // Store file name in the form data
        };
        reader.readAsDataURL(file);
    }
};

/*
const editorInstance = ref(null);

onMounted(() => {
  ClassicEditor
    .create(document.querySelector('#about'), {
        plugins: [
            SourceEditing,
            Essentials,
            Bold,
            Italic,
            Link,
            Paragraph
            ],

        toolbar: {
            items: [
                'sourceEditing',
                'bold',
                'italic',
                'link',
                'undo',
                'redo'
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
*/

const editorInstance = ref(null);

onMounted(() => {
  ClassicEditor
    .create(document.querySelector('#about'), {
//        plugins: [SourceEditing], // Add SourceEditing to the plugins list
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
    if (flash) {
        toast.success(flash);
    }
    // clear usePage().props.status
    usePage().props.status = '';
});

const submit = async () => {
    try {
     await form.put(route(`${usePage().props.role}.update.announcement`, { id: usePage().props.announcement.id }),);
    } catch (error) {
        console.error('An error occurred:', error);
    }
};

</script>
