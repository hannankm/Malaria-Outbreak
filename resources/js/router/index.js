import { createRouter, createWebHistory } from "vue-router";
import Homepage from "../views/HomePage/homepage.vue";
import Login from "../views/HomePage/login.vue";
import Register from "../views/HomePage/register.vue";
import About from "../views/HomePage/About.vue";
import householdRoutes from "./householdRoutes";
import dashboardRoutes from "./dashboardRoutes";
import regionRoutes from "./regionmanager";
import zoneManagerRoutes from "./zonemanager";
import usermanagment from "../views/User/usermanagment.vue";

const routes = [
    { path: "/", component: Homepage, name: "Home" },
    { path: "/login", component: Login, name: "Login" },
    { path: "/register", component: Register, name: "Register" },
    { path: "/about", component: About }, // Ensure this matches the import name
    { path: "/users", component: usermanagment },

    ...householdRoutes, // Spread the household routes here
    ...dashboardRoutes,
    ...regionRoutes,
    ...zoneManagerRoutes,
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
