<template>
    <div class="user-management">
      <div class="header">
        <h1>User Management</h1>
        <select v-model="filterStatus" @change="fetchUsers">
          <option value="">All</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
          <option value="suspended">Suspended</option>
          <option value="pending">Pending</option>
        </select>
        <button @click="showCreateUserForm = true" class="btn btn-primary">Create User</button>
      </div>
  
      <div v-if="showCreateUserForm" class="create-user-form">
        <h2>Create User</h2>
        <input type="text" v-model="newUser.name" placeholder="Name" class="input" />
        <input type="email" v-model="newUser.email" placeholder="Email" class="input" />
        <select v-model="newUser.role" class="input">
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        <div class="button-group">
          <button @click="createUser" class="btn btn-success">Submit</button>
          <button @click="showCreateUserForm = false" class="btn btn-secondary">Cancel</button>
        </div>
      </div>
  
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>{{ user.status }}</td>
            <td>
              <button @click="viewUser(user.id)" class="btn btn-info">View</button>
              <button @click="editUser(user.id)" class="btn btn-warning">Edit</button>
              <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <div v-if="selectedUser" class="user-detail">
        <h2>User Details</h2>
        <p><strong>Name:</strong> {{ selectedUser.name }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Role:</strong> {{ selectedUser.role }}</p>
        <p><strong>Status:</strong> {{ selectedUser.status }}</p>
        <div class="button-group">
          <button
            v-if="selectedUser.status === 'pending'"
            @click="updateStatus('approved')"
            class="btn btn-success"
          >
            Approve
          </button>
          <button
            v-if="selectedUser.status === 'pending'"
            @click="updateStatus('rejected')"
            class="btn btn-danger"
          >
            Reject
          </button>
          <button
            v-if="selectedUser.status === 'approved'"
            @click="updateStatus('suspended')"
            class="btn btn-warning"
          >
            Suspend
          </button>
          <button
            v-if="selectedUser.status === 'suspended'"
            @click="updateStatus('approved')"
            class="btn btn-success"
          >
            Approve
          </button>
          <button
            v-if="selectedUser.status === 'rejected'"
            @click="updateStatus('approved')"
            class="btn btn-success"
          >
            Approve
          </button>
          <button @click="selectedUser = null" class="btn btn-secondary">Close</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import userApi from "@/api/user";
  
  export default {
    data() {
      return {
        users: [],
        filterStatus: "",
        newUser: {
          name: "",
          email: "",
          role: "user",
        },
        showCreateUserForm: false,
        selectedUser: null,
      };
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await userApi.fetchUsers(this.filterStatus);
          this.users = response.data;
        } catch (error) {
          console.error("Failed to fetch users:", error);
        }
      },
      async createUser() {
        try {
          await userApi.createUser(this.newUser);
          this.showCreateUserForm = false;
          this.fetchUsers();
        } catch (error) {
          console.error("Failed to create user:", error);
        }
      },
      async deleteUser(userId) {
        try {
          await userApi.deleteUser(userId);
          this.fetchUsers();
        } catch (error) {
          console.error("Failed to delete user:", error);
        }
      },
      async viewUser(userId) {
        try {
          const response = await userApi.fetchUserDetails(userId);
          this.selectedUser = response.data;
        } catch (error) {
          console.error("Failed to fetch user details:", error);
        }
      },
      async editUser(userId) {
        // Add logic for editing a user, such as showing a form with existing details.
        console.log("Edit user functionality not implemented yet.");
      },
      async updateStatus(newStatus) {
        try {
          if (this.selectedUser) {
            await userApi.updateUserStatus(this.selectedUser.id, newStatus);
            this.fetchUsers();
            this.selectedUser = null;
          }
        } catch (error) {
          console.error("Failed to update user status:", error);
        }
      },
    },
    mounted() {
      this.fetchUsers();
    },
  };
  </script>
  
  <style>
  .user-management {
    padding: 20px;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .create-user-form {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .input {
    display: block;
    margin-bottom: 10px;
    padding: 8px;
    width: 100%;
  }
  
  .table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  
  .table th,
  .table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  
  .button-group {
    display: flex;
    gap: 10px;
  }
  
  .btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .btn-primary {
    background-color: #007bff;
    color: white;
  }
  
  .btn-secondary {
    background-color: #6c757d;
    color: white;
  }
  
  .btn-success {
    background-color: #28a745;
    color: white;
  }
  
  .btn-danger {
    background-color: #dc3545;
    color: white;
  }
  
  .btn-warning {
    background-color: #ffc107;
    color: white;
  }
  
  .btn-info {
    background-color: #17a2b8;
    color: white;
  }
  </style>
  