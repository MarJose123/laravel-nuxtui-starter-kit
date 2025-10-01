<script setup lang="ts">
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth'
import { router } from '@inertiajs/vue3'
import { nextTick, onMounted, ref } from 'vue'

const { recoveryCodesList, fetchRecoveryCodes, errors } = useTwoFactorAuth()
const isRecoveryCodesVisible = ref<boolean>(false)
const recoveryCodeSectionRef = ref<HTMLDivElement | null>(null)

const toggleRecoveryCodesVisibility = async () => {
    if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes()
    }

    isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value

    if (isRecoveryCodesVisible.value) {
        await nextTick()
        recoveryCodeSectionRef.value?.scrollIntoView({ behavior: 'smooth' })
    }
}

const regenerateRecoveryCode = () => {
    router.post(
        route('two-factor.regenerate-recovery-codes'),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                fetchRecoveryCodes()
            },
        },
    )
}

onMounted(async () => {
    if (!recoveryCodesList.value.length) {
        await fetchRecoveryCodes()
    }
})
</script>

<template>
    <UCard class="w-6/12" variant="subtle">
        <template #header>
            <div class="flex flex-row items-center gap-3">
                <UIcon name="i-lucide-lock-keyhole" class="size-6" />
                <div class="flex flex-col">
                    <div class="text-xl font-semibold text-pretty text-highlighted">2FA Recovery Codes</div>
                    <div class="flex gap-1 text-sm text-pretty text-muted">
                        Recovery codes let you regain access if you lose your 2FA device. Store them in a secure
                        password manager.
                    </div>
                </div>
            </div>
        </template>

        <div class="flex flex-col gap-3 select-none sm:flex-row sm:items-center sm:justify-between">
            <UButton @click="toggleRecoveryCodesVisibility" class="w-fit">
                <template #leading>
                    <UIcon :name="isRecoveryCodesVisible ? 'i-lucide-eye-off' : 'i-lucide-eye'" class="size-4" />
                </template>
                <template #default> {{ isRecoveryCodesVisible ? 'Hide' : 'View' }} Recovery Codes </template>
            </UButton>

            <UForm v-if="isRecoveryCodesVisible && recoveryCodesList.length" @submit="regenerateRecoveryCode">
                <UButton type="submit">
                    <template #leading>
                        <UIcon name="i-lucide-refresh-cw" class="size-4" />
                    </template>
                    <template #default> Regenerate Codes </template>
                </UButton>
            </UForm>
        </div>
        <div
            :class="[
                'relative overflow-hidden transition-all duration-300',
                isRecoveryCodesVisible ? 'h-auto opacity-100' : 'h-0 opacity-0',
            ]"
        >
            <div v-if="errors?.length" class="mt-6">
                <UAlert
                    v-if="errors?.length"
                    color="error"
                    variant="subtle"
                    description="You can change the primary color in your app config."
                >
                    <template #description>
                        <ul class="list-inside list-disc text-sm">
                            <li v-for="(error, index) in Array.from(new Set(errors))" :key="index">
                                {{ error }}
                            </li>
                        </ul>
                    </template>
                </UAlert>
            </div>
            <div v-else class="mt-3 space-y-3">
                <div ref="recoveryCodeSectionRef" class="grid gap-1 rounded-lg bg-muted p-4 font-mono text-sm">
                    <div v-if="!recoveryCodesList.length" class="space-y-2">
                        <div v-for="n in 8" :key="n" class="bg-muted-foreground/20 h-4 animate-pulse rounded"></div>
                    </div>
                    <div v-else v-for="(code, index) in recoveryCodesList" :key="index">
                        {{ code }}
                    </div>
                </div>
                <p class="text-muted-foreground text-xs select-none">
                    Each recovery code can be used once to access your account and will be removed after use. If you
                    need more, click
                    <span class="font-bold">Regenerate Codes</span> above.
                </p>
            </div>
        </div>
    </UCard>
</template>

<style scoped></style>
