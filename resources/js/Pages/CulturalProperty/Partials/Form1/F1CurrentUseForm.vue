<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Current Use <i>(Kasalukuyang Gamit)</i>
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
                <InputLabel>Current Use <i>(Kasalukuyang Gamit)</i></InputLabel>
                <MultipleSelectInput
                    :options="currentuse"
                    label="title"
                    placeholder="Select Current Use"
                    :value="f1CurrentUseValue.current_use_name"
                    v-model="f1CurrentUseValue.current_use_name"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4" v-if="shouldShowUnlistedOption">
                <InputLabel value="Others (Please Specify)" />
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f1CurrentUseValue.unlisted_name"
                    v-model="f1CurrentUseValue.unlisted_name"
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import MultipleSelectInput from "@/Components/MultipleSelectInput.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        SecondaryButton,
        MultipleSelectInput,
    },
    props: {
        data: {
            type: Object,
            default: () => ({}),
        },
        editPermissions: Array,
    },
    computed: {
        shouldShowUnlistedOption() {
            return this.hasUnlistedOption(
                "unlisted_name",
                "current_use_name",
                this.f1CurrentUseValue
            );
        },
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.edit.inventory`, { id: this.data?.id });
        },
        saveChanges() {
            this.filterDropdownOptions(
                this.f1CurrentUseValue,
                this.setMultiOptionFields()
            );

            this.$emit("save", {
                parentId: this.data?.id,
                newData: this.f1CurrentUseValue,
                updateRequestType: this.updateRequestType,
            });
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
            let multiOptionFields = ["current_use_name"];

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
        setF1CurrentUseValue() {
            this.f1CurrentUseValue = {
                current_use_name:
                    this.data?.f1_current_use_response.map(
                        (use) => use.name
                    ) || [],
                unlisted_name: this.data?.unlisted_name || "",
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
        this.setF1CurrentUseValue();
    },
    data() {
        return {
            updateRequestType: "f1CurrentUse",
            f1CurrentUseValue: {
                current_use_name: [],
                unlisted_name: "",
            },
            currentuse: [
                { title: "Agrikultura (Agriculture)" },
                { title: "Agri-Industriyal (Agri-Industrial)" },
                { title: "Libingan (Cemetery/Memorial Park)" },
                { title: "Komersyo (Commercial)" },
                { title: "Pang-Edukasyon (Educational)" },
                { title: "Himpilan ng Pamatay-sunog (Fire Station)" },
                { title: "Kagubatan (Forest and Forestland)" },
                { title: "Mga Kuta at Bantayan (Forts and Watchtower)" },
                { title: "Industriyal (Industrial)" },
                { title: "Institusyonal (Institusyonal)" },
                { title: "Parola (Lighthouse)" },
                { title: "Pang-mediko (Medical)" },
                { title: "Pangmilitar (Military)" },
                { title: "Mga Bantayog o Dambana (Monuments or Shrines)" },
                { title: "Pananda (Marker)" },
                { title: "Likas na Liwasan (Natural Park)" },
                { title: "Likas na Pook (Natural Site)" },
                { title: "Liwasan at Panlibangan (Parks and Recreation)" },
                { title: "Presinto ng Pulis/Kampo (Police Precinct/Camp)" },
                { title: "Panrelihiyon (Religious)" },
                { title: "Pantirahan (Residential)" },
                {
                    title: "Transportasyon, Mga Utilidad, at Serbisyo (Transporation, Utilities, and Services)",
                },
                { title: "Turismo (Tourism)" },
                { title: "Mga Pook (Sites)" },
                { title: "Sistemang Pantubig (Waterworks)" },
                { title: "Others (Please Specify)" },
            ],
        };
    },
};
</script>
