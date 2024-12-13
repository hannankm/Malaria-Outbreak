<template>
    <el-aside width="200px" class="sidebar">
        <el-menu
            :default-active="activeMenu"
            class="el-menu-vertical-demo"
            @select="handleMenuSelect"
        >
            <!-- Supervisor Menu -->
            <template v-if="role === 'supervisor'">
                <el-menu-item :index="`/woredas/${woredaId}/households`">
                    <el-icon><House /></el-icon>
                    <span>Households</span>
                </el-menu-item>
                <el-menu-item @click="handleLogout">
                    <el-icon><SwitchButton /></el-icon>
                    <span>Logout</span>
                </el-menu-item>
            </template>

            <!-- Region Admin Menu -->
            <template v-else-if="role === 'region_admin'">
                <el-menu-item index="/region-admin-dashboard">
                    <el-icon><Platform /></el-icon>
                    <span>Region Dashboard</span>
                </el-menu-item>
                <el-menu-item index="/users/region">
                    <el-icon><User /></el-icon>
                    <span>Users</span>
                </el-menu-item>
                <el-menu-item index="/zonemanager">
                    <el-icon><Location /></el-icon>
                    <span>Zones</span>
                </el-menu-item>
                <el-menu-item index="/woredas">
                    <el-icon><MapLocation /></el-icon>
                    <span>Woredas</span>
                </el-menu-item>
                <el-menu-item index="/households/by-region">
                    <el-icon><House /></el-icon>
                    <span>Households</span>
                </el-menu-item>
                <el-menu-item @click="handleLogout">
                    <el-icon><SwitchButton /></el-icon>
                    <span>Logout</span>
                </el-menu-item>
            </template>

            <!-- Super Admin Menu -->
            <template v-else-if="role === 'super_admin'">
                <el-menu-item index="/super-admin-dashboard">
                    <el-icon><OfficeBuilding /></el-icon>
                    <span>Super Admin Dashboard</span>
                </el-menu-item>
                <el-menu-item index="/users">
                    <el-icon><UserFilled /></el-icon>
                    <span>Users</span>
                </el-menu-item>
                <el-menu-item index="/regions">
                    <el-icon><Location /></el-icon>
                    <span>Regions</span>
                </el-menu-item>
                <el-menu-item index="/zonemanager">
                    <el-icon><MapLocation /></el-icon>
                    <span>Zones</span>
                </el-menu-item>
                <el-menu-item index="/woredas">
                    <el-icon><MapLocation /></el-icon>
                    <span>Woredas</span>
                </el-menu-item>
                <el-menu-item index="/households/all">
                    <el-icon><House /></el-icon>
                    <span>Households</span>
                </el-menu-item>
                <el-menu-item @click="handleLogout">
                    <el-icon><SwitchButton /></el-icon>
                    <span>Logout</span>
                </el-menu-item>
            </template>

            <!-- Fallback for Unknown Role -->
            <el-menu-item v-else>
                <el-icon><Warning /></el-icon>
                <span>Access Denied</span>
            </el-menu-item>
        </el-menu>
    </el-aside>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import {
    House,
    User,
    SwitchButton,
    Platform,
    Location,
    MapLocation,
    OfficeBuilding,
    UserFilled,
    Warning,
} from "@element-plus/icons-vue";

import { computed } from "vue";

// Props
defineProps({
    role: {
        type: String,
        required: true,
    },
});

const router = useRouter();
const store = useStore();

// Active menu path
const activeMenu = computed(() => window.location.pathname);
const woredaId = computed(() => store.getters["user/woredaId"]);

// Logout logic
const handleLogout = () => {
    store.dispatch("user/logoutUser");
    router.push("/login");
};
const handleMenuSelect = (index) => {
    if (index === "/logout") {
        handleLogout();
        console.log("Logging out...");
        // Redirect to login page after logout
        router.push("/login");
    } else {
        router.push(index); // Navigate to the path specified in `index`
    }
};
</script>

<style scoped>
.sidebar {
    height: 100vh;
    background-color: #f8f9fa;
}

.el-menu-vertical-demo {
    border-right: none;
}
</style>
