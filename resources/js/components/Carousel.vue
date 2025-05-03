<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import SetDefaultPictureForm from '../pages/settings/components/defaultPictureForm.vue'
import DelectPictureForm from '../pages/settings/components/deletePictureForm.vue'
import { Button } from '@/components/ui/button';
import { usePhotoUrl } from '@/composables/usePhotoUrl';
const { getPhotoUrl } = usePhotoUrl();
const props = defineProps({
    photos: {
        type: Array,
        required: true,
    },
})


</script>
<template>
    <Carousel orientation="horizontal" class="relative w-full max-w-xl" :opts="{
        align: 'start',
    }">
        <CarouselContent class="m-2">
            <CarouselItem v-for="photo in photos" :key="photo.id" class="p-1 md:basis-1/2">
                <div class="p-1">
                    <Card>
                        <CardContent class="flex flex-col items-center justify-center p-6">
                            <img :src="getPhotoUrl(photo.url)" alt="Photo"
                                class="object-cover w-full h-48 rounded-lg mb-2" />

                        </CardContent>
                        <CardFooter class="flex px-6 pb-6">
                            <div class="flex flex-row justify-between w-full" v-if="!photo.is_profile_picture">
                                <SetDefaultPictureForm :photoId="photo.id" />
                                <DelectPictureForm :photoId="photo.id" />
                            </div>
                            <Button v-else variant="outline" class="w-full">
                                Profile picture
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </CarouselItem>
        </CarouselContent>
        <CarouselPrevious />
        <CarouselNext />
    </Carousel>
</template>
