<template>
    <!-- Add Household Stat Modal -->
    <el-dialog
        title="Add Household Stat"
        v-model="isAddHouseholdStatModalVisible"
        @close="closeAddHouseholdStatModal"
    >
        <el-form :model="newHouseholdStat" label-width="120px">
            <el-form-item label="Date">
                <el-date-picker
                    v-model="newHouseholdStat.date"
                    type="date"
                    placeholder="Select Date"
                />
            </el-form-item>

            <el-form-item label="Number of Active Cases">
                <el-input
                    v-model.number="newHouseholdStat.no_of_active_cases"
                    type="number"
                    @input="generateMalariaForms('active')"
                    placeholder="Enter number of active cases"
                />
                <div
                    v-for="(malariaCase, index) in malariaCases.active"
                    :key="index"
                >
                    <el-divider>Active Case {{ index + 1 }}</el-divider>
                    <el-form-item label="Age Group">
                        <el-input
                            v-model="malariaCase.age_group"
                            placeholder="Age group"
                        />
                    </el-form-item>
                    <el-form-item label="Sex">
                        <el-select
                            v-model="malariaCase.sex"
                            placeholder="Select sex"
                        >
                            <el-option label="Male" value="Male" />
                            <el-option label="Female" value="Female" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Diagnosed">
                        <el-switch
                            v-model="malariaCase.diagnosed"
                            active-text="Yes"
                            inactive-text="No"
                        />
                    </el-form-item>
                </div>
            </el-form-item>

            <el-form-item label="Number of Deaths">
                <el-input
                    v-model.number="newHouseholdStat.no_of_death"
                    type="number"
                    @input="generateMalariaForms('death')"
                    placeholder="Enter number of deaths"
                />
                <div
                    v-for="(malariaCase, index) in malariaCases.death"
                    :key="index"
                >
                    <el-divider>Death Case {{ index + 1 }}</el-divider>
                    <el-form-item label="Age Group">
                        <el-input
                            v-model="malariaCase.age_group"
                            placeholder="Age group"
                        />
                    </el-form-item>
                    <el-form-item label="Sex">
                        <el-select
                            v-model="malariaCase.sex"
                            placeholder="Select sex"
                        >
                            <el-option label="Male" value="Male" />
                            <el-option label="Female" value="Female" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Diagnosed">
                        <el-switch
                            v-model="malariaCase.diagnosed"
                            active-text="Yes"
                            inactive-text="No"
                        />
                    </el-form-item>
                </div>
            </el-form-item>

            <el-form-item label="Number of New Cases">
                <el-input
                    v-model.number="newHouseholdStat.no_of_new_cases"
                    type="number"
                    @input="generateMalariaForms('new')"
                    placeholder="Enter number of new cases"
                />
                <div
                    v-for="(malariaCase, index) in malariaCases.new"
                    :key="index"
                >
                    <el-divider>New Case {{ index + 1 }}</el-divider>
                    <el-form-item label="Age Group">
                        <el-input
                            v-model="malariaCase.age_group"
                            placeholder="Age group"
                        />
                    </el-form-item>
                    <el-form-item label="Sex">
                        <el-select
                            v-model="malariaCase.sex"
                            placeholder="Select sex"
                        >
                            <el-option label="Male" value="Male" />
                            <el-option label="Female" value="Female" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Diagnosed">
                        <el-switch
                            v-model="malariaCase.diagnosed"
                            active-text="Yes"
                            inactive-text="No"
                        />
                    </el-form-item>
                </div>
            </el-form-item>

            <el-form-item label="Number of Recovered">
                <el-input
                    v-model.number="newHouseholdStat.no_of_recovered"
                    type="number"
                    @input="generateMalariaForms('recovered')"
                    placeholder="Enter number of recovered cases"
                />
                <div
                    v-for="(malariaCase, index) in malariaCases.recovered"
                    :key="index"
                >
                    <el-divider>Recovered Case {{ index + 1 }}</el-divider>
                    <el-form-item label="Age Group">
                        <el-input
                            v-model="malariaCase.age_group"
                            placeholder="Age group"
                        />
                    </el-form-item>
                    <el-form-item label="Sex">
                        <el-select
                            v-model="malariaCase.sex"
                            placeholder="Select sex"
                        >
                            <el-option label="Male" value="Male" />
                            <el-option label="Female" value="Female" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Diagnosed">
                        <el-switch
                            v-model="malariaCase.diagnosed"
                            active-text="Yes"
                            inactive-text="No"
                        />
                    </el-form-item>
                </div>
            </el-form-item>

            <el-form-item label="Number of People At Risk">
                <el-input
                    v-model.number="newHouseholdStat.no_of_people_at_risk"
                    type="number"
                    @input="generateMalariaForms('at_risk')"
                    placeholder="Enter number of people at risk"
                />
                <div
                    v-for="(malariaCase, index) in malariaCases.at_risk"
                    :key="index"
                >
                    <el-divider>At Risk {{ index + 1 }}</el-divider>
                    <el-form-item label="Age Group">
                        <el-input
                            v-model="malariaCase.age_group"
                            placeholder="Age group"
                        />
                    </el-form-item>
                    <el-form-item label="Sex">
                        <el-select
                            v-model="malariaCase.sex"
                            placeholder="Select sex"
                        >
                            <el-option label="Male" value="Male" />
                            <el-option label="Female" value="Female" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Diagnosed">
                        <el-switch
                            v-model="malariaCase.diagnosed"
                            active-text="Yes"
                            inactive-text="No"
                        />
                    </el-form-item>
                </div>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="saveHouseholdStat">
                    Save Household Stat
                </el-button>
                <el-button @click="closeAddHouseholdStatModal">
                    Cancel
                </el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
