<template>
    <div>
        <SubscriptionForm v-if="!tokenFromUrl" />
        <ConfirmSubscription v-else-if="isConfirm" />
        <UnsubscribeComponent v-else-if="isUnsubscribe" />
    </div>
</template>

<script setup>
import ConfirmSubscription from './ConfirmSubscription.vue'
import SubscriptionForm from './SubscriptionForm.vue'
import UnsubscribeComponent from './UnsubscribeComponent.vue'
import { computed } from 'vue'

const path = window.location.pathname

const isConfirm = computed(() => /^\/confirm\/[\w-]+$/.test(path))
const isUnsubscribe = computed(() => /^\/unsubscribe\/[\w-]+$/.test(path))

const tokenFromUrl = computed(() =>
    isConfirm.value || isUnsubscribe.value
)
</script>
