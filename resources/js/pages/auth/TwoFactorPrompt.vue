<script setup lang="ts">
import Logo from '@/components/Logo.vue'
import { useAppearance } from '@/composables/useAppearance'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface AuthConfigContent {
    title: string
    description: string
    toggleText: string
}

const { updateAppearance } = useAppearance()
const appConfig = useAppConfig()

const authConfigContent = computed<AuthConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Recovery Code',
            description: 'Please confirm access to your account by entering one of your emergency recovery codes.',
            toggleText: 'login using an authentication code',
        }
    }

    return {
        title: 'Authentication Code',
        description: 'Enter the authentication code provided by your authenticator application.',
        toggleText: 'login using a recovery code',
    }
})

const showRecoveryInput = ref<boolean>(false)

const toggleRecoveryMode = () => {
    showRecoveryInput.value = !showRecoveryInput.value
    codeError.value = undefined
    recoveryCodeError.value = undefined
    code.value = []
    recoveryCode.value = ''
}

const code = ref<number[]>([])
const codeValue = computed<string>(() => code.value.join(''))
const codeError = ref<string | undefined>(undefined)
const recoveryCode = ref<string | undefined>(undefined)
const recoveryCodeError = ref<string | undefined>(undefined)

const twoFactorLoginProcessing = ref<boolean>(false)

const twoFactorLogin = (usingRecoveryCode: boolean = false) => {
    twoFactorLoginProcessing.value = true
    codeError.value = undefined
    recoveryCodeError.value = undefined

    try {
        router.post(
            route('two-factor.login.store'),
            {
                ...(usingRecoveryCode ? { recovery_code: recoveryCode.value } : { code: codeValue.value }),
            },
            {
                onSuccess: (response) => {
                    updateAppearance(response.props.theme.mode)
                    // update the app ui color based on the user preference
                    appConfig.ui.colors.primary = response.props.theme.primary ?? 'green'
                    appConfig.ui.colors.neutral = response.props.theme.neutral ?? 'slate'
                },
                onError: (err) =>
                    usingRecoveryCode ? (recoveryCodeError.value = err.recovery_code) : (codeError.value = err.code),
            },
        )
    } finally {
        twoFactorLoginProcessing.value = false
    }
}
</script>

<template>
    <UApp>
        <UMain>
            <UContainer>
                <div class="flex flex-col items-center justify-center gap-4 p-4 pt-2 md:pt-16">
                    <div class="flex">
                        <Head title="Two-Factor Authentication" />

                        <!-- START: Register Form Root Layout -->
                        <div class="w-full max-w-sm space-y-6">
                            <!--  START:  Header Section        -->
                            <div class="flex flex-col text-center">
                                <div class="mb-2">
                                    <Logo class="inline-block size-8 shrink-0" />
                                </div>

                                <div class="text-xl font-semibold text-pretty text-highlighted">
                                    {{ authConfigContent.title }}
                                </div>
                                <div class="mt-1 text-base text-pretty text-muted">
                                    {{ authConfigContent.description }}
                                </div>
                            </div>
                            <!--   END: Header Section        -->

                            <!--  START:  Form Section       -->
                            <div class="w-full">
                                <UForm
                                    v-if="showRecoveryInput"
                                    id="two-factor-recovery-code"
                                    @submit.prevent="twoFactorLogin(true)"
                                    class="flex flex-col gap-3"
                                >
                                    <div class="flex w-full flex-col items-center justify-center">
                                        <UInput
                                            name="recovery_code"
                                            v-model="recoveryCode"
                                            placeholder="Enter recovery code"
                                            :autofocus="showRecoveryInput"
                                            class="flex"
                                            :highlight="recoveryCodeError !== undefined"
                                            :color="recoveryCodeError !== undefined ? 'error' : 'neutral'"
                                            required
                                        />
                                        <span v-if="recoveryCodeError" class="mt-2 text-sm text-error">
                                            {{ recoveryCodeError }}
                                        </span>
                                    </div>

                                    <UButton type="submit" block :disabled="twoFactorLoginProcessing">Continue</UButton>
                                    <div class="text-muted-foreground text-center text-sm">
                                        <span>or you can </span>
                                        <ULink
                                            as="button"
                                            @click.prevent="toggleRecoveryMode"
                                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                        >
                                            {{ authConfigContent.toggleText }} </ULink
                                        >.
                                    </div>
                                </UForm>

                                <UForm
                                    v-else
                                    id="two-facto-authentication-code"
                                    @submit.prevent="twoFactorLogin(false)"
                                    class="flex flex-col gap-3"
                                >
                                    <UFormField class="flex justify-center" :error="codeError" required>
                                        <UPinInput
                                            name="code"
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
                                    <UButton type="submit" block :disabled="twoFactorLoginProcessing">Continue</UButton>
                                    <div class="text-muted-foreground text-center text-sm">
                                        <span>or you can </span>
                                        <ULink
                                            as="button"
                                            @click.prevent="toggleRecoveryMode"
                                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                        >
                                            {{ authConfigContent.toggleText }} </ULink
                                        >.
                                    </div>
                                </UForm>
                            </div>
                            <!--  START:  Form Section       -->
                        </div>
                        <!-- END: Register Form Root Layout -->
                    </div>
                </div>
            </UContainer>
        </UMain>
    </UApp>
</template>

<style scoped></style>
