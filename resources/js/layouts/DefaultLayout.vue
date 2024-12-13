<template>
    <div>
        <!-- Navbar for Non-Authenticated Users -->
        <el-menu
            v-if="!isAuthenticated"
            :default-active="activeMenu"
            mode="horizontal"
        >
            <el-menu-item>
                <router-link to="/">Home</router-link>
            </el-menu-item>
            <el-menu-item>
                <router-link to="/login">Login</router-link>
            </el-menu-item>
            <el-menu-item>
                <router-link to="/register">Register</router-link>
            </el-menu-item>
        </el-menu>

        <!-- Sidebar for Authenticated Users -->
        <div class="main-container" v-else>
            <Sidebar :role="userRole" />
            <div class="content">
                <!-- Authenticated Users' Content -->
                <router-view />
            </div>
        </div>

        <!-- Content for Non-Authenticated Users (Pages like Home, Login, Register) -->
        <div v-if="!isAuthenticated" class="non-auth-content">
            <router-view />
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import Sidebar from "@/components/Sidebar.vue";

const store = useStore();

// Computed properties for user state
const isAuthenticated = computed(() => store.getters["user/isAuthenticated"]);
const userRole = computed(() => store.getters["user/userRole"]);
const activeMenu = computed(() => window.location.pathname);
</script>

<style scoped>
.main-container {
    display: flex;
    height: 100vh; /* Ensures full viewport height */
}

.content {
    flex-grow: 1;
    padding: 20px;
    overflow-y: auto;
}

.non-auth-content {
    padding: 20px;
}
</style>
