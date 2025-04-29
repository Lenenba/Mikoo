<script setup lang="ts">
import { VisXYContainer, VisStackedBar } from '@unovis/vue'
import { computed } from 'vue'

// Props definitions
defineProps<{
    title: string
    total: number | string
    variation: string
    variationColor?: string
    data: { label: string; value: number }[]
    yDomain?: [number, number]
}>()


// Accessors
const x = (d: any, i: number) => i
const y = (d: any) => d.value
</script>

<template>
    <div class="p-4 max-h-full w-full">
        <!-- Header section -->
        <div class="flex flex-col space-y-1">
            <span class="text-2xl font-bold text-gray-700">{{ title }}</span>
            <span class="text-7xl font-bold text-green-800 dark:text-green-200">{{ total }}</span>
            <span class="text-xs text-green-600">{{ variation }}</span>
        </div>

        <!-- Sparkline section -->
        <div class="mt-4 h-24 w-full">
            <VisXYContainer :data="data" :xDomain="[0, data.length - 1]"
                :yDomain="yDomain || [0, Math.max(...data.map(d => d.value)) + 20]" class="h-full w-full"
                :margin="{ top: 10, right: 10, bottom: 10, left: 10 }">
                <VisStackedBar :x="x" :y="y" stroke="black" strokeWidth="2" />
            </VisXYContainer>
        </div>
    </div>
</template>
