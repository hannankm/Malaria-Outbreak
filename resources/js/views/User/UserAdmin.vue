<template>
    <div class="user-management">
        <!-- Header Section -->
        <div class="header">
            <el-page-header title="User Management" />
            <el-select
                v-model="filterStatus"
                placeholder="Filter Users"
                @change="fetchUsers"
            >
                <el-option label="All" value="" />
                <el-option label="Approved" value="active" />
                <el-option label="Rejected" value="rejected" />
                <el-option label="Suspended" value="suspended" />
                <el-option label="Pending" value="pending" />
            </el-select>
            <el-button type="primary" @click="showCreateUserForm = true"
                >Create User</el-button
            >
        </div>

        <!-- Create/Edit User Form -->
        <el-dialog
            :title="isEditMode ? 'Edit User' : 'Create User'"
            v-model="showCreateUserForm"
        >
            <el-form :model="newUser" label-width="120px">
                <el-form-item label="Name">
                    <el-input v-model="newUser.name" placeholder="Enter Name" />
                </el-form-item>
                <el-form-item label="Email">
                    <el-input
                        v-model="newUser.email"
                        placeholder="Enter Email"
                    />
                </el-form-item>
                <el-form-item label="Role">
                    <el-select v-model="newUser.role" placeholder="Select Role">
                        <el-option label="Admin" value="admin" />
                        <el-option label="User" value="user" />
                    </el-select>
                </el-form-item>
            </el-form>

            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="showCreateUserForm = false"
                        >Cancel</el-button
                    >
                    <el-button
                        type="primary"
                        @click="isEditMode ? updateUser() : createUser()"
                    >
                        {{ isEditMode ? "Update" : "Submit" }}
                    </el-button>
                </span>
            </template>
        </el-dialog>

        <!-- User Table -->
        <el-table :data="users" stripe style="width: 100%">
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="email" label="Email" />
            <el-table-column prop="role" label="Role" />
            <el-table-column prop="status" label="Status" />
            <el-table-column label="Actions" width="300">
                <template #default="scope">
                    <el-button type="info" @click="viewUser(scope.row)"
                        >View</el-button
                    >
                    <el-button type="warning" @click="editUser(scope.row)"
                        >Edit</el-button
                    >
                    <el-button type="danger" @click="deleteUser(scope.row.id)"
                        >Delete</el-button
                    >
                </template>
            </el-table-column>
        </el-table>

        <!-- User Details -->
        <el-dialog
            title="User Details"
            v-model="selectedUser"
            @close="selectedUser = null"
        >
            <p><strong>Name:</strong> {{ selectedUser.name }}</p>
            <p><strong>Email:</strong> {{ selectedUser.email }}</p>
            <p><strong>Role:</strong> {{ selectedUser.roles[0] }}</p>
            <p><strong>Status:</strong> {{ selectedUser.status }}</p>
            <div class="button-group">
                <el-button
                    v-if="selectedUser.status === 'pending'"
                    type="success"
                    @click="updateStatus('active')"
                >
                    Approve
                </el-button>
                <el-button
                    v-if="selectedUser.status === 'pending'"
                    type="danger"
                    @click="updateStatus('rejected')"
                >
                    Reject
                </el-button>
                <el-button
                    v-if="selectedUser.status === 'active'"
                    type="warning"
                    @click="updateStatus('suspended')"
                >
                    Suspend
                </el-button>
                <el-button
                    v-if="selectedUser.status === 'suspended'"
                    type="success"
                    @click="updateStatus('active')"
                >
                    Reactivate
                </el-button>
                <el-button @click="selectedUser = null">Close</el-button>
            </div>
        </el-dialog>
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
                id: null,
                name: "",
                email: "",
                role: "user",
            },
            isEditMode: false,
            showCreateUserForm: false,
            selectedUser: null,
        };
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await userApi.fetchUsers(this.filterStatus);
                this.users = response.data.data;
            } catch (error) {
                console.error("Failed to fetch users:", error);
            }
        },
        async createUser() {
            try {
                await userApi.createUser(this.newUser);
                this.showCreateUserForm = false;
                this.resetForm();
                this.fetchUsers();
            } catch (error) {
                console.error("Failed to create user:", error);
            }
        },
        async editUser(user) {
            this.isEditMode = true;
            this.newUser = { ...user }; // Copy user details to form
            this.showCreateUserForm = true;
        },
        async updateUser() {
            try {
                await userApi.updateUser(this.newUser.id, this.newUser);
                this.showCreateUserForm = false;
                this.resetForm();
                this.fetchUsers();
            } catch (error) {
                console.error("Failed to update user:", error);
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
        async viewUser(user) {
            this.selectedUser = user;
        },
        async updateStatus(newStatus) {
            try {
                if (this.selectedUser) {
                    await userApi.updateUserStatus(
                        this.selectedUser.id,
                        newStatus
                    );
                    this.fetchUsers();
                    this.selectedUser = null;
                }
            } catch (error) {
                console.error("Failed to update user status:", error);
            }
        },
        resetForm() {
            this.isEditMode = false;
            this.newUser = { id: null, name: "", email: "", role: "user" };
        },
    },
    mounted() {
        this.fetchUsers();
    },
};
</script>

<style scoped>
.user-management {
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.dialog-footer {
    display: flex;
    justify-content: flex-end;
}
</style>
