<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Ownership <i>(Pagmamay-ari)</i>
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
                <InputLabel>Owner <i>(May-ari)</i></InputLabel>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="f12OwnershipValue.owner_name"
                    v-model="f12OwnershipValue.owner_name"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Sex <i>(Biyolohikal na Pagkakakilanlan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="f12OwnershipValue.owner_sex"
                    v-model="f12OwnershipValue.owner_sex"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel>Administrator <i>(Tagapangasiwa)</i></InputLabel>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="f12OwnershipValue.administrator"
                    v-model="f12OwnershipValue.administrator"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel>Street Address <i>(Numero at Adres)</i></InputLabel>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="f12OwnershipValue.street_address"
                    v-model="f12OwnershipValue.street_address"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >City/Municipality <i>(Lungsod/Bayan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="f12OwnershipValue.city_municipality"
                    v-model="f12OwnershipValue.city_municipality"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel>Province <i>(Lalawigan)</i></InputLabel>
                <SelectInput
                    :options="selectProvince"
                    label="title"
                    placeholder="Select a Province"
                    :value="f12OwnershipValue.province"
                    v-model="f12OwnershipValue.province"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel>Region <i>(Rehiyon)</i></InputLabel>
                <SelectInput
                    :options="selectRegion"
                    label="title"
                    placeholder="Select a Region"
                    :value="f12OwnershipValue.region"
                    v-model="f12OwnershipValue.region"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Kind of Ownership
                    <i>(Klase ng Pagmamay-ari)</i></InputLabel
                >
                <SelectInput
                    :options="kindownership"
                    label="title"
                    placeholder="Select a Kind of Ownership"
                    :value="f12OwnershipValue.kind_of_ownership"
                    v-model="f12OwnershipValue.kind_of_ownership"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Public Accessibility
                    <i>(Napupuntahan ng Publiko)</i></InputLabel
                >
                <SelectInput
                    :options="publicaccess"
                    label="title"
                    placeholder="Select Public Accessibility"
                    :value="f12OwnershipValue.public_accessibility"
                    v-model="f12OwnershipValue.public_accessibility"
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
                newData: this.f12OwnershipValue,
                updateRequestType: this.updateRequestType,
            });
        },
        filterDropdownOptions() {
            this.f12OwnershipValue = {
                ...this.f12OwnershipValue,
                ...(this.f12OwnershipValue.province.hasOwnProperty("title") && {
                    province: this.f12OwnershipValue.province.title,
                }),
                ...(this.f12OwnershipValue.region.hasOwnProperty("title") && {
                    region: this.f12OwnershipValue.region.title,
                }),
                ...(this.f12OwnershipValue.kind_of_ownership.hasOwnProperty(
                    "title"
                ) && {
                    kind_of_ownership:
                        this.f12OwnershipValue.kind_of_ownership.title,
                }),
                ...(this.f12OwnershipValue.public_accessibility.hasOwnProperty(
                    "title"
                ) && {
                    public_accessibility:
                        this.f12OwnershipValue.public_accessibility.title,
                }),
            };
        },
        setf12OwnershipValue() {
            this.f12OwnershipValue = {
                owner_name: this.data?.owner_name || "",
                owner_sex: this.data?.owner_sex || "",
                street_address: this.data?.street_address || "",
                city_municipality:
                    this.data?.city_municipality || "",
                province: this.data?.province || "",
                administrator: this.data?.administrator || "",
                region: this.data?.region || "",
                kind_of_ownership:
                    this.data?.kind_of_ownership || "",
                public_accessibility:
                    this.data?.public_accessibility || "",
                is_Multi_Provincial:
                    this.data?.is_Multi_Provincial || "",
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
        this.setf12OwnershipValue();
    },
    data() {
        return {
            updateRequestType: "f12Ownership",
            f12OwnershipValue: {
                owner_name: "",
                owner_sex: "",
                street_address: "",
                city_municipality: "",
                province: "",
                administrator: "",
                region: "",
                kind_of_ownership: "",
                public_accessibility: "",
                is_Multi_Provincial: "",
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
            selectRegion: [
                { title: "Rehiyon I (Ilocos)" },
                { title: "Rehiyon II (Lambak Cagayan/Cagayan Valley)" },
                { title: "Rehiyon III (Gitnang Luzon/Central Luzon)" },
                { title: "Rehiyon IV-A (CALABARZON)" },
                { title: "Rehiyon IV-B (MIMAROPA)" },
                { title: "Rehiyon V (Bicol)" },
                { title: "Rehiyon VI (Kanlurang Bisayas/Western Visayas)" },
                { title: "Rehiyon VII (Gitnang Bisayas/Central Visayas)" },
                { title: "Rehiyon VIII (Silangang Bisayas/Eastern Visayas)" },
                {
                    title: "Rehiyon IX (Tangway ng Zamboanga/Zamboanga Peninsula)",
                },
                { title: "Rehiyon X (Hilagang Mindanao / Northern Mindanao)" },
                { title: "Rehiyon XI (Davao)" },
                { title: "Rehiyon XII (SOCCSKSARGEN)" },
                { title: "Rehiyon XIII (Caraga)" },
                {
                    title: "CAR (Rehiyong Administratibo ng Cordillera/Cordillera Administrative Region)",
                },
                {
                    title: "BARMM (Rehiyong Awtonomo ng Bangsamoro sa Muslim Mindanao/Bangsamoro Autonomous Region in Muslim Mindanao)",
                },
                { title: "NCR (Kalakhang Maynila/National Capital Region)" },
                {
                    title: "Higit sa isang Rehiyong Kinasasakupan (Multiple Regions)",
                },
                { title: "Hindi Angkop (Not Applicable)" },
                { title: "Buong Bansa (Nationwide)" },
                { title: "Ibayong Dagat (Overseas)" },
            ],
            kindownership: [
                { title: "Pribado (Private)" },
                { title: "Publiko (Public)" },
            ],
            publicaccess: [{ title: "Oo (Yes)" }, { title: "Hindi (No)" }],
        };
    },
};
</script>
