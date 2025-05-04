<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { type SharedData, type Review, type BreadcrumbItem } from '@/types';
import { Progress } from '@/components/ui/progress';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Button } from '@/components/ui/button';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
import { Badge } from '@/components/ui/badge';
import { Star } from 'lucide-vue-next';

const { getPhotoUrl } = usePhotoUrl();

const page = usePage<SharedData & {
    reviews: Review[];
    averageRating: number;
    totalReviews: number;
    starCounts: number[];
    starPercentages: number[];
}>();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: 'BabySitter Profile', href: '/babySitter' },
]);

const babysitter = computed(() => page.props.babysitter);
const reviews = computed(() => page.props.reviews);
const averageRating = computed(() => page.props.averageRating);
const totalReviews = computed(() => page.props.totalReviews);
const starCounts = computed(() => page.props.starCounts);
const starPercentages = computed(() => page.props.starPercentages);

const sortKey = ref<'newest' | 'highest' | 'lowest'>('newest');
const sortedReviews = computed(() => {
    const list = [...reviews.value];
    if (sortKey.value === 'highest') return list.sort((a, b) => b.rating - a.rating);
    if (sortKey.value === 'lowest') return list.sort((a, b) => a.rating - b.rating);
    return list.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime());
});
</script>

<template>

    <Head title="BabySitter" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Left column -->
            <div class="md:col-span-2 flex flex-col gap-4">
                <!-- Profile picture -->
                <div class="flex justify-center">
                    <img :src="getPhotoUrl(babysitter.profile.photos.find(p => p.is_profile_picture)?.url)"
                        alt="Profile Image" class="w-32 h-32 md:w-full md:h-auto object-cover rounded-sm" />
                </div>

                <!-- Details card -->
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details</h3>
                    <p class="text-gray-600 dark:text-gray-400">Email: {{ babysitter.email }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Birthdate: {{ babysitter.profile.birthdate }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Phone: {{ babysitter.profile.phone }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Experience: {{ babysitter.profile.experience }}</p>
                </div>

                <!-- Mini Reviews summary -->
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reviews</h3>
                        <span class="text-sm text-gray-500 dark:text-neutral-400">
                            {{ sortedReviews.length }} / {{ totalReviews }}
                        </span>
                    </div>
                    <Progress :model-value="(averageRating / 5) * 100" class="w-full" />
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                        Avg: {{ averageRating.toFixed(1) }}/5
                    </p>
                </div>
            </div>

            <!-- Right column -->
            <div class="md:col-span-4 flex flex-col gap-4">
                <!-- Header / Booking button -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div>
                        <div class="flex items-baseline gap-2">
                            <h1 class="text-2xl md:text-4xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ babysitter.name }}
                            </h1>
                            <Badge
                                class="text-lg md:text-xl ml-2 font-semibold bg-fuchsia-700 dark:bg-fuchsia-300 text-white">
                                {{ babysitter.profile.price_per_hour }} $
                            </Badge>
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
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">About Me</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ babysitter.profile.bio || 'No bio provided.' }}
                    </p>
                </div>

                <!-- Certifications -->
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
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
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
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

                <!-- Full Reviews section spanning full width -->
                <div class="md:col-span-6 bg-gray-100 dark:bg-gray-800 p-4 rounded-sm">
                    <!-- Title Bar -->
                    <div
                        class="mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reviews</h3>
                        <span class="text-sm text-gray-500 dark:text-neutral-400">
                            {{ sortedReviews.length }} of {{ totalReviews }} reviews
                        </span>
                    </div>

                    <!-- Sort control -->
                    <div class="flex items-center mb-4 text-sm text-gray-500 dark:text-neutral-400">
                        <label class="me-2">Sort by:</label>
                        <select v-model="sortKey"
                            class="rounded border-gray-300 bg-white dark:bg-neutral-700 dark:border-neutral-600">
                            <option value="newest">Newest</option>
                            <option value="highest">Highest rated</option>
                            <option value="lowest">Lowest rated</option>
                        </select>
                    </div>

                    <!-- Average rating + snapshot -->
                    <div class="flex items-center gap-4 mb-6">
                        <p class="text-4xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ averageRating.toFixed(1) }}
                        </p>
                        <div class="space-y-2 flex-1">
                            <div v-for="n in 5" :key="n" class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ 6 - n }} stars</span>
                                <Progress :model-value="starPercentages[5 - n]"
                                    class="w-full h-2 bg-gray-200 dark:bg-neutral-700" />
                                <span class="min-w-4 text-xs text-gray-500 dark:text-neutral-500">
                                    {{ starCounts[5 - n] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Review list -->
                    <div class="space-y-6">
                        <div v-for="review in sortedReviews" :key="review.id"
                            class="pb-6 border-b border-gray-200 dark:border-neutral-700">
                            <!-- Stars -->
                            <div class="flex items-center gap-0.5 mb-2">
                                <template v-for="i in 5" :key="i">
                                    <Star :class="[
                                        'w-4 h-4',
                                        i <= review.rating
                                            ? 'text-indigo-600 dark:text-indigo-400'
                                            : 'text-gray-300 dark:text-neutral-600'
                                    ]" />
                                </template>
                                <span class="ms-2 text-xs text-gray-500 dark:text-neutral-500">
                                    {{ review.rating }}/5
                                </span>
                            </div>
                            <!-- Title & Date -->
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ review.headline }}
                                </h4>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">
                                    {{ new Date(review.created_at).toLocaleDateString() }}
                                </span>
                            </div>
                            <!-- Media preview -->
                            <div v-if="review.photos.length" class="flex gap-2 mb-2">
                                <img v-for="photo in review.photos" :key="photo.id" :src="getPhotoUrl(photo.file_path)"
                                    class="w-20 h-20 object-cover rounded" alt="Review photo" />
                            </div>
                            <!-- Body -->
                            <p class="text-sm text-gray-800 dark:text-neutral-200">
                                {{ review.review }}
                            </p>
                            <!-- Author -->
                            <p class="mt-3 text-xs text-gray-500 dark:text-neutral-500">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
