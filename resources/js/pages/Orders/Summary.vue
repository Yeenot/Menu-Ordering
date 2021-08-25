<template>
    <div class="p-4">
        <div class="flex items-center justify-end w-5/12 mx-auto mb-6">
            <button
                type="button"
                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="$router.back()"
            >
                Back
            </button>
        </div>
        <div class="bg-gray-200 py-6 px-6 rounded-md w-5/12 mx-auto">
            <div class="font-bold text-2xl text-center px-4 text-indigo-600 mb-6">THANK YOU FOR ORDERING IN OUR STORE!</div>
            <div class="font-bold text-xl text-center px-4">ORDER SUMMARY</div>

            <template v-if="order">
                <div class="flex flex-col pt-5 pb-2">
                    <div class="mb-4" v-for="(item, index) in order.items" :key="index">
                        <div class="flex items-center justify-between">
                            <div class="font-bold">{{ item.pivot.name }}</div>
                            <div>$<span class="text-blue-600">{{ item.pivot.adjustedPrice | number }}</span></div>
                        </div>
                        <div class="flex-col flex-start px-4 text-xs font-medium">
                            <div class="flex text-xs font-medium">
                                <div class="flex">
                                    <div>
                                    $<span class="text-blue-600">{{ item.pivot.price| number }}</span>
                                    </div>
                                    <div class="px-2">X</div>
                                    <div>{{ item.pivot.quantity }}</div>
                                </div>
                                <div class="px-2">
                                    (<span class="text-red-600">{{ item.pivot.tax }}%</span>)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-solid border-black pt-4">
                        <div class="flex items-center justify-between font-bold">
                            <div>Subtotal</div>
                            <div>$<span class="text-blue-600">{{ subtotal| number }}</span></div>
                        </div>
                    </div>

                </div>
                <div class="flex" v-if="order.coupon">
                    <div class="flex items-center justify-between w-full">
                        <div class="font-bold italic">{{ couponCode }}</div>
                        <div class="font-medium text-green-600">
                            -{{ order.discount_value }}{{ couponUnit }}
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="pt-4 w-full">
                        <div class="flex items-center justify-between font-bold">
                            <div class="text-xl">TOTAL</div>
                            <div class="text-2xl">$<span class="text-blue-600">{{ total| number }}</span></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        order: null
    }),
    mounted() {
        this.getOrder()
    },
    computed: {
        subtotal() {
            var subtotal = 0;
            this.order.items.forEach(function(item) {
                subtotal += item.pivot.adjustedPrice
            });
            return subtotal
        },
        total() {
            var discount = 0
            if (this.order && this.order.coupon) {
                if (this.isCouponUnitPercentage) {
                    discount = this.subtotal * (this.order.discount_value / 100)
                } else {
                    discount = this.order.discount_value
                }
            }
            return this.subtotal - discount
        },
        couponCode() {
            return this.order && this.order.coupon ? this.order.coupon.code : ''
        },
        couponUnit() {
            var unit = ''
            if (this.isCouponUnitPercentage)
                unit = '%'
            return unit
        },
        isCouponUnitPercentage() {
            return this.order && this.order.coupon && this.order.discount_unit === 'percentage'
        }
    },
    methods: {
        getOrder() {
            axios.get(`/api/orders/${this.$route.params.code}`)
                .then((response) => {
                    if (response.status == 200) {
                        var order = response.data.data
                        order.items = order.items.map(item => {
                            item.pivot.adjustedPrice = this.calculateAdjustedPrice(item)
                            return item
                        })
                        this.order = order
                    }
                })
        },
        calculateAdjustedPrice(item) {
            var price = parseFloat(item.pivot.price) * item.pivot.quantity;
            return item.pivot.adjustedPrice = price + (price * (parseFloat(item.pivot.tax)/100))
        }
    }
}
</script>