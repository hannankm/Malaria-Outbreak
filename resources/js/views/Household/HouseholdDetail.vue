<template>
    <div class="household-detail">
        <el-card>
            <template #header>
                <div class="card-header">
                    <span>Household Details</span>
                </div>
            </template>

            <el-skeleton :loading="loading" animated>
                <template #default>
                    <el-descriptions title="Household Information" border>
                        <el-descriptions-item label="House Number">
                            {{ household.houseNumber }}
                        </el-descriptions-item>
                        <el-descriptions-item label="Name">
                            {{ household.name }}
                        </el-descriptions-item>
                        <!-- Add more fields here -->
                    </el-descriptions>
                </template>
            </el-skeleton>

            <div class="mt-4">
                <el-button type="primary" @click="$router.push('/')"
                    >Back</el-button
                >
            </div>
        </el-card>
    </div>
</template>

<script>
import { getHouseholdById } from "@/api/household";

export default {
    name: "HouseholdDetail",
    props: ["id"],
    data() {
        return {
            household: {},
            loading: true,
        };
    },
    methods: {
        async fetchHousehold() {
            try {
                const response = await getHouseholdById(this.id);
                this.household = response.data;
            } catch (error) {
                console.error("Error fetching household details:", error);
            } finally {
                this.loading = false;
            }
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
</style>
