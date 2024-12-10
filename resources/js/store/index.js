import { defineStore } from 'pinia';

export const useRegionStore = defineStore('region', {
  state: () => ({
    regions: [],
  }),
  actions: {
    async fetchRegions() {
      const response = await axios.get('/api/regions');
      this.regions = response.data;
    },
  },
});