import { addHouseholdStat } from "@/api/household";

export default {
    props: {
        householdId: {
            type: String,
            required: true,
        },
        value: Boolean,
    },
    data() {
        return {
            isAddHouseholdStatModalVisible: this.value,
            newHouseholdStat: {
                date: "",
                no_of_active_cases: 0,
                no_of_death: 0,
                no_of_people_at_risk: 0,
                no_of_new_cases: 0,
                no_of_recovered: 0,
            },
            malariaCases: {
                active: [],
                death: [],
                new: [],
                recovered: [],
                at_risk: [],
            },
        };
    },
    watch: {
        value(val) {
            this.isAddHouseholdStatModalVisible = val;
        },
        isAddHouseholdStatModalVisible(val) {
            this.$emit("input", val);
        },
    },
    methods: {
        openAddHouseholdStatModal() {
            this.isAddHouseholdStatModalVisible = true;
        },
        closeAddHouseholdStatModal() {
            this.isAddHouseholdStatModalVisible = false;
        },
        generateMalariaForms(type) {
            let numberOfCases = 0;

            // Determine the number of cases based on the type
            if (type === "active") {
                numberOfCases = this.newHouseholdStat.no_of_active_cases;
            } else if (type === "death") {
                numberOfCases = this.newHouseholdStat.no_of_death;
            } else if (type === "new") {
                numberOfCases = this.newHouseholdStat.no_of_new_cases;
            } else if (type === "recovered") {
                numberOfCases = this.newHouseholdStat.no_of_recovered;
            } else if (type === "at_risk") {
                numberOfCases = this.newHouseholdStat.no_of_people_at_risk;
            }

            // Update malaria cases for each type
            this.malariaCases[type] = [];
            for (let i = 0; i < numberOfCases; i++) {
                this.malariaCases[type].push({
                    status: type,
                    age_group: "",
                    sex: "",
                    diagnosed: false,
                });
            }
        },
        async saveHouseholdStat() {
            try {
                // Creating an object for household statistics
                const householdStatData = {
                    no_of_active_cases:
                        this.newHouseholdStat.no_of_active_cases,
                    no_of_death: this.newHouseholdStat.no_of_death,
                    no_of_people_at_risk:
                        this.newHouseholdStat.no_of_people_at_risk,
                    no_of_new_cases: this.newHouseholdStat.no_of_new_cases,
                    no_of_recovered: this.newHouseholdStat.no_of_recovered,
                };

                // Creating an array for malaria cases
                const malariaCases = [
                    ...this.malariaCases.active,
                    ...this.malariaCases.death,
                    ...this.malariaCases.new,
                    ...this.malariaCases.recovered,
                    ...this.malariaCases.at_risk,
                ];

                // Sending both household stat data and malaria cases as separate arguments
                await addHouseholdStat(
                    this.householdId,
                    householdStatData,
                    malariaCases
                );
                this.$message.success("Household Stat added successfully.");

                this.$router.push({
                    path: this.$route.path,
                    query: { refresh: Date.now() },
                });

                // Closing the modal after saving the data
                this.closeAddHouseholdStatModal();
            } catch (error) {
                // Handling any errors that may occur
                console.error(error);
            }
        },
    },
};
</script>
