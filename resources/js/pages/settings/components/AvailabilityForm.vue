<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { type Availibility } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, Link } from '@inertiajs/vue3';
import { LoaderCircle, Ban, Save } from 'lucide-vue-next';
import DatePicker from '@/components/DatePicker.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';

const props = defineProps({
    availability: {
        type: Object as () => Availibility,
        required: true,
    },
});

const form = useForm({
    start_date: props.availability.start_date ?? '',
    end_date: props.availability.end_date ?? '',
    start_time: props.availability.start_time ?? '',
    end_time: props.availability.end_time ?? '',
    note: props.availability.note ?? '',
});

const submit = () => {
    const action = props.availability.end_date !== '' ? 'put' : 'post'
    const url = props.availability.end_date !== ''
        ? route('availabilities.update', props.availability.id)
        : route('availabilities.store')

    // Call Inertia form with DRY options
    form[action](url, {
        onSuccess: () => {
            form.reset()
        },
        onError: (errors) => {
            console.error('Form submission failed:', errors)
        },
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
            <div class="grid md:grid-cols-2 gap-2">
                <div class="grid gap-2">
                    <Label for="start_date">Start date :</Label>
                    <Input type="date" v-model="form.start_date" :tabindex="1" />
                    <InputError :message="form.errors.start_date" />
                </div>
                <div class="grid gap-2">
                    <Label for="end_date">End date :</Label>
                    <Input type="date" v-model="form.end_date" :tabindex="1" />
                    <InputError :message="form.errors.end_date" />
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-2">
                <div class="grid gap-2">
                    <Label for="start_time">Start time :</Label>
                    <Input type="time" v-model="form.start_time" :tabindex="1" />
                    <InputError :message="form.errors.start_time" />
                </div>
                <div class="grid gap-2">
                    <Label for="end_time">End time :</Label>
                    <Input type="time" v-model="form.end_time" :tabindex="1" />
                    <InputError :message="form.errors.end_time" />
                </div>
            </div>
            <div class="grid gap-2">
                <Label for="note">Note :</Label>
                <Textarea v-model="form.note" :placeholder="'Note'" :tabindex="1" />
                <InputError :message="form.errors.note" />
            </div>
            <div class="flex justify-between items-center gap-2">
                <Link href="/availabilities"
                    class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    :tabindex="3">
                <Button variant="destructive">
                    <Ban class="h-4 w-4" />
                    Cancel
                </Button>
                </Link>
                <Button type="submit" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <Save v-if="!form.processing" class="h-4 w-4" />
                    Save your changes
                </Button>
            </div>
        </div>
    </form>
</template>
