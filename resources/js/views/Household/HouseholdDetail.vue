<template>
    <div class="household-detail">
        <!-- Household Details -->
        <el-card>
            <template #header>
                <div class="card-header">
                    <span>Household Details</span>
                </div>
            </template>

            <!-- Loading Skeleton -->
            <el-skeleton :loading="loading" animated>
                <template #default>
                    <el-descriptions
                        :column="2"
                        border
                        class="custom-descriptions"
                    >
                        <el-descriptions-item label="House Number">
                            {{ household.house_number || "N/A" }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Full Name">
                            {{ household.full_name || "N/A" }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Spouse Name">
                            {{ household.spouse_name || "N/A" }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Location">
                            {{ household.location || "N/A" }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Adults">
                            {{ household.no_of_adults || "N/A" }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Children">
                            {{ household.no_of_children || "N/A" }}
                        </el-descriptions-item>
                    </el-descriptions>
                </template>
            </el-skeleton>
        </el-card>

        <el-card>
            <el-button type="primary" @click="openUpdateModal"
                >Update Household</el-button
            >

            <el-button type="danger" @click="openDeleteModal"
                >Delete Household</el-button
            >
        </el-card>

        <!-- Household Stats Section -->

        <h1 class="mt-5">Household Stats</h1>
        <!-- Button to Open the Modal -->
        <el-button type="primary" @click="openAddHouseholdStatModal">
            Add Household Stat
        </el-button>

        <!-- Modal Component -->
        <AddHouseholdStatModal
            :householdId="household.id"
            v-model="isAddHouseholdStatModalVisible"
            @close="closeAddHouseholdStatModal"
        />
        <div
            v-if="household.household_stats && household.household_stats.length"
        >
            <el-collapse v-model="activeStats">
                <!-- Loop through household stats -->
                <el-collapse-item
                    v-for="stat in household.household_stats"
                    :key="stat.id"
                    :title="stat.date"
                >
                    <!-- General Stats -->
                    <el-descriptions :column="2" border>
                        <el-descriptions-item label="Active Cases">
                            {{ stat.no_of_active_cases || 0 }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Deaths">
                            {{ stat.no_of_death || 0 }}
                        </el-descriptions-item>
                        <el-descriptions-item label="At Risk">
                            {{ stat.no_of_people_at_risk || 0 }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Recovered">
                            {{ stat.no_of_recovered || 0 }}
                        </el-descriptions-item>
                        <el-descriptions-item label="New Cases">
                            {{ stat.no_of_new_cases || 0 }}
                        </el-descriptions-item>
                    </el-descriptions>

                    <!-- Malaria Cases Grouped by Status -->
                    <div
                        v-if="
                            stat.malaria_cases &&
                            stat.malaria_cases.grouped_by_status
                        "
                    >
                        <el-collapse
                            v-model="activeMalariaCases[stat.id]"
                            accordion
                        >
                            <!-- Loop through malaria cases grouped by status -->
                            <el-collapse-item
                                v-for="(group, status) in stat.malaria_cases
                                    .grouped_by_status"
                                :key="status"
                                :title="'Status: ' + status"
                            >
                                <!-- Loop through cases in each group -->
                                <div
                                    v-for="malaria in group.cases"
                                    :key="malaria.id"
                                >
                                    <el-descriptions :column="2" border>
                                        <el-descriptions-item label="Age Group">
                                            {{ malaria.age_group }}
                                        </el-descriptions-item>
                                        <el-descriptions-item label="Sex">
                                            {{ malaria.sex }}
                                        </el-descriptions-item>
                                        <el-descriptions-item label="Diagnosed">
                                            {{
                                                malaria.diagnosed ? "Yes" : "No"
                                            }}
                                        </el-descriptions-item>
                                    </el-descriptions>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>

                    <!-- No Malaria Cases -->
                    <div v-else>
                        <p>No malaria cases available for this stat.</p>
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <el-button
                type="primary"
                @click="$router.push({ name: 'HouseholdView' })"
            >
                Back
            </el-button>
        </div>

        <!-- Update Modal -->
        <el-dialog
            title="Update Household"
            v-model="isUpdateModalVisible"
            @close="closeUpdateModal"
        >
            <el-form :model="household" label-width="120px" class="mb-4">
                <el-form-item label="Full Name">
                    <el-input
                        v-model="household.full_name"
                        placeholder="Full name"
                    />
                </el-form-item>

                <el-form-item label="House Number">
                    <el-input
                        v-model="household.house_number"
                        placeholder="House number"
                    />
                </el-form-item>

                <el-form-item label="Phone Number">
                    <el-input
                        v-model="household.phone_number"
                        placeholder="Phone number"
                    />
                </el-form-item>

                <el-form-item label="No of Adults">
                    <el-input
                        v-model="household.no_of_adults"
                        type="number"
                        placeholder="No of adults"
                    />
                </el-form-item>

                <el-form-item label="No of Children">
                    <el-input
                        v-model="household.no_of_children"
                        type="number"
                        placeholder="No of children"
                    />
                </el-form-item>

                <el-form-item label="Location">
                    <el-input
                        v-model="household.location"
                        placeholder="Location"
                    />
                </el-form-item>

                <el-form-item label="Spouse Name">
                    <el-input
                        v-model="household.spouse_name"
                        placeholder="Spouse name"
                    />
                </el-form-item>

                <el-form-item label="Spouse Phone Number">
                    <el-input
                        v-model="household.spouse_phone_number"
                        placeholder="Spouse phone number"
                    />
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="updateHousehold"
                        >Update Household</el-button
                    >
                    <el-button @click="closeUpdateModal">Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <!-- Delete Modal -->
        <el-dialog
            title="Delete Household"
            v-model="isDeleteModalVisible"
            @close="closeUpdateModal"
        >
            <p>Are you sure you want to delete this household?</p>
            <el-button type="danger" @click="confirmDelete"
                >Yes, Delete</el-button
            >
            <el-button @click="closeDeleteModal">Cancel</el-button>
        </el-dialog>
    </div>
</template>

<script>
import {
    getHouseholdById,
    updateHousehold,
    deleteHousehold,
} from "@/api/household";
import AddHouseholdStatModal from "./AddHouseholdStatModal.vue";

export default {
    name: "HouseholdDetail",
    props: ["id"],
    components: {
        AddHouseholdStatModal,
    },
    data() {
        return {
            household: {},
            loading: true,
            activeStats: [], // Track active collapse items for household_stats
            activeMalariaCases: {}, // Track active malaria case items
            isUpdateModalVisible: false,
            isDeleteModalVisible: false,
            isAddHouseholdStatModalVisible: false,
        };
    },
    computed: {
        // Access householdId from the route params
        householdId() {
            return this.$route.params.id;
        },
    },
    methods: {
        async fetchHousehold() {
            try {
                const response = await getHouseholdById(this.id);
                this.household = response.data.data;
            } catch (error) {
                console.error("Error fetching household details:", error);
            } finally {
                this.loading = false;
            }
        },
        openUpdateModal() {
            console.log("Opening update modal");
            this.isUpdateModalVisible = true;
        },
        closeUpdateModal() {
            console.log("closing update modal");

            this.isUpdateModalVisible = false;
        },
        async updateHousehold() {
            try {
                await updateHousehold(this.household.id, this.household);
                // this.$router.push({
                //     name: "HouseholdView",
                //     params: { woredaId: this.$store.getters["user/woredaId"] },
                // });
                this.$message.success("Household updated successfully.");

                this.closeUpdateModal();
            } catch (error) {
                console.error("Error updating household:", error);
            }
        },
        openDeleteModal() {
            console.log("Opening delete modal");

            this.isDeleteModalVisible = true;
        },
        closeDeleteModal() {
            console.log("closing dlee modal");

            this.isDeleteModalVisible = false;
        },
        async confirmDelete() {
            try {
                await deleteHousehold(this.household.id);
                this.$router.push({
                    name: "HouseholdView",
                    params: { woredaId: this.$store.getters["user/woredaId"] },
                });
                this.$message.success("Household deleted successfully.");
                this.closeDeleteModal();
            } catch (error) {
                console.error("Error deleting household:", error);
                this.$message.error("Failed to delete household.");
            }
        },
        openAddHouseholdStatModal() {
            this.isAddHouseholdStatModalVisible = true;
        },
        // Method to close the modal
        closeAddHouseholdStatModal() {
            this.isAddHouseholdStatModalVisible = false;
        },
    },
    mounted() {
        this.fetchHousehold();
    },
};
</script>

<style scoped>
.household-detail {
    padding: 20px;
}

.card-header {
    font-size: 1.25rem;
    font-weight: bold;
}

.mt-5 {
    margin-top: 20px;
}

.custom-descriptions {
    margin: 20px 0;
}
</style>
