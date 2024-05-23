<template>
    <DashboardLayout>
        <!-- HEADER -->
        <div class="flex border-b border-midnight-express">

            <!-- green space -->
            <div class="w-10 bg-mountain-meadow"></div>

            <!-- edit inv title -->
            <div class="w-3/5 flex flex-col items-center bg-midnight-express">
                <div class="h-2/3 pt-10">
                    <p class="font-dm-sans font-semibold text-6xl text-white">Edit Inventory</p>
                </div>

                <!-- helpful FAQ modal -->
                <div class="h-1/2">
                    <span class="text-ronchi text-3xl text-center">Miscellaneous </span>
                    <span class="text-white cursor-pointer whitespace-normal">mp</span>
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
            <div v-if="!loading && componentsLoaded">
                <div class="flex flex-wrap gap-0">
                    <button class="hover:bg-mountain-meadow hover:text-white cursor-pointer transition duration-400 ease-out"
                        @click="selectComponent(component)" 
                        v-for="component in loadedComponents" 
                        :key="component.key"
                        :class="{ 'py-2': true, 'px-4': true, 'text-center': true, 'bg-hawkes-blue': selectedComponent !== component, 'text-underline-offset': selectedComponent === component, 'tabs-header': selectedComponent === component, 'rounded-tr-2xl': true }"
                    >
                        {{ component.title }}
                    </button>
                </div>
                <div class="tab-content" v-if="selectedComponent">
                    <component :is="selectedComponent.key" 
                            :data="culturalPropertyData[selectedComponent.dataKey]" 
                            :formId="culturalPropertyData.form_id"
                            :editPermissions="editPermissions" 
                            @save="updateCulturalProperty" />
                </div>
            </div>
            <div v-else>Loading...</div>
        </div>
    </DashboardLayout>
</template>

<script>
import axios from 'axios';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useToast } from "vue-toast-notification";
import { usePage } from '@inertiajs/vue3';

export default {
    setup() {
        const toast = useToast();
        return { toast }
    },
    data() {
        return {
            culturalPropertyData: null,
            loading: true,
            componentsLoaded: false,
            selectedComponent: null,
            loadedComponents: [],
            componentsToLoad: [
                { key: 'F12CF', path: './Partials/Form12/F12CF.vue', formName: 'f12ClassificationResponse', title: 'Classification', dataKey: 'f12_classification_response', propName: 'f12ClassificationData'},
                { key: 'F1LegalDescriptionForm', path: './Partials/Form1/F1LegalDescriptionForm.vue', formName: 'f1LegalDescription' , title: 'Legal Description of Property', dataKey: 'f1_legal_description', propName: 'f1LegalDescriptionData'},
                { key: 'F1CurrentUseForm', path: './Partials/Form1/F1CurrentUseForm.vue', formName: 'f1CurrentUse', title: 'Current Use', dataKey: 'f1_current_use', propName: 'f1CurrentUseData' },
                { key: 'F12OwnershipForm', path: './Partials/Form12/F12OwnershipForm.vue', formName: 'f12Ownership', title: 'Ownership', dataKey: 'f12_ownership', propName: 'f12OwnershipData' },
                { key: 'F2ReferenceForm', path: './Partials/Form2/F2ReferenceForm.vue', formName: 'f2ReferenceAndInformant', title: 'Major Bibliographic References and Key Informants', dataKey: 'f2_reference_and_informant', propName: 'f2ReferenceData' },
                { key: 'F2StoryHeritageForm', path: './Partials/Form2/F2StoryHeritageForm.vue', formName: 'f2StoryAndHeritage', title: 'Stories or Intangible Heritage', dataKey: 'f2_story_and_heritage', propName: 'f2StoryHeritageData' },
                { key: 'F3CulturalBearersForm', path: './Partials/Form3/F3CulturalBearersForm.vue', formName: 'f3CulturalBearer', title: 'Community or Cultural Bearers', dataKey: 'f3_cultural_bearer', propName: 'f3CulturalBearersData' },
                { key: 'F3RelatedDomainsForm', path: './Partials/Form3/F3RelatedDomainsForm.vue', formName: 'f3RelatedDomain', title: 'Related Domains', dataKey: 'f3_related_domain', propName: 'f3RelatedDomainsData' },
                { key: 'F3DescriptionForm', path: './Partials/Form3/F3DescriptionForm.vue', formName: 'f3Description', title: 'Description', dataKey: 'f3_description', propName: 'f3DescriptionData' },
                { key: 'F3MeasuresForm', path: './Partials/Form3/F3MeasuresForm.vue', formName: 'f3SafeguardingMeasures', title: 'Safeguarding Measures', dataKey: 'f3_safeguarding_measures', propName: 'f3SafeguardingMeasureData' },
            ]
        };
    },
    components: {
        DashboardLayout,
    },
    methods: {
        async loadComponents() {
            if (!this.componentsLoaded) {
                const files = import.meta.glob('./Partials/Form*/*.vue');
                for (const [path, component] of Object.entries(files)) {
                    const key = path.split('/').pop().split('.')[0];
                    const matchingComponent = this.componentsToLoad.find(comp => comp.key === key);
                    if (matchingComponent && this.belongsToForm(matchingComponent.formName)) {
                        try {
                            const loadedComponent = await component();
                            this.$options.components[key] = loadedComponent.default;
                            this.loadedComponents.push({
                                key: key,
                                title: matchingComponent.title,
                                dataKey: matchingComponent.dataKey
                            });
                        } catch (error) {
                            console.error(`Error loading component ${key}:`, error);
                        }
                    }
                }
                if (this.loadedComponents.length > 0) {
                    this.selectedComponent = this.loadedComponents[0];
                }
                this.componentsLoaded = true;
            }
        },
        belongsToForm(formName) {
            return this.exclusiveForms.includes(formName);
        },
        getCulturalProperty() {
            axios.get(`/api/${usePage().props.role}/cultural-property/edit-inventory/${this.id}/2`).then((response) => {
                this.culturalPropertyData = response.data;
                this.stopLoading();
                this.loadComponents();
            }).catch(error => {
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
            axios.post(`/api/${usePage().props.role}/cultural-property/edit-inventory/${parentId}`, {
                form_id: this.culturalPropertyData.form_id,
                child_id: childId,
                data: newData,
                update_request_type: updateRequestType
            })
                .then(() => {
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
            this.loading = false;
        },
        selectComponent(component) {
            this.selectedComponent = component;
        },
    },
    created() {
        this.getCulturalProperty();
    },
    props: {
        id: Number,
        exclusiveForms: Array,
        editPermissions: String,
    },
}
</script>
<style>
.tabs-header {
    background-color: #15223D;
    color: white;
    text-underline-offset: 4px;
    text-decoration: underline;
}
.tab-content{
    margin-top: 0 !important;
}
</style>