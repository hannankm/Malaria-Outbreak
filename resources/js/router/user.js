import UserAdmin from "@/views/User/UserAdmin.vue";
import RegionAdmin from "@/views/User/RegionAdmin.vue";

const userRoutes = [
    {
        path: "/users",
        name: "UserAdmin",
        component: UserAdmin,
    },
    {
        path: "/users/region",
        name: "RegionAdmin",
        component: RegionAdmin,
    },
];

export default userRoutes;
