<template>
    <Head title="Create Company Form" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div class="max-w-3xl space-y-6">
                <header>
                    <h1 class="font-dm-sans text-5xl">Create Relevant Interested Party Form</h1>
                </header>

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="px-5 py-3 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
                        <p class="text-2xl font-semibold text-white">Create Relevant Interested Party Form</p>
                        <p class="text-sm italic text-ronchi">Please accomplish all fields and submit.</p>
                    </div>

                    <div class="px-5 pt-3 pb-5 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
                        <div>
                            <InputLabel for="full_name">Company</InputLabel>
                            <TextInput
                                v-model="form.company_name"
                                type="text"
                                name="company_name"
                                id="company_name"
                                class="mt-1 block w-full"
                                placeholder="Type company here"
                            />
                            <InputError class="mt-2" :message="form.errors.company_name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="province">Province</InputLabel>
                            <select
                                v-model="form.selectedProvince"
                                name="province"
                                id="province"
                                class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                                @change="handleProvinceCityChange"
                            >
                                <option value="" disabled>Select a province</option>
                                <option v-for="province in form.province" :value="province.provCode">{{ province.provDesc }}</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="city">City</InputLabel>
                            <select
                                v-model="form.selectedCity"
                                :options="filteredCities"
                                name="city"
                                id="city"
                                class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                            >
                                <option value="" disabled>Select a city</option>
                                <option v-for="city in filteredCities" :value="city.citymunDesc">{{ city.citymunDesc }}</option>                                            
                            </select>

                            <InputError class="mt-2" :message="form.errors.city" />
                        </div>
    <!--
                        <div class="md:col-span-3">
                            <label for="full_name">Municipality</label>
                            <input
                                v-model="form.municipality"
                                type="text"
                                name="municipality"
                                id="municipality"
                                class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                            />
                            <InputError class="mt-2" :message="form.errors.municipality" />
                        </div>
    -->
                        <div class="mt-4">
                            <InputLabel for="barangay">Barangay</InputLabel>
                            <TextInput
                                v-model="form.barangay"
                                type="text"
                                name="barangay"
                                id="barangay"
                                class="mt-1 block w-full"
                                placeholder="Type barangay here"
                            />
                            <InputError class="mt-2" :message="form.errors.barangay" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="street">Street Address</InputLabel>
                            <TextInput
                                v-model="form.street_address"
                                type="text"
                                name="street_address"
                                id="street_address"
                                class="mt-1 block w-full"
                                placeholder="Type street address here"
                            />
                            <InputError class="mt-2" :message="form.errors.barangay" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="country">Country</InputLabel>
                            <TextInput
                                v-model="form.country"
                                type="text"
                                name="country"
                                id="country"
                                readonly
                                class="mt-1 block w-full"
                            />
                        </div>

                        <div class="flex items-center justify-center mt-10">
                            <PrimaryButton>
                                Save
                            </PrimaryButton>

                            <SecondaryButton class="ml-2" @click="clearFields">
                                Clear Fields
                            </SecondaryButton>

                            <SecondaryButton id="backButton" class="ml-2"               @click="redirectToCompany">
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
import {watch} from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import ProvinceJson from '../../../data/refprovince.json';
import CityJson from '../../../data/refcitymun.json';
import { computed } from "vue";

const form = useForm({
    selectedCity: '',
    selectedProvince: '',

    company_name: '',
    street_address: '',
    barangay: '',
    province: ProvinceJson.RECORDS,
    cities: CityJson.RECORDS,
    country: 'Philippines',

    provinceDesc: null
});

//clear selection
const clearFields = () => {
  form.reset();
};

const redirectToCompany = () => {
    window.location.href = route(`${usePage().props.role}.company`);
};

const toast = useToast()

const handleProvinceCityChange = () => {
    console.log('Selected Province: ', form.selectedProvince);
    form.selectedCity = '';

    // Get provDesc for insertion on table
    if (form.selectedProvince) {
        const selectedProvince = form.province.find(province => province.provCode === form.selectedProvince);
        form.provinceDesc = selectedProvince ? selectedProvince.provDesc : null;
    }
    console.log('form.provinceDesc', form.provinceDesc);
};

const filteredCities = computed(() => {
  if (!form.selectedProvince) {
    return [];
  }
  return form.cities.filter(
    (city) => city.provCode === form.selectedProvince);
});


watch(() => usePage().props.status, flash => {
    let toastId = null;
    if (flash) {
        form.reset('company_name', 'street_address', 'barangay', 'selectedCity', 'selectedProvince')
        toastId = toast.info(flash)
    }
    if (toastId !== null) {
        setTimeout(() => toast.dismiss(toastId), 5000)
    }
}, {deep: true})


const submit = async () => {
  try {
    await form.post(route(`${usePage().props.role}.store.company`));
  } catch (error) {
    // Handle any network or unexpected errors here
    console.error('An error occurred:', error);
  }
};

</script>
