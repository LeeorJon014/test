<!-- StatusUpdateModal.vue -->
<template>
    <div
        v-if="isOpen"
        class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-50"
    >
        <div
            class="fixed inset-0 opacity-50 backdrop-filter backdrop-blur-lg"
            @click.self="closeModal"
        ></div>
        <div class="relative shadow bg-white rounded-lg p-8">
            <div class="overflow-x-auto font-semibold">
                <p class="text-lg">{{ name }}</p>
                <table
                    class="inline-block min-w-full border-midnight-express border-opacity-30 rounded-tl-lg rounded-tr-lg overflow-hidden"
                >
                    <thead class="bg-midnight-express text-ronchi">
                        <tr>
                            <th class="px-4 py-2">Current Validation Status</th>
                            <th class="px-4 py-2">Update To:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 font-semibold text-center">
                                <p v-if="currentStatus === 'PUBLIC VALIDATION'">
                                    {{ daysPassed }} days and
                                    {{ hoursPassed }} hours <br />
                                    in
                                </p>
                                {{ currentStatus }}
                                <p
                                    v-if="shouldDisplayMessage"
                                    class="text-red-500"
                                >
                                    Can't update status, wait for the status to
                                    become {{ requiredStatus }}
                                </p>
                            </td>
                            <td class="px-4 py-2">
                                <button
                                    v-for="option in options"
                                    :key="option.value"
                                    @click="handleButtonClick(option)"
                                    :disabled="isButtonDisabled"
                                    class="block py-2 px-4 mb-2 rounded-md bg-gray-200 hover:bg-mountain-meadow focus:outline-none w-full h-full"
                                    :class="{
                                        'hover:bg-gray-200 cursor-not-allowed':
                                            isButtonDisabled,
                                    }"
                                >
                                    {{ option.label }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "options",
        "isOpen",
        "currentStatus",
        "name",
        "currentRole",
        "validationStarted",
    ],
    computed: {
        shouldDisplayMessage() {
            return this.isButtonDisabled;
        },
        requiredStatus() {
            const statusConditions = {
                "administrative-service-officer": [],
                "registry-coordinator": ["COMPLIANT"],
                "cultural-registry-data-officer": ["REVIEW"],
                "precup-head": ["ACCESSIONING"],
                "geographic-information-systems-analyst": ["PUBLISHED IN SITE"],
                "super-administrator": [],
                "invalid-slug": [],
            };

            const allowedStatuses = statusConditions[this.currentRole];

            if (
                allowedStatuses &&
                !allowedStatuses.includes(this.currentStatus)
            ) {
                // Returns a list of statuses that for the user is able to change the status of depending on their role
                return allowedStatuses.join(" or ");
            }

            return "";
        },
        daysPassed() {
            if (
                this.currentStatus === "PUBLIC VALIDATION" &&
                this.validationStarted
            ) {
                // Calculate the difference in milliseconds between current date and validated date
                const diffMs = new Date() - new Date(this.validationStarted);
                // Convert milliseconds to days (1 day = 24 hours = 86400000 milliseconds)
                return Math.floor(diffMs / 86400000);
            } else {
                return "";
            }
        },
        hoursPassed() {
            if (
                this.currentStatus === "PUBLIC VALIDATION" &&
                this.validationStarted
            ) {
                // Calculate the difference in milliseconds between current date and validated date
                const diffMs = new Date() - new Date(this.validationStarted);
                // Convert milliseconds to hours (1 hour = 3600000 milliseconds)
                return Math.floor(diffMs / 3600000) % 24; // Modulo 24 to get hours within a day
            } else {
                return "";
            }
        },
        isButtonDisabled() {
            const statusConditions = {
                "administrative-service-officer": [
                    "PENDING",
                    "NONCOMPLIANT",
                    "COMPLIANT",
                    "REVIEW",
                    "ACCESSIONING",
                    "PUBLISHED IN SITE",
                    "PUBLIC VALIDATION",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "registry-coordinator": [
                    "COMPLIANT",
                    "REVIEW",
                    "ACCESSIONING",
                    "PUBLISHED IN SITE",
                    "PUBLIC VALIDATION",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "cultural-registry-data-officer": [
                    "REVIEW",
                    "ACCESSIONING",
                    "PUBLISHED IN SITE",
                    "PUBLIC VALIDATION",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "precup-head": [
                    "ACCESSIONING",
                    "PUBLISHED IN SITE",
                    "PUBLIC VALIDATION",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "geographic-information-systems-analyst": [
                    "PUBLISHED IN SITE",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "super-administrator": [
                    "PENDING",
                    "NONCOMPLIANT",
                    "COMPLIANT",
                    "REVIEW",
                    "ACCESSIONING",
                    "PUBLISHED IN SITE",
                    "PUBLIC VALIDATION",
                    "PROCESSING FOR MAPAMANA",
                    "PUBLISHED IN MAPAMANA",
                ],
                "invalid-slug": [],
            };

            const allowedStatuses = statusConditions[this.currentRole];

            if (
                allowedStatuses &&
                !allowedStatuses.includes(this.currentStatus)
            ) {
                return true; // Disable the button
            }
            return false; // Button is not disabled
        },
    },
    methods: {
        handleButtonClick(option) {
            this.$emit("selectOption", option);
            this.$emit("modalClosed");
            this.$emit("close");
        },
        closeModal() {
            this.$emit("modalClosed");
            this.$emit("close");
        },
    },
};
</script>

<style scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
}
.shadow {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.modal-content {
    background-color: white;
    padding: 20px;
}

.backdrop-filter {
    -webkit-backdrop-filter: blur(0.8px); /* Safari */
    backdrop-filter: blur(0.8px); /* Standard syntax */
}

.cursor-not-allowed {
    cursor: not-allowed;
}

.cursor-not-allowed:hover {
    background-color: grey;
}
</style>
