<template>
    <form @submit.prevent="saveChanges">
        <header class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-white"> Documentation and Validation <i>(Dokumentasyon at
                    Pagpapatunay)</i></p>
            <p class="text-sm italic text-ronchi">Alalahaning i-<strong>save ang iyong gawa</strong> para mailagay sa
                database ang mga pagbabagong naganap.</p>
        </header>

        <div class="px-8 py-6 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
            <p class="text-2xl font-semibold text-midnight-express leading-4"> Submitted by <i>(Inihanda ni/nina)</i>:</p>

            <div class="mt-4">
                <InputLabel>Name <i>(Pangalan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.name" v-model="submitterValue.name"
                    placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Sex <i>(Biyolohikal na Pagkakakilanlan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.sex" v-model="submitterValue.sex"
                    placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Designation <i>(Katungkulan)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.designation"
                    v-model="submitterValue.designation" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Date <i>(Petsa)</i></InputLabel>
                <DatePicker @selectDate="handleDateSelection" v-model="submitterValue.date"
                    :initialDate="submitterValue.date" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Organization <i>(Organisasiyon)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.organization"
                    v-model="submitterValue.organization" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Address of Organization <i>(Adres ng Organisasyon)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.address_of_organization"
                    v-model="submitterValue.address_of_organization" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Facebook Page <i>(Pahinang Facebook)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.facebook_page"
                    v-model="submitterValue.facebook_page" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Website Page <i>(Pahinang Website)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.website_page"
                    v-model="submitterValue.website_page" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Encoder Name <i>(Nag-encode)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.encoder_name"
                    v-model="submitterValue.encoder_name" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div class="mt-4">
                <InputLabel>Year of Submission <i>(Taon ng Pagpapasa)</i></InputLabel>
                <TextInput type="text" class="mt-1 block w-full" :value="submitterValue.year_of_submission"
                    v-model="submitterValue.year_of_submission" placeholder="Type here" :disabled="!canEdit()" />
            </div>

            <div v-if="canEdit()" class="flex items-center justify-center mt-10">
                <PrimaryButton>
                    Save
                </PrimaryButton>

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
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DatePicker from '@/Components/DatePicker.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { format } from 'date-fns';
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        DatePicker,
        SecondaryButton,
    },
    props: {
        formId: Number,
        submitterData: Object,
        editPermissions: Array
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.view.inventory`);
        },
        handleDateSelection(selectedDate) {
            this.submitterValue.date = selectedDate;
        },
        saveChanges() {
            this.$emit('save', {
                parentId: this.submitterData.id,
                newData: this.submitterValue,
                updateRequestType: this.updateRequestType,
            });
        },
        formatDate(dateString) {
            return format(new Date(dateString), 'yyyy-MM-dd');
        },
        setSubmitterValue() {
            this.submitterValue = {
                address_of_organization: this.submitterData.address_of_organization,
                date: this.formatDate(this.submitterData.date),
                designation: this.submitterData.designation,
                encoder_name: this.submitterData.encoder_name,
                facebook_page: this.submitterData.facebook_page,
                name: this.submitterData.name,
                organization: this.submitterData.organization,
                sex: this.submitterData.sex,
                website_page: this.submitterData.website_page,
                year_of_submission: this.submitterData.year_of_submission,
            }
        },
        canEdit() {
            return this.editPermissions.includes('updateAll') || this.editPermissions.includes(this.updateRequestType);
        },
    },
    created() {
        this.setSubmitterValue();
    },
    data() {
        return {
            updateRequestType: "submitter",
            submitterValue: {
                address_of_organization: '',
                date: '',
                designation: '',
                encoder_name: '',
                facebook_page: '',
                name: '',
                organization: '',
                sex: '',
                website_page: '',
                year_of_submission: '',
            },
        };
    },
}
</script>