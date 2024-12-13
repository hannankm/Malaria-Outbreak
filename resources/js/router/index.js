import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "../layouts/DefaultLayout.vue"; // Default Layout
import Homepage from "../views/HomePage/homepage.vue";
import Login from "../views/HomePage/login.vue";
import Register from "../views/HomePage/register.vue";
import About from "../views/HomePage/About.vue";

import householdRoutes from "./householdRoutes";
import dashboardRoutes from "./dashboardRoutes";
import regionRoutes from "./regionmanager";
import zoneManagerRoutes from "./zonemanager";
import userRoutes from "./user";

// Wrap all routes with DefaultLayout
const routes = [
    {
        path: "/",
        component: DefaultLayout,
        children: [
            { path: "", component: Homepage, name: "Home" },
            { path: "login", component: Login, name: "Login" },
            { path: "register", component: Register, name: "Register" },
            { path: "about", component: About, name: "About" },
            ...householdRoutes,
            ...dashboardRoutes,
            ...regionRoutes,
            ...zoneManagerRoutes,
            ...userRoutes,
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
