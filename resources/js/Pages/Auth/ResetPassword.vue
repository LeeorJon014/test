<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

let showPasswordHint = ref(false);

// Hide the password hint when the user starts typing
const hidePasswordHint = () => {
    showPasswordHint.value = false;
};

//check if new password = confirm new password
const passwordsMatch = ref(true);

watch(() => {
    passwordsMatch.value = form.password === form.password_confirmation;
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('current_password', 'password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="bg-color-bg min-h-screen flex flex-col justify-start items-center">
        <div class="flex bg-midnight-express py-6 pl-8 w-full">
            <div class="w-3/5 text-left">
                <h2 class="font-dm-sans text-ronchi text-7xl">Change Password</h2>
                <p class="text-hawkes-blue mt-2 italic">
                    Looking to log in the TALAPAMANA System?
                    <Link class="text-ronchi italic underline underline-offset-4">Log in here.</Link>
                </p>
            </div>

            <div class="m-auto mr-20">
                <img class="w-64" src="/assets/img/talapamana_logo.png" alt="" />
            </div>

        </div>

        <Head title="Reset Password" />

        <div class="mt-20 bg-midnight-express sm:rounded-lg w-auto p-px">
            <p class="mt-6 font-semibold text-xl text-left text-hawkes-blue px-8">Fill out the required information to reset
                your password</p>
            <p class="text-left text-xs text-ronchi px-8">Make sure that the email you enter is valid and is the on
                designated for your account.</p>

            <form @submit.prevent="submit" class="mt-4 px-6 py-2 bg-hawkes-blue">


                <div class="mt-4">
                    <InputLabel for="current_password" value="Current Password" />
                    <TextInput id="current_password" type="password" class="mt-1 block w-full"
                        v-model="form.current_password" required autocomplete="current-password" />
                    <InputError class="mt-2" :message="form.errors.current_password" />
                </div>
                <div class="mt-4">
                    <InputLabel for="password" value="New Password" />
                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="new-password" @focus="showPasswordHint = true" @blur="showPasswordHint = false"
                        @input="hidePasswordHint" />

                    <div v-if="showPasswordHint" class="text-sm italic text-red-color">
                        *Password should be at least 10 characters long. <br />
                        *Password should have an Uppercase, Lowercase, Numbers, and/or Symbols.
                    </div>

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirm New Password" />
                    <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                        v-model="form.password_confirmation" required autocomplete="new-password" />

                    <!-- Display a message if passwords don't match -->
                    <div v-if="!passwordsMatch && form.password_confirmation !== ''" class="text-red-color">
                        Password do not match.
                    </div>

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                <div class="flex items-center justify-center py-5">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Change Password
                    </PrimaryButton>
                </div>


            </form>
        </div>

        <div class="flex items-center justify-center mt-4">
            <Link class="py-5 text-sm text-carrot-orange dark:hover:text-mountain-meadow font-semibold cursor-pointer">
            Report a problem
            </Link>

            <div class="border border-midnight-express border-opacity-60 h-3 mx-2"></div>

            <Link class="py-5 text-sm text-carrot-orange dark:hover:text-mountain-meadow font-semibold cursor-pointer">
            Help
            </Link>

            <div class="border border-midnight-express border-opacity-60 h-3 mx-2"></div>

            <Link class="py-5 text-sm text-carrot-orange dark:hover:text-mountain-meadow font-semibold cursor-pointer">
            Homepage
            </Link>
        </div>

    </div>
</template>