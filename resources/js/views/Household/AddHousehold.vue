<template>
    <div class="add-household">
        <h2>Add New Household</h2>

        <!-- New Household Form -->
        <el-form
            :model="newHousehold"
            ref="form"
            label-width="120px"
            class="mb-4"
        >
            <el-form-item
                label="Full Name"
                :rules="[
                    {
                        required: true,
                        message: 'Please input full name',
                        trigger: 'blur',
                    },
                ]"
            >
                <el-input
                    v-model="newHousehold.full_name"
                    placeholder="Enter full name"
                />
            </el-form-item>

            <el-form-item
                label="House Number"
                :rules="[
                    {
                        required: true,
                        message: 'Please input house number',
                        trigger: 'blur',
                    },
                ]"
            >
                <el-input
                    v-model="newHousehold.house_number"
                    placeholder="Enter house number"
                />
            </el-form-item>

            <el-form-item
                label="Phone Number"
                :rules="[
                    {
                        required: true,
                        message: 'Please input phone number',
                        trigger: 'blur',
                    },
                ]"
            >
                <el-input
                    v-model="newHousehold.phone_number"
                    placeholder="Enter phone number"
                />
            </el-form-item>

            <el-form-item label="No of Adults">
                <el-input
                    v-model="newHousehold.no_of_adults"
                    type="number"
                    placeholder="Enter number of adults"
                />
            </el-form-item>

            <el-form-item label="No of Children">
                <el-input
                    v-model="newHousehold.no_of_children"
                    type="number"
                    placeholder="Enter number of children"
                />
            </el-form-item>

            <el-form-item label="Location">
                <el-input
                    v-model="newHousehold.location"
                    placeholder="Enter location"
                />
            </el-form-item>

            <el-form-item label="Spouse Name">
                <el-input
                    v-model="newHousehold.spouse_name"
                    placeholder="Enter spouse name"
                />
            </el-form-item>

            <el-form-item label="Spouse Phone Number">
                <el-input
                    v-model="newHousehold.spouse_phone_number"
                    placeholder="Enter spouse phone number"
                />
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="addHousehold"
                    >Add Household</el-button
                >
                <el-button
                    type="primary"
                    @click="
                        $router.push({
                            name: 'HouseholdView',
                            params: {
                                woredaId: this.$store.getters['user/woredaId'],
                            },
                        })
                    "
                >
                    Cancel
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import { addHousehold } from "@/api/household"; // Import the API function

export default {
    name: "AddHousehold",
    data() {
        return {
            newHousehold: {
                full_name: "",
                house_number: "",
                phone_number: "",
                no_of_adults: 0,
                no_of_children: 0,
                location: "",
                spouse_name: "",
                spouse_phone_number: "",
            },
        };
    },
    methods: {
        async addHousehold() {
            try {
                const response = await addHousehold(this.newHousehold);
                const woredaId = this.$store.getters["user/woredaId"];
                this.$router.push({
                    name: "HouseholdView",
                    params: { woredaId: woredaId },
                });
            } catch (error) {
                console.error("Error adding household:", error);
            }
        },
    },
};
</script>

<style scoped>
.add-household {
    padding: 20px;
}
h2 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
