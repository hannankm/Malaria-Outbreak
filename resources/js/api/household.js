import apiService from "@/utils/apiService";
import store from "@/store";

const woredaId = store.getters["user/woredaId"];

export const getHouseholds = () => {
    return apiService.get(`/woredas/${woredaId}/households`); // API endpoint to fetch households
};

export const getHouseholdById = (id) => {
    return apiService.get(`/households/${id}`); // API endpoint for specific household
};
