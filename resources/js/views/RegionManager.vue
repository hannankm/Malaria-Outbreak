<template>
  <div>
    <h1>Region Manager</h1>

    <!-- Search Input -->
    <el-input 
      v-model="searchQuery" 
      placeholder="Search Regions" 
      @input="fetchRegions" 
      class="mb-4"
    ></el-input>

    <!-- Create Button -->
    <el-button type="primary" @click="showCreateDialog">Create Region</el-button>

    <!-- Region Table -->
    <el-table :data="filteredRegions" stripe style="width: 100%" class="mt-4">
      <el-table-column prop="name" label="Region Name"></el-table-column>
      <el-table-column label="Actions">
        <template #default="scope">
          <el-button size="mini" @click="showEditDialog(scope.row)">Edit</el-button>
          <el-button size="mini" type="danger" @click="deleteRegion(scope.row.id)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- Create/Edit Dialog -->
    <el-dialog :visible.sync="isDialogVisible" title="Region">
      <el-form :model="regionForm">
        <el-form-item label="Name" :label-width="formLabelWidth">
          <el-input v-model="regionForm.name"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isDialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="saveRegion">Save</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { 
  fetchRegions, 
  createRegion, 
  updateRegion, 
  deleteRegion 
} from "../api/region";

export default {
  data() {
    return {
      regions: [], // Full list of regions from the database
      searchQuery: "", // Query for searching regions
      isDialogVisible: false, // Dialog visibility state
      isEditMode: false, // Editing state
      regionForm: {
        id: "",
        name: "",
      },
      formLabelWidth: "120px",
    };
  },
  computed: {
    // Computed property for filtered regions based on the search query
    filteredRegions() {
      if (!this.searchQuery) {
        return this.regions;
      }
      return this.regions.filter((region) =>
        region.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  methods: {
    // Fetch regions from the database
    async fetchRegions() {
      try {
        const response = await fetchRegions();
        this.regions = response.data; // Assuming the API returns an array of regions
      } catch (error) {
        console.error("Error fetching regions:", error);
      }
    },

    // Show the dialog for creating a new region
    showCreateDialog() {
      this.isEditMode = false;
      this.regionForm = { id: "", name: "" };
      this.isDialogVisible = true;
    },

    // Show the dialog for editing an existing region
    showEditDialog(region) {
      this.isEditMode = true;
      this.regionForm = { ...region };
      this.isDialogVisible = true;
    },

    // Save a region (create or update)
    async saveRegion() {
      try {
        if (this.isEditMode) {
          await updateRegion(this.regionForm.id, { name: this.regionForm.name });
        } else {
          await createRegion({ name: this.regionForm.name });
        }
        this.isDialogVisible = false;
        this.fetchRegions();
      } catch (error) {
        console.error("Error saving region:", error);
      }
    },

    // Delete a region
    async deleteRegion(regionId) {
      try {
        await deleteRegion(regionId);
        this.fetchRegions();
      } catch (error) {
        console.error("Error deleting region:", error);
      }
    },
  },
  mounted() {
    this.fetchRegions();
  },
};
</script>

<style scoped>
.mb-4 {
  margin-bottom: 1rem;
}
.mt-4 {
  margin-top: 1rem;
}
</style>
