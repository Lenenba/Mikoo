<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage, Link } from '@inertiajs/vue3'
import { type BreadcrumbItem, type SharedData } from '@/types'
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { usePhotoUrl } from '@/composables/usePhotoUrl'
import { Badge } from '@/components/ui/badge'
const { getPhotoUrl } = usePhotoUrl()

// Breadcrumbs
const page = usePage<SharedData>()
const reservation = computed(() => page.props.reservation)

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reservations', href: '/reservations' },
    { title: `#${reservation.value.id}`, href: route('reservations.show', reservation.value.id) },
]
</script>

<template>

    <Head :title="`Reservation #${reservation.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 max-w-7xl">
                <div class="md:col-span-2 flex flex-col gap-4">
                    <!-- Profile picture -->
                    <div class="flex justify-center">
                        <img :src="getPhotoUrl(reservation.babysitter.profile.photos.find(p => p.is_profile_picture)?.url)"
                            alt="Profile Image" class="w-32 h-32 md:w-full md:h-auto object-cover rounded-sm" />
                    </div>

                    <!-- Details card -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details</h3>
                        <p class="text-gray-600 dark:text-gray-400">Email: {{ reservation.babysitter.email }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Birthdate: {{
                            reservation.babysitter.profile.birthdate }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Phone: {{ reservation.babysitter.profile.phone }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">Experience: {{
                            reservation.babysitter.profile.experience }}</p>
                    </div>
                </div>
                <div class="md:col-span-4 flex flex-col gap-4">
                    <!-- Header / Booking button -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <h1 class="text-2xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ reservation.babysitter.name }}
                            </h1>
                            <h2 class="text-sm md:text-lg text-gray-500 dark:text-gray-400">
                                {{ reservation.babysitter.profile.address }}
                            </h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <Link :href="route('reservations.create', { user: reservation.babysitter })">
                            <Button>Book me again</Button>
                            </Link>
                            <Button variant="outline">
                                {{ reservation.status }}
                            </Button>
                        </div>
                    </div>

                    <!-- About Me -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">About Me</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ reservation.babysitter.profile?.bio ?? 'lorem' }}
                        </p>
                    </div>

                    <!-- Reservation details -->
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Reservation Details
                        </h3>

                        <div class="flex-1 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-1">
                                Reservation ID: {{ reservation.id }}
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">Status: {{ reservation.status }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Start Date: {{
                                reservation.recurrence_start_date }}</p>
                            <p class="text-gray-600 dark:text-gray-400">End Date: {{ reservation.recurrecnce_end_date }}
                            </p>

                        </div>
                        <div class="flex-1 bg-gray-50 dark:bg-gray-700 p-4 rounded my-4">
                            <h4>
                                Notes: {{ reservation.notes }}
                            </h4>
                        </div>
                    </div>
                    <div class="flex justify-end my-auto">
                        <Link :href="route('reservations.index')">
                        <Button>
                            Back to Reservations
                        </Button>
                        </Link>

                        <Button variant="outline" class="ml-2">
                            Edit Reservation
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
