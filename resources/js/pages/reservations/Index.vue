<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import DataTable from '@/components/DataTable.vue';
import { type ReservationRow, columns } from '@/types/reservationRow';
import ReservationLineChart from './components/ReservationLineChart.vue';

const { getPhotoUrl } = usePhotoUrl();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reservations', href: '/reservations' },
];

// Shared page props
const page = usePage<SharedData>();

// Raw reservations list
const reservations = computed<ReservationRow[]>(
    () => page.props.reservations as ReservationRow[]
);

// Detailed reservations for the table
const reservationsDetails = ref<ReservationRow[]>([]);
onMounted(() => {
    reservationsDetails.value = reservations.value;
});

// ---- Statistiques mensuelles ----
// Type for chart data
type Stat = { label: string; value: number };

// Total counts
const reservationCount = computed<number>(
    () => page.props.allCount as number
);
const reservationLastMonthCount = computed<number>(
    () => page.props.allLastMonthCount as number
);
const reservationStats = computed<Stat[]>(() => page.props.allStats as Stat[]);

const pendingCount = computed<number>(
    () => page.props.pendingCount as number
);
const pendingLastMonthCount = computed<number>(
    () => page.props.pendingLastMonthCount as number
);
const pendingMonthStats = computed<Stat[]>(() => page.props.pendingStats as Stat[]);

const canceledCount = computed<number>(
    () => page.props.canceledCount as number
);
const canceledLastMonthCount = computed<number>(
    () => page.props.canceledLastMonthCount as number
);
const canceledMonthStats = computed<Stat[]>(() => page.props.canceledStats as Stat[]);

const confirmedCount = computed<number>(
    () => page.props.confirmedCount as number
);
const confirmedLastMonthCount = computed<number>(
    () => page.props.confirmedLastMonthCount as number
);
const confirmedMonthStats = computed<Stat[]>(() => page.props.confirmedStats as Stat[]);
</script>

<template>

    <Head title="Reservations" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <ReservationLineChart title="Total des Réservations" :total="reservationCount"
                        :variation="'plus de ' + reservationLastMonthCount + ' le mois en cours'"
                        :data="reservationStats" />
                </div>

                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <ReservationLineChart title="Total des Réservations annulees" :total="pendingCount"
                        :variation="'plus de ' + pendingLastMonthCount + ' le mois en cours'"
                        :data="pendingMonthStats" />
                </div>

                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <ReservationLineChart title="Total des Réservations en Attente" :total="canceledCount"
                        :variation="'plus de ' + canceledLastMonthCount + ' le mois en cours'"
                        :data="canceledMonthStats" />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <ReservationLineChart title="Total des Réservations en Attente" :total="confirmedCount"
                        :variation="'plus de ' + confirmedLastMonthCount + ' le mois en cours'"
                        :data="confirmedMonthStats" />
                </div>
            </div>

            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTable :columns="columns" :data="reservationsDetails" class="p-4" />
            </div>
        </div>
    </AppLayout>
</template>
