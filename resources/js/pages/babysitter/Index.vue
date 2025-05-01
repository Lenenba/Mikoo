<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { type SharedData } from '@/types';
import { Progress } from '@/components/ui/progress';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Button } from '@/components/ui/button';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
import { Badge } from '@/components/ui/badge';
const { getPhotoUrl } = usePhotoUrl();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'BabySitter Profile', href: '/babySitter' },
];

const page = usePage<SharedData>();
const babysitter = computed(() => page.props.babySitter[0]);
</script>

<template>

    <Head title="BabySitter" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 max-w-7xl">
                <!-- Left column -->
                <div class="md:col-span-2 flex flex-col gap-4">
                    <!-- Profile picture -->
                    <div class="flex justify-center">
                        <img :src="getPhotoUrl(babysitter.profile.photos.find(p => p.is_profile_picture)?.url)"
                            alt="Profile Image" class="w-32 h-32 md:w-full md:h-auto object-cover rounded-sm" />
                    </div>

                    <!-- Details card -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details</h3>
                        <p class="text-gray-600 dark:text-gray-400">Email: {{ babysitter.email }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Birthdate: {{ babysitter.profile.birthdate }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Phone: {{ babysitter.profile.phone }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Experience: {{ babysitter.profile.experience }}</p>
                    </div>

                    <!-- Review card -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Review</h3>
                        <Progress :model-value="33" class="w-full" />
                    </div>
                </div>

                <!-- Right column -->
                <div class="md:col-span-4 flex flex-col gap-4">
                    <!-- Header / Booking button -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <div class="flex flex-row items-baseline">
                                <h1 class="text-2xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ babysitter.name }}
                                </h1>
                                <badge
                                    class="text-lg md:text-xl ml-2 font-semibold bg-fuchsia-700 dark:bg-fuchsia-300 text-white">
                                    {{
                                        babysitter.profile.price_per_hour }} $</badge>
                            </div>
                            <h2 class="text-sm md:text-lg text-gray-500 dark:text-gray-400">
                                {{ babysitter.profile.address }}
                            </h2>
                        </div>
                        <Link :href="route('reservations.create', { user: babysitter })">
                        <Button variant="default">Book me now</Button>
                        </Link>
                    </div>

                    <!-- About Me -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">About Me</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ babysitter.profile?.bio ?? 'lorem' }}</p>
                    </div>

                    <!-- Certifications -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Certification & Experience
                        </h3>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div v-for="cert in babysitter.profile.certifications" :key="cert.id"
                                class="flex-1 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-1">
                                    {{ cert.title }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ cert.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Photo gallery -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Images</h3>
                        <ScrollArea class="rounded w-full">
                            <div class="flex space-x-4 p-2">
                                <div v-for="photo in babysitter.profile.photos" :key="photo.id" class="flex-shrink-0">
                                    <img :src="getPhotoUrl(photo.url)" alt="Gallery image"
                                        class="w-24 h-32 object-cover rounded" />
                                </div>
                            </div>
                            <ScrollBar orientation="horizontal" />
                        </ScrollArea>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
