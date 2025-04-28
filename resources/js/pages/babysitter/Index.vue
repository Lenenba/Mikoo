<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { type SharedData } from '@/types';
import { Progress } from '@/components/ui/progress';
import { Star } from 'lucide-vue-next';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area'
import { Button } from '@/components/ui/button';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
const { getPhotoUrl } = usePhotoUrl();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'BabySitter Profile',
        href: '/babySitter',
    },
];

const page = usePage<SharedData>();
const babysitter = computed(() => page.props.babySitter[0]);

</script>

<template>

    <Head title="BabySitter" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-6 space-y-4 p-4">
            <div class="grid-cols-2 w-full">
                <div class="flex flex-row items-center justify-center" v-for="photo in babysitter.profile.photos"
                    :key="photo.id">
                    <img :src="getPhotoUrl(photo.url)" v-if="photo.is_profile_picture" alt="Profile Image"
                        class="w-full object-cover rounded-lg mb-4">
                </div>
                <div class="bg-gray-100 w-full rounded-lg mb-4">
                    <div class="flex flex-col p-8">
                        <h3 class="text-xl semi-bold mb-2">Details</h3>
                        <p class="text-gray-500">Email : {{ babysitter.email }}</p>
                        <p class="text-gray-500">birthdate : {{ babysitter.profile.birthdate }}</p>
                        <p class="text-gray-500">Phone : {{ babysitter.profile.phone }}</p>
                        <p class="text-gray-500">Experience : {{ babysitter.profile.experience }}</p>
                    </div>
                </div>

                <div class="bg-gray-100 w-full rounded-lg mb-4 p-8">
                    <div class="flex flex-col">
                        <h3 class="text-xl semi-bold mb-2">Review</h3>
                        <Progress :model-value="33" class="text-yellow-500">
                        </Progress>
                    </div>
                </div>
            </div>
            <div class="col-span-4 h-full">
                <div class="flex flex-col m-8">
                    <div class="flex flex-row items-center justify-between">
                        <h1 class="text-4xl semi-bold mb-2">{{ babysitter.name }}</h1>
                        <Link :href="route('reservations.create', { user: babysitter })">
                        <Button variant="destructive">Book me now</Button>
                        </Link>
                    </div>
                    <h2 class="text-lg text-gray-400 mb-4">{{ babysitter.profile.address }}</h2>
                </div>
                <div class="bg-gray-100 p-4 m-8 rounded-lg">
                    <h3 class="text-xl semi-bold mb-2">About Me</h3>
                    <p class="text-gray-500">{{ babysitter.profile.bio }}</p>
                </div>
                <div class="bg-gray-100 p-4 m-8 rounded-lg">
                    <h3 class="text-xl semi-bold">Certification & Experience</h3>
                    <div class="bg-gray-100 p-4 flex flex-row">
                        <div class="flex flex-col m-8" v-for="certification in babysitter.profile.certifications"
                            :key="certification.id">
                            <h3 class="text-xl semi-bold mb-2">{{ certification.title }}</h3>
                            <p class="text-gray-500">{{ certification.description }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 p-4 m-8 rounded-lg">
                    <h3 class="text-xl semi-bold mb-2">Images</h3>
                    <ScrollArea class="rounded-md w-full whitespace-nowrap">
                        <div class="flex p-4 space-x-4 w-max">
                            <div v-for="photo in babysitter.profile.photos" :key="photo.id">
                                <figure class="shrink-0">
                                    <div class="overflow-hidden rounded-md">
                                        <img :src="getPhotoUrl(photo.url)" :alt="`Photo by me`"
                                            class="aspect-[3/4] w-36 h-56 object-cover">
                                    </div>
                                    <figcaption class="pt-2 text-xs text-muted-foreground">
                                        Babysitter
                                        <span class="font-semibold text-foreground">
                                            {{ babysitter.name }}
                                        </span>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                        <ScrollBar orientation="horizontal" />
                    </ScrollArea>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
