<script setup lang="ts">
import { Head, usePage, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import SettingsLayout from '@/layouts/settings/Layout.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import InputError from '@/components/InputError.vue'
import { type SharedData, type Profile } from '@/types'
import { LoaderCircle, SaveIcon } from 'lucide-vue-next'
const page = usePage<SharedData>()
const profile = computed(() => page.props.profile as Profile)

const breadcrumbs = [
    { title: 'Profile Details', href: '/settings/profile/details' },
]

const form = useForm({
    first_name: profile.value.first_name ?? '',
    last_name: profile.value.last_name ?? '',
    phone: profile.value.phone ?? '',
    address: profile.value.address ?? '',
    birthdate: profile.value.birthdate ?? '',
    bio: profile.value.bio ?? '',
    experience: profile.value.experience ?? '',
})

function submit() {
    form.post(route('babysitter.profile.details.update'), { preserveScroll: true })
}
</script>

<template>

    <Head title="Profile Details Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <SettingsLayout>
            <div class="max-w-3xl mx-auto p-6 ">
                <HeadingSmall title="Profile Information" description="Update your personal details" />
                <Form @submit.prevent="submit" class="mt-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <Label for="first_name">First Name</Label>
                            <Input id="first_name" v-model="form.first_name" class="mt-1 w-full" />
                            <InputError :message="form.errors.first_name" class="mt-1" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <Label for="last_name">Last Name</Label>
                            <Input id="last_name" v-model="form.last_name" class="mt-1 w-full" />
                            <InputError :message="form.errors.last_name" class="mt-1" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <Label for="phone">Phone</Label>
                            <Input id="phone" v-model="form.phone" class="mt-1 w-full" />
                            <InputError :message="form.errors.phone" class="mt-1" />
                        </div>

                        <!-- Birthdate -->
                        <div>
                            <Label for="birthdate">Birthdate</Label>
                            <Input id="birthdate" type="date" v-model="form.birthdate" class="mt-1 w-full" />
                            <InputError :message="form.errors.birthdate" class="mt-1" />
                        </div>

                        <!-- Address full width -->
                        <div class="sm:col-span-2">
                            <Label for="address">Address</Label>
                            <Input id="address" v-model="form.address" class="mt-1 w-full" />
                            <InputError :message="form.errors.address" class="mt-1" />
                        </div>

                        <!-- experience full width -->
                        <div class="sm:col-span-2">
                            <Label for="experience">experience</Label>
                            <Textarea id="experience" rows="3" v-model="form.experience" class="mt-1 w-full" />
                            <InputError :message="form.errors.experience" class="mt-1" />
                        </div>

                        <!-- Bio full width -->
                        <div class="sm:col-span-2">
                            <Label for="bio">Bio</Label>
                            <Textarea id="bio" rows="4" v-model="form.bio" class="mt-1 w-full" />
                            <InputError :message="form.errors.bio" class="mt-1" />
                        </div>
                        <!-- Action buttons -->
                    </div>
                    <div class="mt-8 flex flex-col">
                        <Button type="submit" class="w-full my-2" :tabindex="4" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <SaveIcon v-else /> Update profile details
                        </Button>
                        <Button type="button" variant="outline" class="w-full" @click="form.reset()">Reset</Button>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
