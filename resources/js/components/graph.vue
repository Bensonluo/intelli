<template>
    <div>
        <h3>Registration Numbers By Year</h3>
        <div>
            <GChart type="LineChart" :data="chartData" :options="chartOptions"/>
            <GChart type="PieChart" :data="chartData" :options="chartOptions"/>
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    export default {
        data () {
            return {
                chartData: '',
                chartOptions: {
                    chart: {
                    }
                }
            }
        },

        created() {
            this.fetchRegistrationNumberByYear();
        },

        methods: {
            fetchRegistrationNumberByYear() {
                let vm = this;
                let url = '/api/membersByYear';
                fetch(url)
                    .then(res => res.json())
                    .then(res => {
                        this.chartData = res;
                    })
                    .catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped>

</style>
