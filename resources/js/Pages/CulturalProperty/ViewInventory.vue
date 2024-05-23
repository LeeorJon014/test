<template>
    <Head title="Cultural Property Table" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <h1 class="font-dm-sans text-5xl">Cultural Property Table</h1>

            <form @submit.prevent="filterForm">
                <!-- PROPERTY NAME FILTER -->
                <div
                    class="flex relative mt-4 bg-midnight-express p-7 rounded-tl-xl rounded-tr-xl"
                >
                    <div class="absolute left-10 top-0 mt-10">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>

                    <input
                        type="search"
                        v-model="searchQuery"
                        class="w-full bg-white pl-8 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                        placeholder="Type search keyword/s here"
                        aria-label="Search"
                        aria-describedby="button-addon1"
                    />

                    <!--Search button-->
                    <FormButton class="ml-5"> Search </FormButton>
                </div>

                <div class="bg-hawkes-blue p-5 rounded-bl-xl rounded-br-xl">
                    <div>
                        <span class="font-dm-sans text-lg font-bold"
                            >Filter Options
                        </span>
                        <span
                            class="text-carrot-orange float-right cursor-pointer font-semibold"
                            @click="dropdownOpen = !dropdownOpen"
                            :class="dropdownOpen ? 'hidden' : ''"
                        >
                            Show options
                        </span>
                    </div>

                    <!-- LOCATION FILTER-->
                    <div v-show="dropdownOpen">
                        <div class="mt-3 mb-5">
                            <InputLabel>Region</InputLabel>
                            <v-select
                                v-model="selectedRegion"
                                :options="regions"
                                label="regDesc"
                                @input="handleProvinceCityChange"
                                placeholder="Select a Region"
                                class="bg-white rounded-lg text-m border border-midnight-express border-opacity-50 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                            />
                        </div>

                        <div class="mb-5">
                            <InputLabel class="text-dark">Province</InputLabel>
                            <v-select
                                v-model="selectedProvinces"
                                :options="filteredProvinces"
                                label="provDesc"
                                @input="handleCityChange"
                                placeholder="Select a Province"
                                class="bg-white rounded-lg text-m border border-midnight-express border-opacity-50 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                            />
                        </div>

                        <div>
                            <InputLabel class="text-dark">City</InputLabel>
                            <v-select
                                v-model="selectedCities"
                                :options="filteredCities"
                                label="citymunDesc"
                                placeholder="Select a City"
                                class="bg-white rounded-lg text-m border border-solid border-midnight-express border-opacity-50 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                            />
                        </div>

                        <div class="flex items-center justify-center mt-6">
                            <SecondaryButton class="mr-2" @click="clearFields">
                                Clear Selection
                            </SecondaryButton>

                            <PrimaryButton @click="filterForm">
                                Apply Filter
                            </PrimaryButton>
                        </div>

                        <div class="flex justify-end">
                            <span
                                class="text-carrot-orange mt-5 cursor-pointer font-semibold"
                                @click="dropdownOpen = !dropdownOpen"
                                >Hide options</span
                            >
                        </div>
                    </div>
                </div>
            </form>

            <!-- cultural properties table -->
            <div class="relative overflow-x-auto pt-7">
                <div class="my-3">
                    <span class="text-2xl font-semibold leading-tight"
                        >Results</span
                    >
                    <span class="text-md float-right">
                        Showing {{}}-{{ }} of {{}} results</span
                    >
                </div>

                <div class="overflow-x-auto">
                    <div
                        class="inline-block min-w-full border border-midnight-express border-opacity-30 border-b-0 rounded-tl-xl rounded-tr-xl overflow-hidden"
                    >
                        <table>
                            <thead class="bg-midnight-express text-ronchi">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Cultural Property ID
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        UUID
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Form ID
                                    </th>
                                    <th
                                        scope="col"
                                        class="w-1/6 px-5 py-3 text-left text-sm"
                                    >
                                        Property Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="w-1/5 px-5 py-3 text-left text-sm"
                                    >
                                        Location
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Submitter
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Company
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Validation Status
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-5 py-3 text-left text-sm"
                                    >
                                        Edit Inventory
                                    </th>
                                    <th 
                                    v-if="isPermittedToDelete"
                                    scope="col" class="px-5 py-3 text-left text-sm">
                                        Delete Inventory
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- display cultural properties data per page -->
                                <tr
                                    class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30"
                                    v-for="culturalProperty in culturalPropertiesData.data"
                                    :key="pageNumber + '-' + culturalProperty.id"
                                >
                                    <td class="px-5 py-3">
                                        {{ culturalProperty.id }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ culturalProperty.uuid }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ culturalProperty.form_id }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{
                                            culturalProperty?.property_name
                                                ?.official_name
                                        }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{
                                            concatenateAddress(
                                                culturalProperty.location
                                            )
                                        }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ culturalProperty.submitter.name }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ culturalProperty.company?.name }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span :class="getStatusClass()">
                                            {{
                                                getStatusText(
                                                    culturalProperty.is_Validated
                                                )
                                            }}
                                        </span>
                                        <!-- Dynamic dropdown based on currentUserRole -->
                                        <template style="display: block">
                                            <div>
                                                <button
                                                    @click="
                                                        openModal(
                                                            culturalProperty.id,
                                                            culturalProperty
                                                                .property_name
                                                                .official_name,
                                                            getStatusText(
                                                                culturalProperty.is_Validated
                                                            ),
                                                            culturalProperty.validation_started_at
                                                        )
                                                    "
                                                    class="inline-flex items-center px-4 py-2 bg-white border border-midnight-express border-opacity-40 rounded-xl font-semibold text-xs text-midnight-express hover:bg-mountain-meadow transition ease-in-out duration-150"
                                                    v-if="!isRelevantInterestedParty"
                                                    >
                                                    Update Status
                                                </button>
                                                
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-5 py-3">
                                        <a
                                            :href="
                                                route(
                                                    `${$page.props.role}.edit.inventory`,
                                                    { id: culturalProperty.id }
                                                )
                                            "
                                            class="inline-flex items-center px-4 py-2 bg-white border border-midnight-express border-opacity-40 rounded-xl font-semibold text-xs text-midnight-express hover:bg-mountain-meadow transition ease-in-out duration-150"
                                        >
                                            {{
                                                $page.props.currentUserRole ===
                                                    "administrative-service-officer" ||
                                                $page.props.currentUserRole ===
                                                    "registry-coordinator"
                                                    ? "View"
                                                    : "Edit"
                                            }}
                                        </a>
                                    </td>
                                    <td 
                                    v-if="isPermittedToDelete"
                                    class="px-5 py-3">
                                        <div>
                                            <button
                                                class="inline-flex items-center px-4 py-2  bg-white border border-midnight-express border-opacity-40 rounded-xl font-semibold text-xs text-midnight-express hover:bg-red-600 transition ease-in-out duration-150"
                                                @click="openDeletionModal(culturalProperty.id)">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Update Modal-->
            <status-update-modal
                :options="modalOptions"
                :is-open="modalOpen"
                :currentStatus="
                    selectedPropertyStatus
                "
                :name="culturalPropertyName"
                :currentRole="getUserRole()"
                :validationStarted="
                    validationStartedAt
                "
                @selectOption="
                    handleOptionSelect
                "
                @modalClosed="
                    handleModalClosed
                "
                @close="closeModal"
            ></status-update-modal>
            <!--Update Modal end-->

            <div class="flex justify-end my-3">
                <SecondaryButton class="mr-3">
                    Download Table Data
                </SecondaryButton>

                <SecondaryButton> Email Table Data </SecondaryButton>
            </div>

            <!-- Laravel Vue Pagination -->
            <div class="grid place-items-center pt-5 border-0">
                <TailwindPagination
                    :data="culturalPropertiesData"
                    @pagination-change-page="loadPage"
                    :limit="10"
                    :keepLength="true"
                />
            </div>

            <!-- Modal -->
                <div
                    :class="`modal ${!open ? 'opacity-0 pointer-events-none' : ''}
                    z-50 fixed w-full h-full top-0 left-0 flex items-center justify-center`"
                >
                    <div @click="closeDeleteModal"/>
                    <div class="z-50 w-11/12 mx-auto overflow-y-auto bg-midnight-express rounded-lg overflow-hidden shadow-xl transform transition-all-container md:max-w-md">
                        <div
                            class="absolute top-0 right-0 z-50 flex flex-col items-center mt-4 mr-4 text-sm text-white cursor-pointer modal-close"
                        >
                        </div>
                        <!-- Add margin if you want to see some of the overlay behind the modal -->
                        <div class="px-6 py-4 text-left modal-content">
                            <!-- Title -->
                            <div class="flex items-center justify-between pb-3">
                                <p class="text-2xl font-dm-sans font-semibold text-white">
                                    Delete Cultural Property?
                                </p>
                                <div class="z-50 cursor-pointer modal-close" @click="closeDeleteModal">
                                    <svg
                                        class="text-black fill-current"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 18 18"
                                    >
                                        <path
                                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <!-- Body -->
                            <p class ="text-base text-ronchi leading-4">Are you sure do you want continue with this decision? If yes, state your reason below.</p>
                            <textarea v-model="reasonInput" maxlength="200" class="bg-white rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow mt-1 block w-full pr-2" type="text" modelvalue="-" placeholder="Type here"
                            @input="updateCharacterCount"></textarea>
                            <div class="inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-400">
                                {{ reasonInput ? reasonInput.length : 0 }}/200
                            </div>
                            <p v-if="reasonInput.length >= 200" class="text-red-500 text-sm mt-1">Maximum length reach. Can't put more text anymore.</p>
                            <!-- Footer -->
                            <div class="flex justify-end pt-2">
                                <button
                                    class="inline-flex items-center px-4 py-2 text-indigo-500 bg-transparent rounded-lg hover:bg-gray-100 hover:text-indigo-400 focus:outline-none"
                                    @click="closeDeleteModal"
                                >
                                    Close
                                </button>

                                <button
                                    class="inline-flex items-center px-4 py-2  bg-white border border-midnight-express border-opacity-40 rounded-xl font-semibold text-xs text-midnight-express hover:bg-red-600 transition ease-in-out duration-150"
                                    @click="deleteCulturalProperty(culturalPropertyId, reasonInput)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Modal end -->

        </div>
    </DashboardLayout>
</template>

<style>
/* main.scss */
@import "vue-select/dist/vue-select.css";

.green-text {
    color: green;
}

.red-text {
    color: red;
}

.text-dark {
    color: rgb(3 7 18);
}
</style>

<script>
import axios from "axios";
import vSelect from "vue-select";
import { ref, onMounted, nextTick, } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormButton from "@/Components/FormButton.vue";
import StatusUpdateModal from "@/Components/StatusUpdateModal.vue";
import RegionJson from "../../../data/refregion.json";
import ProvinceJson from "../../../data/refprovince.json";
import CityJson from "../../../data/refcitymun.json";
import { useToast } from "vue-toast-notification";
import { usePage } from "@inertiajs/vue3";
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const dropdownOpen = ref(false);


export default {
    setup() {
        const culturalPropertiesData = ref({});
        const searchQuery = ref("");
        const dropdownOpen = ref(false);
        const userRole = ref("");
        const toast = useToast();
        let statusConstants = {}; // set as let because it is a static data
        const currentPage = ref(1);

        const getResults = async (page = 1, url = null) => {
            try {
                if (!url) {
                    url = `/api/${
                        usePage().props.role
                    }/cultural-property/view-inventory?page=${page}`;
                }
                const response = await axios.get(url);
                culturalPropertiesData.value = response.data.culturalProperties; // changed response.data to include culturalProperties because I changed the value returned by the controller from $request to 'response'
                statusConstants.value = response.data.statusConstants;
            } catch (error) {
                console.error(error);
            }
        };

        onMounted(() => {
            getResults();
            userRole.value = usePage().props.role;
        });

        const userRoleContains = (role) => {
            const currentUserRole = usePage().props.currentUserRole;
            return currentUserRole.includes(role);
        };

        const getUserRole = () => {
            return usePage().props.currentUserRole;
        };

        return {
            culturalPropertiesData,
            searchQuery,
            getResults,
            dropdownOpen,
            userRoleContains,
            userRole,
            toast,
            statusConstants,
            getUserRole,
            currentPage,
        };
    },
    methods: {
        // Methods for modal
        openModal(id, name, status, validationStarted) {
            this.modalOpen = true;
            this.culturalPropertyId = id;
            this.culturalPropertyName = name;
            this.selectedPropertyStatus = status;
            this.validationStartedAt = validationStarted;
            //console.log("culturalPropertyId: " + this.culturalPropertyId);
            // Set options based on user role
            if (this.userRoleContains("administrative-service-officer")) {
                this.modalOptions = [
                    { label: "Pending", value: "0" },
                    { label: "Compliant", value: "1" },
                    { label: "Non-Compliant", value: "2" },
                ];
            } else if (this.userRoleContains("registry-coordinator")) {
                this.modalOptions = [
                    { label: "Compliant", value: "1" },
                    { label: "Review", value: "3" },
                ];
            } else if (
                this.userRoleContains("cultural-registry-data-officer")) {
                this.modalOptions = [
                    { label: "Review", value: "3" },
                    { label: "Accessioning", value: "4" },
                ];
            } else if (this.userRoleContains("precup-head")) {
                this.modalOptions = [
                    { label: "Accessioning", value: "4" },
                    { label: "Public Validation", value: "5" },
                    { label: "Published in Site", value: "6" },
                ];
            } else if (
                this.userRoleContains("geographic-information-systems-analyst")) {
                this.modalOptions = [
                    { label: "Published in Site", value: "6" },
                    { label: "Processing for MAPAMANA", value: "7" },
                    { label: "Published in MAPAMANA", value: "8" },
                ];
            } else if (this.userRoleContains("super-administrator")) {
                this.modalOptions = [
                    { label: "Pending", value: "0" },
                    { label: "Compliant", value: "1" },
                    { label: "Non-Compliant", value: "2" },
                    { label: "Review", value: "3" },
                    { label: "Accessioning", value: "4" },
                    { label: "Public Validation", value: "5" },
                    { label: "Published in Site", value: "6" },
                    { label: "Processing for MAPAMANA", value: "7" },
                    { label: "Published in MAPAMANA", value: "8" },
                ];
            } else if (this.userRoleContains("invalid-slug")) {
                this.modalOptions = [];
            }
            // Add more options based on other roles...
        },
        updateCulturalPropertyInDatabase(optionValue) {
            // Construct payload to send to the backend
            const payload = {
                status: optionValue.value, // Pass the selected option value
            };

            // Make the API call to update the cultural property in the database
            axios
                .post(
                    `/api/${this.userRole}/cultural-property/update-status/${this.culturalPropertyId}`,
                    payload
                )
                .then((response) => {
                    // Update successful
                    this.toast.success(response.data.message);
                })
                .catch((error) => {
                    // Update failed
                    if (error.response.data.message) {
                        this.toast.error("Status failed: " + error.response.status + " - " + error.response.data.message);
                    } else if (error.response.data.error) {
                        this.toast.error("Status failed: " + error.response.status + " - " + error.response.data.error);
                    } else {
                        this.toast.error("Updating status failed: " + error);
                    }
                });
        },
        handleOptionSelect(optionValue) {
            // Here you can call the method to update the database with the selected option value
            this.updateCulturalPropertyInDatabase(optionValue);
        },
        closeModal() {
            this.modalOpen = false;
        },
        handleModalClosed() {
            // Call loadPage method to refresh data when modal is closed
            this.loadPage(this.currentPage);
        },
        getStatusText(statusCode) {
            // Get status text from statusConstants
            return Object.keys(this.statusConstants.value).find(
                (key) => this.statusConstants.value[key] === statusCode
            );
        },
        getStatusClass() {
            return {
                "font-semibold": true,
            };
        },
        closeDeleteModal() {
            this.open = false; // Close the modal
            this.resetReasonInput(); // Reset the reason input field
        },
        resetReasonInput() {
            this.reasonInput = ''; // Reset the reason input field
        },
        updateCharacterCount() {
            // Update the character count dynamically
            this.characterCount = this.reasonInput ? this.reasonInput.length : 0;
        },
        openDeletionModal(id) {
            //console.log("openModal id: "+id);
            // Open the modal
            this.open = true;
            // Set the cultural property ID
            this.culturalPropertyId = id;
            // Reset the deletion reasoning
            this.deletionReason = '';
        },
        deleteCulturalProperty(id, reason) {
            this.culturalPropertyId = id;
            if (reason.length > 200) {
                return this.toast.error("Reasoning is too long.");
            }
            this.deletionReason = reason;
            // Construct payload to send to the backend
            const payload = {
                reason: this.deletionReason
            };

            // Make the API call to delete the cultural property
            axios.post(`/api/${this.$page.props.role}/cultural-property/view-inventory/${this.culturalPropertyId}`,  payload)
                .then(response => {
                    // Delete successful
                    this.toast.success("Deletion Successful");
                    this.closeDeleteModal();
                    this.loadPage(this.currentPage);
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.message) {
                        // Display the error message returned from the server
                        this.closeDeleteModal();
                        this.toast.error(error.response.data.message);
                    } else {
                        // Handle other types of errors
                        this.closeDeleteModal();
                        this.toast.error("An error occurred while processing the request");
                    }
                });
        },
        concatenateAddress(location) {
            const fields = [
                location.street_address,
                location.barangay,
                location.city_municipality,
                location.province,
                location.region,
            ];
            return fields.filter((field) => field !== null).join(", ");
        },
        handleProvinceCityChange() {
            this.selectedProvinces = "";
            this.selectedCities = "";
        },
        handleCityChange() {
            this.selectedCities = "";
        },
        createFilterURL(page = 1) {
            let url = `/api/${
                usePage().props.role
            }/cultural-property/view-inventory`;
            const queryParams = new URLSearchParams();

            // Set the default viewing to the first page of the data when a filtered search occurs.
            queryParams.append("page", page);

            if (this.searchQuery) {
                queryParams.append("property_name", this.searchQuery);
            }

            if (this.selectedRegion) {
                queryParams.append("region", this.selectedRegion.regDesc);
            }

            if (this.selectedProvinces) {
                queryParams.append("province", this.selectedProvinces.provDesc);
            }

            if (this.selectedCities) {
                queryParams.append("city", this.selectedCities.citymunDesc);
            }

            return (url += `?${queryParams.toString()}`);
        },


        filterForm() {
            this.getResults(1, this.createFilterURL());
        },

        clearFields() {
            this.searchQuery = "";
            this.selectedRegion = "";
            this.selectedProvinces = "";
            this.selectedCities = "";
            this.getResults();
        },


        loadPage(pageNumber) {
            this.currentPage = pageNumber;
            let url;
            if (
                this.searchQuery ||
                this.selectedRegion ||
                this.selectedProvinces ||
                this.selectedCities
            ) {
                url = this.createFilterURL(pageNumber);
            } else {
                url = `/api/${
                    usePage().props.role
                }/cultural-property/view-inventory?page=${pageNumber}`;
            }
            return this.getResults(pageNumber, url);
            // return this.getResults(pageNumber, this.createFilterURL(pageNumber));
        },
    },
    // "Watcher function (on change)
    // Observe if there's a value, and if there is one, execute real-time changes.
    computed: {
        filteredProvinces() {
            if (!this.selectedRegion) {
                return [];
            }
            return this.provinces.filter(
                (province) => province.regCode === this.selectedRegion.regCode
            );
        },
        filteredCities() {
            if (!this.selectedProvinces) {
                return [];
            }
            return this.cities.filter(
                (city) => city.provCode === this.selectedProvinces.provCode
            );
        },
        isRelevantInterestedParty() {
            if (usePage().props.currentUserRole === 'relevant-interested-party'){
                return true;
            }
        },
        isPermittedToDelete(){
            if (usePage().props.currentUserRole === 'super-administrator' ||
                usePage().props.currentUserRole === 'relevant-interested-party'
            ){
                return true;
            }
        },
    },
    components: {
        DashboardLayout,
        TailwindPagination,
        SecondaryButton,
        InputLabel,
        vSelect,
        PrimaryButton,
        FormButton,
        StatusUpdateModal,
    },
    data() {
        return {
            selectedRegion: "",
            selectedProvinces: "",
            selectedCities: "",
            regions: RegionJson.RECORDS,
            provinces: ProvinceJson.RECORDS,
            cities: CityJson.RECORDS,
            modalOpen: false,
            modalOptions: [],
            culturalProperties: [],
            culturalPropertyId: null,
            statusConstants: {},
            currentStatus: "",
            culturalPropertyName: null,
            selectedPropertyStatus: null,
            validationStartedAt: null,
            reason: '',
            deletionReason: '',
            open: false,
            reasonInput: '',
            characterCount: 0,
        };
    },
};
</script>
