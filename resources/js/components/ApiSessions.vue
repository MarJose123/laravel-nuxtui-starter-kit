<script setup lang="ts">
import { ApiSession } from '@/types/apiSession'
import { useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    apiSessions: ApiSession[]
}>()

const form = useForm<{ password: string; api: boolean; token: number[] | number | undefined }>({
    password: '',
    api: true,
    token: [],
})
const showSessionsRevokeModal = ref(false)
const sessionTokens = ref<number>()
const revokeType = ref<'all' | 'individual'>('all')

const modalConfig = computed({
    get: () => {
        if (revokeType.value === 'all') {
            return {
                title: 'Revoke All API Sessions',
                description:
                    'Please enter your password to confirm you would like to log out of your other API sessions across all of your devices.',
                footer: {
                    label: {
                        cancel: 'Cancel',
                        submit: 'Revoke All',
                    },
                    cta: {
                        submit: () => onSubmitRevokeAllApiSessions(),
                    },
                },
            }
        }
        return {
            title: 'Revoke API Sessions',
            description: 'Please enter your password to confirm you would like to revoke this API sessions.',
            footer: {
                label: {
                    cancel: 'Cancel',
                    submit: 'Revoke',
                },
                cta: {
                    submit: () => onSubmitRevokeApiSessions(),
                },
            },
        }
    },
    set: (value: number) => {
        sessionTokens.value = value
    },
})

const onSubmitRevokeApiSessions = () => {
    form.token = sessionTokens.value as number

    form.delete(route('settings.sessions.destroy'), {
        onSuccess: () => {
            showSessionsRevokeModal.value = false
            form.resetAndClearErrors()
        },
        onError: () => form.reset(),
    })
}

const onSubmitRevokeAllApiSessions = () => {
    form.token = []
    props.apiSessions.forEach((session: ApiSession) => {
        ;(form.token as number[]).push(session.id as number)
    })

    form.delete(route('settings.sessions.destroy'), {
        onSuccess: () => {
            showSessionsRevokeModal.value = false
            form.resetAndClearErrors()
        },
        onError: () => form.reset(),
    })
}
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
        <UCard v-if="apiSessions.length > 0" variant="subtle" class="flex w-6/12 flex-col">
            <template #header>
                <div class="flex w-full flex-row">
                    <UModal v-model:open="showSessionsRevokeModal" :ui="{ footer: 'justify-end' }">
                        <UButton
                            class="ml-auto flex"
                            color="error"
                            size="sm"
                            variant="subtle"
                            :label="modalConfig.footer.label.submit"
                            @click.prevent="revokeType = 'all'"
                        />

                        <template #title>{{ modalConfig.title }}</template>
                        <template #description>{{ modalConfig.description }}</template>
                        <template #body>
                            <UForm @submit.prevent="() => modalConfig.footer.cta.submit()">
                                <USimplePasswordInput
                                    name="password"
                                    label="Password"
                                    v-model="form.password"
                                    :error="form.errors.password"
                                    placeholder="Password"
                                    required
                                    autofocus
                                />
                            </UForm>
                        </template>
                        <template #footer="{ close }">
                            <UButton
                                :label="modalConfig.footer.label.cancel"
                                color="neutral"
                                variant="outline"
                                @click="close"
                            />
                            <UButton
                                :label="modalConfig.footer.label.submit"
                                color="error"
                                variant="solid"
                                @click.prevent="() => modalConfig.footer.cta.submit()"
                            />
                        </template>
                    </UModal>
                </div>
            </template>

            <div v-if="apiSessions?.length > 0" class="space-y-6">
                <div v-for="(session, i) in apiSessions" :key="i" class="flex w-full flex-row items-center">
                    <div class="flex items-center">
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
                    <div class="ml-auto flex items-center">
                        <UButton
                            label="Revoke"
                            color="error"
                            variant="subtle"
                            size="sm"
                            @click="
                                () => {
                                    sessionTokens = session.id
                                    revokeType = 'individual'
                                    showSessionsRevokeModal = true
                                }
                            "
                        />
                    </div>
                </div>
            </div>
        </UCard>

        <UCard v-else variant="subtle" class="flex w-6/12 flex-col">
            <div class="flex flex-col items-center justify-center gap-2">
                <UIcon name="i-heroicons-signal" class="size-8 text-gray-500" />
                <span class="text-sm text-pretty text-muted">No API session</span>
            </div>
        </UCard>
        <!--  END:  Form Section       -->
    </div>
</template>

<style scoped></style>
