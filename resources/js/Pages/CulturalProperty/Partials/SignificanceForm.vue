<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Significance <i>(Kabuluhan)</i>
            </p>
            <p class="text-sm italic text-ronchi">
                Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay
                sa database ang mga pagbabagong naganap.
            </p>
        </header>

        <div
            class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-midnight-express leading-4">
                Primary Criteria
            </p>

            <div class="mt-4">
                <InputLabel
                    >Historical Significance
                    <i>(Makasaysayang Kabuluhan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.historical"
                    v-model="significanceValue.historical"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Social Significance
                    <i>(Panlipunan na Kabuluhan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.social"
                    v-model="significanceValue.social"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Political Significance
                    <i>(Pulitikal na Kabuluhan)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="significanceValue.political"
                    v-model="significanceValue.political"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    value="Economic Significance (Pangekonomiyang Kabuluhan)"
                />
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.economic"
                    v-model="significanceValue.economic"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    value="Spiritual Significance (Espiritwal na Kabuluhan)"
                />
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.spiritual"
                    v-model="significanceValue.spiritual"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    value="Scientific, Research, or Technological Significance (Pang-agham, Pananaliksik, o Teknolohikal na Kabuluhan)"
                />
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.scientific"
                    v-model="significanceValue.scientific"
                    :disabled="!canEdit()"
                />
            </div>

            <div>
                <InputLabel
                    value="Aesthetic or Artistic Significance (Pansining na Kabuluhan)"
                />
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="significanceValue.aesthetic"
                    v-model="significanceValue.aesthetic"
                    :disabled="!canEdit()"
                />
            </div>

            <div v-if="belongsToForm('comparative_criteria')">
                <InputLabel value="Comparative Criteria" />
                <div
                    class="mt-4"
                    v-if="
                        innerDivBelongsToForm(
                            'comparative_criteria',
                            'representativeness'
                        )
                    "
                >
                    <InputLabel
                        value="Representativeness. Can the cultural property stand as a good representative of the particular category of object, way of life, or historical theme?
                (Pagkakatawan. Makakatayo ba ang ari-ariang kultural bilang mahusay na kinatawan ng isang tiyak na uri ng bagay, pamumuhay, o makasaysayang paksa?)"
                    />
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        :value="significanceValue.representativeness"
                        v-model="significanceValue.representativeness"
                        :disabled="!canEdit()"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="
                        innerDivBelongsToForm('comparative_criteria', 'rarity')
                    "
                >
                    <InputLabel class="leading-5">
                        <u>Rarity.</u> Is the cultural property rare, unusual,
                        or particularly a fine example of its type?
                        <br />
                        <i
                            >(<u>Natatangi.</u> Bihira na ba ang ganitong
                            ari-ariang kultural, hindi pangkaraniwan, o
                            maituturing ba itong mahusay na halimbawa ng
                            ganitong uri?)</i
                        >
                    </InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        :value="significanceValue.rarity"
                        v-model="significanceValue.rarity"
                        placeholder="Type here"
                        :disabled="!canEdit()"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="
                        innerDivBelongsToForm(
                            'comparative_criteria',
                            'interpretative_potential'
                        )
                    "
                >
                    <InputLabel class="leading-5">
                        <u>Interpretative Potential.</u> Does the cultural
                        property have the capacity to interpret and demonstrate
                        aspects of experience, historical themes, people and
                        activities, or the capacity to tell a story?
                        <br />
                        <i
                            >(<u>Mapagpaliwanag na Potensiyal.</u> Mayroon bang
                            kakayahan itong ari-ariang kultural upang
                            bigyang-kahulugan at ipakita ang mga aspekto ng
                            danas, makasaysayang mga paksa, mga tao at gawain, o
                            kakayahang magsalaysay?)</i
                        >
                    </InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        :value="significanceValue.interpretive_potential"
                        v-model="significanceValue.interpretive_potential"
                        placeholder="Type here"
                        :disabled="!canEdit()"
                    />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel
                    value="Areas of Significance (Mga Bahagi ng Kabuluhan)"
                />
                <MultipleSelectInput
                    placeholder="Select Areas of Significance"
                    :options="areasofsig"
                    :value="significanceValue.area_of_significance_name"
                    v-model="significanceValue.area_of_significance_name"
                    label="title"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4" v-if="shouldShowUnlistedOption">
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextAreaResize
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Type here"
                    :value="significanceValue.unlisted_area"
                    v-model="significanceValue.unlisted_area"
                    :disabled="!canEdit()"
                />
            </div>
        </div>

        <div v-if="canEdit()" class="flex items-center justify-center mt-10">
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
    </form>
</template>

<script>
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaResize from "@/Components/TextAreaResize.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import MultipleSelectInput from "@/Components/MultipleSelectInput.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        SelectInput,
        TextAreaResize,
        SecondaryButton,
        MultipleSelectInput,
    },
    props: {
        formId: Number,
        significanceData: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
    },
    computed: {
        shouldShowUnlistedOption() {
            return this.hasUnlistedOption(
                "unlisted_area",
                "area_of_significance_name",
                this.significanceValue
            );
        },
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        saveChanges() {
            //this.updateConditionFields(); a missing function
            this.filterDropdownOptions(
                this.significanceValue,
                this.setMultiOptionFields()
            );

            this.$emit("save", {
                parentId: this.significanceData?.id,
                childId: this.childId,
                newData: this.significanceValue,
                updateRequestType: this.updateRequestType,
            });
        },
        belongsToForm(formSectionName) {
            return formSectionName in this.formFields[this.formId];
        },
        innerDivBelongsToForm(formSectionName, innerDivName) {
            // Check if the inner div names are stored in an array for the specified form.
            if (Array.isArray(this.formFields[this.formId][formSectionName])) {
                // If it is, it returns true, indicating that the inner div belongs to the form section
                return this.formFields[this.formId][formSectionName].includes(
                    innerDivName
                );
            }
            return false;
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
            let multiOptionFields = ["area_of_significance_name"];

            return multiOptionFields;
        },
        setChildId() {
            this.childId = {
                ...(this.belongsToForm("comparative_criteria") && {
                    comparative_criteria:
                        this.significanceData?.comparative_criteria.id,
                }),
                primary_criteria: this.significanceData?.primary_criteria.id,
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
        setSignificanceValue() {
            this.significanceValue = {
                ...((this.belongsToForm("comparative_criteria") && {
                    interpretive_potential:
                        this.significanceData?.comparative_criteria
                            ?.interpretive_potential || "",
                    rarity:
                        this.significanceData?.comparative_criteria?.rarity ||
                        "",
                    representativeness:
                        this.significanceData?.comparative_criteria
                            ?.representativeness || "",
                }) ||
                    ""),
                area_of_significance_name:
                    this.significanceData?.area_of_significance?.map(
                        (area) => area.name
                    ) || [],
                aesthetic:
                    this.significanceData?.primary_criteria?.aesthetic || "",
                economic:
                    this.significanceData?.primary_criteria?.economic || "",
                historical:
                    this.significanceData?.primary_criteria?.historical || "",
                political:
                    this.significanceData?.primary_criteria?.political || "",
                scientific:
                    this.significanceData?.primary_criteria?.scientific || "",
                social: this.significanceData?.primary_criteria?.social || "",
                spiritual:
                    this.significanceData?.primary_criteria?.spiritual || "",
                unlisted_area: this.significanceData?.unlisted_area || "",
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
        this.setChildId();
        this.setSignificanceValue();
    },
    data() {
        return {
            /* // Define a timer variable
            typingTimer: null,
            // Define the delay time (in milliseconds)
            doneTypingInterval: 3000, */
            updateRequestType: "significance",
            childId: {
                comparative_criteria: "",
                primary_criteria: "",
            },
            significanceValue: {
                area_of_significance_name: [],
                // comparative_criteria
                interpretive_potential: "",
                rarity: "",
                representativeness: "",
                // primary_criteria
                aesthetic: "",
                economic: "",
                historical: "",
                political: "",
                scientific: "",
                social: "",
                spiritual: "",
                // general
                unlisted_area: "",
            },
            formFields: {
                1: {
                    comparative_criteria: [
                        "representativeness",
                        "rarity",
                        "interpretative_potential",
                    ],
                },
                2: {
                    comparative_criteria: [
                        "representativeness",
                        "rarity",
                        "interpretative_potential",
                    ],
                },
                3: {},
            },
            areasofsig: [
                { title: "Agrikultura (Agriculture)" },
                { title: "Antropolohiya (Anthropology)" },
                { title: "Arkitektura (Architecture)" },
                { title: "Sining at Musika (Arts and Music)" },
                { title: "Astronomiya (Astronomy)" },
                {
                    title: "Komersyo, Kalakalan, and Industriya (Commerce, Trade, and Industry)",
                },
                { title: "Komunikasyon (Communication)" },
                { title: "Edukasyon (Education)" },
                { title: "Inhinyerya (Engineering)" },
                { title: "Libangan (Entertainment)" },
                { title: "Agham Pangkapaligiran (Environmental Science)" },
                { title: "Pangkalusugan (Health)" },
                { title: "Pook Pamana (Heritage Site)" },
                { title: "Pang-ugnayang Panlabas (International Relations)" },
                { title: "Abogasya (Law)" },
                { title: "Tanawin (Landscape)" },
                { title: "Panitikan (Literature)" },
                { title: "Pangkabuhayan (Livelihood)" },
                { title: "Matematika (Mathematics)" },
                { title: "Militar (Military)" },
                { title: "Musika (Music)" },
                { title: "Politika at Pamamahala (Politics and Governance)" },
                { title: "Relihiyon (Religion)" },
                { title: "Agham (Science)" },
                { title: "Kagalingang Panlipunan (Social Welfare)" },
                { title: "Teknolohiya (Technology)" },
                { title: "Turismo (Tourism)" },
                { title: "Transportasyon (Transportation)" },
                { title: "Sining Biswal (Visual Art)" },
                { title: "Others (Please Specify)" },
            ],
        };
    },
};
</script>
