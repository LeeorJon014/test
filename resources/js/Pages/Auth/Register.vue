<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref, onMounted } from "vue";

const form = useForm({
    name: "",
    email: "",
    company_id: "",
    role_id: null,
    password: "",
    password_confirmation: "",
    terms: false,
});

const props = defineProps({
    api_token: String,
    roles: Array,
});

// const usersData = ref({});
// const roles = ref([]);

// const getCompanies = async () => {
//     try {
//         const response = await fetch(`/api/${usePage().props.role}/company`);
//         usersData.value = await response.json();
//         console.log(usersData.value);
//     } catch (error) {
//         console.error(error);
//     }
// };

// onMounted(getCompanies);
const usersData = ref([]);
const roles = ref([]);

const getAllCompanies = async () => {
    try {
        const response = await fetch(`/api/${usePage().props.role}/companies/all`);
        usersData.value = await response.json();
        console.log(usersData.value);
    } catch (error) {
        console.error(error);
    }
};

onMounted(getAllCompanies);

roles.value = props.roles;

const toast = useToast();
const submit = async () => {
    try {
        await form.post(route("register"), {
            onSuccess: () => {
                toast.success("Email verification sent");
                form.reset(
                    "name",
                    "email",
                    "password",
                    "password_confirmation"
                );
            },
            onError: () => {
                toast.error("Registration failed. Please try again.");
            },
        });

        // Optionally, you can include a loading message while the registration is in progress.
        toast.info("Registration in Progress");
    } catch (error) {
        console.error(error);
    }
};

const generatePassword = () => {
    const length = 12; // Define the length of the generated password
    const charset =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+"; // Define the character set for the password

    const password = Array.from({ length }, () =>
        charset.charAt(Math.floor(Math.random() * charset.length))
    ).join("");

    form.password = password;
    form.password_confirmation = password;

    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById(
        "password_confirmation"
    );
    if (passwordInput && confirmPasswordInput) {
        passwordInput.type = "text";
        confirmPasswordInput.type = "text";
    }
};
</script>

<template>
    <DashboardLayout>
        <Head title="Register"></Head>
        <div class="px-5 sm:px-10 py-5">
            <div class="max-w-3xl space-y-6">
                <header>
                    <h1 class="font-dm-sans text-5xl">Users Registration</h1>
                    <p class="mt-1 text-base text-midnight-express italic">
                        Register another user through this form.
                    </p>
                </header>

                <form @submit.prevent="submit">
                    <div
                        class="px-5 py-3 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30"
                    >
                        <p class="text-2xl font-semibold text-white">
                            Fill out the required information to reset your
                            password.
                        </p>
                        <p class="text-sm italic text-ronchi">
                            Make sure that the email you enter is valid and is
                            the one designated for your account.
                        </p>
                    </div>

                    <div
                        class="px-5 pt-3 pb-5 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30"
                    >
                        <div>
                            <InputLabel for="name" value="Name" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autocomplete="name"
                                placeholder="Type here"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="Type here"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <!-- <div>
                            <InputLabel for="company_name" value="Company" />
                            <select
                                v-model="form.company_id"
                                class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                            >
                                <option value="" disabled>
                                    Select a Company
                                </option>
                                <option
                                    v-for="company in usersData.data"
                                    :key="company.id"
                                    :value="company.id"
                                >
                                    {{ company.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_id"
                            />
                        </div> -->
                        <div>
                            <InputLabel for="company_name" value="Company" />
                            <select
                                v-model="form.company_id"
                                class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                            >
                                <option value="" disabled>
                                    Select a Company
                                </option>
                                <option
                                    v-for="company in usersData"
                                    :key="company.id"
                                    :value="company.id"
                                >
                                    {{ company.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.company_id"
                            />
                        </div>

                        <div>
                            <InputLabel for="roles" value="Role" />
                            <select
                                v-model="form.role_id"
                                class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                            >
                                <option value="" disabled>Select a role</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    {{ role.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.role_id"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />

                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Type here"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div class="flex items-center justify-end mt-2">
                            <PrimaryButton
                                type="button"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click.prevent="generatePassword"
                            >
                                Generate Password
                            </PrimaryButton>
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="password_confirmation"
                                value="Confirm Password"
                            />

                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Type here"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <div class="flex items-center justify-start mt-10">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Register
                            </PrimaryButton>

                            <Link :href="`/${$page.props.role}/users`">
                                <SecondaryButton class="ml-4 text-opacity-70">
                                    Back to User Page
                                </SecondaryButton>
                            </Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>
