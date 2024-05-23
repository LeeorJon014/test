<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Description <i>(Paglalarawan)</i>
            </p>
            <p class="text-sm italic text-ronchi">
                Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay
                sa database ang mga pagbabagong naganap.
            </p>
        </header>

        <div
            class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30"
        >
            <div v-if="belongsToForm('condition')" class="mb-4">
                <InputLabel>Condition <i>(Kondisyon)</i></InputLabel>
                <MultipleSelectInput
                    placeholder="Select Condition"
                    :options="condition"
                    :value="descriptionValue.f12_condition_responses_name"
                    v-model="descriptionValue.f12_condition_responses_name"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >General Threat Level
                    <i>(Pangkalahatang Antas ng Panganib)</i></InputLabel
                >
                <MultipleSelectInput
                    placeholder="Select General Threat Level"
                    :options="generalthreat"
                    :value="descriptionValue.general_threat_levels_name"
                    v-model="descriptionValue.general_threat_levels_name"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    >Previous Threats Encountered
                    <i>(Mga Sinuong na Panganib)</i></InputLabel
                >
                <TextAreaResize
                    type="text"
                    class="mt-1 block w-full"
                    :value="descriptionValue.previous_threats_encountered"
                    v-model="descriptionValue.previous_threats_encountered"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    >Potential Threat Level
                    <i>(Mga Potensiyal na Panganib)</i></InputLabel
                >
                <MultipleSelectInput
                    placeholder="Select Potential Threat Level"
                    :options="potentialthreat"
                    :value="descriptionValue.potential_threat_levels_name"
                    v-model="descriptionValue.potential_threat_levels_name"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4" v-if="shouldShowUnlistedOption">
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="descriptionValue.unlisted_potential_threat"
                    v-model="descriptionValue.unlisted_potential_threat"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Statement Potential Threat
                    <i>(Pahayag ng Potensyal na Panganib)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="descriptionValue.statement_potential_threat"
                    v-model="descriptionValue.statement_potential_threat"
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
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
        descriptionData: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
    },
    computed: {
        shouldShowUnlistedOption() {
            return this.hasUnlistedOption(
                "unlisted_potential_threat",
                "potential_threat_levels_name",
                this.descriptionValue
            );
        },
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        saveChanges() {
            const multiOptionFields = this.setMultiOptionFields();
            this.filterDropdownOptions(
                this.descriptionValue,
                multiOptionFields
            );

            this.$emit("save", {
                parentId: this.descriptionData?.id,
                newData: this.descriptionValue,
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
                "general_threat_levels_name",
                "potential_threat_levels_name",
            ];

            if (this.belongsToForm("condition")) {
                multiOptionFields.push("f12_condition_responses_name");
            }

            return multiOptionFields;
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
        setdescriptionValue() {
            this.descriptionValue = {
                previous_threats_encountered:
                    this.descriptionData?.previous_threats_encountered || "",
                statement_potential_threat:
                    this.descriptionData?.statement_potential_threat || "",
                unlisted_potential_threat:
                    this.descriptionData?.unlisted_potential_threat || "",
                ...(this.belongsToForm("condition") && {
                    f12_condition_responses_name:
                        this.descriptionData?.f12_condition_responses.map(
                            (response) => response.name
                        ) || [],
                }),
                general_threat_levels_name:
                    this.descriptionData?.general_threat_levels.map(
                        (generalThreat) => generalThreat.name
                    ) || [],
                potential_threat_levels_name:
                    this.descriptionData?.potential_threat_levels.map(
                        (potentialThreat) => potentialThreat.name
                    ) || [],
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
        this.setdescriptionValue();
    },
    data() {
        return {
            updateRequestType: "description",
            descriptionValue: {
                f12_condition_responses_name: [],
                general_threat_levels_name: [],
                potential_threat_levels_name: [],
                previous_threats_encountered: "",
                statement_potential_threat: "",
                unlisted_potential_threat: "",
            },
            formFields: {
                1: ["condition"],
                2: ["condition"],
                3: [],
            },
            condition: [
                {
                    title: "Mahusay na Kondisyon o Mahigit 90% Buo Pa (Excellent)",
                },
                { title: "Mabuting Kondisyon o Mahigit 70% Buo Pa (Good)" },
                {
                    title: "Katamtamang Ayos ng Kondisyon o Mahigit 50% Buo Pa (Fair)",
                },
                { title: "Nasisirang Kondisyon (Deteriorating)" },
                { title: "Sira/Guho (Ruins)" },
                { title: "Di Nakikita (Unexposed)" },
            ],
            generalthreat: [
                {
                    title: "Pulang Alert (Red Alert) - Ang nasabing alert ay nagbabadya ng malaking panganib sa ari-ariang kultural at nangangailangan ng madalian o agad na aksyon mula sa kinauukulan. This level indicates direct and severe threat to the cultural property. IMMEDIATE ACTION is needed.",
                },
                {
                    title: "Bughaw na Alert (Blue Alert) - Ang nasabing alert ay nagpapabatid ng panganib sa ari-ariang kultural at ito’y nangangailangan ng kinauukulang aksyon. This level indicates imminent threat to the cultural property and appropriate action is needed.",
                },
                {
                    title: "Puting Alert (White Alert) - Ang nasabing antas ay nagpapabatid na ang ari-ariang kultural ay malayo sa tiyak na kapahamakan. Subalit ito ay hindi gumagarantiya na hindi ito mapapahamak sa darating na panahon. Maaaring kaukulang paghahanda ang kinakailangan para maprotektahan ang nasabing ari-ariang kultural. This level indicates no immediate threat to the cultural property. However, it does not guarantee that it will be risk free in the near future. Preparatory action may be needed to protect the property from threat.",
                },
            ],
            potentialthreat: [
                {
                    title: "Bagyo, Buhawi, o Malakas na Hangin (Typhoons, Tornadoes, or Strong Wind)",
                },
                { title: "Daluyong (Storm Surge)" },
                {
                    title: "Digmaan o Armadong Pakikibaka (War or Armed Conflict)",
                },
                { title: "Lindol (Earthquake)" },
                {
                    title: "Malakas na pag-ulan, o habagat/amihan, at mga kaugnay na pangyayari tulad ng pagbaha (Strong Rain or Monsoon Rains, and associated phenomenon like flooding)",
                },
                { title: "Pagbaha (Floods or Flash Floods)" },
                { title: "Pagguho ng Lupa (Landslides or Mudslides)" },
                {
                    title: "Pagnanakaw o Ilegal na Pangangalakal (Theft and Illicit Traffic of Cultural Property)",
                },
                {
                    title: "Pagputok ng bulkan at mga kaugnay na pangyayari tulad ng pagdaloy ng lahar o lava (Volcanic Eruption and its associated phenomenon like lahar or lava flows)",
                },
                { title: "Sunog (Fire)" },
                { title: "Tagtuyoy (Drought)" },
                {
                    title: "Tsunami o malaking alon dala ng paglindol (Tsunami)",
                },
                { title: "Others (Please Specify)" },
            ],
        };
    },
};
</script>
