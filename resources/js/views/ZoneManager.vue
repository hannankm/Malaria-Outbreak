<template>
    <div class="zone-manager">
        <el-row gutter="20">
            <el-col :span="24">
                <h1 class="header">Zone Manager</h1>
            </el-col>

            <el-col :span="12">
                <el-card class="form-card">
                    <el-form>
                        <el-form-item
                            label="Select Region:"
                            label-width="120px"
                        >
                            <el-select
                                v-model="selectedRegion"
                                @change="loadZones"
                                placeholder="Select a Region"
                                class="form-control"
                            >
                                <el-option
                                    v-for="region in regions"
                                    :key="region.id"
                                    :label="region.name"
                                    :value="region.id"
                                ></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Search Zones:" label-width="120px">
                            <el-input
                                v-model="searchQuery"
                                @input="searchZones"
                                placeholder="Search zones..."
                                clearable
                            ></el-input>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>

            <el-col :span="12">
                <el-card class="form-card">
                    <el-form>
                        <el-form-item
                            label="Enter Zone Name:"
                            label-width="120px"
                        >
                            <el-input
                                v-model="newZone.name"
                                placeholder="Enter Zone Name"
                            ></el-input>
                        </el-form-item>
                        <el-button type="primary" @click="createZone"
                            >Create Zone</el-button
                        >
                    </el-form>
                </el-card>
            </el-col>
        </el-row>

        <el-row gutter="20">
            <el-col :span="24">
                <el-card v-if="zones.length > 0" class="zone-list-card">
                    <h2 class="subheader">Zones</h2>
                    <el-list>
                        <el-list-item
                            v-for="zone in zones"
                            :key="zone.id"
                            class="list-item"
                        >
                            <span>{{ zone.name }}</span>
                            <div class="button-group">
                                <el-button
                                    type="warning"
                                    size="small"
                                    @click="editZone(zone)"
                                    >Edit</el-button
                                >
                                <el-button
                                    type="danger"
                                    size="small"
                                    @click="deleteZone(zone.id)"
                                    >Delete</el-button
                                >
                            </div>
                        </el-list-item>
                    </el-list>
                </el-card>

                <el-empty
                    v-else
                    description="No zones available. Please select a region or add one."
                    class="empty-message"
                />
            </el-col>
        </el-row>

        <el-dialog
            v-model="showForm"
            :title="isEditMode ? 'Edit Zone' : 'Create Zone'"
        >
            <el-form>
                <el-form-item label="Zone Name:" label-width="120px">
                    <el-input
                        v-model="currentZone.name"
                        placeholder="Enter Zone Name"
                    ></el-input>
                </el-form-item>
                <div class="button-group">
                    <el-button type="success" @click="saveZone"
                        >Save Changes</el-button
                    >
                    <el-button type="secondary" @click="cancelForm"
                        >Cancel</el-button
                    >
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
import {
    getRegionsDropdown,
    getZonesByRegion,
    createZone,
    editZoneApi,
    deleteZoneApi,
} from "@/api/zone";

export default {
    data() {
        return {
            regions: [], // List of regions for the dropdown
            selectedRegion: null, // Currently selected region
            zones: [], // List of zones for the currently selected region
            searchQuery: "", // Search query input
            newZone: { name: "" }, // Data for new zone creation
            showForm: false,
            isEditMode: false, // Tracks if editing a zone
            currentZone: { id: null, name: "" },
        };
    },

    async created() {
        await this.fetchRegions();
    },

    methods: {
        async fetchRegions() {
            try {
                const response = await getRegionsDropdown();
                this.regions = response.data.map((region) => ({
                    id: region.id,
                    name: region.name,
                }));
            } catch (error) {
                console.error("Failed to fetch regions:", error);
            }
        },

        async loadZones() {
            if (!this.selectedRegion) return;

            try {
                const response = await getZonesByRegion(this.selectedRegion);
                this.zones = response.data || [];
            } catch (error) {
                console.error("Failed to load zones:", error);
            }
        },

        async searchZones() {
            try {
                this.zones = this.zones.filter((zone) =>
                    zone.name
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase())
                );
            } catch (error) {
                console.error("Failed to search zones:", error);
            }
        },

        async createZone() {
            if (!this.newZone.name.trim()) {
                console.warn("Zone name is required!");
                return;
            }
            try {
                await createZone(this.selectedRegion, this.newZone);
                this.newZone.name = "";
                this.loadZones();
            } catch (error) {
                console.error("Failed to create zone:", error);
            }
        },

        async saveZone() {
            try {
                if (this.isEditMode) {
                    await editZoneApi(this.selectedRegion, this.currentZone);
                } else {
                    await createZone(this.selectedRegion, this.currentZone);
                }
                this.loadZones();
                this.cancelForm();
            } catch (error) {
                console.error("Failed to save zone:", error);
            }
        },

        editZone(zone) {
            this.currentZone = { ...zone };
            this.showForm = true;
            this.isEditMode = true;
        },

        async deleteZone(zoneId) {
            try {
                await deleteZoneApi(this.selectedRegion, zoneId);
                this.loadZones();
            } catch (error) {
                console.error("Failed to delete zone:", error);
            }
        },

        cancelForm() {
            this.showForm = false;
            this.currentZone = { id: null, name: "" };
            this.isEditMode = false;
        },
    },
};
</script>

<style scoped>
.zone-manager {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.form-card {
    margin-bottom: 20px;
}

.list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
}

.button-group {
    display: flex;
    gap: 10px;
}

.empty-message {
    text-align: center;
    font-size: 16px;
    color: #666;
}
</style>
