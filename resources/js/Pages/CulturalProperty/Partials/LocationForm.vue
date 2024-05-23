<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Location <i>(Lokasyon)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div class="mt-4">
                <InputLabel>Street Address <i>(Numero at Adres)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="locationValue.street_address"
                    v-model="locationValue.street_address" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Barangay <i>(Kasama mga Baryo, Sitio, o Purok kung mayroon)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="locationValue.barangay"
                    v-model="locationValue.barangay" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>City/Municipality <i>(Lungsod/Bayan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="locationValue.city_municipality"
                    v-model="locationValue.city_municipality" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Province <i>(Lalawigan)</i></InputLabel>
                <SelectInput :options="selectProvince" :value="locationValue.province" v-model="locationValue.province"
                    label="title" placeholder="Select a Province" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Region <i>(Rehiyon)</i></InputLabel>
                <SelectInput :options="selectRegion" :value="locationValue.region" v-model="locationValue.region"
                    label="title" placeholder="Select a Region" :disabled="!canEdit()" />
            </div>

            <div v-if="belongsToForm('neighbouring_places')" class="mt-4">
                <InputLabel>Neighbouring places where the intangible heritage is also being practiced, if applicable <i>(Mga
                        Karatig Lugar na Kinakikitaan ng Nasabing Pagsasapraktika ng Elemento, kung angkop)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="locationValue.neighbouring_places"
                    v-model="locationValue.neighbouring_places" placeholder="Type here" :disabled="!canEdit()" />
            </div>


            <div v-if="canEdit()" class="flex items-center justify-center mt-10">
                <PrimaryButton type="submit">
                    Save
                </PrimaryButton>

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
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
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
        formId: Number,
        locationData: {
            type: Object,
            default: () => ({})
        },
        editPermissions: Array
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        saveChanges() {
            this.filterDropdownOptions();

            this.$emit('save', {
                parentId: this.locationData?.id,
                newData: this.locationValue,
                updateRequestType: this.updateRequestType
            });
        },
        belongsToForm(formSectionName) {
            return this.formFields[this.formId].includes(formSectionName);
        },
        filterDropdownOptions() {
            this.locationValue = {
                ...this.locationValue,
                ...(this.locationValue.province.hasOwnProperty('title')) && {
                    province: this.locationValue.province.title
                },
                ...(this.locationValue.region.hasOwnProperty('title')) && {
                    region: this.locationValue.region.title
                },
            }
        },
        setLocationValue() {
            this.locationValue = {
                street_address: this.locationData?.street_address || '',
                barangay: this.locationData?.barangay || '',
                city_municipality: this.locationData?.city_municipality || '',
                province: this.locationData?.province || '',
                region: this.locationData?.region || '',
                neighbouring_places: this.locationData?.neighbouring_places || '',
            };
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setLocationValue();
    },
    data() {
        return {
            updateRequestType: "location",
            locationValue: {
                street_address: '',
                barangay: '',
                city_municipality: '',
                province: '',
                region: '',
                neighbouring_places: '',
            },
            formFields: {
                1: [],
                2: [],
                3: [
                    'neighbouring_places',
                ]
            },
            selectProvince: [
                { title: 'Higly Urbanized City' },
                { title: 'Multiple Provinces (Please indicate below)' },
                { title: 'Abra' },
                { title: 'Agusan del Norte' },
                { title: 'Aklan' },
                { title: 'Albay' },
                { title: 'Antique' },
                { title: 'Apayao' },
                { title: 'Aurora' },
                { title: 'Basilan' },
                { title: 'Bataan' },
                { title: 'Batanes' },
                { title: 'Batangas' },
                { title: 'Benguet' },
                { title: 'Biliran' },
                { title: 'Bohol' },
                { title: 'Bukidnon' },
                { title: 'Bulacan' },
                { title: 'Cagayan' },
                { title: 'Camarines Norte' },
                { title: 'Camarines Sur' },
                { title: 'Camiguin' },
                { title: 'Capiz' },
                { title: 'Catanduanes' },
                { title: 'Cavite' },
                { title: 'Cebu' },
                { title: 'Cotabato (North)' },
                { title: 'Davao de Oro' },
                { title: 'Davao del Norte' },
                { title: 'Davao del Sur' },
                { title: 'Davao Occidental' },
                { title: 'Davao Oriental' },
                { title: 'Dinagat Islands' },
                { title: 'Eastern Samar' },
                { title: 'Guimaras' },
                { title: 'Ifugao' },
                { title: 'Ilocos Norte' },
                { title: 'Ilocos Sur' },
                { title: 'Iloilo' },
                { title: 'Isabela' },
                { title: 'Kalinga' },
                { title: 'La Union' },
                { title: 'Laguna' },
                { title: 'Lanao del Norte' },
                { title: 'Lanao del Sur' },
                { title: 'Leyte' },
                { title: 'Maguindanao' },
                { title: 'Marinduque' },
                { title: 'Masbate' },
                { title: 'Misamis Occidental' },
                { title: 'Misamis Oriental' },
                { title: 'Mountain Province' },
                { title: 'Negros Occidental' },
                { title: 'Negros Oriental' },
                { title: 'Northern Samar' },
                { title: 'Nueva Ecija' },
                { title: 'Nueva Vizcaya' },
                { title: 'Occidental Mindoro' },
                { title: 'Oriental Mindoro' },
                { title: 'Palawan' },
                { title: 'Pampanga' },
                { title: 'Pangasinan' },
                { title: 'Quezon' },
                { title: 'Quirino' },
                { title: 'Rizal' },
                { title: 'Romblon' },
                { title: 'Samar (Western)' },
                { title: 'Sarangani' },
                { title: 'Siquijor' },
                { title: 'Sorsogon' },
                { title: 'South Cotabato' },
                { title: 'Southern Leyte' },
                { title: 'Sultan Kudarat' },
                { title: 'Sulu' },
                { title: 'Surigao del Norte' },
                { title: 'Surigao del Sur' },
                { title: 'Tarlac' },
                { title: 'Tawi-Tawi' },
                { title: 'Zambales' },
                { title: 'Zamboanga del Norte' },
                { title: 'Zamboanga del Sur' },
                { title: 'Zamboanga Sibugay' },
                { title: 'Metro Manila' },
                { title: 'Hindi Angkop (Not Applicable)' },
            ],
            selectRegion: [
                { title: 'Rehiyon I (Ilocos)' },
                { title: 'Rehiyon II (Lambak Cagayan/Cagayan Valley)' },
                { title: 'Rehiyon III (Gitnang Luzon/Central Luzon)' },
                { title: 'Rehiyon IV-A (CALABARZON)' },
                { title: 'Rehiyon IV-B (MIMAROPA)' },
                { title: 'Rehiyon V (Bicol)' },
                { title: 'Rehiyon VI (Kanlurang Bisayas/Western Visayas)' },
                { title: 'Rehiyon VII (Gitnang Bisayas/Central Visayas)' },
                { title: 'Rehiyon VIII (Silangang Bisayas/Eastern Visayas)' },
                { title: 'Rehiyon IX (Tangway ng Zamboanga/Zamboanga Peninsula)' },
                { title: 'Rehiyon X (Hilagang Mindanao / Northern Mindanao)' },
                { title: 'Rehiyon XI (Davao)' },
                { title: 'Rehiyon XII (SOCCSKSARGEN)' },
                { title: 'Rehiyon XIII (Caraga)' },
                { title: 'CAR (Rehiyong Administratibo ng Cordillera/Cordillera Administrative Region)' },
                { title: 'BARMM (Rehiyong Awtonomo ng Bangsamoro sa Muslim Mindanao/Bangsamoro Autonomous Region in Muslim Mindanao)' },
                { title: 'NCR (Kalakhang Maynila/National Capital Region)' },
                { title: 'Higit sa isang Rehiyong Kinasasakupan (Multiple Regions)' },
                { title: 'Hindi Angkop (Not Applicable)' },
                { title: 'Buong Bansa (Nationwide)' },
                { title: 'Ibayong Dagat (Overseas)' },
            ],
        };
    },
}

</script>

