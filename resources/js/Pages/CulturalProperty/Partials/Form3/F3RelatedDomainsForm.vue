<template>
        <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Community or Cultural Bearers <i>(Komunidad o mga Tagapagtaglay ng Kasanayan)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
    
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div>
                <InputLabel>Related Domains of the Intangible Cultural Heritage/Vital Wisdom <i>(Kaugnay na Pag-uuri ng Buhay na Dunong)</i></InputLabel>
                <MultipleSelectInput :options="relatedomains" label="title" placeholder="Select Related Domains"
                    :value="f3RelatedDomainsValue.related_domains_name" v-model="f3RelatedDomainsValue.related_domains_name"
                    :disabled="!canEdit()" />
            </div>

            <div v-if="hasUnlistedOption('unlisted_related_domains', 'related_domains_name', this.f3RelatedDomainsValue)" class="mt-4">
                <InputLabel value="Others (Please Specify)" />
                <TextInput type="text" class="mt-1 block w-full" :value="f3RelatedDomainsValue.unlisted_related_domains"
                    v-model="f3RelatedDomainsValue.unlisted_related_domains" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel value="Supporting Documentation" />
                <MultipleSelectInput :options="supportingdocu" label="title" placeholder="Select Related Domains"
                    :value="f3RelatedDomainsValue.supporting_document_name"
                    v-model="f3RelatedDomainsValue.supporting_document_name" :disabled="!canEdit()" />
            </div>

            <div
                v-if="hasUnlistedOption('unlisted_supporting_docu', 'supporting_document_name', this.f3RelatedDomainsValue)" class="mt-4">
                <InputLabel value="Others (Please Specify)" />
                <TextInput type="text" class="mt-1 block w-full" :value="f3RelatedDomainsValue.unlisted_supporting_docu"
                    v-model="f3RelatedDomainsValue.unlisted_supporting_docu" :disabled="!canEdit()" />
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

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        MultipleSelectInput,
    },
    props: {
        data: {
            type: Object,
            default: () => ({})
        },
        editPermissions: Array
    },
    methods: {
        saveChanges() {
            this.filterDropdownOptions(this.f3RelatedDomainsValue, this.setMultiOptionFields());

            this.$emit('save', {
                parentId: this.data?.id,
                newData: this.f3RelatedDomainsValue,
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
                'related_domains_name',
                'supporting_document_name'
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
        setF3RelatedDomainsValue() {
            this.f3RelatedDomainsValue = {
                related_domains_name: this.data?.f3_given_related_domain?.map(domain => domain.name) || [],
                supporting_document_name: this.data?.f3_given_supporting_docu?.map(document => document.name) || [],
                unlisted_related_domains: this.data?.unlisted_related_domains || '',
                unlisted_supporting_docu: this.data?.unlisted_related_domains || '',
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setF3RelatedDomainsValue();
    },
    data() {
        return {
            updateRequestType: 'f3RelatedDomain',
            f3RelatedDomainsValue: {
                related_domains_name: [],
                supporting_document_name: [],
                unlisted_related_domains: '',
                unlisted_supporting_docu: '',
            },
            relatedomains: [
                { title: 'Mga Tradisyong Bibigan, at Ibang Pahayag (Oral Traditions and Expressions)' },
                { title: 'Mga Sining ng Pagtatanghal (Performing Arts)' },
                { title: 'Mga Panlipunang Kaugalian, Ritwal, o Seremonyang Panrelihiyon, Kaugalian sa Pagluluto, at mga Pagdiriwang (Social Practices, Rituals, Culinary Traditions, and Festive Events)' },
                { title: 'Mga Kaalaman at Kaugalian Tungkol sa Kalikasan at sa Sansinukob (Knowledge and practices concerning nature and the universe)' },
                { title: 'Mga Katutubong Kahusayan sa Paglikha (Traditional Craftsmanship)' },
                { title: 'Mga Kontemporanyong Sining at Paglikha (Contemporary Arts and Crafts)' },
                { title: 'Others (Please Specify)' },
            ],
            supportingdocu: [
                { title: 'Mga Larawan (Photographs)' },
                { title: 'Mga Audio-visual na Pagrerekord (Video, Audio Recording, MP3, atbp.)' },
                { title: 'Others (Please Specify)' },
            ],
        };
    },
}

</script>