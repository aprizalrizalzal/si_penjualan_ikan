<template>
    <canvas ref="chartCanvas"></canvas>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';

const chartCanvas = ref(null);
const props = defineProps({
    lableCharts: Array,
    dataCharts: Array,
});

let myChart = null; // Chart instance variable

onMounted(() => {
    const ctx = chartCanvas.value;

    // Initialize chart
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: props.lableCharts,
            datasets: [{
                label: 'Count',
                data: props.dataCharts,
                backgroundColor: generateRandomColors(props.dataCharts.length).map(color => `${color}33`),
                borderColor: generateRandomColors(props.dataCharts.length),
                borderWidth: 2,
                pointRadius: 4,
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointHoverRadius: 8,
                pointStyle: 'rectRounded'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    // Calculate max value for y axis based on props.dataCharts
                    max: calculateMaxY(props.dataCharts) + 2,
                    ticks: {
                        stepSize: 1, // Ensure the step size is 1 to only show integers
                        callback: function (value) {
                            return Math.round(value); // Ensure only integers are displayed
                        },
                    }
                }
            }
        }
    });

    // Watch for changes in dataCharts prop
    watch(() => props.dataCharts, (newValue, oldValue) => {
        // Update chart data when props.dataCharts changes
        myChart.data.datasets[0].data = newValue;
        // Recalculate max value for y axis and update options
        myChart.options.scales.y.max = calculateMaxY(newValue) + 2;
        myChart.update();
    });

    return () => myChart.destroy(); // Cleanup chart on unmount
});

// Function to calculate max value for y axis
function calculateMaxY(data) {
    if (data.length === 0) return 0; // Handle case where data is empty
    return Math.max(...data); // Return maximum value in data
}

// Function to generate random colors
function generateRandomColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const color = getRandomColor();
        colors.push(color);
    }
    return colors;
}

// Function to generate random hex color
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
