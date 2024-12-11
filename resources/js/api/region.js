// resources/api/region.js
import apiService from "@/utils/apiService";

export const fetchRegions = async () => {
  try {
    const response = await apiService.get("/regions");
    return response.data;
  } catch (error) {
    console.error("Error fetching regions:", error);
    throw error;
  }
};

export const fetchRegion = async (regionId) => {
  try {
    const response = await apiService.get(`/regions/${regionId}`);
    return response.data;
  } catch (error) {
    console.error(`Error fetching region with ID ${regionId}:`, error);
    throw error;
  }
};

export const createRegion = async (data) => {
  try {
    const response = await apiService.post("/regions", data);
    return response.data;
  } catch (error) {
    console.error("Error creating region:", error);
    throw error;
  }
};

export const updateRegion = async (regionId, data) => {
  try {
    const response = await apiService.put(`/regions/${regionId}`, data);
    return response.data;
  } catch (error) {
    console.error(`Error updating region with ID ${regionId}:`, error);
    throw error;
  }
};

export const deleteRegion = async (regionId) => {
  try {
    const response = await apiService.delete(`/regions/${regionId}`);
    return response.data;
  } catch (error) {
    console.error(`Error deleting region with ID ${regionId}:`, error);
    throw error;
  }
};

export const fetchZones = async (regionId) => {
  try {
    const response = await apiService.get(`/regions/${regionId}/zones`);
    return response.data;
  } catch (error) {
    console.error(`Error fetching zones for region with ID ${regionId}:`, error);
    throw error;
  }
};
