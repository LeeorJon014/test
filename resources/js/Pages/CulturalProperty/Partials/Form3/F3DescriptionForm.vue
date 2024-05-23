<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Description of the ICH/Vital Wisdom <i>(Paglalarawan ng Buhay na Dunong)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
    
    
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div>
                <InputLabel>
                    Describe the history of the practice, occasion or season, processes involved, procedures, beliefs associated, settings, aims, and other pertinent data. You may use a separate sheet if needed.
                    <br> <i>(Pakilarawan ang kasaysayan ng kasanayan, okasyon, o panahon, mga prosesong kasali, mga pamamaraan, mga kaugnay na paniniwala, mga konteksto, mga layunin, at iba pang mahalagang impormasyon tungkol sa buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.)</i>
                </InputLabel>
                <TextAreaResize type="text" class="mt-1 block w-full"
                    :value="f3DescriptionValue.describe_history_of_practice"
                    v-model="f3DescriptionValue.describe_history_of_practice" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Mode of Transmission <i>(Paraan ng Pagsalin)</i></InputLabel>
                <TextAreaResize type="text" class="mt-1 block w-full" :value="f3DescriptionValue.mode_of_transmission"
                    v-model="f3DescriptionValue.mode_of_transmission" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>
                    Describe how the intangible practice is being passed on. You may use a separate sheet if needed.
                    <br> <i>(Ilarawan kung paano ipinamamana o isasalin ang kasanayan ng buhay na dunong. Maaaring gumamit ng hiwalay na pahina para sa karagdagang impormasyon.)</i>
                </InputLabel>
                <TextAreaResize type="text" class="mt-1 block w-full"
                    :value="f3DescriptionValue.describe_intangible_practices"
                    v-model="f3DescriptionValue.describe_intangible_practices" :disabled="!canEdit()" />
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
import SelectInput from '@/Components/SelectInput.vue';
import TextAreaResize from '@/Components/TextAreaResize.vue';

export default {
    components: {
        InputLabel,
        PrimaryButton,
        SelectInput,
        TextAreaResize,
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
            this.$emit('save', {
                parentId: this.data?.id,
                newData: this.f3DescriptionValue,
                updateRequestType: this.updateRequestType,
            });
        },
        setF3DescriptionValue() {
            this.f3DescriptionValue = {
                describe_history_of_practice: this.data?.describe_history_of_practice,
                mode_of_transmission: this.data?.mode_of_transmission,
                describe_intangible_practices: this.data?.describe_intangible_practices,
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setF3DescriptionValue();
    },
    data() {
        return {
            updateRequestType: 'f3Description',
            f3DescriptionValue: {
                describe_history_of_practice: '',
                mode_of_transmission: '',
                describe_intangible_practices: '',
            },
        }
    }

}

</script>