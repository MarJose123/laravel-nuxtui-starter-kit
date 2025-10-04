<script setup lang="ts">
import { ApiSession } from '@/types/apiSession'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps<{
    apiSessions: ApiSession[]
}>()

const form = useForm({
    password: '',
})
const showSessionsRevokeModal = ref(false)
</script>

<template>
    <div class="flex w-full flex-col space-y-6 pt-8">
        <!--  START:  Header Section        -->
        <HeadingSmall
            title="API sessions"
            description="This is a list of devices that have logged into your account via the API. Revoke any session that you do not recognize to sign out of all your devices."
        />
        <span class="flex w-7/12 border-b border-b-slate-200" />
        <!--   END: Header Section        -->

        <!--  START:  Form Section       -->
        <UCard as="div" variant="subtle" class="flex w-6/12 flex-col">
            <template #header>
                <div class="flex w-full flex-row">
                    <UModal v-model:open="showSessionsRevokeModal" :ui="{ footer: 'justify-end' }">
                        <UButton class="ml-auto flex" color="error" size="sm" variant="subtle" label="Revoke All" />

                        <template #title>Log Out Other Browser Sessions</template>
                        <template #description>
                            Please enter your password to confirm you would like to log out of your other browser
                            sessions across all of your devices.
                        </template>
                        <template #body>
                            <USimplePasswordInput
                                name="password"
                                label="Password"
                                v-model="form.password"
                                :error="form.errors.password"
                                placeholder="Password"
                                required
                                autofocus
                            />
                        </template>
                        <template #footer="{ close }">
                            <UButton label="Cancel" color="neutral" variant="outline" @click="close" />
                            <UButton
                                label="Revoke All"
                                color="error"
                                variant="solid"
                                @click.prevent="onSubmitRevokeWebSessions"
                            />
                        </template>
                    </UModal>
                </div>
            </template>

            <div v-if="apiSessions?.length > 0" class="space-y-6">
                <div v-for="(session, i) in apiSessions" :key="i" class="flex items-center">
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
                                <div class="flex flex-row gap-1">
                                    <div>
                                        <UIcon
                                            :name="`i-circle-flags:${session.agent.country_code ? session.agent.country_code.toLowerCase() : 'xx'}`"
                                        />
                                    </div>
                                    <div>
                                        {{ session.agent.country ?? 'Unknown' }} -
                                        {{ session.agent.city ?? 'Unknown' }}
                                    </div>
                                </div>
                                <div>Device: {{ session.name }}</div>
                                <div>IP: {{ session.ip_address }}</div>
                                <div>ISP: {{ session.agent.isp ?? 'N/A' }}</div>
                                <div v-if="!session.is_current_device">Last used: {{ session.last_used }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </UCard>
        <!--  END:  Form Section       -->
    </div>
</template>

<style scoped></style>
