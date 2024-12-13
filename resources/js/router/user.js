import { createRouter, createWebHistory } from 'vue-router';
import UserManagement from '@/views/User/userManagement.vue'; 
import UserDetails from '@/views/UserDetails.vue'; 

const routes = [
  {
    path: '/users',
    name: 'UserManagement',
    component: UserManagement,
  },
  {
    path: '/users/:id',
    name: 'UserDetails',
    component: UserDetails,
    props: true, // Pass route params as props
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
