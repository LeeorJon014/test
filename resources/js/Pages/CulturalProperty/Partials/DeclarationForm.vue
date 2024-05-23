<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Declaration of Cultural Property <i>(Deklarasyon)</i>
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
                    >National/International Declaration
                    <i>(Pambansa/Pandaigdig na Deklarasyon)</i></InputLabel
                >
                <MultipleSelectInput
                    placeholder="Select National Declaration"
                    :options="nationalDeclaration"
                    :value="declarationValue.national_declaration_name"
                    v-model="declarationValue.national_declaration_name"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4" v-if="unlistedOptions.shouldShowUnlistedNational">
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextAreaResize
                    type="text"
                    class="mt-1 block w-full"
                    :value="declarationValue.unlisted_national_name"
                    v-model="declarationValue.unlisted_national_name"
                    label="title"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Local Declaration <i>(Lokal na Deklarasyon)</i></InputLabel
                >
                <MultipleSelectInput
                    placeholder="Select Local Declaration"
                    :value="declarationValue.local_declaration_name"
                    v-model="declarationValue.local_declaration_name"
                    :options="localDeclaration"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4" v-if="unlistedOptions.shouldShowUnlistedLocal">
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextAreaResize
                    type="text"
                    class="mt-1 block w-full"
                    :value="declarationValue.unlisted_local_name"
                    v-model="declarationValue.unlisted_local_name"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div v-if="belongsToForm('recognitions_noncultural_body')">
                <div class="mt-4">
                    <InputLabel
                        >Recognitions from Non-cultural Bodies
                        <i>(Pagkilala mula sa mga Lupong hindi Pangkultura)</i>
                    </InputLabel>
                    <MultipleSelectInput
                        placeholder="Select Recognitions from Non-cultural Bodies"
                        :options="recogitions"
                        :value="
                            declarationValue.recognitions_noncultural_body_name
                        "
                        v-model="
                            declarationValue.recognitions_noncultural_body_name
                        "
                        label="title"
                        :disabled="!canEdit()"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="unlistedOptions.shouldShowUnlistedRecognition"
                >
                    <InputLabel>Others (Please Specify)</InputLabel>
                    <TextAreaResize
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Type here"
                        :value="declarationValue.unlisted_recognition_name"
                        v-model="declarationValue.unlisted_recognition_name"
                        :disabled="!canEdit()"
                    />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel
                    >Number and Title of Declaration, Ordinance or Resolution
                    <i
                        >(Numero at Pamagat ng Deklarasyon, Ordinansa, o
                        Resolusyon)</i
                    ></InputLabel
                >
                <TextAreaResize
                    type="text"
                    class="mt-1 block w-full"
                    :value="declarationValue.number_and_title"
                    v-model="declarationValue.number_and_title"
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
import TextAreaResize from "@/Components/TextAreaResize.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import MultipleSelectInput from "@/Components/MultipleSelectInput.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        TextAreaResize,
        SecondaryButton,
        MultipleSelectInput,
    },
    props: {
        formId: Number,
        declarationData: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
    },
    computed: {
        unlistedOptions() {
            const unlistedOptions = {
                shouldShowUnlistedNational: false,
                shouldShowUnlistedLocal: false,
                shouldShowUnlistedRecognition: false,
            };

            if (
                this.hasUnlistedOption(
                    "unlisted_national_name",
                    "national_declaration_name",
                    this.declarationValue
                )
            ) {
                unlistedOptions.shouldShowUnlistedNational = true;
            }

            if (
                this.hasUnlistedOption(
                    "unlisted_local_name",
                    "local_declaration_name",
                    this.declarationValue
                )
            ) {
                unlistedOptions.shouldShowUnlistedLocal = true;
            }

            if (
                this.hasUnlistedOption(
                    "unlisted_recognition_name",
                    "recognitions_noncultural_body_name",
                    this.declarationValue
                )
            ) {
                unlistedOptions.shouldShowUnlistedRecognition = true;
            }

            return unlistedOptions;
        },
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        saveChanges() {
            this.filterDropdownOptions(
                this.declarationValue,
                this.setMultiOptionFields()
            );

            this.$emit("save", {
                parentId: this.declarationData?.id,
                newData: this.declarationValue,
                updateRequestType: this.updateRequestType,
            });
        },
        belongsToForm(formSectionName) {
            return this.formFields[this.formId].includes(formSectionName);
        },
        filterDropdownOptions(formValue, multiOptionFields) {
            multiOptionFields.forEach((field) => {
                const optionValues = this.extractTitleValues(formValue[field]);
                const uniqueOptionValues = this.removeDuplicates(optionValues);
                formValue[field] = uniqueOptionValues;
            });
        },
        extractTitleValues(options) {
            return options
                .map((option) => {
                    if (
                        typeof option === "object" &&
                        option !== null &&
                        "title" in option
                    ) {
                        return option.title;
                    } else if (typeof option === "string") {
                        return option;
                    }
                })
                .filter((value) => value !== null);
        },
        removeDuplicates(values) {
            return [...new Set(values)];
        },
        setMultiOptionFields() {
            let multiOptionFields = [
                "national_declaration_name",
                "local_declaration_name",
            ];

            if (this.belongsToForm("recognitions_noncultural_body")) {
                multiOptionFields.push("recognitions_noncultural_body_name");
            }

            return multiOptionFields;
        },
        setDeclarationValue() {
            this.declarationValue = {
                ...(this.belongsToForm("recognitions_noncultural_body") && {
                    recognitions_noncultural_body_name:
                        this.declarationData?.recognitions_noncultural_body.map(
                            (recognition) => recognition.name
                        ) || [],
                }),
                national_declaration_name:
                    this.declarationData?.national_declaration.map(
                        (declaration) => declaration.name
                    ) || [],
                local_declaration_name:
                    this.declarationData?.local_declaration.map(
                        (declaration) => declaration.name
                    ) || [],
                unlisted_national_name:
                    this.declarationData?.unlisted_national_name || "",
                unlisted_local_name:
                    this.declarationData?.unlisted_local_name || "",
                unlisted_recognition_name:
                    this.declarationData?.unlisted_recognition_name || "",
                number_and_title: this.declarationData?.number_and_title || "",
            };
        },
        hasUnlistedOption(unlistedFieldName, fieldName, formValue) {
            if (!formValue) {
                return false;
            }

            this.filterDropdownOptions(formValue, this.setMultiOptionFields());

            if (
                formValue[fieldName]?.some(
                    (field) =>
                        field === "Others (Please Specify)" ||
                        field === "Iba Pa (Others)"
                )
            ) {
                return true;
            } else {
                formValue[unlistedFieldName] = null;
                return false;
            }
        },
        canEdit() {
            return (
                this.editPermissions.includes("updateAll") ||
                this.editPermissions.includes(this.updateRequestType)
            );
        },
    },
    created() {
        this.setDeclarationValue();
    },
    data() {
        return {
            hasTriggered: false,
            updateRequestType: "declaration",
            formFields: {
                1: ["recognitions_noncultural_body"],
                2: [],
                3: [],
            },
            declarationValue: {
                national_declaration_name: [],
                local_declaration_name: [],
                recognitions_noncultural_body_name: [],
                unlisted_local_name: "",
                unlisted_national_name: "",
                unlisted_recognition_name: "",
                number_and_title: "",
            },
            nationalDeclaration: [
                {
                    title: "Pook Pamanang Pandaigdig (UNESCO World Heritage Site)",
                },
                { title: "ASEAN Pamanang Likas (ASEAN Natural Heritage)" },
                {
                    title: "Pambansang Yamang Pangkalinangan (National Cultural Treasure)",
                },
                {
                    title: "Mahalagang Ari-ariang Pangkalinangan (Important Cultural Property)",
                },
                {
                    title: "Itinuturing na Ari-Ariang Kultural (Presumed Important Cultural Property)",
                },
                {
                    title: "NIPAS - Pambansang Sistema ng mga Pinagsamang Iniingatang Pook (NIPAS - National Integrated Protected Areas System)",
                },
                { title: "Pambansang Dambana (National Shrine)" },
                { title: "Pambansang Bantayog (National Monument)" },
                {
                    title: "Pambansang Palatandaan/Pook Pangkasaysayan (National Historical Landmark/Site)",
                },
                { title: "Panandang Pangkasaysayan (Historical Marker)" },
                { title: "Lugar na Pamana (Heritage Zone)" },
                { title: "Pamanang Bahay (Heritage House)" },
                { title: "Walang Deklarasyon (No Declaration)" },
                {
                    title: "Bantayog-wika (Bantayog-wika Marker-Commission on the Filipino Language)",
                },
                {
                    title: "Inskripsyon sa Talaan ng Buhay na Dunong na Nangangailangan nang Maagap na Pangangalaga (Inscription on the List of Intangible Cultural Heritage in Need of Urgent Safeguarding-United Nations Educational, Scientific and Cultural Organization)",
                },
                {
                    title: "Inskripsyon sa Talaan ng mga Kumakatawang Buhay na Dunong ng Sangkatauhan (Inscription on the Representative List of the Intangible Cultural Heritage of Humanity-United Nations Educational, Scientific and Cultural Organization)",
                },
                { title: "Others (Please Specify)" },
            ],
            localDeclaration: [
                { title: "Panrehiyon (Regional)" },
                { title: "Panlalawigan (Provincial)" },
                { title: "Panglungsod o Pambayan (City or Municipality)" },
                { title: "Pambarangay o Pandistrito (Barangay or District)" },
                { title: "Walang Deklarasyon (No Declaration)" },
                { title: "Others (Please Specify)" },
            ],
            recogitions: [
                {
                    title: "Sistema ng Pamanang Pang-agrikultura na Makabuluhan sa Pandaigdig na Saklaw (Globally Important Agricultural Heritage Systems by the Food and Agriculture Organization of the United Nations)",
                },
                {
                    title: "Lupaing Ninuno (Ancestral Domain/Ancestral Land by the National Commission on Indigenous Peoples)",
                },
                {
                    title: "Tourism Enterprise Zone (Tourism Enterprise Zone by the Tourism Infrastructure and Enterprise Zone Authority)",
                },
                {
                    title: "Philippine Threatened Wildlife (Philippine Threatened Wildlife by the Department of Environment and Natural Resources)",
                },
                {
                    title: "Pinangangalagaang Lunan (Protected Area by the Department of Environment and Natural Resources)",
                },
                {
                    title: "Pook Pang-Agriturismo (Agri-tourism Site by the Department of Tourism)",
                },
                {
                    title: "Teritoryong Pang-Ekonomiya (Economic Zones by the Philippine Economic Zone Authority)",
                },
                { title: "Others (Please Specify)" },
            ],
        };
    },
};
</script>
