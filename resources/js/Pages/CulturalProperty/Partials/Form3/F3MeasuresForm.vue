<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Community or Cultural Bearers <i>(Komunidad o mga Tagapagtaglay ng Kasanayan)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
    
    
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div>
                <InputLabel value="Type(s) of Safeguarding Measure" />
                <MultipleSelectInput :options="measures" label="title" placeholder="Select Safegarding Measures"
                    :value="f3SafeguardingMeasureValue.kind_of_measure_name"
                    v-model="f3SafeguardingMeasureValue.kind_of_measure_name" :disabled="!canEdit()" />
            </div>

            <div
                v-if="hasUnlistedOption('unlisted_kinds_measures_name', 'kind_of_measure_name', this.f3SafeguardingMeasureValue)" class="mt-4">
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextInput type="text" class="mt-1 block w-full"
                    :value="f3SafeguardingMeasureValue.unlisted_kinds_measures_name"
                    v-model="f3SafeguardingMeasureValue.unlisted_kinds_measures_name" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Safeguarding Measures <i>(Mga Hakbang ng Pangangalaga)</i></InputLabel>
                <TextAreaResize type="text" class="mt-1 block w-full" :value="f3SafeguardingMeasureValue.measures"
                    v-model="f3SafeguardingMeasureValue.measures" :disabled="!canEdit()" />
            </div>

            <div v-if="canEdit()" class="flex items-center justify-center mt-10">
                <PrimaryButton>
                    Save
                </PrimaryButton>
            </div>
        </div>
    </form>
</template>


<script>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import MultipleSelectInput from '@/Components/MultipleSelectInput.vue';
import TextAreaResize from '@/Components/TextAreaResize.vue';

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        MultipleSelectInput,
        TextAreaResize,
    },
    props: {
        data: Object,
        editPermissions: Array
    },
    methods: {
        saveChanges() {
            this.filterDropdownOptions(this.f3SafeguardingMeasureValue, this.setMultiOptionFields());

            this.$emit('save', {
                parentId: this.data?.id,
                newData: this.f3SafeguardingMeasureValue,
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
            return options.map(option => {
                if (typeof option === 'object' && option !== null && 'title' in option) {
                    return option.title;
                } else if (typeof option === 'string') {
                    return option;
                }
            }).filter(value => value !== null);
        },
        removeDuplicates(values) {
            return [...new Set(values)];
        },
        setMultiOptionFields() {
            let multiOptionFields = [
                'kind_of_measure_name',
            ];

            return multiOptionFields;
        },
        hasUnlistedOption(unlistedFieldName, fieldName, formValue) {
            if (!formValue) {
                return false;
            }

            this.filterDropdownOptions(formValue, this.setMultiOptionFields());

            if (formValue[fieldName]?.some(field => field === "Others (Please Specify)" || field === "Iba Pa (Others)")) {
                return true;
            } else {
                formValue[unlistedFieldName] = null;
                return false;
            }
        },
        setF3SafeguardingMeasureValue() {
            this.f3SafeguardingMeasureValue = {
                kind_of_measure_name: this.data?.f3_given_kind_of_measure?.map(measure => measure.name) || [],
                measures: this.data?.measures || '',
                unlisted_kinds_measures_name: this.data?.unlisted_kinds_measures_name || '',
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setF3SafeguardingMeasureValue();
    },
    data() {
        return {
            updateRequestType: "f3SafeguardingMeasure",
            f3SafeguardingMeasureValue: {
                kind_of_measure_name: [],
                measures: '',
                unlisted_kinds_measures_name: '',
            },
            measures: [
                { title: 'Transmisyon, Partikular sa Pamamagitan ng Pormal o Di Pormal na Edukasyon (Transmission, particularly through formal and non-formal education)' },
                { title: 'Pagkilala, Dokumentasyon, at Pananaliksik (Identification, Documentation, and Research)' },
                { title: 'Pangangalaga o Proteksyon (Preservation, Protection)' },
                { title: 'Pagtataguyod, Pagpapayabong (Promotion, Enhancement)' },
                { title: 'Muling Pagpapasigla (Revitalization)' },
                { title: 'Others (Please Specify)' },
            ]
        }
    }
}

</script>