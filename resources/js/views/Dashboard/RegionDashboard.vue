<template>
    <div>
        <h1>Super Admin Dashboard</h1>

        <!-- Region selection -->

        <!-- Summation Data Cards -->
        <div class="summation-cards">
            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">Total Deaths</div>
                <div class="card-content">
                    {{ dashboardData.summations.deaths }}
                </div>
            </el-card>

            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">Active Cases</div>
                <div class="card-content">
                    {{ dashboardData.summations.active_cases }}
                </div>
            </el-card>

            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">New Cases</div>
                <div class="card-content">
                    {{ dashboardData.summations.new_cases }}
                </div>
            </el-card>

            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">Recoveries</div>
                <div class="card-content">
                    {{ dashboardData.summations.recoveries }}
                </div>
            </el-card>

            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">At Risk</div>
                <div class="card-content">
                    {{ dashboardData.summations.at_risk }}
                </div>
            </el-card>

            <el-card class="card" :body-style="{ padding: '20px' }">
                <div class="card-header">Household Count</div>
                <div class="card-content">
                    {{ dashboardData.household_count }}
                </div>
            </el-card>
        </div>

        <!-- Graph Data: Pie and Bar Charts -->
        <div class="charts-container">
            <!-- Diagnosed Pie Chart -->
            <div class="chart-container">
                <div ref="diagnosedPieChart" class="chart"></div>
            </div>

            <!-- Age Group Bar Chart -->
            <div class="chart-container">
                <div ref="ageGroupBarChart" class="chart"></div>
            </div>

            <!-- Gender Pie Chart -->
            <div class="chart-container">
                <div ref="genderPieChart" class="chart"></div>
            </div>
        </div>
    </div>
</template>

<script>
import apiService from "@/utils/apiService";
import * as echarts from "echarts"; // Import ECharts

export default {
    data() {
        return {
            dashboardData: {
                summations: {},
                household_count: 0,
                graph_data: {
                    by_diagnosed: [],
                    by_age_group: [],
                    by_gender: [],
                },
            },
            diagnosedPieChartInstance: null,
            ageGroupBarChartInstance: null,
            genderPieChartInstance: null,
        };
    },
    mounted() {
        this.fetchSuperAdminDashboardData();
    },
    methods: {
        fetchSuperAdminDashboardData() {
            apiService
                .get(`/dashboard`)
                .then((response) => {
                    this.dashboardData = response.data;
                    this.updateCharts(); // Update the charts after data is fetched
                })
                .catch((error) => {
                    console.error(
                        "Error fetching super admin dashboard data:",
                        error
                    );
                });
        },

        updateCharts() {
            // Pie chart for Diagnosed Data
            if (this.diagnosedPieChartInstance) {
                this.diagnosedPieChartInstance.setOption({
                    title: {
                        text: "Diagnosed Cases",
                        left: "center",
                    },
                    tooltip: {
                        trigger: "item",
                        formatter: "{a} <br/>{b}: {c} ({d}%)",
                    },
                    series: [
                        {
                            name: "Diagnosed",
                            type: "pie",
                            radius: "50%",
                            data: this.dashboardData.graph_data.by_diagnosed.map(
                                (item) => ({
                                    value: item.value,
                                    name: item.diagnosed_status,
                                })
                            ),
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: "rgba(0, 0, 0, 0.5)",
                                },
                            },
                        },
                    ],
                });
            }

            // Bar chart for Age Group Data
            if (this.ageGroupBarChartInstance) {
                this.ageGroupBarChartInstance.setOption({
                    title: {
                        text: "Age Group Distribution",
                        left: "center",
                    },
                    tooltip: {
                        trigger: "axis",
                    },
                    xAxis: {
                        type: "category",
                        data: this.dashboardData.graph_data.by_age_group.map(
                            (item) => item.age_group
                        ),
                    },
                    yAxis: {
                        type: "value",
                    },
                    series: [
                        {
                            data: this.dashboardData.graph_data.by_age_group.map(
                                (item) => item.value
                            ),
                            type: "bar",
                        },
                    ],
                });
            }

            // Pie chart for Gender Data
            if (this.genderPieChartInstance) {
                this.genderPieChartInstance.setOption({
                    title: {
                        text: "Gender Distribution",
                        left: "center",
                    },
                    tooltip: {
                        trigger: "item",
                        formatter: "{a} <br/>{b}: {c} ({d}%)",
                    },
                    series: [
                        {
                            name: "Gender",
                            type: "pie",
                            radius: "50%",
                            data: this.dashboardData.graph_data.by_gender.map(
                                (item) => ({
                                    value: item.value,
                                    name: item.gender,
                                })
                            ),
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: "rgba(0, 0, 0, 0.5)",
                                },
                            },
                        },
                    ],
                });
            }
        },
    },

    beforeDestroy() {
        // Dispose of the chart instances when the component is destroyed
        if (this.diagnosedPieChartInstance)
            this.diagnosedPieChartInstance.dispose();
        if (this.ageGroupBarChartInstance)
            this.ageGroupBarChartInstance.dispose();
        if (this.genderPieChartInstance) this.genderPieChartInstance.dispose();
    },
    updated() {
        // Reinitialize charts if needed after data change
        if (this.dashboardData && this.dashboardData.graph_data.by_diagnosed) {
            if (!this.diagnosedPieChartInstance) {
                this.diagnosedPieChartInstance = echarts.init(
                    this.$refs.diagnosedPieChart
                );
                this.updateCharts();
            }

            if (!this.ageGroupBarChartInstance) {
                this.ageGroupBarChartInstance = echarts.init(
                    this.$refs.ageGroupBarChart
                );
                this.updateCharts();
            }

            if (!this.genderPieChartInstance) {
                this.genderPieChartInstance = echarts.init(
                    this.$refs.genderPieChart
                );
                this.updateCharts();
            }
        }
    },
};
</script>

<style scoped>
.summation-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.card {
    width: 200px;
    height: 120px;
    border-radius: 10px;
}

.card-header {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

.card-content {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

.charts-container {
    margin-top: 40px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.chart-container {
    width: 100%;
    height: 400px;
}
</style>
