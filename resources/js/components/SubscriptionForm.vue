<script setup>
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
    email: '',
    city: '',
    frequency: 'daily',
})

const success = ref(false)
const error = ref('')
const loading = ref(false)

const validate = () => {
    return form.value.email.trim() !== '' &&
        form.value.city.trim() !== '' &&
        form.value.frequency.trim() !== ''
}

const submit = async () => {
    error.value = ''
    success.value = false

    if (!validate()) {
        error.value = 'Please fill in all required fields.'
        return
    }

    loading.value = true

    try {
        await axios.post('/api/subscribe', form.value)
        success.value = true
        form.value = { email: '', city: '', frequency: 'daily' }
    } catch (e) {
        error.value = e.response?.data?.message || 'Something went wrong. Please try again.'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <VApp>
        <VMain class="bg-grey-lighten-4">
            <VContainer class="d-flex justify-center align-center fill-height">
                <VCard class="pa-6" max-width="500">
                    <VCardTitle class="text-h5 text-center">
                        Weather Forecast Subscription
                    </VCardTitle>

                    <VCardText>
                        <VForm @submit.prevent="submit">
                            <VTextField
                                v-model="form.email"
                                label="Email"
                                variant="outlined"
                                type="email"
                                required
                            />
                            <VTextField
                                v-model="form.city"
                                label="City"
                                variant="outlined"
                                required
                            />
                            <VSelect
                                v-model="form.frequency"
                                label="Frequency"
                                variant="outlined"
                                :items="['hourly', 'daily']"
                                required
                            />
                            <VBtn
                                :loading="loading"
                                append-icon="mdi-check-circle-outline"
                                type="submit"
                                color="primary"
                                size="large"
                                block
                                class="pa-7"
                            >
                                Subscribe
                            </VBtn>
                        </VForm>

                        <VAlert
                            v-if="success"
                            type="success"
                            class="mt-4 text-subtitle-1"
                            elevation="2"
                        >
                            Successfully. Please check your email.
                        </VAlert>

                        <VAlert
                            v-if="error"
                            type="error"
                            class="mt-4 text-subtitle-1"
                            elevation="2"
                        >
                            {{ error }}
                        </VAlert>
                    </VCardText>
                </VCard>
            </VContainer>
        </VMain>
    </VApp>
</template>
