import apiService from "@/utils/apiService";

// Fetch regions for dropdown
export const getRegionsDropdown = async () => {
  try {
    const response = await apiService.get('/regions');
    console.log('API Response for regions:', response.data); // Debug log
    return response.data;
  } catch (error) {
    console.error('Error fetching regions:', error);
    throw error;
  }
};

// Fetch zones data based on a specific region ID
export const getZonesByRegion = async (regionId) => {
  try {
    const response = await apiService.get(`/regions/${regionId}/zones`);
    console.log('API Response for zones:', response.data); // Debug log
    return response.data;
  } catch (error) {
    console.error('Error fetching zones:', error);
    throw error;
  }
};

// Create a new zone
export const createZone = async (regionId, zoneData) => {
  try {
    const response = await apiService.post(`/regions/${regionId}/zones`, zoneData);
    return response.data;
  } catch (error) {
    console.error('Error creating zone:', error);
    throw error;
  }
};

// Edit an existing zone
export const editZoneApi = async (regionId, zoneData) => {
  try {
    const response = await apiService.put(
      `/regions/${regionId}/zones/${zoneData.id}`,
      zoneData
    );
    return response.data;
  } catch (error) {
    console.error('Error editing zone:', error);
    throw error;
  }
};

// Delete a zone by its ID
export const deleteZoneApi = async (regionId, zoneId) => {
  try {
    const response = await apiService.delete(
      `/regions/${regionId}/zones/${zoneId}`
    );
    return response.data;
  } catch (error) {
    console.error('Error deleting zone:', error);
    throw error;
  }
};
