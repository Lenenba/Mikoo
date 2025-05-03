<!-- components/FormSelect.vue -->
<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { type PropType } from 'vue'

interface Option {
    value: string | number
    label: string
}

const props = defineProps({
    label: { type: String, required: true },
    items: { type: Array as PropType<Option[]>, required: true },
    modelValue: { type: [String, Number] as PropType<string | number>, required: false },
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void
}>()

/** Handle selection change */
function onSelect(value: string | number) {
    emit('update:modelValue', value)
}
</script>

<template>
    <Select class="w-full gap-2" :value="modelValue" @value-change="onSelect">
        <SelectTrigger>
            <SelectValue placeholder="Please select…" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>{{ label }}</SelectLabel>
                <SelectItem v-for="item in items" :key="item.value" :value="item.value">
                    {{ item.label }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
