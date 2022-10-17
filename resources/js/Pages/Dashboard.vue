<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/inertia-vue3';
import {ref, onMounted, computed} from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const availableProducts = ref([])
const pendingProducts = ref([])
const notifications = ref([])
const userId = computed(() => {
    return usePage().props.value.auth.user.id
})

function update() {
    axios.get('/api/products')
        .then(({data}) => {
            availableProducts.value = data
        })

    axios.get('/api/products/status/pending')
        .then(({data}) => {
            pendingProducts.value = data
        })

    axios.get(`/api/users/${userId.value}/notifications`)
        .then(({data}) => {
            notifications.value = data
        })
}

function createProduct() {
    axios.post('/api/products',{
            name: `new product`,
            monthly_inventory: Math.round(Math.random() * (10 - 0) + 0)
        }).then(() => update())
}

function approve(productId) {
    axios.post(`/api/product/${productId}/manage/approved`)
        .then(() => update())
}

function reject(productId) {
    axios.post(`/api/product/${productId}/manage/rejected`)
        .then(() => update())
}

function buy(productId) {
    axios.post(`/api/product/${productId}/sell`)
        .then(() => update())
}

function markSeen(notificationId) {
    axios.put(`/api/users/${userId.value}/notifications/${notificationId}`)
        .then(() => update())
}

onMounted(() => {
    update();
})

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-row">
                        <div class="m-2 basis-1/2">
                            <ul class="w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600 text-white bg-blue-700">Available products</li>

                                <li
                                    v-for="product in availableProducts"
                                    :key="product.id"
                                    class="py-2 px-4 w-full border-b border-gray-200 dark:border-gray-600">
                                    <div class="flex flex-row justify-between">
                                        <div>
                                            <span class="bg-gray-100 text-gray-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                                </svg>
                                                <strong>{{ product.monthly_inventory }}</strong>
                                            </span>
                                            {{ product.name }}
                                            <span v-if="product.owner.id === userId" class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Owner</span>
                                        </div>
                                        <div>
                                            <button type="button" @click="buy(product.id)" class="py-2 px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Buy</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="m-2 basis-1/2">
                            <ul class="w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600 text-white bg-blue-700">Pending products</li>

                                <li
                                    v-for="product in pendingProducts"
                                    :key="product.id"
                                    class="py-2 px-4 w-full border-b border-gray-200 dark:border-gray-600">

                                    <div class="flex flex-row justify-between">
                                        <div>
                                            <span class="bg-gray-100 text-gray-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                                </svg>
                                                <strong>{{ product.monthly_inventory }}</strong>
                                            </span>
                                            {{ product.name }}
                                            <span v-if="product.owner.id === userId" class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Owner</span>
                                        </div>
                                        <div>
                                            <button type="button" @click="approve(product.id)" class="mr-2 py-2 px-3 text-xs font-medium text-center text-white text-white bg-green-700 hover:bg-green-800 rounded-lg  focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Approve</button>
                                            <button type="button" @click="reject(product.id)" class="py-2 px-3 text-xs font-medium text-center text-white text-white bg-red-700 hover:bg-red-800 rounded-lg  focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">reject</button>
                                        </div>
                                    </div>
                                </li>

                                <a href="#" @click="createProduct" class="block py-2 px-4 w-full rounded-b-lg cursor-pointer bg-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                                    </svg>
                                    Create product
                                </a>
                            </ul>
                        </div>
                    </div>


                    <div class="p-6 bg-white border-b border-gray-200 flex flex-row">
                        <div class="m-2 w-full">
                            <ul class="w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600 text-white bg-blue-700">My notifications</li>

                                <li
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    class="py-2 px-4 w-full border-b border-gray-200 dark:border-gray-600">

                                    <div class="flex flex-row justify-between">
                                        <div>
                                            {{ notification.data.message }}
                                        </div>
                                        <div>
                                            <button type="button" @click="markSeen(notification.id)" class="py-2 px-3 text-xs font-medium text-center text-white text-white bg-green-700 hover:bg-green-800 rounded-lg  focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                Mark seen
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
