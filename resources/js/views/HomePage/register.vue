<template>
    <div class="register-page">
        <div class="register-container">
            <el-card class="register-card">
                <h2>Register</h2>
                <el-form
                    :model="registerForm"
                    :rules="dynamicRules"
                    ref="registerForm"
                    label-position="top"
                >
                    <el-form-item label="Name" prop="name">
                        <el-input
                            v-model="registerForm.name"
                            placeholder="Name"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Email" prop="email">
                        <el-input
                            v-model="registerForm.email"
                            placeholder="Email"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Password" prop="password">
                        <el-input
                            type="password"
                            v-model="registerForm.password"
                            placeholder="Password"
                        ></el-input>
                    </el-form-item>
                    <el-form-item
                        label="Confirm Password"
                        prop="password_confirmation"
                    >
                        <el-input
                            type="password"
                            v-model="registerForm.password_confirmation"
                            placeholder="Confirm Password"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Phone Number" prop="phone_number">
                        <el-input
                            v-model="registerForm.phone_number"
                            placeholder="Phone Number"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Region" prop="region_id">
                        <el-select
                            v-model="registerForm.region_id"
                            filterable
                            placeholder="Select Region"
                            @change="fetchZones"
                        >
                            <el-option
                                v-for="region in regions"
                                :key="region.id"
                                :label="region.name"
                                :value="region.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item
                        label="Zone"
                        prop="zone_id"
                        v-if="zones.length"
                    >
                        <el-select
                            v-model="registerForm.zone_id"
                            filterable
                            placeholder="Select Zone"
                            @change="fetchWoredas"
                        >
                            <el-option
                                v-for="zone in zones"
                                :key="zone.id"
                                :label="zone.name"
                                :value="zone.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item
                        label="Woreda"
                        prop="woreda_id"
                        v-if="woredas.length"
                    >
                        <el-select
                            v-model="registerForm.woreda_id"
                            filterable
                            placeholder="Select Woreda"
                        >
                            <el-option
                                v-for="woreda in woredas"
                                :key="woreda.id"
                                :label="woreda.name"
                                :value="woreda.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Role" prop="role">
                        <el-select
                            v-model="registerForm.role"
                            placeholder="Select Role"
                        >
                            <el-option
                                label="Region-Admin"
                                value="region_admin"
                            ></el-option>
                            <el-option
                                label="Supervisor"
                                value="supervisor"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button
                            type="primary"
                            @click="handleRegister"
                            class="submit-btn"
                            >Register</el-button
                        >
                    </el-form-item>
                </el-form>
                <div>
                    <el-alert
                        v-if="errorMessage"
                        :title="errorMessage"
                        type="error"
                        show-icon
                    ></el-alert>
                    <el-alert
                        v-if="successMessage"
                        :title="successMessage"
                        type="success"
                        show-icon
                    ></el-alert>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
import Navigation from "@/layouts/components/Navigation.vue";
import auth from "@/api/auth";
import { fetchRegions } from "@/api/region";

export default {
    components: {
        Navigation,
    },
    data() {
        return {
            registerForm: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                phone_number: "",
                region_id: "",
                zone_id: "",
                woreda_id: "",
                role: "",
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "Name is required",
                        trigger: "blur",
                    },
                ],
                email: [
                    {
                        required: true,
                        message: "Email is required",
                        trigger: "blur",
                    },
                    {
                        type: "email",
                        message: "Invalid email format",
                        trigger: "blur",
                    },
                ],
                phone_number: [
                    {
                        required: true,
                        message: "Phone number is required",
                        trigger: "blur",
                    },
                ],
                region_id: [
                    {
                        required: true,
                        message: "Region is required",
                        trigger: "change",
                    },
                ],
                role: [
                    {
                        required: true,
                        message: "Role is required",
                        trigger: "change",
                    },
                ],
            },
            regions: [],
            zones: [],
            woredas: [],
            errorMessage: "",
            successMessage: "",
        };
    },
    computed: {
        dynamicRules() {
            const rules = { ...this.rules };
            if (this.registerForm.role === "supervisor") {
                rules.woreda_id = [
                    {
                        required: true,
                        message: "Woreda is required for Supervisors",
                        trigger: "change",
                    },
                ];
            } else {
                rules.woreda_id = [{ required: false, trigger: "change" }];
            }
            return rules;
        },
    },
    methods: {
        async fetchRegions() {
            try {
                const response = await fetchRegions();
                this.regions = response.data; // Update here to match the API response structure
            } catch (error) {
                console.error("Error fetching regions:", error);
            }
        },
        fetchZones(regionId) {
            const selectedRegion = this.regions.find(
                (region) => region.id === regionId
            );
            this.zones = selectedRegion ? selectedRegion.zones : [];
            this.registerForm.zone_id = "";
            this.woredas = [];
        },
        fetchWoredas(zoneId) {
            const selectedZone = this.zones.find((zone) => zone.id === zoneId);
            this.woredas = selectedZone ? selectedZone.woredas : [];
        },
        async handleRegister() {
            try {
                // Validate the form synchronously
                const valid = await this.$refs.registerForm.validate();
                if (!valid) {
                    this.errorMessage =
                        "Please correct the errors before submitting.";
                    return; // Stop the registration process
                }

                // Ensure woreda_id is null if not selected
                if (!this.registerForm.woreda_id) {
                    this.registerForm.woreda_id = null;
                }

                console.log("Form Data:", this.registerForm); // Debug log to check form data
                const response = await auth.registerUser(this.registerForm);
                this.successMessage = "User registered successfully";
                console.log(response);
            } catch (error) {
                if (error.response && error.response.data) {
                    console.error(
                        "Validation Errors:",
                        error.response.data.errors
                    );
                    this.errorMessage =
                        "Validation failed: " +
                        JSON.stringify(error.response.data.errors);
                } else {
                    console.error("Registration error:", error);
                    this.errorMessage = "Registration failed";
                }
            }
        },
    },
    mounted() {
        this.fetchRegions();
    },
};
</script>

<style scoped>
.register-page {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #f5f5f5;
    padding: 20px;
}

.register-container {
    display: flex;
    justify-content: center;
    width: 100%;
}

.register-card {
    width: 500px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background-color: white;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.el-form-item {
    margin-bottom: 15px;
}

.el-input,
.el-select,
.el-button {
    width: 100%;
}

.submit-btn {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 15px;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.el-alert {
    margin-top: 20px;
}
</style>
