import RegionManagerView from "@/views/RegionManager.vue";
// import RegionDetail from "@/views/Region/RegionDetail.vue";

const regionRoutes = [
    {
        path: "/regions",
        name: "RegionManagerView",
        component: RegionManagerView,
        meta: { title: "Regions" },
    },
    // {
    //     path: "/regions/:id",
    //     name: "RegionDetail",
    //     component: RegionDetail,
    //     props: true,
    //     meta: { title: "Region Details" },
    // },
];

export default regionRoutes;
