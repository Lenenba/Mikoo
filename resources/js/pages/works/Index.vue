<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { format } from 'date-fns';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import type { Work } from '@/types/work';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
const { getPhotoUrl } = usePhotoUrl();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Works list',
        href: '/works',
    },
];

const page = usePage();
const works = computed<Work[]>(() => page.props.works);
const selectedWork = ref<Work | null>(null);

onMounted(() => {
    if (works.value.length > 0) {
        selectedWork.value = works.value[0];
    }
});

function selectWork(work: Work) {
    selectedWork.value = work;
}

function formatDate(dateStr: string | undefined) {
    return dateStr ? format(new Date(dateStr), 'dd MMMM yyyy') : 'N/A';
}
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Left column: work list -->
                <ScrollArea class="h-[80vh] w-full rounded-md border md:col-span-1">
                    <div class="p-4">
                        <h4 class="mb-4 text-sm font-medium leading-none">Travaux</h4>
                        <div v-for="work in works" :key="work.id" @click="selectWork(work)"
                            class="cursor-pointer hover:bg-muted px-2 py-1 rounded border border-transparent hover:border-gray-200">
                            <div class="flex flex-row justify-between items-center">
                                <div class="text-sm font-semibold">{{ formatDate(work.scheduled_for) }}</div>
                                <p class="text-sm mt-2">Heure : {{ work.start_time }} → {{ work.end_time
                                    }}</p>
                            </div>
                            <div class="text-xs text-muted-foreground">{{ work.reservation?.notes }}</div>
                            <Separator class="my-2" />
                        </div>
                    </div>
                </ScrollArea>

                <!-- Right column: work detail -->
                <div class="col-span-3 p-6 rounded-md border bg-white dark:bg-gray-900">
                    <template v-if="selectedWork">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-bold">Travail du {{ formatDate(selectedWork.scheduled_for) }}
                                </h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Réservation : {{ selectedWork.reservation?.notes || 'Aucune note' }}
                                </p>
                                <p class="text-sm mt-2">Heure : {{ selectedWork.start_time }} → {{ selectedWork.end_time
                                    }}</p>
                                <p class="text-sm mt-2">Statut :
                                    <span class="font-medium">
                                        {{ selectedWork.is_completed ? 'Terminé' : 'En attente' }}
                                    </span>
                                    —
                                    <span>
                                        {{ selectedWork.is_approved_by_parent ? 'Validé par le parent' :
                                            'En attente de validation' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex flex-row md:flex-col md:items-center md:justify-between gap-2">
                                <Link :href="route('reservations.index')">
                                <Button variant="default">Valide</Button>
                                </Link>
                                <Link :href="route('reservations.index')">
                                <Button variant="destructive">Annule</Button>
                                </Link>
                                <Link :href="route('reservations.index')">
                                <Button variant="outline">Notes </Button>
                                </Link>
                            </div>
                        </div>
                        <Separator class="my-4" />
                        <!-- Fiche Babysitter si dispo -->
                        <div v-if="selectedWork.reservation?.babysitter">
                            <Separator class="my-4" />

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 w-full">
                                <!-- Left column -->
                                <div class="md:col-span-2 flex flex-col gap-4">
                                    <!-- Profile picture -->
                                    <div class="flex justify-center">
                                        <img :src="getPhotoUrl(selectedWork.reservation.babysitter.profile.photos.find(p => p.is_profile_picture)?.url)"
                                            alt="Profile Image"
                                            class="w-32 h-32 md:w-full md:h-auto object-cover rounded-sm" />
                                    </div>
                                </div>
                                <!-- Right column -->
                                <div class="md:col-span-4 flex flex-col gap-4">
                                    <!-- Header / Booking button -->
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                        <div>
                                            <div class="flex flex-row items-baseline">
                                                <h1
                                                    class="text-2xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ selectedWork.reservation.babysitter.name }}
                                                </h1>
                                            </div>
                                            <h2 class="text-sm md:text-lg text-gray-500 dark:text-gray-400">
                                                {{ selectedWork.reservation.babysitter.profile.address }}
                                            </h2>
                                        </div>

                                    </div>

                                    <!-- About Me -->
                                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            About Me</h3>
                                        <p class="text-gray-600 dark:text-gray-400">{{
                                            selectedWork.reservation.babysitter.profile?.bio ??
                                            'lorem' }}</p>
                                    </div>

                                    <!-- Certifications -->
                                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            Certification & Experience
                                        </h3>
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div v-for="cert in selectedWork.reservation.babysitter.profile.certifications"
                                                :key="cert.id" class="flex-1 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-1">
                                                    {{ cert.title }}
                                                </h4>
                                                <p class="text-gray-600 dark:text-gray-400">{{ cert.description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details card -->
                                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">Email: {{
                                            selectedWork.reservation.babysitter.email }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Birthdate: {{
                                            selectedWork.reservation.babysitter.profile.birthdate }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Phone: {{
                                            selectedWork.reservation.babysitter.profile.phone
                                            }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Experience: {{
                                            selectedWork.reservation.babysitter.profile.experience }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fiche du Parent si pas de babysitter -->
                        <div v-else>
                            <Separator class="my-4" />
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 w-full">
                                <!-- Left column -->
                                <div class="md:col-span-2 flex flex-col gap-4">
                                    <!-- Profile picture -->
                                    <div class="flex justify-center">
                                        <img :src="getPhotoUrl(selectedWork.reservation.user?.profile.photos.find(p => p.is_profile_picture)?.url)"
                                            alt="Profile Image"
                                            class="w-32 h-32 md:w-full md:h-auto object-cover rounded-sm" />
                                    </div>

                                </div>
                                <!-- Right column -->
                                <div class="md:col-span-4 flex flex-col gap-4">
                                    <!-- Header / Booking button -->
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                        <div>
                                            <div class="flex flex-row items-baseline">
                                                <h1
                                                    class="text-2xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ selectedWork.reservation.user.name }}
                                                </h1>
                                            </div>
                                            <h2 class="text-sm md:text-lg text-gray-500 dark:text-gray-400">
                                                {{ selectedWork.reservation.user.profile.address }}
                                            </h2>
                                        </div>

                                    </div>

                                    <!-- About Me -->
                                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            About Me</h3>
                                        <p class="text-gray-600 dark:text-gray-400">{{
                                            selectedWork.reservation.user.profile?.bio ??
                                            'lorem' }}</p>
                                    </div>
                                    <!-- Details card -->
                                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm ">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">Email: {{
                                            selectedWork.reservation.user?.email }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Birthdate: {{
                                            selectedWork.reservation.user?.profile.birthdate }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Phone: {{
                                            selectedWork.reservation.user?.profile.phone
                                            }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">Experience: {{
                                            selectedWork.reservation.user?.profile.experience }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <p class="text-sm text-muted-foreground">Aucun travail sélectionné</p>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
