<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
    label?: string
    error?: string
    modelValue: string
    placeholder?: string
    name?: string
    autofocus?: boolean | undefined
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const showPassword = ref(false)
</script>

<template>
    <UFormField :label="label ?? 'Password'" :name="name?.toLowerCase() ?? 'password'" :error="error" required>
        <UInput
            :model-value="modelValue"
            @input="emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder ?? 'Input your password'"
            :type="showPassword ? 'text' : 'password'"
            :autofocus="autofocus"
            :ui="{ trailing: 'pe-1' }"
            class="w-full"
        >
            <template #trailing>
                <UButton
                    color="neutral"
                    variant="link"
                    size="sm"
                    :icon="showPassword ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    :aria-pressed="showPassword"
                    aria-controls="password"
                    @click="showPassword = !showPassword"
                />
            </template>
        </UInput>
    </UFormField>
</template>

<style scoped></style>
