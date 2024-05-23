<template>
    <form @submit.prevent="saveChanges">
        <header
            class="px-8 py-4 bg-midnight-express rounded-tr-xl border border-midnight-express border-opacity-30"
        >
            <p class="text-2xl font-semibold text-white">
                Classification of Cultural Property
                <i>(Klasipikasyon/Pag-uuri)</i>
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
                <InputLabel>Category <i>(Kategorya)</i></InputLabel>
                <SelectInput
                    :options="getCategoryOptions(formId)"
                    label="title"
                    placeholder="Select Category"
                    :disabled="!canEdit()"
                    :value="f12ClassificationValue.category"
                    v-model="f12ClassificationValue.category"
                    @input="handleCategoryChange"
                />
            </div>

            <div
                class="mt-4"
                v-if="
                    shouldShowUnlistedCategory
                "
            >
                <InputLabel>Others (Please Specify)</InputLabel>
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f12ClassificationValue.unlisted_category"
                    v-model="f12ClassificationValue.unlisted_category"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div v-if="showSubcategoryDropdown(formId)">
                <div>
                    <InputLabel
                        >Subcategory
                        <i>(Pangalawahing Kategorya)</i></InputLabel
                    >
                    <SelectInput
                        :options="getSubcategoryOptions(formId)"
                        label="title"
                        placeholder="Select Subcategory"
                        :value="f12ClassificationValue.subcategory"
                        v-model="f12ClassificationValue.subcategory"
                        @input="handleSubcategoryChange"
                        :disabled="!canEdit()"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="
                        shouldShowUnlistedSubcategory
                    "
                >
                    <InputLabel>Others (Please Specify)</InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        :value="f12ClassificationValue.unlisted_subcategory"
                        v-model="f12ClassificationValue.unlisted_subcategory"
                        placeholder="Type here"
                        :disabled="!canEdit()"
                    />
                </div>
            </div>

            <div v-if="showClassificationDropdown(formId)">
                <div>
                    <InputLabel>Classification <i>(Pag-uuri)</i></InputLabel>
                    <SelectInput
                        :options="getClassificationOptions(formId)"
                        label="title"
                        placeholder="Select Classification"
                        :value="f12ClassificationValue.classification"
                        v-model="f12ClassificationValue.classification"
                        @input="handleClassificationChange"
                        :disabled="!canEdit()"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="
                        shouldShowUnlistedClassification
                    "
                >
                    <InputLabel>Others (Please Specify)</InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        :value="f12ClassificationValue.unlisted_classification"
                        v-model="f12ClassificationValue.unlisted_classification"
                        placeholder="Type here"
                        :disabled="!canEdit()"
                    />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel
                    >Historical Site <i>(Makasaysayang Lugar)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f12ClassificationValue.historical_site"
                    v-model="f12ClassificationValue.historical_site"
                    placeholder="Type here"
                    :disabled="!canEdit()"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    >Cultural Landscape <i>(Kultural na Pook)</i></InputLabel
                >
                <TextInput
                    type="text"
                    class="mt-1 block w-full"
                    :value="f12ClassificationValue.cultural_landscape"
                    v-model="f12ClassificationValue.cultural_landscape"
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
import SelectInput from "@/Components/SelectInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        InputLabel,
        PrimaryButton,
        TextInput,
        SelectInput,
        SecondaryButton,
    },
    computed: {
        shouldShowUnlistedCategory() {
            return this.hasUnlistedOption('unlisted_category', 'category', this.f12ClassificationValue)
        },
        shouldShowUnlistedSubcategory () {
            return this.hasUnlistedOption('unlisted_subcategory', 'subcategory', this.f12ClassificationValue)
        },
        shouldShowUnlistedClassification () {
            return this.hasUnlistedOption('unlisted_classification', 'classification', this.f12ClassificationValue)
        },
    },
    props: {
        data: {
            type: Object,
            default: () => ({}),
        },
        formId: Number,
        editPermissions: Array,
    },
    methods: {
        redirectToInventory() {
            window.location.href = route(`${usePage().props.role}.edit.inventory`, { id: this.data?.id });
        },
        saveChanges() {
            this.filterDropdownOptions(
                this.f12ClassificationValue,
                this.setDropdownFields()
            );

            this.$emit("save", {
                parentId: this.data?.id,
                newData: this.f12ClassificationValue,
                updateRequestType: this.updateRequestType,
            });
        },
        filterDropdownOptions(formValue, dropdownFields) {
            dropdownFields.forEach((field) => {
                const optionValues = this.extractTitleValues(formValue[field]);
                formValue[field] = optionValues;
            });
        },
        setDropdownFields() {
            const dropdownFields = [
                "category",
                "subcategory",
                "classification",
            ];
            return dropdownFields;
        },
        handleCategoryChange(data) {
            data = this.extractTitleValues(data);

            this.f12ClassificationValue = {
                ...this.f12ClassificationValue,
                category: data,
                subcategory: null,
                classification: null,
            };
        },
        handleSubcategoryChange(data) {
            data = this.extractTitleValues(data);
            this.f12ClassificationValue = {
                ...this.f12ClassificationValue,
                subcategory: data,
                classification: null,
            };
        },
        handleClassificationChange(data) {
            data = this.extractTitleValues(data);

            this.f12ClassificationValue = {
                ...this.f12ClassificationValue,
                classification: data,
            };
        },
        getCategoryOptions(formType) {
            return formType === 1 ? this.f1Category : this.f2Category;
        },
        getSubcategoryOptions(formType) {
            this.f12ClassificationValue.category = this.extractTitleValues(
                this.f12ClassificationValue.category
            );

            const selectedCategory = this.getCategoryOptions(formType).find(
                (category) =>
                    category.title === this.f12ClassificationValue.category
            );
            return selectedCategory.subcategory || [];
        },
        getClassificationOptions(formType) {
            this.f12ClassificationValue.subcategory = this.extractTitleValues(
                this.f12ClassificationValue.subcategory
            );

            const selectedSubcategory = this.getSubcategoryOptions(
                formType
            ).find(
                (subcategory) =>
                    subcategory.title ===
                    this.f12ClassificationValue.subcategory
            );
            return selectedSubcategory.classification || [];
        },
        showSubcategoryDropdown(formType) {
            this.f12ClassificationValue.category = this.extractTitleValues(
                this.f12ClassificationValue.category
            );

            const selectedCategory = this.getCategoryOptions(formType).find(
                (category) =>
                    category.title === this.f12ClassificationValue.category
            );
            return !!(
                selectedCategory && selectedCategory.subcategory !== undefined
            );
        },
        showClassificationDropdown(formType) {
            this.f12ClassificationValue.subcategory = this.extractTitleValues(
                this.f12ClassificationValue.subcategory
            );

            const selectedSubcategory = this.getSubcategoryOptions(
                formType
            ).find(
                (subcategory) =>
                    subcategory.title ===
                    this.f12ClassificationValue.subcategory
            );
            return !!(
                selectedSubcategory &&
                selectedSubcategory.classification !== undefined
            );
        },
        extractTitleValues(value) {
            if (
                typeof value === "object" &&
                value !== null &&
                "title" in value
            ) {
                return value.title;
            } else if (typeof value === "string") {
                return value;
            }
        },
        hasUnlistedOption(unlistedFieldName, fieldName, formValue) {
            if (!formValue) {
                return false;
            }

            this.filterDropdownOptions(formValue, this.setDropdownFields());

            if (
                ["Others (Please Specify)", "Iba Pa (Others)"].includes(
                    formValue[fieldName]
                )
            ) {
                return true;
            } else {
                formValue[unlistedFieldName] = null;
                return false;
            }
        },
        setF12ClassificationValue() {
            this.f12ClassificationValue = {
                category: this.data?.category || "",
                subcategory: this.data?.subcategory || "",
                classification:
                    this.data?.classification || "",
                unlisted_category:
                    this.data?.unlisted_category || "",
                unlisted_subcategory:
                    this.data?.unlisted_subcategory || "",
                unlisted_classification:
                    this.data?.unlisted_classification || "",
                historical_site:
                    this.data?.historical_site || "",
                cultural_landscape:
                    this.data?.cultural_landscape || "",
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
        this.setF12ClassificationValue();
    },
    data() {
        return {
            updateRequestType: "f12Classification",
            f12ClassificationValue: {
                category: "",
                classification: "",
                subcategory: "",
                unlisted_category: "",
                unlisted_subcategory: "",
                unlisted_classification: "",
                historical_site: "",
                cultural_landscape: "",
            },
            f1Category: [
                //form 1
                {
                    title: "Mga Gusaling Pampamahalaan, Pribado, at Pangkomersyo (Government Structures, Private Structures, and Commercial Establishments)",
                    subcategory: [
                        { title: "Paliparan (Airport)" },
                        { title: "Panaderya (Bakeshop)" },
                        { title: "Bangko (Bank)" },
                        { title: "Bulwagang Pambarangay (Barangay Hall)" },
                        { title: "Tulay (Bridge)" },
                        { title: "Sabungan (Cockpit)" },
                        { title: "Dam/Dike" },
                        { title: "Pabrika (Factory)" },
                        { title: "Himpilang Pangsunog (Fire Station)" },
                        { title: "Moog (Fortress)" },
                        {
                            title: "Gusali ng Ahensyang Pampamahalaan (Government Agency Building)",
                        },
                        { title: "Gymnasium" },
                        { title: "Hotel" },
                        { title: "Ice Plant" },
                        { title: "Laboratoryo (Laboratory)" },
                        { title: "Aklatan (Library)" },
                        { title: "Palengke (Marketplace)" },
                        {
                            title: "Munisipyo/Bulwagang Panlungsod (Municipal/City Hall)",
                        },
                        { title: "Museo (Museum)" },
                        { title: "Himpilang Pampulisya (Police Station)" },
                        { title: "Planta ng Enerhiya (Power Plant)" },
                        {
                            title: "Kapitolyo (Provincial/Regional Capitol Building)",
                        },
                        {
                            title: "Pasilidad Pampreso/Koreksiyonal (Prison/Correctional Facility)",
                        },
                        {
                            title: "Sentrong Panlibangan/Isport (Recreation/Sports Center)",
                        },
                        { title: "Kiskisan (Rice Mill)" },
                        { title: "Pier (Seaport)" },
                        { title: "Dapilan/Asukarera (Sugar Mill)" },
                        { title: "Tindahan (Store)" },
                        { title: "Himpilan ng Tren/Bus (Train/Bus Station)" },
                        { title: "Pagawaan ng Alak (Winery)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Paaralan at Pang-Edukasyong Komplex (Schools and Educational Complexes)",
                    subcategory: [
                        { title: "Gabaldon" },
                        { title: "Uring Marcos (Marcos Type)" },
                        { title: "Uring Imelda (Imelda Type)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Ospital at Pasilidad Pangkalusugan (Hospital and Health Facilities)",
                    subcategory: [
                        { title: "Pagamutan (Clinic)" },
                        { title: "Sentrong Pangkalusugan (Health Center)" },
                        { title: "Ospital (Hospital)" },
                        { title: "Pagamutang Pangketong (Leprosarium)" },
                        { title: "Bahay Tuluyan ng Matatanda (Nursing Home)" },
                        { title: "Sanitaryum (Sanitarium)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Simbahan, Templo, at mga Lugar ng Pagsamba (Churches, Temples, and Places of Worship)",
                    subcategory: [
                        { title: "Simbahan (Church)" },
                        { title: "Masjid (Mosque)" },
                        { title: "Templo (Temple)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Bantayog at Pananda (Monuments and Markers)",
                    subcategory: [
                        { title: "Entabladong Pambanda (Bandstand)" },
                        { title: "Likhang-Bukal (Fountain)" },
                        { title: "Templo (Shrine)" },
                        { title: "Rebulto (Statue)" },
                        { title: "Balon (Well)" },
                        { title: "Panandang Pagtanggap (Welcome Marker)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Lugar (Sites)",
                    subcategory: [
                        { title: "Lugar Arkeolohiya (Archaeological Site)" },
                        { title: "Lugar Libingan (Burial Site)" },
                        { title: "Libingan (Cemetery)" },
                        { title: "Pamanang Tanawin (Heritage Landscape)" },
                        {
                            title: "Pamanang Tubig-Tanawin (Heritage Waterscape)",
                        },
                        { title: "Parke/Liwasan (Park)" },
                        { title: "Liwasan (Plaza)" },
                        { title: "Lugar Peregrinasyon (Pilgrimage Site)" },
                        { title: "Sistemang Daang-Bakal (Railway System)" },
                        { title: "Palaruang Kompleks (Sports Complex)" },
                        { title: "Kalsada (Street)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Pamanang Bahay/Katutubong Arkitektura (Heritage Houses/Vernacular Architecture)",
                    subcategory: [
                        { title: "Katutubo (Indigenous)" },
                        { title: "Pahanon ng Espanyol (Spanish Period)" },
                        { title: "Panahon ng Amerikano (American Period)" },
                        {
                            title: "Panahon ng Ikalawang Digmaang Pandaigdig (World War II Period)",
                        },
                        { title: "Makalipas ang Digmaan (Postwar)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Likas na Pagkakabuong Heolohikal at Pisyograpikal/Pormasyon ng Lupa (Natural Geological and Physiographical/Land Formations)",
                    subcategory: [
                        { title: "Tubigang Guwang (Basin)" },
                        { title: "Kweba (Cave)" },
                        { title: "Burol (Hill)" },
                        { title: "Latian (Marshland)" },
                        { title: "Bundok (Mountain)" },
                        { title: "Putikan (Mudhole)" },
                        { title: "Kapatagan (Plains)" },
                        { title: "Talampas (Plateau)" },
                        { title: "Kabatohan (Rock Formation)" },
                        { title: "Rock Shelter" },
                        { title: "Sandbar" },
                        { title: "Hukay (Sinkhole)" },
                        { title: "Pampang (Shore)" },
                        { title: "Lambak (Valley)" },
                        { title: "Bulkan (Volcano)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Uri ng Katubigan (Bodies of Water)",
                    subcategory: [
                        { title: "Kanal (Canal)" },
                        { title: "Estero (Estuary)" },
                        { title: "Lawa (Lake)" },
                        { title: "Pond" },
                        { title: "Ilog o Pampang (River or Riverbank)" },
                        { title: "Karagatan (Sea)" },
                        { title: "Bukal (Spring)" },
                        { title: "Sapa (Stream)" },
                        { title: "Talon (Waterfalls)" },
                        { title: "Latian (Wetland)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Halaman (Flora)",
                    subcategory: [
                        { title: "Pantubig (Aquatic)" },
                        { title: "Yerba (Herb) (Succulent Plant (Herb))" },
                        { title: "Palumpong (Shrub)" },
                        { title: "Puno (Tree)" },
                        { title: "Baging (Vine)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Hayop (Fauna)",
                    subcategory: [
                        { title: "Pantubig at Panlupa (Amphibian)" },
                        { title: "Ibon (Bird)" },
                        { title: "Isda (Fish)" },
                        { title: "Insekto/Araknida (Insect/Arachnid)" },
                        { title: "Mamalya (Mammal)" },
                        {
                            title: "Marine/Tubig-Tabang Organismo (Marine/Freshwater Organism)",
                        },
                        { title: "Reptilya (Reptile)" },
                        { title: "Uod/Myriapoda (Worm/Myriapod)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Iniingatang Pook (Protected Areas)",
                    subcategory: [
                        {
                            title: "Kublihang Pang-Ibon at Hayop-Gubat (Bird and Wildlife Sanctuary)",
                        },
                        { title: "Kublihang Pang-isda (Fish Sanctuary)" },
                        { title: "Silungan ng Pangangaso (Game Refuge)" },
                        {
                            title: "Tandang Likas Historikal (Natural Historical Landmark)",
                        },
                        { title: "Pambansang Liwasan (National Park)" },
                        {
                            title: "Itinalaga at Pinangangasiwaang Tanawin (Protected and Managed Landscape)",
                        },
                        {
                            title: "Itinalaga at Pinangangasiwaang Katubigan (Protected and Managed Seascape)",
                        },
                        {
                            title: "Itinalagang Pook Pangkalikasan (Strict Nature Reserve)",
                        },
                        { title: "Kagubatang Basal (Virgin Forest)" },
                        {
                            title: "Kublihang Pantubig/Bakawan (Water/Mangrove Reserve)",
                        },
                        { title: "Pook Pangkagubatan (Wilderness Area)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
            ],
            f2Category: [
                //form 2
                {
                    title: "Kagamitang Arkeolohikal (Archaeological Materials)",
                    subcategory: [
                        {
                            title: "Artepakto",
                            classification: [
                                {
                                    title: "Seramiko- Mga Kagamitang Gawa sa Bato, Porselana, at Luwad (Ceramic- Stoneware, Porcelain, Earthenware)",
                                },
                                {
                                    title: "Salamin/Gawa sa Salamin (Glass/Glasswares)",
                                },
                                {
                                    title: "Bakal/Gawa sa Metal (Metal/Metalware)",
                                },
                                { title: "Bulalakaw (Meteorites)" },
                                {
                                    title: "Mga Kagamitang Gawa sa Buto ng Hayop (Modified Bone)",
                                },
                                {
                                    title: "Mga Kagamitang Gawa sa Balat ng Kabibi (Modified Shell)",
                                },
                                {
                                    title: "Mga Dekorasyong Gawa sa Bato (Stone Ornaments/Decorations)",
                                },
                                {
                                    title: "Mga Kagamitang Gawa sa Bato (Stone Tools)",
                                },
                                { title: "Others (Please Specify)" },
                            ],
                        },
                        {
                            title: "Ekopakto",
                            classification: [
                                { title: "Buto (Bones)" },
                                { title: "Fossil (Fossils)" },
                                {
                                    title: "Mga Labi ng Halaman (Plant Remains)",
                                },
                                { title: "Balat ng Kabibi/Kabibi (Shells)" },
                                { title: "Others (Please Specify)" },
                            ],
                        },
                    ],
                },
                {
                    title: "Kagamitang Etnograpikal (Ethnographical Materials)",
                    subcategory: [
                        { title: "Crafts (Crafts)" },
                        { title: "Mga Gamit Pambahay (Household Items)" },
                        {
                            title: "Mga Instrumentong Pangmusika (Musical Instruments)",
                        },
                        {
                            title: "Mga Personal na Palamuti (Personal Adornment)",
                        },
                        { title: "Mga Habi (Textiles)" },
                        {
                            title: "Mga Tradisyonal Na Pananamit o Kasuotan (Traditional Clothing)",
                        },
                        { title: "Mga Armas (Weaponry)" },
                        {
                            title: "Mga Kasangkapan sa Manu-Manong Trabaho (Work Implements)",
                        },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Kagamitang Panrelihiyon (Religious Objects)",
                    subcategory: [
                        {
                            title: "Mga Kagamitang Pangliturhiya/Panrelihiyon (Liturgical Objects)",
                        },
                        { title: "Mga Reliko (Relics)" },
                        {
                            title: "Panrelihiyong Lilok/Imahen (Religious Sculpture/Image)",
                        },
                        {
                            title: "Mga Pananamit ng mga Alagad ng Relihiyon (Vestments)",
                        },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Likha ng Sining Industriyal/Komersyal (Works of Industrial/Commercial Arts)",
                    subcategory: [
                        { title: "Mga Barya (Coins)" },
                        { title: "Mga Muwebles/Kasangkapan (Furnitures)" },
                        { title: "Mga Uri ng Sasakyan (Vehicles)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Likha ng Sining (Artworks)",
                    subcategory: [
                        { title: "Ipinintang Larawan (Painting)" },
                        {
                            title: "Inimprentang Larawan (Prints- Linocut Print, Woodcut Print)",
                        },
                        { title: "Lilok (Sculpture)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Arkibo (Archival Holdings)",
                    subcategory: [
                        { title: "Mga Librong Nakaimprenta (Book)" },
                        {
                            title: "Magnetic Media (including Cassette Tape and Vinyl Record)",
                        },
                        {
                            title: "Manuskrito/Librong Nakasulat-kamay (Manuscript/Handwritten Book)",
                        },
                        {
                            title: "Camera Negatives, Microfiche (Negatives - Camera Negatives, Microfiche)",
                        },
                        { title: "Mga Dokumentong Nakasulat sa Papel (Paper)" },
                        { title: "Mga Larawan (Photographs)" },
                        { title: "Others (Please Specify)" },
                    ],
                },
                {
                    title: "Mga Espesimen ng Natural na Kasaysayan (Natural History Specimens)",
                    subcategory: [
                        {
                            title: "Bagay na may Kaugnayan sa Byolohiya (Biological Objects)",
                        },
                        {
                            title: "Bagay na may Kaugnayan sa Botanika (Botanical Objects)",
                        },
                        {
                            title: "Bagay na may Kaugnayan sa Kapaligiran (Environmental Objects)",
                        },
                        {
                            title: "Bagay na may Kaugnayan sa Heolohiya (Geological Objects)",
                        },
                        {
                            title: "Bagay na may Kaugnayan sa Palyontolohiya (Paleontological Objects)",
                        },
                        { title: "Others (Please Specify)" },
                    ],
                },
                { title: "Iba Pa (Others)" },
            ],
        };
    },
};
</script>
