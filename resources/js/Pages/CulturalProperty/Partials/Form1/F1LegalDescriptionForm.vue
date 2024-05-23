<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Legal Description <i>(Legal na Paglalarawan ng Ari-Arian)</i>
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
                    >Registry of Deeds <i>(Numero ng Titulo)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f1LegalDescriptionValue.registry_of_deeds"
                    v-model="f1LegalDescriptionValue.registry_of_deeds"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Barangay (with Barrio, Sitio, or Purok when applicable)
                    <i
                        >(Kasama ang Baryo, Sitio, o Purok kung mayroon)</i
                    ></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f1LegalDescriptionValue.legal_barangay"
                    v-model="f1LegalDescriptionValue.legal_barangay"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >City / Municipality <i>(Lungsod / Bayan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f1LegalDescriptionValue.legal_city"
                    v-model="f1LegalDescriptionValue.legal_city"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel>Province <i>(Lalawigan)</i></InputLabel>
                <SelectInput
                    :options="selectProvince"
                    label="title"
                    placeholder="Select a Province"
                    :value="f1LegalDescriptionValue.legal_province"
                    v-model="f1LegalDescriptionValue.legal_province"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Approximate area of property in square meters
                    <i
                        >(Tinatayang Sukat ng Ari-Arian sa Metro Kwadrado)</i
                    ></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f1LegalDescriptionValue.approximate_area"
                    v-model="f1LegalDescriptionValue.approximate_area"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div
                v-if="canEdit()"
                class="flex items-center justify-center mt-10"
            >
                <PrimaryButton> Save </PrimaryButton>

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
        SelectInput,
        SecondaryButton,
    },
    props: {
        data: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.edit.inventory`, { id: this.data?.id });
        },
        saveChanges() {
            this.filterDropdownOptions();

            this.$emit("save", {
                parentId: this.data?.id,
                newData: this.f1LegalDescriptionValue,
                updateRequestType: this.updateRequestType,
            });
        },
        filterDropdownOptions() {
            this.f1LegalDescriptionValue = {
                ...this.f1LegalDescriptionValue,
                ...(this.f1LegalDescriptionValue.legal_province.hasOwnProperty(
                    "title"
                ) && {
                    legal_province:
                        this.f1LegalDescriptionValue.legal_province.title,
                }),
            };
        },
        setF1LegalDescriptionValue() {
            this.f1LegalDescriptionValue = {
                approximate_area:
                    this.data?.approximate_area || "",
                legal_barangay:
                    this.data?.legal_barangay || "",
                legal_city: this.data?.legal_city || "",
                legal_province:
                    this.data?.legal_province || "",
                registry_of_deeds:
                    this.data?.registry_of_deeds || "",
            };
        },
        canEdit() {
            return (
                this.editPermissions.includes("updateAll") ||
                this.editPermissions.includes(this.updateRequestType)
            );
        },
    },
    created() {
        this.setF1LegalDescriptionValue();
    },
    data() {
        return {
            updateRequestType: "f1LegalDescription",
            f1LegalDescriptionValue: {
                registry_of_deeds: "",
                legal_barangay: "",
                legal_city: "",
                legal_province: "",
                approximate_area: "",
            },
            selectProvince: [
                { title: "Higly Urbanized City" },
                { title: "Multiple Provinces (Please indicate below)" },
                { title: "Abra" },
                { title: "Agusan del Norte" },
                { title: "Aklan" },
                { title: "Albay" },
                { title: "Antique" },
                { title: "Apayao" },
                { title: "Aurora" },
                { title: "Basilan" },
                { title: "Bataan" },
                { title: "Batanes" },
                { title: "Batangas" },
                { title: "Benguet" },
                { title: "Biliran" },
                { title: "Bohol" },
                { title: "Bukidnon" },
                { title: "Bulacan" },
                { title: "Cagayan" },
                { title: "Camarines Norte" },
                { title: "Camarines Sur" },
                { title: "Camiguin" },
                { title: "Capiz" },
                { title: "Catanduanes" },
                { title: "Cavite" },
                { title: "Cebu" },
                { title: "Cotabato (North)" },
                { title: "Davao de Oro" },
                { title: "Davao del Norte" },
                { title: "Davao del Sur" },
                { title: "Davao Occidental" },
                { title: "Davao Oriental" },
                { title: "Dinagat Islands" },
                { title: "Eastern Samar" },
                { title: "Guimaras" },
                { title: "Ifugao" },
                { title: "Ilocos Norte" },
                { title: "Ilocos Sur" },
                { title: "Iloilo" },
                { title: "Isabela" },
                { title: "Kalinga" },
                { title: "La Union" },
                { title: "Laguna" },
                { title: "Lanao del Norte" },
                { title: "Lanao del Sur" },
                { title: "Leyte" },
                { title: "Maguindanao" },
                { title: "Marinduque" },
                { title: "Masbate" },
                { title: "Misamis Occidental" },
                { title: "Misamis Oriental" },
                { title: "Mountain Province" },
                { title: "Negros Occidental" },
                { title: "Negros Oriental" },
                { title: "Northern Samar" },
                { title: "Nueva Ecija" },
                { title: "Nueva Vizcaya" },
                { title: "Occidental Mindoro" },
                { title: "Oriental Mindoro" },
                { title: "Palawan" },
                { title: "Pampanga" },
                { title: "Pangasinan" },
                { title: "Quezon" },
                { title: "Quirino" },
                { title: "Rizal" },
                { title: "Romblon" },
                { title: "Samar (Western)" },
                { title: "Sarangani" },
                { title: "Siquijor" },
                { title: "Sorsogon" },
                { title: "South Cotabato" },
                { title: "Southern Leyte" },
                { title: "Sultan Kudarat" },
                { title: "Sulu" },
                { title: "Surigao del Norte" },
                { title: "Surigao del Sur" },
                { title: "Tarlac" },
                { title: "Tawi-Tawi" },
                { title: "Zambales" },
                { title: "Zamboanga del Norte" },
                { title: "Zamboanga del Sur" },
                { title: "Zamboanga Sibugay" },
                { title: "Metro Manila" },
                { title: "Hindi Angkop (Not Applicable)" },
            ],
        };
    },
};
</script>
