import apiService from "@/utils/apiService";

// Fetch all regions
export const fetchRegions = async () => {
  console.log("Fetching all regions...");
  try {
    const response = await apiService.get("/regions");
    console.log("Regions fetched successfully:", response.data); // Log the response data
    return response.data;
  } catch (error) {
    console.error("Error fetching regions:", error);
    throw error; // Rethrow the error to be handled by the caller
  }
};

// Fetch a single region by ID
export const fetchRegion = async (regionId) => {
  console.log(`Fetching region with ID: ${regionId}`);
  try {
    const response = await apiService.get(`/regions/${regionId}`);
    console.log("Region fetched successfully:", response.data);
    return response.data;
  } catch (error) {
    console.error(`Error fetching region with ID ${regionId}:`, error);
    throw error;
  }
};

// Create a new region
export const createRegion = async (data) => {
  console.log("Creating a new region with data:", data);
  try {
    const response = await apiService.post("/regions", data);
    console.log("Region created successfully:", response.data);
    return response.data;
  } catch (error) {
    console.error("Error creating region:", error);
    throw error;
  }
};

// Update an existing region by ID
export const updateRegion = async (regionId, data) => {
  console.log(`Updating region with ID ${regionId} and data:`, data);
  try {
    const response = await apiService.put(`/regions/${regionId}`, data);
    console.log("Region updated successfully:", response.data);
    return response.data;
  } catch (error) {
    console.error(`Error updating region with ID ${regionId}:`, error);
    throw error;
  }
};

// Delete a region by ID
export const deleteRegion = async (regionId) => {
  console.log(`Deleting region with ID: ${regionId}`);
  try {
    const response = await apiService.delete(`/regions/${regionId}`);
    console.log("Region deleted successfully:", response.data);
    return response.data;
  } catch (error) {
    console.error(`Error deleting region with ID ${regionId}:`, error);
    throw error;
  }
};

// Fetch zones for a specific region by region ID
export const fetchZones = async (regionId) => {
  console.log(`Fetching zones for region with ID: ${regionId}`);
  try {
    const response = await apiService.get(`/regions/${regionId}/zones`);
    console.log("Zones fetched successfully:", response.data);
    return response.data;
  } catch (error) {
    console.error(`Error fetching zones for region with ID ${regionId}:`, error);
    throw error;
  }
};

// Fetch woredas by zone ID
export const fetchWoredas = async (zoneId) => {
  try {
    const response = await apiService.get(`/zones/${zoneId}/woredas`);
    return response.data;
  } catch (error) {
    console.error("Error fetching woredas:", error);
    throw error;
  }
};