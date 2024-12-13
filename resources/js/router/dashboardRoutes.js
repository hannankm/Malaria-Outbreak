import RegionAdminDashboard from "@/views/Dashboard/RegionDashboard.vue";
import SuperAdminDashboard from "@/views/Dashboard/SuperAdminDashboard.vue";

const dashboardRoutes = [
    {
        path: "/region-admin-dashboard",
        name: "RegionAdminDashboard",
        component: RegionAdminDashboard,
        meta: { requiresAuth: true, role: "region-admin" },
    },
    {
        path: "/super-admin-dashboard",
        name: "SuperAdminDashboard",
        component: SuperAdminDashboard,
        meta: { requiresAuth: true, role: "super-admin" },
    },
    // Other routes...
];

export default dashboardRoutes;
