<template>
  <div>
    <h1>Region Manager</h1>

    <!-- Search Section -->
    <div class="search-section">
      <input
        v-model="searchQuery"
        @input="searchRegions"
        type="text"
        placeholder="Search Regions..."
      />
    </div>

    <!-- Add New Region -->
    <div class="form-section">
      <input
        v-model="newRegion.name"
        type="text"
        placeholder="Enter Region Name"
      />
      <button @click="createRegion">Create Region</button>
    </div>

    <!-- List of Regions -->
    <div class="region-list">
      <table>
        <thead>
          <tr>
            <th>Region Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="region in regions" :key="region.id">
            <td>{{ region.name }}</td>
            <td>
              <button @click="editRegion(region)">Edit</button>
              <button @click="deleteRegion(region.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <h3>Edit Region</h3>
        <input v-model="currentRegion.name" type="text" />
        <button @click="updateRegion">Save Changes</button>
        <button @click="closeEditModal">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchRegions, createRegion, updateRegion, deleteRegion } from "@/api/region";

export default {
  data() {
    return {
      regions: [], // List of regions
      searchQuery: "", // For search functionality
      newRegion: { name: "" }, // Input field for creating a region
      showEditModal: false, // Controls visibility of Edit Modal
      currentRegion: { id: null, name: "" }, // Currently selected region for editing
    };
  },
  created() {
    this.loadRegions();
  },
  methods: {
    // Fetch all regions
    async loadRegions() {
      try {
        const response = await fetchRegions();
        this.regions = response; // Populate regions list
      } catch (error) {
        console.error("Error loading regions:", error);
      }
    },

    // Search regions dynamically
    async searchRegions() {
      try {
        if (this.searchQuery.trim() === "") {
          this.loadRegions();
        } else {
          const response = await fetchRegions();
          this.regions = response.filter((region) =>
            region.name.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        }
      } catch (error) {
        console.error("Error searching regions:", error);
      }
    },

    // Create a new region
    async createRegion() {
      try {
        if (!this.newRegion.name.trim()) {
          alert("Please provide a valid name.");
          return;
        }

        await createRegion({ name: this.newRegion.name });
        this.newRegion.name = "";
        this.loadRegions();
      } catch (error) {
        console.error("Error creating region:", error);
      }
    },

    // Delete a region
    async deleteRegion(id) {
      try {
        if (confirm("Are you sure you want to delete this region?")) {
          await deleteRegion(id);
          this.loadRegions();
        }
      } catch (error) {
        console.error("Error deleting region:", error);
      }
    },

    // Open Edit Modal
    editRegion(region) {
      this.showEditModal = true;
      this.currentRegion = { ...region };
    },

    // Close Edit Modal
    closeEditModal() {
      this.showEditModal = false;
      this.currentRegion = { id: null, name: "" };
    },

    // Update region in database
    async updateRegion() {
      try {
        if (!this.currentRegion.name.trim()) {
          alert("Region name cannot be empty.");
          return;
        }

        await updateRegion(this.currentRegion.id, { name: this.currentRegion.name });
        this.closeEditModal();
        this.loadRegions();
      } catch (error) {
        console.error("Error updating region:", error);
      }
    },
  },
};
</script>

<style scoped>
/* General Styling */
h1 {
  margin-bottom: 10px;
}

.search-section {
  margin: 10px 0;
}

.form-section {
  margin: 10px 0;
}

.region-list table {
  width: 100%;
  border: 1px solid #ccc;
  border-collapse: collapse;
}

.region-list th,
.region-list td {
  padding: 8px;
  text-align: center;
  border: 1px solid #ccc;
}

button {
  margin: 0 5px;
  cursor: pointer;
}

/* Modal Styling */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 5px;
}
</style>
