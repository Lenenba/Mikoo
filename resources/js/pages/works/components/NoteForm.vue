<!-- ReviewDialog.vue -->
<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
    DialogClose,
} from '@/components/ui/dialog';
import { Star, X } from 'lucide-vue-next';
import type { Work } from '@/types/work';

// define props
const props = defineProps<{
    work: Work;
}>();

// Inertia form state
const formNotes = useForm({
    rating: 0,                       // overall rating: 1–5
    headline: '',                    // title of the review
    review: '',                      // detailed review
    media: null as FileList | null,  // will be populated from previews
});

// store both File & preview URL
const mediaPreviews = ref<{ file: File; preview: string }[]>([]);
const MAX_PHOTOS = 5;

/**
 * Rebuild the FileList in formNotes.media from mediaPreviews.
 */
function updateFormMedia() {
    const dt = new DataTransfer();
    mediaPreviews.value.forEach(mp => dt.items.add(mp.file));
    formNotes.media = dt.files;
}

/**
 * Handle input change: add new files up to MAX_PHOTOS.
 */
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files) return;

    // add files until limit
    const toAdd = Array.from(target.files).slice(
        0,
        MAX_PHOTOS - mediaPreviews.value.length
    );

    toAdd.forEach(file => {
        const url = URL.createObjectURL(file);
        mediaPreviews.value.push({ file, preview: url });
    });

    updateFormMedia();
    target.value = ''; // reset input to allow re-upload same file if removed
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
 * Clean up all object URLs when component is destroyed.
 */
onBeforeUnmount(() => {
    mediaPreviews.value.forEach(mp => URL.revokeObjectURL(mp.preview));
});

/**
 * Set the star rating.
 */
const setRating = (value: number) => {
    formNotes.rating = value;
};

/**
 * Submit handler.
 */
const submit = () => {
    // uncomment to actually send:
    formNotes.post(route('reviews.store', props.work.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => formNotes.reset(),
    });
};

const closeModal = () => {
    formNotes.clearErrors();
    formNotes.reset();
};
</script>

<template>
    <Dialog>
        <!-- Trigger button -->
        <DialogTrigger as-child>
            <Button variant="outline" class="w-32">Valider &amp; Noter</Button>
        </DialogTrigger>

        <!-- Modal content -->
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>Create review</DialogTitle>
                <DialogDescription>
                    Please share your thoughts on “{{ work.reservation.babysitter.name }}”
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Product info -->
                <div class="flex items-center gap-x-3">
                    <img :src="work.reservation.babysitter.profile.photos.find(p => p.is_profile_picture)?.url"
                        alt="Profile" class="w-12 h-12 object-cover rounded-lg bg-gray-100 dark:bg-neutral-700" />

                </div>

                <!-- Rating stars -->
                <div>
                    <Label class="block mb-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                        Overall rating
                    </Label>
                    <div class="flex items-center space-x-1">
                        <template v-for="i in 5" :key="i">
                            <Star @click="setRating(i)" :class="[
                                'w-7 h-7 cursor-pointer',
                                formNotes.rating >= i
                                    ? 'text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-300 dark:text-neutral-600',
                                'hover:text-indigo-500 dark:hover:text-indigo-300'
                            ]" />
                        </template>
                    </div>
                </div>

                <!-- Headline -->
                <div>
                    <Label for="headline" class="block mb-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                        Add a headline
                    </Label>
                    <Input id="headline" v-model="formNotes.headline" placeholder="What's most important to know?"
                        class="py-3 px-4 w-full" />
                </div>

                <!-- Review textarea -->
                <div>
                    <Label for="review" class="block mb-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                        Add a review
                    </Label>
                    <Textarea id="review" v-model="formNotes.review" rows="4"
                        placeholder="What did you like or dislike?" class="py-3 px-4 w-full" />
                </div>

                <!-- Photo or video upload + previews -->
                <div>
                    <Label class="block mb-2 text-sm font-medium text-gray-800 dark:text-neutral-200">
                        Add photos (max {{ MAX_PHOTOS }})
                    </Label>
                    <div class="flex flex-wrap gap-2">
                        <!-- Add button -->
                        <label for="media"
                            class="flex shrink-0 justify-center items-center w-16 h-16 border-2 border-dotted border-gray-300 rounded-xl text-gray-400 cursor-pointer hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-600 dark:hover:bg-neutral-700/20">
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
                                class="relative w-16 h-16 rounded-xl overflow-hidden border border-gray-300 dark:border-neutral-700">
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

                <!-- Footer -->
                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="submit" class="ml-auto">Submit</Button>
                    </DialogClose>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
