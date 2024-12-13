// src/router/householdRoutes.js
import HouseholdView from "@/views/Household/HouseholdView.vue";
import HouseholdDetail from "@/views/Household/HouseholdDetail.vue";
import AddHousehold from "@/views/Household/AddHousehold.vue";

const householdRoutes = [
    {
        path: "/woredas/:woredaId/households",
        name: "HouseholdView",
        component: HouseholdView,
        meta: { title: "Households" },
    },
    {
        path: "/households/by-region",
        name: "HouseholdViewRegion",
        component: HouseholdView,
        meta: { title: "Households" },
    },
    {
        path: "/households/all",
        name: "HouseholdViewAll",
        component: HouseholdView,
        meta: { title: "Households" },
    },

    {
        path: "/woredas/:woredaId/households/:id",
        name: "HouseholdDetail",
        component: HouseholdDetail,
        props: true,
        meta: { title: "Household Details" },
    },
    {
        path: "/households/add",
        name: "AddHousehold",
        component: AddHousehold,
    },
];

export default householdRoutes;
