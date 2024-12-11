import { createRouter, createWebHistory } from 'vue-router';
import RegionPage from '../components/RegionPage.vue';
import ZonePage from '../components/ZonePage.vue';
import WoredaPage from '../components/WoredaPage.vue';
import HouseholdPage from '../components/HouseholdPage.vue';
import Homepage from '../views/homepage.vue'; // Ensure this matches
import Login from '../views/login.vue';
import Register from '../views/register.vue';
import RegionManager from '../views/RegionManager.vue'
import About from '../views/About.vue'


const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Homepage },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/about', component: About },
    { path: '/regions', component: RegionManager },
  ],
});

export default router;
