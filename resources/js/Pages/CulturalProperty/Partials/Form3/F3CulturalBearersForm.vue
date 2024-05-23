<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Community or Cultural Bearers <i>(Komunidad o mga Tagapagtaglay ng Kasanayan)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
    
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div>
                <InputLabel>Community or Ethnolinguistic Group <i>(Mga Komunidad o Grupong Etnolinggwistik na Nagtataglay ng Kasanayan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="f3CulturalBearersValue.ethnolinguistic_group"
                    v-model="f3CulturalBearersValue.ethnolinguistic_group" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Name(s) of Cultural Bearers <i>(Mga Pangalan ng Tagapagtaglay ng Kasanayan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="f3CulturalBearersValue.name"
                    v-model="f3CulturalBearersValue.name" :disabled="!canEdit()" />
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
import SelectInput from '@/Components/SelectInput.vue';

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        SelectInput,
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
                newData: this.f3CulturalBearersValue,
                updateRequestType: this.updateRequestType,
            });
        },
        setF3CulturalBearersValue() {
            this.f3CulturalBearersValue = {
                name: this.data?.name || '',
                ethnolinguistic_group: this.data?.ethnolinguistic_group || '',
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setF3CulturalBearersValue()
    },
    data() {
        return {
            updateRequestType: 'f3CulturalBearer',
            f3CulturalBearersValue: {
                ethnolinguistic_group: '',
                name: '',
            }
        }
    }
}

</script>