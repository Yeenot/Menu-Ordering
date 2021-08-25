<template>
    <div class="p-4">
        <div class="bg-gray-200 py-4 px-2 rounded-md">
            <img class="object-contain h-48 w-full" :src="getImagePath(info.image)" alt="">
            <div class="flex items-center justify-center mt-2">
                <div class="flex items-center justify-between w-11/12">
                    <div class="font-bold">{{ info.name }}</div>
                    <div class="font-medium">$<span class="text-blue-600">{{ info.price | number }}</span></div>
                </div>
            </div>
            <div class="flex items-end justify-center">
                <div class="flex items-center justify-end w-11/12">
                    <div class="text-xs">
                        Tax: <span class="text-red-600">{{ info.tax }}%</span>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-center mt-4">
                <div class="flex items-center justify-center w-11/12">
                    <div class="px-2">
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="number"
                            v-model="quantity"
                        />
                    </div>
                    <div class="px-2">
                        <button
                            type="button"
                            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click="addToOrder()"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            info: {
                type: Object,
                default: null
            }
        },
        data: () => ({
            quantity: 1
        }),
        methods: {
            addToOrder() {
                var quantity = parseInt(this.quantity)
                
                if (quantity > 0) {
                    var item = Object.assign({}, this.info, { quantity })
                    this.$emit('order', item)
                    this.quantity = 1;
                }
            },
            getImagePath(image) {
                return '/storage/'+image
            }
        }
    }
</script>
