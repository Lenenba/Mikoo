<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { type SharedData, type Invoice } from '@/types';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { Button } from '@/components/ui/button';
import { format } from 'date-fns';
import Divider from 'primevue/divider';
import { Stepper, StepList, Step, StepPanels, StepPanel } from '@shadcn/vue-stepper';

// Fetch invoices
const page = usePage<SharedData & { invoices: Invoice[] }>();
const invoices = computed(() => page.props.invoices);

// Step state
const activeStep = ref(1);
const steps = ref([
    { step: 1, title: 'Address' },
    { step: 2, title: 'Payment' },
    { step: 3, title: 'Summary' },
]);

// Selected invoice
const selectedInvoice = ref<Invoice | null>(null);
if (invoices.value.length) selectedInvoice.value = invoices.value[0];
function selectInvoice(inv: Invoice) {
    selectedInvoice.value = inv;
    activeStep.value = 1;
}

// Helpers
function formatDate(dateStr?: string) {
    return dateStr ? format(new Date(dateStr), 'dd MMM yyyy') : 'N/A';
}

const generatePdf = () => {
    if (!selectedInvoice.value) return;
    window.open(route('invoices.pdf', { invoice: selectedInvoice.value.id }));
};
const sendToClient = () => {
    if (!selectedInvoice.value) return;
    window.location.href = route('invoices.send', { invoice: selectedInvoice.value.id });
};
</script>

<template>

    <Head title="Invoices" />
    <AppLayout :breadcrumbs="[{ title: 'Invoices', href: '/invoices' }]">

    </AppLayout>
</template>
