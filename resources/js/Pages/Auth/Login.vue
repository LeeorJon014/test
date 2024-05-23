<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});


const submit = () => {
    axios.get('/sanctum/csrf-cookie').then(response => {
        // Login...
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    });

};
</script>

<template>
    <GuestLayout>

        <Head title="Log in" />

        <!-- display success message once user verified their email -->
        <div v-if="$page.props.flash.message" class="mb-4 font-medium text-sm text-green-600">
            {{ $page.props.flash.message }}
        </div>

        <div class="my-20 py-20 flex flex-col self-center">
            <h2 class="font-dm-sans text-center text-carrot-orange text-4xl">Log In</h2>

            <form @submit.prevent="submit">
                <div class="mt-2 px-6 bg-hawkes-blue overflow-hidden sm:rounded-lg w-full">
                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                            autocomplete="current-password" />

                        <InputError class="mt-2" :message="form.errors.password" />

                        <!-- <Link v-if="canResetPassword" :href="route('password.request')"
                            class="flex items-center justify-end mt-2 underline text-sm text-gray-600 dark:text-gray-400 dark:hover:text-mountain-meadow rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        Forgot your password?
                        </Link> -->

                    </div>

                    <!-- 
                    <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                    </div>
        -->
                    <div class="flex items-center justify-center py-5">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Log in
                        </PrimaryButton>
                    </div>
                </div>
            </form>

            <!-- <Link class="flex items-center justify-center py-5 underline text-sm text-ronchi dark:hover:text-mountain-meadow ">
                Request an account
            </Link> -->
        </div>
    </GuestLayout>
</template>
