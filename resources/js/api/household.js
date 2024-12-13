import apiService from "@/utils/apiService";
import store from "@/store";

export const getHouseholds = (searchTerm = "") => {
    const woredaId = store.getters["user/woredaId"];
    const auth = store.getters["user/authToken"];

    // Include searchTerm as a query parameter
    return apiService.get(`/woredas/${woredaId}/households`, {
        params: {
            searchTerm, // Pass search term as query parameter
        },
    });
};

export const getHouseholdsAll = (searchTerm = "") => {
    // Include searchTerm as a query parameter
    return apiService.get(`/households`, {
        params: {
            searchTerm, // Pass search term as query parameter
        },
    });
};
export const getHouseholdsInRegion = (searchTerm = "") => {
    // Include searchTerm as a query parameter
    return apiService.get(`/households/by-region`, {
        params: {
            searchTerm, // Pass search term as query parameter
        },
    });
};

export const getHouseholdById = (id) => {
    // Fetch user role and woredaId from the store
    const userRole = store.getters["user/userRole"];
    const woredaId = store.getters["user/woredaId"];

    // Check the user role and determine the endpoint
    if (userRole === "supervisor") {
        // Fetch based on woreda ID if the role is 'woreda'
        return apiService.get(`/woredas/${woredaId}/households/${id}`);
    } else {
        // Fetch directly from the global household endpoint
        return apiService.get(`/households/${id}`);
    }
};

export const addHousehold = (householdData) => {
    const woredaId = store.getters["user/woredaId"];
    return apiService.post(`/woredas/${woredaId}/households`, householdData);
};

export const updateHousehold = (id, householdData) => {
    const woredaId = store.getters["user/woredaId"];
    return apiService.put(
        `/woredas/${woredaId}/households/${id}`,
        householdData
    );
};
export const deleteHousehold = (id) => {
    const woredaId = store.getters["user/woredaId"];
    return apiService.delete(`/woredas/${woredaId}/households/${id}`);
};
export const addHouseholdStat = async (
    householdId,
    householdStatData,
    malariaCases
) => {
    try {
        // 1. First POST request: Create Household Stat
        const householdStatResponse = await apiService.post(
            `/household/${householdId}/household-stats`,
            householdStatData
        );

        // Extract the householdStatId from the response
        const householdStatId = householdStatResponse.data.data.id;

        // 2. Post each malaria case in sequence
        const malariaCasesResponses = [];
        for (const malariaCase of malariaCases) {
            const malariaCaseResponse = await apiService.post(
                `/household-stat/${householdStatId}/malaria-cases`,
                malariaCase
            );
            malariaCasesResponses.push(malariaCaseResponse.data);
        }

        // 3. Return both responses combined
        return {
            householdStats: householdStatResponse.data,
            malariaCases: malariaCasesResponses,
        };
    } catch (error) {
        console.error("Error adding household stat or malaria cases:", error);
        throw error;
    }
};
