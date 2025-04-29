<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import type { BreadcrumbItemType } from '@/types';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Rocket, AlertCircle } from 'lucide-vue-next';
const page = usePage()

const flashSuccess = computed(
    () => page.props.flash.success
)
const flashError = computed(
    () => page.props.flash.error
)
interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="m-4" v-if="flashSuccess">
                <Alert>
                    <Rocket class="h-4 w-4" />
                    <AlertTitle>Success</AlertTitle>
                    <AlertDescription> {{ flashSuccess }}
                    </AlertDescription>
                </Alert>
            </div>
            <div class="m-4" v-if="flashError">
                <Alert variant="destructive">
                    <AlertCircle class="h-4 w-4" />
                    <AlertTitle>Error</AlertTitle>
                    <AlertDescription> {{ flashError }}
                    </AlertDescription>
                </Alert>
            </div>
            <slot />
        </AppContent>
    </AppShell>
</template>
