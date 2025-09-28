<script setup lang="ts">
import AppLayout from '@/components/AppLayout.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import Layout from '@/layouts/default.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { BasicColorSchema } from '@vueuse/core'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Appearance',
        to: route('settings.appearance.edit'),
        target: '_self',
    },
])

const modes: {
    id: BasicColorSchema
    label: string
    icon: string
}[] = [
    { id: 'light', label: 'light', icon: 'i-lucide-sun' },
    { id: 'dark', label: 'dark', icon: 'i-lucide-moon' },
    { id: 'auto', label: 'system', icon: 'i-mynaui-desktop' },
]
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Appearance settings" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <div class="w-5/12 space-y-6">
                <UFormField label="Theme">
                    <div class="flex flex-row gap-2">
                        <UButton
                            v-for="_mode in modes"
                            :key="_mode.label"
                            :icon="_mode.icon"
                            variant="outline"
                            color="neutral"
                            :label="_mode.label"
                        />
                    </div>
                </UFormField>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
