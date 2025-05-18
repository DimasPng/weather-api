<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const message = ref('')
const isSuccess = ref(false)

const alertClass = computed(() =>
    isSuccess.value ? 'success' : 'error'
)

onMounted(async () => {
    const path = window.location.pathname
    const match = path.match(/^\/confirm\/([\w-]+)$/)
    const token = match ? match[1] : null

    if (!token) {
        message.value = 'Invalid URL: token not found.'
        isSuccess.value = false
        return
    }

    try {
        const response = await axios.get(`/api/confirm/${token}`)
        message.value = response.data.message
        isSuccess.value = true
    } catch (error) {
        message.value = error.response?.data?.message || 'An unknown error occurred.'
        isSuccess.value = false
    }
})
</script>

<template>
    <div class="center-container">
        <div v-if="message" :class="['alert-box', alertClass]">
            {{ message }}
        </div>
    </div>
</template>

<style scoped>
.center-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f4f6f8;
}

.alert-box {
    padding: 20px 30px;
    border-radius: 8px;
    font-size: 18px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.success {
    background-color: #e6f4ea;
    color: #207243;
    border: 1px solid #c9e3d2;
}

.error {
    background-color: #fdecea;
    color: #9f3a38;
    border: 1px solid #f5c6cb;
}
</style>
