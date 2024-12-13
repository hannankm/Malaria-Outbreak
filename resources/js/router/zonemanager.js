import ZoneManager from '../views/ZoneManager.vue';

export default [
  {
    path: '/zonemanager',
    name: 'ZoneManager',
    component: ZoneManager,
    meta: { requiresAuth: true } // Protect route with authentication
  }
];
