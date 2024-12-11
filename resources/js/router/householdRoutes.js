// src/router/householdRoutes.js
import HouseholdView from "@/views/Household/HouseholdView.vue";
import HouseholdDetail from "@/views/Household/HouseholdDetail.vue";

const householdRoutes = [
    {
        path: "/woredas/:woredaId/households",
        name: "HouseholdView",
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
];

export default householdRoutes;
