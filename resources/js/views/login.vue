<template>
    <div class="login">
        <Navigation />
        <el-card class="login-card">
            <h2>Login</h2>
            <el-form :model="loginForm" @submit.prevent="handleLogin">
                <el-form-item label="Email">
                    <el-input
                        v-model="loginForm.email"
                        placeholder="Email"
                    ></el-input>
                </el-form-item>
                <el-form-item label="Password">
                    <el-input
                        type="password"
                        v-model="loginForm.password"
                        placeholder="Password"
                    ></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" native-type="submit"
                        >Login</el-button
                    >
                </el-form-item>
            </el-form>
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
        </el-card>
    </div>
</template>

<script>
import Navigation from "../layouts/components/Navigation.vue";
import auth from "@/api/auth";
import { mapActions } from "vuex";

export default {
    components: {
        Navigation,
    },
    data() {
        return {
            loginForm: {
                email: "",
                password: "",
            },
            errorMessage: "",
            successMessage: "",
        };
    },
    methods: {
        ...mapActions(["user/setUser"]),
        async handleLogin() {
            try {
                console.log("Login form submitted:", this.loginForm);
                const response = await auth.loginUser(this.loginForm);

                // Dispatch Vuex action to store user data
                this.$store.dispatch("user/setUser", {
                    token: response.token,
                    user: {
                        name: response.user.name,
                        region_id: response.user.region_id,
                        woreda_id: response.user.woreda_id,
                    },
                    role: response.role, // Assuming role is an array, we're sending the first role
                });
                // console.log(this.$store.getter["user/user"]);

                // Handle successful login
                this.successMessage = response.message;
                this.errorMessage = "";

                const role = this.$store.getters["user/userRole"];
                console.log(role);

                const woredaId = this.$store.getters["user/woredaId"];
                console.log(woredaId);
                // Redirect to the dashboard
                if (role === "supervisor") {
                    this.$router.push(`/woredas/${woredaId}/households`);
                } else if (role === "user") {
                    this.$router.push("/user-dashboard");
                } else {
                    this.$router.push("/"); // Default route
                }
                // this.$router.push("/dashboard");
            } catch (error) {
                console.error("Login failed:", error);
                this.errorMessage =
                    error.response?.data?.message ||
                    "Invalid email or password";
                this.successMessage = "";
            }
        },
    },
};
</script>

<style scoped>
.login {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f5f5f5;
}
.login-card {
    width: 400px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
