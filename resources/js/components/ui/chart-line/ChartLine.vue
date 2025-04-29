<template>
    <canvas ref="canvasRef" class="w-full h-full"></canvas>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Legend,
    Title,
    Tooltip,
} from 'chart.js'

// Register necessary Chart.js components
Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Legend, Title, Tooltip)

// Dataset interface
interface Dataset {
    label: string
    data: number[]
    borderColor?: string | string[]
    backgroundColor?: string | string[]
    fill?: boolean
}

// Props interface
interface ChartLineProps {
    labels: string[]
    datasets: Dataset[]
    options?: Record<string, any>
}

const props = defineProps<ChartLineProps>()
const canvasRef = ref<HTMLCanvasElement | null>(null)
let chartInstance: Chart<'line', number[], string> | null = null

onMounted(() => {
    if (!canvasRef.value) return
    const ctx = canvasRef.value.getContext('2d')
    if (!ctx) return

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: props.labels,
            datasets: props.datasets,
        },
        options: props.options || {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: false },
            },
            scales: {
                x: {
                    grid: { drawOnChartArea: false, drawTicks: false, drawBorder: false },
                    ticks: { display: false },
                },
                y: {
                    grid: { drawOnChartArea: false, drawTicks: false, drawBorder: false },
                    ticks: { display: false },
                },
            },
        },
    })
})

watch(
    () => [props.labels, props.datasets, props.options],
    () => {
        if (!chartInstance) return
        chartInstance.data.labels = props.labels
        chartInstance.data.datasets = props.datasets
        if (props.options) {
            chartInstance.options = props.options
        }
        chartInstance.update()
    },
    { deep: true }
)

onBeforeUnmount(() => {
    chartInstance?.destroy()
})
</script>

<style scoped>
canvas {
    display: block;
}
</style>
