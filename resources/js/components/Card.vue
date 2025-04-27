<script setup lang='ts'>
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
</script>

<template>
    <Card class="w-full">
        <CardHeader>
            <CardTitle>
                <slot name="title" />
            </CardTitle>
            <CardDescription>
                <slot name="Description" />
            </CardDescription>
        </CardHeader>
        <CardContent>
            <slot />
        </CardContent>
        <CardFooter class="flex justify-between px-6">
            <slot name="footer" />
        </CardFooter>
    </Card>
</template>
