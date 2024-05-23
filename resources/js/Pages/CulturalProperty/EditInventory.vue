<template>
    <Head title="Edit Inventory" />
    <DashboardLayout>
        <!-- HEADER -->
        <div class="flex border-b border-midnight-express">
            <!-- <Link :href="'/cultural-property/view-inventory'" class="float-right">
            <PrimaryButton>
                <span class="mx-4">Back to View Inventory</span>
            </PrimaryButton>
            </Link> -->

            <!-- green space -->
            <div class="w-10 bg-mountain-meadow"></div>

            <!-- edit inv title -->
            <div class="w-3/5 flex flex-col items-center bg-midnight-express">
                <div class="h-2/3 pt-10">
                    <p class="font-dm-sans font-semibold text-6xl text-white">Edit Inventory</p>
                </div>

                <!-- helpful FAQ modal -->
                <div class="h-1/2">
                    <p class="text-white cursor-pointer">modal part</p>
                </div>
            </div>

            <!-- mahalagang paalala paragraphs -->
            <div class="w-full bg-hawkes-blue">
                <div class="p-5">
                    <p class="italic">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                        ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                    <br />
                    <p><strong>Mahalagang panuto:</strong> Alalahanin mag-<strong>save</strong> para mailagay sa database
                        ang mga pagbabagong nailagay sa form.
                        Para sa ibang pang mga katanungan ukol sa paggamit ng website, tignan ang mga <span
                            class="text-ronchi underline underline-offset-4">FAQs (Frequently Asked Questions).</span></p>
                </div>
            </div>
        </div>

        <div class="px-5 sm:px-10 py-5">
            <h1 class="text-2xl font-semibold" v-if="!loading">{{ propertyName }}</h1>

            <!-- mahalagang paalala paragraphs -->
            <div class="w-full bg-hawkes-blue">
                <div class="p-5">
                    <p class="italic">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                        ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                    <br />
                    <p><strong>Mahalagang panuto:</strong> Alalahanin mag-<strong>save</strong> para mailagay sa database
                        ang mga pagbabagong nailagay sa form.
                        Para sa ibang pang mga katanungan ukol sa paggamit ng website, tignan ang mga <span
                            class="text-ronchi underline underline-offset-4">FAQs (Frequently Asked Questions).</span></p>
                </div>
            </div>
        </div>

        <div class="px-5 sm:px-10 py-5">
            <TabsWrapper v-if="!loading" :id="culturalPropertyData.id" :role="userRole">
                <Tab title="Property">
                    <PropertyForm :propertyData="culturalPropertyData.property_name"
                        :companyData="culturalPropertyData.company" :listOfCompanies="companies"
                        :canSelectCompany="canSelectCompany" :editPermissions="editPermissions"
                        @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Location">
                    <LocationForm :formId="culturalPropertyData.form_id" :locationData="culturalPropertyData.location"
                        :editPermissions="editPermissions" @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Description">
                    <DescriptionForm :formId="culturalPropertyData.form_id"
                        :descriptionData="culturalPropertyData.description" :editPermissions="editPermissions"
                        @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Declaration">
                    <DeclarationForm :formId="culturalPropertyData.form_id"
                        :declarationData="culturalPropertyData.declaration" :editPermissions="editPermissions"
                        @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Significance">
                    <SignificanceForm :formId="culturalPropertyData.form_id"
                        :significanceData="culturalPropertyData.significance" :editPermissions="editPermissions"
                        @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Documentation and Validation">
                    <SubmitterForm :formId="culturalPropertyData.form_id" :submitterData="culturalPropertyData.submitter"
                        :editPermissions="editPermissions" @save="updateCulturalProperty" />
                </Tab>

                <Tab title="Miscellaneous" />
            </TabsWrapper>
            <p v-else>Loading...</p>
        </div>
    </DashboardLayout>
</template>

<script>
import axios from 'axios';
import { Link, Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import TabsWrapper from '@/Components/TabsWrapper.vue';
import Tab from '@/Components/Tab.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PropertyForm from './Partials/PropertyForm.vue';
import LocationForm from './Partials/LocationForm.vue';
import DescriptionForm from './Partials/DescriptionForm.vue';
import DeclarationForm from './Partials/DeclarationForm.vue';
import SignificanceForm from './Partials/SignificanceForm.vue';
import SubmitterForm from './Partials/SubmitterForm.vue';
import { useToast } from "vue-toast-notification";
import { usePage } from '@inertiajs/vue3';


export default {
    setup() {
        const toast = useToast();
        const userRole = usePage().props.role;

        return { toast, userRole }
    },
    data() {
        return {
            culturalPropertyData: null, // Initialize with null or empty data
            loading: true, // Initialize a loading state
        };
    },
    methods: {
        getCulturalProperty() {
            axios.get(`/api/${this.userRole}/cultural-property/edit-inventory/${this.id}/1`).then((response) => {
                // Stop loading screen when data is now available.
                this.culturalPropertyData = response.data;
                this.stopLoading();
            }).catch(error => {
                // Set loading to false and handle errors.
                this.stopLoading();
                console.error('Error fetching data:', error);
            });
        },
        updateCulturalProperty(payload) {
            let { parentId, childId = null, newData, updateRequestType } = payload;

            childId = {
                ...childId,
                cultural_property: this.culturalPropertyData.id,
            };

            axios.post(`/api/${this.userRole}/cultural-property/edit-inventory/${parentId}`, {
                // with underscores left: camelCase
                form_id: this.culturalPropertyData.form_id,
                child_id: childId,
                data: newData,
                update_request_type: updateRequestType,
            })
                .then(() => {
                    // Fetch new data and reload the page.
                    this.getCulturalProperty();
                    this.stopLoading();
                    this.toast.success('Inventory updated successfully.');
                })
                .catch(() => {
                    this.stopLoading();
                    this.toast.error('Updating inventory failed.');
                });
        },
        stopLoading() {
            return this.loading = false;
        }
    },
    created() {
        this.getCulturalProperty();
    },
    props: {
        id: Number,
        propertyName: String,
        editPermissions: Array,
        companies: Object,
        canSelectCompany: Boolean,
    },
    components: {
        DashboardLayout,
        TabsWrapper,
        Tab,
        PropertyForm,
        LocationForm,
        DescriptionForm,
        DeclarationForm,
        SignificanceForm,
        SubmitterForm,
        PrimaryButton,
        Link,
    },
}


</script>
