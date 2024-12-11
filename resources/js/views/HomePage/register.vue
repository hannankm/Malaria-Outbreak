<template>
  <div class="register-page">
    <Navigation wide />

    <div class="register-container">
      <el-card class="register-card">
        <h2>Register</h2>
        <el-form :model="registerForm" :rules="rules" ref="registerForm" label-position="top">
          <el-form-item label="Name" prop="name">
            <el-input v-model="registerForm.name" placeholder="Name"></el-input>
          </el-form-item>
          <el-form-item label="Email" prop="email">
            <el-input v-model="registerForm.email" placeholder="Email"></el-input>
          </el-form-item>
          <el-form-item label="Password" prop="password">
            <el-input type="password" v-model="registerForm.password" placeholder="Password"></el-input>
          </el-form-item>
          <el-form-item label="Confirm Password" prop="confirmPassword">
            <el-input type="password" v-model="registerForm.confirmPassword" placeholder="Confirm Password"></el-input>
          </el-form-item>
          <el-form-item label="Phone Number" prop="phoneNumber">
            <el-input v-model="registerForm.phoneNumber" placeholder="Phone Number"></el-input>
          </el-form-item>
          <el-form-item label="Region" prop="regionId">
            <el-select v-model="registerForm.regionId" filterable placeholder="Select Region" @change="fetchZones">
              <el-option v-for="region in regions" :key="region.id" :label="region.name" :value="region.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Zone" prop="zoneId" v-if="zones.length">
            <el-select v-model="registerForm.zoneId" filterable placeholder="Select Zone" @change="fetchWoredas">
              <el-option v-for="zone in zones" :key="zone.id" :label="zone.name" :value="zone.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Woreda" prop="woredaId" v-if="woredas.length">
            <el-select v-model="registerForm.woredaId" filterable placeholder="Select Woreda">
              <el-option v-for="woreda in woredas" :key="woreda.id" :label="woreda.name" :value="woreda.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Role" prop="role">
            <el-select v-model="registerForm.role" placeholder="Select Role">
              <el-option label="Superadmin" value="superadmin"></el-option>
              <el-option label="Supervisor" value="supervisor"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="handleRegister">Register</el-button>
          </el-form-item>
        </el-form>
        <div>
          <el-alert v-if="errorMessage" :title="errorMessage" type="error" show-icon></el-alert>
          <el-alert v-if="successMessage" :title="successMessage" type="success" show-icon></el-alert>
        </div>
      </el-card>
    </div>
  </div>
</template>

<script>
import Navigation from '../../layouts/components/Navigation.vue';;
import api from '@/api/auth';

export default {
  components: {
    Navigation,
  },
  data() {
    return {
      registerForm: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
        phoneNumber: '',
        regionId: '',
        zoneId: '',
        woredaId: '',
        role: '',
      },
      rules: {
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
        email: [
          { required: true, message: 'Email is required', trigger: 'blur' },
          { type: 'email', message: 'Invalid email format', trigger: 'blur' },
        ],
      },
      regions: [],
      zones: [],
      woredas: [],
      errorMessage: '',
      successMessage: '',
    };
  },
  methods: { async fetchRegions() { 
    try { const response = await api.getRegions();
       console.log('API Response:', response);
        if (Array.isArray(response)) 
        { this.regions = response;

         } 
        else { console.error('Unexpected data format:', response); 

        }
       } catch (error) 
       { console.error('Error fetching regions:', error); } },

       async fetchZones() { this.zones = await api.getZones(this.registerForm.regionId); 
        this.registerForm.zoneId = ''; this.woredas = []; },
    async fetchWoredas() {
      this.woredas = await api.getWoredas(this.registerForm.zoneId);
    },
    async handleRegister() {
      console.log(this.registerForm);
    },
  },
  mounted() {
     this.fetchRegions();
    
    // this.regions = [ { id: '1', name: 'Region 1' }, { id: '2', name: 'Region 2' }, ];
  },
};
</script>

<style scoped>
.register-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #f5f5f5;
}

.register-container {
  padding: 20px;
  display: flex;
  justify-content: center;
}

.register-card {
  width: 500px;
  box-shadow: 0 0 5px gray;
}
</style>
