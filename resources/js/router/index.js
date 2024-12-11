import { createRouter, createWebHistory } from 'vue-router';
import RegionPage from '../components/RegionPage.vue';
import ZonePage from '../components/ZonePage.vue';
import WoredaPage from '../components/WoredaPage.vue';
import HouseholdPage from '../components/HouseholdPage.vue';
import Homepage from '../views/homepage.vue'; // Ensure this matches
import Login from '../views/login.vue';
import Register from '../views/register.vue';
import About from '../views/About.vue'
import RegionManager from '../views/RegionManager.vue'
const routes = [
  { path: '/', component: Homepage },
  { path: '/login', component: Login }, 
  { path: '/register', component: Register }, 
  { path: '/about', component: About }, // Ensure this matches the import name
  { path: '/regions/:regionId/zones', component: ZonePage },
  { path: '/zones/:zoneId/woredas', component: WoredaPage },
  { path: '/woredas/:woredaId/households', component: HouseholdPage },
  // { path: '/about', component: () => import('@/views/About.vue') },
  // { path: '/login', component: () => import('@/views/Login.vue') },
  // { path: '/register', component: () => import('@/views/Register.vue') },
   { path: '/regions', component: RegionManager},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
