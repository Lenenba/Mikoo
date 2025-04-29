<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { type SharedData } from '@/types';
import Carousel from '@/components/Carousel.vue';

const page = usePage<SharedData>();
const photos = computed(() => page.props.photos);
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Photos settings',
        href: '/settings/Photos',
    },
];

const form = useForm({
    images: [],
})
const imageErrors = computed(() => Object.values(form.errors))
const canUpload = computed(() => form.images.length)
const upload = () => {
    form.post(
        route('photos.store'),
        {
            onSuccess: () => form.reset('images'),
        },
    )
}
const addFiles = (event) => {
    for (const image of event.target.files) {
        form.images.push(image)
    }
}
const reset = () => form.reset('images')
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Photos settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Photos settings" description="Update your account's Photos settings" />

                <h3>Upload New Images</h3>
                <form @submit.prevent="upload">
                    <section class="flex items-center gap-2 my-4">
                        <Input
                            class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
                            type="file" multiple @input="addFiles" />
                        <Button type="submit" class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                            :disabled="!canUpload">
                            Upload
                        </Button>
                        <Button type="reset" class="btn-outline" @click="reset">
                            Reset
                        </Button>
                    </section>
                    <div v-if="imageErrors.length" class="input-error">
                        <div v-for="(error, index) in imageErrors" :key="index">
                            {{ error }}
                        </div>
                    </div>
                </form>
                <div class="mx-auto p-2">
                    <Carousel :photos="photos" />
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
