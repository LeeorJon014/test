<template>
    <DashboardLayout>
        <div class="font-public-sans px-5 sm:px-10 py-5">
            <h1 class="font-dm-sans text-5xl">Upload Inventory</h1>

            <div class="w-7xl mx-auto pt-5 space-y-6">
                <div class="div-rectangle sm:p-5">
                    <form
                        @submit.prevent="submitForm"
                        enctype="multipart/form-data"
                    >
                        <div>
                            <InputLabel
                                for="type"
                                value="Select Type"
                                class="text-white"
                            />
                            <select
                                v-model="selectedType"
                                class="mt-3 py-2 block w-full border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                                id="type"
                            >
                                <option
                                    class="bg-color-bg"
                                    value=""
                                    disabled
                                    selected
                                >
                                    --choose a type--
                                </option>
                                <option
                                    class="optionclass bg-color-bg"
                                    value="1"
                                >
                                    Type 1
                                </option>
                                <option
                                    class="optionclass bg-color-bg"
                                    value="2"
                                >
                                    Type 2
                                </option>
                                <option
                                    class="optionclass bg-color-bg"
                                    value="3"
                                >
                                    Type 3
                                </option>
                            </select>
                        </div>

                        <div class="mt-7">
                            <InputLabel
                                for="type"
                                value="Upload Excel File"
                                class="text-white"
                            />
                            <input
                                type="file"
                                id="file"
                                name="file"
                                @change="handleFileInput"
                                class="relative mt-3 w-full h-10.5 leading-9 overflow-hidden text-sm text-midnight-express border-2 border-solid border-midnight-express bg-hawkes-blue rounded-l-md rounded-r-md focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow cursor-pointer file:mr-5 file:py-2 file:px-6 file:border-0 file:text-sm file:font-medium file:bg-carrot-orange file:text-white hover:file:cursor-pointer hover:file:bg-orange hover:file:bg-opacity-70"
                            />
                        </div>
                        <div v-if="canEdit()" class="mt-7">
                            <InputLabel
                                for="type"
                                value="Relevant Interested Party"
                                class="text-white"
                            />
                            <select
                                v-model="selectedCompany"
                                class="mt-3 py-2 block w-full border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow"
                                id="type"
                            >
                                <option
                                    class="bg-color-bg"
                                    value=""
                                    disabled
                                    selected
                                >
                                    --choose a relevant interested party--
                                </option>
                                <option
                                    v-for="company in companies"
                                    :key="company.id"
                                    class="optionclass bg-color-bg"
                                    :value="company.id"
                                >
                                    {{ company.name }}
                                </option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-14">
                            <SecondaryButton class="mr-2" type="">
                                Clear Selection
                            </SecondaryButton>

                            <PrimaryButton
                                class="mr-2"
                                type="submit"
                                :disabled="isDisabled"
                            >
                                Upload File
                            </PrimaryButton>

                            <!-- <SecondaryButton>
                                <a :href="`/${role}/view.inventory`" class="mx-4 btn">
                                    Back
                                </a>
                            </SecondaryButton> -->

                            <!-- <SecondaryButton id="backButton" class="ml-2"               @click="redirectToInventory">
                                Back
                            </SecondaryButton> -->
                            <SecondaryButton
                                id="backButton"
                                class="ml-2"
                                @click="redirectToInventory"
                            >
                                Back
                            </SecondaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import axios from "axios";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { useToast } from "vue-toast-notification";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    setup() {
        const toast = useToast();
        return { toast };
    },
    components: {
        PrimaryButton,
        InputLabel,
        DashboardLayout,
        SecondaryButton,
        TextInput,
    },
    props: {
        companies: Object,
        canSelectCompany: Boolean,
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        canEdit() {
            return this.canSelectCompany;
        },
        handleFileInput(event) {
            this.file = event.target.files[0];
        },
        submitForm() {
            const formData = this.createFormData();

            axios
                .post(
                    `/api/${
                        usePage().props.role
                    }/cultural-property/upload-inventory`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                )
                .then((response) => {
                    this.responseData = response.data;
                    this.toast.success("File uploaded successfully.");
                })
                .catch((error) => {
                    console.log(error);
                    this.toast.error("File upload failed.");
                });

            this.clearFormData();
        },
        createFormData() {
            let formData = new FormData();

            const formFields = {
                type: this.selectedType,
                file: this.file,
                companyId: this.selectedCompany
                    ? this.selectedCompany
                    : this.companies[0].id,
            };

            for (const [key, value] of Object.entries(formFields)) {
                formData.append(key, value);
            }

            return formData;
        },
        clearFormData() {
            this.selectedType = "";
            this.file = null;
        },
    },
    computed: {
        isDisabled() {
            const isTypeEmpty =
                !this.selectedType || this.selectedType.trim() === "";
            const isFileEmpty = !this.file;
            const isCompanyEmpty =
                this.canSelectCompany && !this.selectedCompany;

            return isTypeEmpty || isFileEmpty || isCompanyEmpty;
        },
    },
    data() {
        return {
            selectedType: "",
            selectedCompany: "",
            file: null,
            responseData: null,
            validationErrors: [],
        };
    },
};
</script>

<style>
.div-rectangle {
    background: #15223d;
    border-radius: 10px;
}

.option-class {
    cursor: pointer;
    padding: 10rem;
}
</style>
