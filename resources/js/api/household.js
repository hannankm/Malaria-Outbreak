import apiService from "@/utils/apiService";
import store from "@/store";

export const getHouseholds = () => {
    const woredaId = store.getters["user/woredaId"];
    return apiService.get(`/woredas/${woredaId}/households`);
};

export const getHouseholdById = (id) => {
    return apiService.get(`/households/${id}`); // API endpoint for specific household
};
