<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const dropdownOpen = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

//clear selection
const clearFields = () => {
  form.reset();
};

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
        <form @submit.prevent="updatePassword">
            <!-- rounded upper edges -->
            <div v-if="dropdownOpen">
                <div class="px-6 py-4 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
                    <div class="flex">
                        <span class="grow font-dm-sans text-lg text-white font-bold">Update Password</span>
                        <div class="flex cursor-pointer mt-1">
                            <span class="text-carrot-orange text-base font-semibold" @click="dropdownOpen = false"> Hide options </span>
                            <!-- icon for show options here -->
                            <!-- icon for show options here -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #F59211;transform: ;msFilter:;margin-left: 5px;">
                                <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.293 12.707L12 10.414l-4.293 4.293-1.414-1.414L12 7.586l5.707 5.707-1.414 1.414z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- rounded all edges -->
            <div v-else>
                <div class="px-6 py-4 bg-midnight-express rounded-xl border border-midnight-express border-opacity-30">
                    <div class="flex">
                        <span class="grow font-dm-sans text-lg text-white font-bold">Update Password</span>
                        <div class="flex cursor-pointer mt-1" @click="dropdownOpen = !dropdownOpen" :class="dropdownOpen ? 'hidden' : ''">
                            <span class="text-carrot-orange text-base font-semibold"> Show options </span>
                            <!-- icon for show options here -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="fill: #F59211;transform: ;msFilter:;margin-left: 5px;">
                                <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 14.414-5.707-5.707 1.414-1.414L12 13.586l4.293-4.293 1.414 1.414L12 16.414z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="dropdownOpen">
                <div class="px-6 py-4 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
                    <div>
                        <InputLabel for="current_password" value="Current Password" />

                        <TextInput
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                        />

                        <InputError :message="form.errors.current_password" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="password" value="New Password" />

                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirm Password" />

                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />

                        <InputError :message="form.errors.password_confirmation" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center mt-6">
                        <PrimaryButton :disabled="form.processing">
                            Save Settings
                        </PrimaryButton>

                        <SecondaryButton class="ml-2" @click="clearFields">
                                Restore Defaults
                        </SecondaryButton>

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                        </Transition>
                    </div>
                </div>
            </div>

        </form>
</template>
