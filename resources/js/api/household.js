import apiService from "@/utils/apiService";
import store from "@/store";

export const getHouseholds = (searchTerm = "") => {
    const woredaId = store.getters["user/woredaId"];
    const auth = store.getters["user/authToken"];

    alert(woredaId);
    alert(auth);

    // Include searchTerm as a query parameter
    return apiService.get(`/woredas/${woredaId}/households`, {
        params: {
            searchTerm, // Pass search term as query parameter
        },
    });
};

export const getHouseholdById = (id) => {
    const woredaId = store.getters["user/woredaId"];
    alert(woredaId);
    alert(id);
    return apiService.get(`/woredas/${woredaId}/households/${id}`);
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
