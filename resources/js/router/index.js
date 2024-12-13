import { createRouter, createWebHistory } from "vue-router";
import Homepage from "../views/HomePage/homepage.vue";
import Login from "../views/HomePage/login.vue";
import Register from "../views/HomePage/register.vue";
import About from "../views/HomePage/About.vue";
import householdRoutes from "./householdRoutes";
import regionRoutes from "./regionmanager"; 
import zoneManagerRoutes from "./zonemanager";
import usermanagment from "../views/User/usermanagment.vue";

const routes = [
    { path: "/", component: Homepage },
    { path: "/login", component: Login },
    { path: "/register", component: Register },
    { path: "/about", component: About },
    { path: "/users", component: usermanagment },

    ...householdRoutes, // Spread household routes
    ...regionRoutes, 
    ...zoneManagerRoutes, 
      // Spread region routes
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
