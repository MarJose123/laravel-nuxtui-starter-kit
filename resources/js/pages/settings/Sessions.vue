<script setup lang="ts">
import Layout from '@/layouts/default.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Account',
        to: route('settings.account.edit', {}, false),
        target: '_self',
    },
])

interface WebSession {
    agent: {
        browser: string | null
        platform: string | null
        is_desktop: boolean
    }
    ip_address: string
    is_current_device: boolean
    last_active: string
    id: number
    user_id: number
}

defineProps<{
    sessions: WebSession[]
}>()
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Sessions" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall
                title="Web sessions"
                description="This is a list of devices that have logged into your account. Revoke any sessions that you do not recognize."
            />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <!--  START:  Main content Section       -->
            <UCard as="div" variant="subtle" class="flex w-6/12 flex-col">
                <template #header>
                    <div class="flex w-full flex-row">
                        <UButton class="ml-auto flex" color="error" size="sm" variant="subtle" label="Revoke All" />
                    </div>
                </template>

                <div v-if="sessions?.length > 0" class="space-y-6">
                    <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
                        <div>
                            <UTooltip :disabled="!session.is_current_device" text="Current device">
                                <UChip color="success" :show="session.is_current_device" inset>
                                    <UIcon
                                        v-if="session.agent.is_desktop"
                                        name="i-heroicons-computer-desktop"
                                        class="size-8 text-gray-500"
                                    />
                                    <UIcon v-else name="i-heroicons-device-phone-mobile" class="size-8 text-gray-500" />
                                </UChip>
                            </UTooltip>
                        </div>

                        <div class="ms-3">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ session.agent.platform ? session.agent.platform : 'Unknown' }} -
                                {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                            </div>

                            <div>
                                <div class="flex-col text-xs text-gray-500">
                                    <div>🌍 Unknown</div>
                                    <div>IP: {{ session.ip_address }}</div>
                                    <div v-if="!session.is_current_device">Last active: {{ session.last_active }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </UCard>
            <!--  END:  Main content Section       -->
        </div>

        <div class="flex w-full flex-col space-y-6 pt-8">
            <!--  START:  Header Section        -->
            <HeadingSmall
                title="API sessions"
                description="This is a list of devices that have logged into your account via the API. Revoke any session that you do not recognize to sign out of all your devices."
            />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->
        </div>
    </AppLayout>
</template>

<style scoped></style>
