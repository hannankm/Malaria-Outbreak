import { createRouter, createWebHistory } from "vue-router";
import Homepage from "../views/homepage.vue"; // Ensure this matches
import Login from "../views/login.vue";
import Register from "../views/register.vue";
import About from "../views/About.vue";
import householdRoutes from "./householdRoutes";

const routes = [
    { path: "/", component: Homepage },
    { path: "/login", component: Login },
    { path: "/register", component: Register },
    { path: "/about", component: About }, // Ensure this matches the import name
    ...householdRoutes, // Spread the household routes here
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
