<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import { watch, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    companyName: {
        type: String,
    },
    streetAddress: {
        type: String,
    },
    barangay: {
        type: String,
    },
    city: {
        type: String,
    },
    municipality: {
        type: String,
    },
    province: {
        type: String,
    },
});

const dropdownOpen = ref(false);
const user = usePage().props.auth.user;
const companyName = usePage().props.company_name;
const streetAddress = usePage().props.street_address;
const barangay = usePage().props.barangay;
const city = usePage().props.city;
const municipality = usePage().props.municipality;
const province = usePage().props.province;

const form = useForm({
    name: user.name,
    email: user.email,
    contact_person: user.contact_person,
    contact_number: user.contact_number,
    company_name: companyName,
    street_address: streetAddress,
    barangay: barangay,
    city: city,
    municipality: municipality,
    province: province,
});

//clear selection
const clearFields = () => {
  form.reset();
};

const toast = useToast()

watch(() => usePage().props.status, flash => {
    if (flash) {
        toast.success(flash);
    }
    usePage().props.status = '';
});
</script>

<template>
        <form @submit.prevent="form.patch(route(`${$page.props.role}.profile.update`))">
            <!-- rounded upper edges -->
            <div v-if="dropdownOpen">
                <div class="px-6 py-4 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
                    <div class="flex">
                        <span class="grow font-dm-sans text-lg text-white font-bold">Update Profile Information</span>
                        <div class="flex cursor-pointer mt-1">
                            <span class="text-carrot-orange text-base font-semibold" @click="dropdownOpen = false"> Hide options </span>
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
                        <span class="grow font-dm-sans text-lg text-white font-bold">Update Profile Information</span>
                        <div class="flex cursor-pointer mt-1" @click="dropdownOpen = !dropdownOpen" :class="dropdownOpen ? 'hidden' : ''">
                            <span class="text-base text-carrot-orange font-semibold"> Show options </span>
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
                    <div class="grid grid-cols-2 gap-3">
                        <div class="order-1">
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="order-2">
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                autocomplete="username"
                            />

                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Added Company, Address, Email, Contact Person, Contact Number -->
                        <div class="order-3">
                            <InputLabel for="company_name" value="Company Name" />
                            <TextInput
                                id="company_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.company_name"
                                disabled
                            />

                            <InputError class="mt-2" :message="form.errors.company_name" />
                        </div>

                        <div class="order-4">
                            <InputLabel for="street" value="Street" />
                            <TextInput
                                id="street"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.street_address"
                                disabled
                            />
                        </div>

                        <div class="order-5">
                            <InputLabel for="barangay" value="Barangay" />
                            <TextInput
                                id="barangay"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.barangay"
                                disabled
                            />
                        </div>

                        <div class="order-6">
                            <InputLabel for="city" value="City" />
                            <TextInput
                                id="city"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.city"
                                disabled
                            />
                        </div>

                        <div class="order-7">
                            <InputLabel for="municipality" value="Municipality" />
                            <TextInput
                                id="municipality"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.municipality"
                                disabled
                            />
                        </div>
                        
                        <div class="order-8">
                            <InputLabel for="province" value="Province" />
                            <TextInput
                                id="province"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.province"
                                disabled
                            />
                        </div>

                        <div class="order-9">
                            <InputLabel
                                for="contact_person"
                                value="Name of Contact Person"
                            />

                            <TextInput
                                id="contact_person"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contact_person"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.contact_person"
                            />
                        </div>

                        <div class="order-10">
                            <InputLabel for="contact_number" value="Contact Number" />

                            <TextInput
                                id="contact_number"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contact_number"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.contact_number"
                            />
                        </div>

                        <div v-if="mustVerifyEmail && user.email_verified_at === null">
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                Your email address is unverified.
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="
                                        underline
                                        text-sm text-gray-600
                                        dark:text-gray-400
                                        hover:text-gray-900
                                        dark:hover:text-gray-100
                                        rounded-md
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-offset-2
                                        focus:ring-indigo-500
                                        dark:focus:ring-offset-gray-800
                                    "
                                >
                                    Click here to re-send the verification email.
                                </Link>
                            </p>

                            <div
                                v-show="status === 'verification-link-sent'"
                                class="
                                    mt-2
                                    font-medium
                                    text-sm text-green-600
                                    dark:text-green-400
                                "
                            >
                                A new verification link has been sent to your email address.
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center mt-6">
                        <PrimaryButton :disabled="form.processing">
                            Save Settings
                        </PrimaryButton>

                        <SecondaryButton class="ml-2" @click="clearFields">
                                Restore Defaults
                        </SecondaryButton>

                        <Transition
                            enter-from-class="opacity-0"
                            leave-to-class="opacity-0"
                            class="transition ease-in-out"
                        >
                            <p
                                v-if="form.recentlySuccessful"
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                    
                </div>
            </div>
        </form>
</template>
