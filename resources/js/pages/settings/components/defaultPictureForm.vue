<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Star } from 'lucide-vue-next';

const props = defineProps({
    photoId: {
        type: Number,
        required: true,
    },
});
const form = useForm({});

const setDefaultPicture = () => {
    form.put(route('photos.setDefault', props.photoId), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-6">
        <Dialog>
            <DialogTrigger as-child>
                <Button>
                    <star class="h-4 w-4" />
                </Button>
            </DialogTrigger>
            <DialogContent>
                <form class="space-y-6" @submit.prevent="setDefaultPicture">
                    <DialogHeader class="space-y-3">
                        <DialogTitle>Are you sure you want to set it as default picture</DialogTitle>
                    </DialogHeader>
                    <DialogFooter class="gap-2">
                        <DialogClose as-child>
                            <Button variant="secondary" @click="closeModal"> Cancel </Button>
                        </DialogClose>

                        <Button variant="destructive" :disabled="form.processing">
                            <button type="submit">Set as default</button>
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
