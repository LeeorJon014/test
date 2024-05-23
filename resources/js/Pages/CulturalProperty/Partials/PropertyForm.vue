<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Name of Cultural Property
                <i>(Pangalan ng Ari-Ariang Kultural)</i>
            </p>
            <p class="text-sm italic text-ronchi">
                Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay
                sa database ang mga pagbabagong naganap.
            </p>
        </header>

        <div
            class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30"
        >
            <div>
                <InputLabel
                    >Official Name <i>(Opisyal na Pangalan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="propertyValue.official_name"
                    v-model="propertyValue.official_name"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>
            <div class="mt-4">
                <InputLabel
                    >Common Name <i>(Karaniwang Pangalan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="propertyValue.common_name"
                    v-model="propertyValue.common_name"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>
            <div class="mt-4">
                <InputLabel
                    >Filipino Name <i>(Lokal na Pangalan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="propertyValue.filipino_name"
                    v-model="propertyValue.filipino_name"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>
            <div v-if="canSelectCompany" class="mt-4">
                <InputLabel>Relevant Interested Party</InputLabel>
                <select
                    v-model="selectedCompany"
                    class="mt-1 block w-full text-base border-gray-300 dark:border-gray-700 dark:bg-white dark:text-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    id=" type"
                >
                    <option
                        v-for="company in listOfCompanies"
                        :key="company.id"
                        class="optionclass bg-color-bg"
                        :value="company.id"
                    >
                        {{ company.name }}
                    </option>
                </select>
            </div>
            <div
                v-if="canEdit()"
                class="flex items-center justify-center mt-10"
            >
                <PrimaryButton type="submit"> Save </PrimaryButton>

                <SecondaryButton class="ml-2" type="">
                    Clear Selection
                </SecondaryButton>

                <SecondaryButton
                    id="backButton"
                    class="ml-2"
                    @click="redirectToInventory"
                >
                    Back
                </SecondaryButton>
            </div>
        </div>
    </form>
</template>

<script>
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        SecondaryButton,
        SelectInput,
    },
    props: {
        propertyData: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
        companyData: Object,
        listOfCompanies: Object,
        canSelectCompany: Boolean,
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        saveChanges() {
            this.$emit("save", {
                parentId: this.propertyData?.id,
                newData: {
                    ...this.propertyValue,
                    companyId: this.selectedCompany
                        ? this.selectedCompany.id || this.selectedCompany
                        : this.companyData.id,
                },
                updateRequestType: this.updateRequestType,
            });
        },
        setPropertyValue() {
            this.propertyValue = {
                common_name: this.propertyData?.common_name || "",
                filipino_name: this.propertyData?.filipino_name || "",
                official_name: this.propertyData?.official_name || "",
            };
        },
        setCompanyValue() {
            this.selectedCompany = this.companyData.id;
        },
        canEdit() {
            return (
                this.editPermissions.includes("updateAll") ||
                this.editPermissions.includes(this.updateRequestType)
            );
        },
    },
    created() {
        this.setPropertyValue();
        this.setCompanyValue();
    },
    data() {
        return {
            updateRequestType: "propertyName",
            propertyValue: {
                common_name: "",
                filipino_name: "",
                official_name: "",
            },
            selectedCompany: "",
        };
    },
};
</script>
