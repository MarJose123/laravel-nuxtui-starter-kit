<script setup lang="ts">
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth'
import { router } from '@inertiajs/vue3'
import { useClipboard } from '@vueuse/core'
import { computed, ref, watch } from 'vue'

interface Props {
    open: boolean
    requiresConfirmation: boolean
    twoFactorEnabled: boolean
}

const emit = defineEmits<{ 'update:open': [boolean] }>()
const props = defineProps<Props>()
const isOpen = ref(props.open)

const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } = useTwoFactorAuth()
const { copy, copied, isSupported } = useClipboard() // support https only.

const showVerificationStep = ref(false)
const code = ref<number[]>([])
const codeValue = computed<string>(() => code.value.join(''))
const codeError = ref<string | undefined>(undefined)
const codeProcessing = ref(false)

const modalConfig = computed<{
    title: string
    description: string
    buttonText: string
}>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: 'Two-Factor Authentication Enabled',
            description:
                'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
            buttonText: 'Close',
        }
    }

    if (showVerificationStep.value) {
        return {
            title: 'Verify Authentication Code',
            description: 'Enter the 6-digit code from your authenticator app',
            buttonText: 'Continue',
        }
    }

    return {
        title: 'Enable Two-Factor Authentication',
        description:
            'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
        buttonText: 'Continue',
    }
})

const handleModalNextStep = () => {
    if (props.requiresConfirmation) {
        showVerificationStep.value = true

        return
    }

    clearSetupData()
}

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData()
    }

    showVerificationStep.value = false
    code.value = []
}

const handleVerifyAuthenticationCode = () => {
    codeError.value = undefined
    try {
        codeProcessing.value = true
        router.post(
            route('two-factor.confirm'),
            {
                code: codeValue.value,
            },
            {
                onSuccess: () => {
                    code.value = []
                    isOpen.value = false
                },
                onError: (error: { confirmTwoFactorAuthentication?: { code: string } | undefined }) => {
                    code.value = []
                    codeError.value = error?.confirmTwoFactorAuthentication?.code ?? ''
                },
            },
        )
    } catch {
        console.error('Something went wrong while confirming the code.')
    } finally {
        codeProcessing.value = false
    }
}

// Watch for changes to the prop
watch(
    () => props.open,
    async (newValue) => {
        isOpen.value = newValue
        if (!newValue) {
            resetModalState()
            return
        }
        if (!qrCodeSvg.value) {
            await fetchSetupData()
        }
    },
)

// Watch for changes to the local state and emit update
watch(isOpen, (newValue) => {
    emit('update:open', newValue)
})
</script>

<template>
    <UModal v-model:open="isOpen" :title="modalConfig.title" :description="modalConfig.description">
        <template #body>
            <div class="relative flex w-auto flex-col items-center justify-center space-y-5">
                <div v-if="!showVerificationStep">
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
                    <div v-else>
                        <div class="relative mx-auto flex max-w-md items-center overflow-hidden">
                            <div
                                class="border-border relative mx-auto aspect-square w-64 overflow-hidden rounded-lg border"
                            >
                                <div
                                    v-if="!qrCodeSvg"
                                    class="bg-background absolute inset-0 z-10 flex aspect-square h-auto w-full animate-pulse items-center justify-center"
                                >
                                    <UIcon name="i-lucide-loader" class="size-6 animate-spin" />
                                </div>
                                <div v-else class="relative z-10 overflow-hidden border p-5">
                                    <div
                                        v-html="qrCodeSvg"
                                        class="flex aspect-square size-full items-center justify-center"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex w-full items-center justify-center space-x-5">
                            <UButton @click="handleModalNextStep" :label="modalConfig.buttonText" />
                        </div>

                        <div class="relative flex w-full items-center justify-center">
                            <USeparator label="or, enter the code manually" />
                        </div>

                        <div class="flex w-full items-center justify-center space-x-2">
                            <div
                                v-if="!manualSetupKey"
                                class="flex h-full w-full items-center justify-center bg-muted p-3"
                            >
                                <UIcon name="i-lucide-loader" class="size-4 animate-spin" />
                            </div>
                            <div v-else>
                                <UInput
                                    v-model="manualSetupKey"
                                    :ui="{ trailing: isSupported ? 'pr-0.5' : '' }"
                                    readonly
                                >
                                    <template v-if="manualSetupKey?.length && isSupported" #trailing>
                                        <UTooltip text="Copy to clipboard" :content="{ side: 'right' }">
                                            <UButton
                                                :color="copied ? 'success' : 'neutral'"
                                                variant="link"
                                                size="sm"
                                                :icon="copied ? 'i-lucide-copy-check' : 'i-lucide-copy'"
                                                aria-label="Copy to clipboard"
                                                @click="copy(manualSetupKey)"
                                            />
                                        </UTooltip>
                                    </template>
                                </UInput>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="w-full">
                    <UForm @submit.prevent="handleVerifyAuthenticationCode">
                        <UFormField class="flex justify-center" :error="codeError" required>
                            <UPinInput
                                class="flex justify-center"
                                v-model="code"
                                otp
                                :autofocus="true"
                                :highlight="false"
                                type="number"
                                placeholder="○"
                                :length="6"
                            />
                        </UFormField>

                        <div class="flex w-full items-center justify-center space-x-5 pt-4">
                            <UButton
                                variant="subtle"
                                color="neutral"
                                @click="
                                    () => {
                                        code = []
                                        showVerificationStep = false
                                    }
                                "
                                :disabled="codeProcessing"
                                label="Back"
                            >
                                <template #leading>
                                    <UIcon name="i-lucide-arrow-left" class="size-4" />
                                </template>
                            </UButton>

                            <UButton
                                type="submit"
                                variant="solid"
                                :disabled="codeProcessing || codeValue?.length < 6"
                                :loading="codeProcessing"
                                label="Confirm"
                            >
                            </UButton>
                        </div>
                    </UForm>
                </div>
            </div>
        </template>
    </UModal>
</template>

<style scoped></style>
