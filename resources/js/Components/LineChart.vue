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
                label: 'Jumlah',
                data: props.dataCharts,
                backgroundColor: "#ffffff",
                borderColor: generateRandomColors(props.dataCharts.length),
                borderWidth: 4,
                pointRadius: 8,
                pointBorderWidth: 4,
                pointHoverBorderWidth: 4,
                pointHoverRadius: 16,
                pointStyle: 'rectRounded',
            }]
        },
        options: {
            scales: {
                x: {
                    grid: {
                        color: '#ffffff', // Warna garis grid sumbu x
                    },
                    ticks: {
                        color: '#ffffff', // Warna teks pada sumbu x
                    }
                },
                y: {
                    beginAtZero: true,
                    max: calculateMaxY(props.dataCharts) + 2,
                    grid: {
                        color: '#1D4ED8', // Warna garis grid sumbu y
                    },
                    ticks: {
                        stepSize: 1, // Pastikan ukuran langkah adalah 1 untuk hanya menampilkan bilangan bulat
                        callback: function (value) {
                            return Math.round(value); // Pastikan hanya bilangan bulat yang ditampilkan
                        },
                        color: '#1D4ED8', // Warna teks pada sumbu y
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
