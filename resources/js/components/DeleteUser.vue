<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const show = ref(false)

const form = useForm({
    current_password: '',
})

const onSubmit = () => {
    form.delete(route('settings.account.destroy'), {
        onSuccess: () => form.reset('current_password'),
    })
}
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall title="Delete account" description="Delete your account and all of its resources" />
        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">Warning</p>
                <p class="text-sm">Please proceed with caution, this cannot be undone.</p>
            </div>
            <UModal :close="false" :ui="{ footer: 'justify-end' }" @update:open="() => form.resetAndClearErrors()">
                <UButton color="error" variant="solid" data-test="delete-account">Delete account</UButton>

                <template #title> Are you sure you want to delete your account? </template>

                <template #description>
                    Once your account is deleted, all of its resources and data will also be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </template>

                <template #body>
                    <UForm class="w-full space-y-6" @submit.prevent="onSubmit">
                        <UFormField
                            label="Confirm Password"
                            name="password"
                            :error="form.errors.current_password"
                            required
                        >
                            <UInput
                                v-model="form.current_password"
                                placeholder="Confirm your password"
                                :type="show ? 'text' : 'password'"
                                :ui="{ trailing: 'pe-1' }"
                                class="w-full"
                            >
                                <template #trailing>
                                    <UButton
                                        color="neutral"
                                        variant="link"
                                        size="sm"
                                        :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                                        :aria-label="show ? 'Hide password' : 'Show password'"
                                        :aria-pressed="show"
                                        aria-controls="password"
                                        @click="show = !show"
                                    />
                                </template>
                            </UInput>
                        </UFormField>
                    </UForm>
                </template>

                <template #footer="{ close }">
                    <UButton label="Cancel" color="neutral" variant="outline" @click="close" />
                    <UButton
                        label="Delete account"
                        color="error"
                        variant="solid"
                        @click.prevent="onSubmit"
                        data-test="confirm-delete-user-button"
                    />
                </template>
            </UModal>
        </div>
    </div>
</template>

<style scoped></style>
