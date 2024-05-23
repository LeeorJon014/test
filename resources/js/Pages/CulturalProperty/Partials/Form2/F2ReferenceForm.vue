<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Major Bibliographic References and Key Informants <i>(Mga Pangunahing Sanggunian)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>
    
        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <div>
                <TextAreaResize type="text" class="mt-1 block w-full" :value="f2ReferenceValue.name"
                    v-model="f2ReferenceValue.name" :disabled="!canEdit()" />
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
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextAreaResize from '@/Components/TextAreaResize.vue';

export default {
    components: {
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
                newData: this.f2ReferenceValue,
                updateRequestType: this.updateRequestType,
            });
        },
        setF2ReferenceValue() {
            this.f2ReferenceValue = {
                name: this.data?.name,
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setF2ReferenceValue()
    },
    data() {
        return {
            updateRequestType: 'f2Reference',
            f2ReferenceValue: {
                name: '',
            }
        }
    }
}

</script>