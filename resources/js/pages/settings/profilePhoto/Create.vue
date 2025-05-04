<!-- SettingsPhotos.vue -->
<script setup lang="ts">
import { ref, onBeforeUnmount, computed } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { type SharedData } from '@/types';
import Carousel from '@/components/Carousel.vue';
import { X } from 'lucide-vue-next';

const page = usePage<SharedData>();
const photos = computed(() => page.props.photos);
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Photos settings', href: '/settings/Photos' },
];

// Inertia form state
const form = useForm({ images: [] });
const imageErrors = computed(() => Object.values(form.errors));
const canUpload = computed(() => form.images.length > 0);

// Submit handler
const upload = () => {
    form.post(route('photos.store'), {
        onSuccess: () => resetAll(),
    });
};

// Store both File & preview URL
const mediaPreviews = ref<{ file: File; preview: string }[]>([]);
const MAX_PHOTOS = 5;

/**
 * Rebuild the FileList in form.images from mediaPreviews.
 */
function updateFormMedia() {
    const dt = new DataTransfer();
    mediaPreviews.value.forEach(mp => dt.items.add(mp.file));
    form.images = dt.files;
}

/**
 * Handle input change: add new files up to MAX_PHOTOS.
 */
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files) return;

    const toAdd = Array.from(target.files).slice(
        0,
        MAX_PHOTOS - mediaPreviews.value.length
    );

    toAdd.forEach(file => {
        const url = URL.createObjectURL(file);
        mediaPreviews.value.push({ file, preview: url });
    });

    updateFormMedia();
    target.value = '';
};

/**
 * Remove a preview & revoke its URL.
 */
const removePhoto = (index: number) => {
    URL.revokeObjectURL(mediaPreviews.value[index].preview);
    mediaPreviews.value.splice(index, 1);
    updateFormMedia();
};

/**
 * Reset the form and clear previews.
 */
const resetAll = () => {
    mediaPreviews.value.forEach(mp => URL.revokeObjectURL(mp.preview));
    mediaPreviews.value = [];
    form.reset('images');
};

// Clean up all object URLs when component is destroyed.
onBeforeUnmount(() => {
    mediaPreviews.value.forEach(mp => URL.revokeObjectURL(mp.preview));
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Photos settings" />
        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Photos settings" description="Update your account's Photos settings" />
                <form @submit.prevent="upload">
                    <!-- Photo upload + previews -->
                    <div>
                        <Label class="block mb-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                            Add photos (max {{ MAX_PHOTOS }})
                        </Label>
                        <div class="flex flex-wrap gap-2">
                            <!-- Add button -->
                            <label for="media"
                                class="flex shrink-0 justify-center items-center w-32 h-32 border-2 border-dotted border-gray-300 rounded-xl text-gray-400 cursor-pointer hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-600 dark:hover:bg-neutral-700/20">
                                <input id="media" type="file" accept="image/*" multiple class="hidden"
                                    @change="onFileChange" :disabled="mediaPreviews.length >= MAX_PHOTOS" />
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5v14" />
                                </svg>
                            </label>

                            <!-- Previews -->
                            <template v-for="(mp, idx) in mediaPreviews" :key="idx">
                                <div
                                    class="relative w-32 h-32 rounded-xl overflow-hidden border border-gray-300 dark:border-neutral-700">
                                    <img :src="mp.preview" class="object-cover w-full h-full" alt="preview" />
                                    <button type="button" @click="removePhoto(idx)"
                                        class="absolute top-1 right-1 p-1 bg-white rounded-full dark:bg-neutral-800">
                                        <X class="w-4 h-4 text-gray-500 dark:text-neutral-400" />
                                    </button>
                                </div>
                            </template>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-500">
                            Shoppers find images more helpful than text alone.
                        </p>
                    </div>

                    <div v-if="imageErrors.length" class="input-error">
                        <div v-for="(error, index) in imageErrors" :key="index">
                            {{ error }}
                        </div>
                    </div>

                    <div class="flex mt-4">
                        <Button type="submit" class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                            :disabled="!canUpload">
                            Upload
                        </Button>
                        <Button type="button" class="ml-4" variant="outline" @click="resetAll">
                            Reset
                        </Button>
                    </div>
                </form>

                <div class="mx-auto p-2">
                    <Carousel :photos="photos" />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
