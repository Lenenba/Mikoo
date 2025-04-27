<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AvailabilityForm from './components/AvailabilityForm.vue';
import AvailabilityTable from './components/AvailabilityTable.vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const availability = {
    start_date: '',
    end_date: '',
    start_time: '',
    end_time: '',
    note: '',
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Availability settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Availabilities settings" description="Add or Update your availabilities" />
                <AvailabilityForm :availability="availability" />
                <AvailabilityTable />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
