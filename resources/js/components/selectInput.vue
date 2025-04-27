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

defineProps<{
    Items: {
        value: string
        label: string
    }[]
    Label: string
}>();
</script>

<template>
    <Select class="w-full" :defaultValue="Items[0].value">
        <SelectTrigger>
            <SelectValue placeholder="Select item" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>{{ Label }}</SelectLabel>
                <SelectItem v-for="item in Items" :key="item.value" :value="item.value">
                    {{ item.label }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
