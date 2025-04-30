<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { watch, computed } from 'vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Search } from 'lucide-vue-next'
import { type SharedData } from '@/types';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
const { getPhotoUrl } = usePhotoUrl();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Search',
        href: '/search',
    },
];

const page = usePage<SharedData>();
const babysitters = computed(() => page.props.babySitters);


const filterForm = useForm({
    name: page.props.filters.name ?? "",
});

const Bio = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'

// Fonction de filtrage avec un délai pour éviter des appels excessifs
let filterTimeout: NodeJS.Timeout | null = null;
const autoFilter = (routeName: string) => {
    if (filterTimeout) {
        clearTimeout(filterTimeout);
    }
    filterTimeout = setTimeout(() => {
        filterForm.get(route(routeName), {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300); // Délai de 300ms pour éviter les appels excessifs
};

// Réinitialiser le formulaire lorsque la recherche est vide
watch(() => filterForm.name, (newValue: string) => {
    if (!newValue) {
        filterForm.name = "";
        autoFilter('search.babysitter');
    }
});

</script>

<template>

    <Head title="Search" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 max-w-7xl mx-auto">
            <div class="relative max-w-7xl items-center">
                <Input id="search" type="text" placeholder="Search..." class="pl-10" v-model="filterForm.name"
                    @input="filterForm.name.length >= 1 ? autoFilter('search.babysitter') : null" />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>

            <div v-if="babysitters.data.length > 0"
                class="grid grid-cols-1 max-w-7xl gap-4 md:grid-cols-2 lg:grid-cols-5 mt-5 md:space-y-0">
                <div v-for="babysitter in babysitters.data" :key="babysitter.id" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <div v-if="babysitter.profile.photos.length > 0">
                            <div v-for="avatar in babysitter.profile.photos" :key="avatar.id">
                                <img v-if="avatar.is_profile_picture" :src="getPhotoUrl(avatar.url)"
                                    :alt="babysitter.name" class="h-52 w-full object-cover rounded-lg" />
                            </div>
                        </div>
                        <h2 class="text-lg font-semibold">{{ babysitter.name }}</h2>
                        <p class="text-gray-500 min-h-[10vh]" v-if="babysitter.profile?.bio">
                            {{ babysitter.profile?.bio.slice(0, 100) }}
                        </p>
                        <p class="text-gray-500 min-h-[10vh]" v-else>
                            {{ Bio.slice(0, 100) }}
                        </p>
                        <Link :href="route('babysitter.show', babysitter)">
                        <Button variant="outline" class="w-full">
                            View Profile
                        </Button>
                        </Link>
                        <Link :href="route('reservations.create', { user: babysitter })">
                        <Button class="w-full">Book me now</Button>
                        </Link>
                    </div>
                </div>
            </div>
            <div v-else class="w-full flex flex-1 justify-center max-w-7xl">
                <p class="text-gray-500 text-center">No babysitters found.</p>
            </div>
        </div>
    </AppLayout>
</template>
