<template>
    <div class="household-view">
        <h2 class="mb-4">Households</h2>

        <!-- Search Box -->
        <el-input
            v-model="searchTerm"
            placeholder="Search by House Number or Name"
            @input="onSearch"
            style="margin-bottom: 20px; width: 100%"
        />
        <!-- Button to navigate to the Add New Household page -->
        <el-button type="primary" @click="goToAddHouseholdPage">
            Add New Household
        </el-button>

        <!-- Table -->
        <el-table :data="filteredHouseholds" stripe style="width: 100%">
            <el-table-column label="#" type="index" width="50" />

            <el-table-column
                prop="house_number"
                label="House Number"
                width="200"
            />

            <el-table-column prop="full_name" label="Name" width="300" />

            <el-table-column label="Action" align="center">
                <template #default="scope">
                    <el-button
                        type="primary"
                        size="small"
                        @click="viewDetails(scope.row.id)"
                    >
                        View
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
import { getHouseholds } from "@/api/household";

export default {
    name: "HouseholdView",
    data() {
        return {
            households: [], // Holds the full list of households
            filteredHouseholds: [], // Holds the filtered list based on search
            searchTerm: "", // Stores the search term
        };
    },
    methods: {
        // Fetch households from API
        async fetchHouseholds() {
            try {
                const response = await getHouseholds();
                this.households = response.data.data || [];
                this.filteredHouseholds = [...this.households]; // Initialize filteredHouseholds
                console.log(response.data); // Check the actual response
            } catch (error) {
                console.error("Error fetching households:", error);
            }
        },

        // Handle the search logic
        onSearch() {
            if (this.searchTerm.trim() === "") {
                this.filteredHouseholds = [...this.households]; // Reset to full list if no search term
            } else {
                // Filter households based on house_number or full_name
                this.filteredHouseholds = this.households.filter(
                    (household) => {
                        return (
                            household.house_number
                                .toLowerCase()
                                .includes(this.searchTerm.toLowerCase()) ||
                            household.full_name
                                .toLowerCase()
                                .includes(this.searchTerm.toLowerCase())
                        );
                    }
                );
            }
        },

        // Navigate to household detail view
        viewDetails(id) {
            this.$router.push({ name: "HouseholdDetail", params: { id } });
        },
        goToAddHouseholdPage() {
            this.$router.push({ name: "AddHousehold" });
        },
    },
    mounted() {
        this.fetchHouseholds(); // Fetch data on component mount
    },
};
</script>

<style scoped>
.household-view {
    padding: 20px;
}
h2 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
