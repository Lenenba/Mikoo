<script setup lang="ts">
import { Button } from '@/components/ui/button'

import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import {
    DateFormatter,
    type DateValue,
    getLocalTimeZone,
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref } from 'vue'

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
})

const value = ref<DateValue>()
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="[
                'justify-start text-left font-normal',
                !value ? 'text-muted-foreground' : ''
            ]">
                {{ value ? df.format(value.toDate(getLocalTimeZone())) : "Pick a date" }}
                <CalendarIcon class="ms-auto h-4 w-4 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar v-model="value" initial-focus />
        </PopoverContent>
    </Popover>
</template>
