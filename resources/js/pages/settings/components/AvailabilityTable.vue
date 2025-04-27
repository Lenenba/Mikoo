<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { usePage, Link, } from '@inertiajs/vue3';
import { type SharedData } from '@/types';
import { FilePenLine } from 'lucide-vue-next';
import Delete from './Delete.vue';
const page = usePage<SharedData>();
const availabilities = computed(() => page.props.availabilities);

</script>

<template>
    <table class="w-full table-auto border-collapse rounded-lg overflow-hidden shadow-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 text-left font-semibold text-gray-700">Start date</th>
                <th class="p-4 text-left font-semibold text-gray-700">End date</th>
                <th class="p-4 text-left font-semibold text-gray-700">Start time</th>
                <th class="p-4 text-left font-semibold text-gray-700">End time</th>
                <th class="p-4 text-left font-semibold text-gray-700">Note</th>
                <th class="p-4 text-left font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="availability in availabilities" :key="availability.id"
                class="border-b last:border-0 hover:bg-gray-50">
                <td class="p-4">{{ availability.start_date }}</td>
                <td class="p-4">{{ availability.end_date }}</td>
                <td class="p-4">{{ availability.start_time }}</td>
                <td class="p-4">{{ availability.end_time }}</td>
                <td class="p-4">{{ availability.note }}</td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <Delete :availability="availability" />
                        <Link :href="route('availabilities.edit', availability)">
                        <Button variant="outline" size="icon" class="w-8 h-8">
                            <FilePenLine class="h-4 w-4" />
                        </Button>
                        </Link>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
