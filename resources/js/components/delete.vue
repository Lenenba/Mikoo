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
import { Trash2 } from 'lucide-vue-next';

const Props = defineProps({
    availability: {
        type: Object,
        required: true,
    },
});
const form = useForm({});

const deleteAvailability = (availability: any) => {
    form.delete(route('availabilities.destroy', availability), {
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
                <Button variant="destructive" size="icon" class="w-8 h-8">
                    <Trash2 class="h-4 w-4" />
                </Button>
            </DialogTrigger>
            <DialogContent>
                <form class="space-y-6" @submit="deleteAvailability(availability)">
                    <DialogHeader class="space-y-3">
                        <DialogTitle>Are you sure you want to delete your availability?</DialogTitle>
                        <DialogDescription>
                            Once your account is availability, all of its resources and data will also be
                            permanently deleted.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter class="gap-2">
                        <DialogClose as-child>
                            <Button variant="secondary" @click="closeModal"> Cancel </Button>
                        </DialogClose>

                        <Button variant="destructive" :disabled="form.processing">
                            <button type="submit">Delete availability</button>
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
