<template>
    <div class="household-view">
        <h2 class="mb-4">Households</h2>

        <!-- Table -->
        <el-table :data="households" stripe style="width: 100%">
            <el-table-column label="#" type="index" width="50" />

            <el-table-column
                prop="houseNumber"
                label="House Number"
                width="200"
            />

            <el-table-column prop="name" label="Name" width="300" />

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
            households: [],
        };
    },
    methods: {
        async fetchHouseholds() {
            try {
                const response = await getHouseholds();
                this.households = response.data;
            } catch (error) {
                console.error("Error fetching households:", error);
            }
        },
        viewDetails(id) {
            this.$router.push({ name: "HouseholdDetail", params: { id } });
        },
    },
    mounted() {
        this.fetchHouseholds();
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
