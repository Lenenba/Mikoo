<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type SharedData } from '@/types';
import { computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
import Badge from '@/components/ui/badge/Badge.vue';
import { UserRoundSearch, Shell, CalendarDays, Calendar1, CalendarSync } from 'lucide-vue-next';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import rrulePlugin from '@fullcalendar/rrule';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group'


const { getPhotoUrl } = usePhotoUrl();

const page = usePage<SharedData>();
const babysitter = computed(() => page.props.babysitter);
const events = computed(() => page.props.events);
const profilePicture = computed(() => page.props.profilePicture);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Add Reservations',
        href: '/reservations',
    },
];

const form = useForm({
    recurrence_start_date: '',
    recurrence_end_date: '',
    start_time: '',
    end_time: '',
    babysitter_id: babysitter.value.id,
    recurrence_interval: 1,
    days_of_week: [],
    notes: '',
    recurrence_freq: 'daily',
    is_recurring: false,
});

const createReservation = () => {
    form.post(route('reservations.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

function onToggle(value: boolean) {
    form.is_recurring = value;
}
// FullCalendar options
const calendarOptions = ref({
    aspectRatio: 2.3,
    plugins: [rrulePlugin, dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth', // Affiche la semaine en cours
    weekends: true, // initial value
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,dayGridMonth', // Options de vue semaine/mois
    },
    events: events,
});
</script>

<template>

    <Head title="Add Reservations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 mb-8">

            <!-- Babysitter profile card -->
            <div class="flex flex-row items-center border p-4 border-gray-200 justify-between rounded-lg">
                <div class="flex flex-row items-center p-4">
                    <img :src="getPhotoUrl(profilePicture)" alt="Profile Picture"
                        class="h-44 w-44 object-cover rounded-lg mr-6" />
                    <div class="flex flex-col">
                        <h1 class="text-4xl font-semibold mb-2">{{ babysitter.name }}</h1>
                        <p class="text-gray-500">Email: {{ babysitter.email }}</p>
                        <p class="text-gray-500">Phone: {{ babysitter.profile.phone }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-end">
                    <Badge class="mb-2">
                        <UserRoundSearch class="mr-2" />
                        Disponible
                    </Badge>
                    <Badge>
                        <Shell class="mr-2 animate-spin" />
                        En ligne
                    </Badge>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="grid gap-6 md:grid-cols-3 w-full min-h-[calc(100vh-8rem)] md:min-h-min">
                <!-- Form / Infos -->
                <div class="col-span-1 flex flex-col justify-start p-4">
                    <h1 class="text-2xl font-semibold mb-2">Add Reservations</h1>
                    <p class="text-gray-500 mb-6">Please fill in the form below to add a reservation.</p>

                    <form @submit.prevent="createReservation" class="space-y-6">
                        <div class="flex flex-col gap-4 items-center justify-between rounded-lg border p-4">
                            <div class="flex flex-row items-center justify-between w-full">
                                <div class="space-y-0.5">
                                    <h2 class="text-base">
                                        Is recurring reservation?
                                    </h2>
                                    <p key="description" class="text-sm text-gray-500">
                                        If you want to create a recurring reservation, please select the option below.
                                    </p>
                                </div>
                                <Switch v-model="form.is_recurring" @update:model-value="onToggle" />
                            </div>
                            <div class="grid gap-2 w-full">
                                <ToggleGroup type="single" class="w-full" v-model="form.recurrence_freq"
                                    variant="outline">
                                    <ToggleGroupItem value="daily" aria-label="Toggle bold">
                                        <Calendar1 class="h-4 w-4" /> Daily
                                    </ToggleGroupItem>
                                    <ToggleGroupItem value="weekly" aria-label="Toggle italic">
                                        <CalendarDays class="h-4 w-4" /> Weekly
                                    </ToggleGroupItem>
                                    <ToggleGroupItem value="monthly" aria-label="Toggle underline">
                                        <CalendarSync class="h-4 w-4" /> Monthly
                                    </ToggleGroupItem>
                                </ToggleGroup>
                            </div>
                            <div class="grid gap-2 w-full">
                                <ToggleGroup type="multiple" class="w-full" v-model="form.days_of_week"
                                    variant="outline">
                                    <ToggleGroupItem aria-label="Toggle bold"
                                        v-for="day in ['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU']" :value="day"
                                        :key="day">
                                        {{ day }}
                                    </ToggleGroupItem>
                                </ToggleGroup>
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between rounded-lg border p-4 gap-4">
                            <div class="grid gap-2 w-full">
                                <Label for="name">Start date</Label>
                                <Input type="date" class="mt-1 block w-full" v-model="form.recurrence_start_date"
                                    required placeholder="Start date" />
                                <InputError class="mt-2" :message="form.errors.recurrence_start_date" />
                            </div>
                            <div class="grid gap-2 w-full">
                                <Label for="name">End date</Label>
                                <Input type="date" class="mt-1 block w-full" v-model="form.recurrence_end_date" required
                                    placeholder="End date" />
                                <InputError class="mt-2" :message="form.errors.recurrence_end_date" />
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between rounded-lg border p-4 gap-4">
                            <div class="grid gap-2 w-full">
                                <Label for="name">Start time</Label>
                                <Input type="time" class="mt-1 block w-full" v-model="form.start_time" required
                                    placeholder="Start time" />
                                <InputError class="mt-2" :message="form.errors.start_time" />
                            </div>
                            <div class="grid gap-2 w-full">
                                <Label for="name">End time</Label>
                                <Input type="time" class="mt-1 block w-full" v-model="form.end_time" required
                                    placeholder="End time" />
                                <InputError class="mt-2" :message="form.errors.end_time" />
                            </div>
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="name">Notes</Label>
                            <Textarea type="text" class="mt-1 block w-full" v-model="form.notes" required
                                placeholder="Notes" />
                            <InputError class="mt-2" :message="form.errors.notes" />
                        </div>
                        <div class="flex flex-row items-center justify-between ">
                            <Button type="submit" :disabled="form.processing">
                                Add Reservation
                            </Button>
                            <Button type="button" variant="secondary" @click="form.reset">
                                Reset
                            </Button>
                        </div>
                    </form>
                </div>

                <!-- Calendar -->
                <div class="col-span-2 flex flex-col p-4">
                    <div class="flex-1 overflow-hidden rounded-lg shadow bg-white">
                        <FullCalendar :options="calendarOptions" ref="calendarRef" class="w-full h-full" />
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
