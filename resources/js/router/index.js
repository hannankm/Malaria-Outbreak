import { createRouter, createWebHistory } from "vue-router";
import Homepage from "../views/HomePage/homepage.vue";
import Login from "../views/HomePage/login.vue";
import Register from "../views/HomePage/register.vue";
import About from "../views/HomePage/About.vue";
import householdRoutes from "../views/RegionManager.vue";
import regionRoutes from "./regionmanager"; // Import region routes

const routes = [
    { path: "/", component: Homepage },
    { path: "/login", component: Login },
    { path: "/register", component: Register },
    { path: "/about", component: About },
    ...householdRoutes, // Spread household routes
    ...regionRoutes,    // Spread region routes
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
