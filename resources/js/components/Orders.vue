<template>
    <div class="p-4">
        <div class="bg-gray-200 py-6 px-6 rounded-md">
            <div class="font-bold text-xl text-center px-4">ORDER DETAILS</div>

            <div class="flex flex-col pt-5 pb-2">
                <div class="mb-4" v-for="(item, index) in orders" :key="index">
                    <div class="flex items-center justify-between">
                        <div class="font-bold">{{ item.name }}</div>
                        <div>$<span class="text-blue-600">{{ item.adjustedPrice | number }}</span></div>
                    </div>
                    <div class="flex items-center justify-between pl-4 text-xs font-medium">
                        <div class="flex text-xs font-medium">
                            <div class="flex">
                                <div>
                                $<span class="text-blue-600">{{ item.price| number }}</span>
                                </div>
                                <div class="px-2">X</div>
                                <div>{{ item.quantity }}</div>
                            </div>
                            <div class="px-2">
                                (<span class="text-red-600">{{ item.tax }}%</span>)
                            </div>
                        </div>
                        <div>
                            <div
                                class="text-xs text-red-600 cursor-pointer"
                                @click="removeItem(index)"
                            >
                                Remove
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
            <div class="flex">
                <template v-if="coupon.info">
                    <div class="flex items-center justify-between w-full">
                        <div class="font-bold italic">{{ coupon.info.code }}</div>
                        <div class="font-medium text-green-600">
                            <span
                                class="text-red-600 cursor-pointer mx-2"
                                @click="removeCoupon()"
                            >(Remove)</span>
                            -{{ coupon.info.discount }}{{ couponUnit }}
                        </div>
                    </div>
                </template>
                <template v-else>
                    <template v-if="coupon.show">
                        <div class="flex-grow pr-1">
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                type="text"
                                v-model="coupon.code"
                                placeholder="Coupon">
                                
                                <div
                                    v-if="!coupon.success"
                                    class="text-red-600 text-sm m-1 px-1"
                                >
                                    Invalid Coupon Code
                                </div>
                        </div>
                        <div class="flex-none pl-1">
                            <button
                                type="button"
                                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                @click="getCoupon()"
                            >
                                Apply
                            </button>
                            <button
                                type="button"
                                class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                @click="closeCoupon()"
                            >
                                Close
                            </button>
                        </div>
                    </template>
                    <div v-else>
                        <div
                            class="cursor-pointer text-indigo-600 italic"
                            @click="coupon.show = true"
                        >
                            Do you have coupon?
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex">
                <div class="pt-4 w-full">
                    <div class="flex items-center justify-between font-bold">
                        <div class="text-xl">TOTAL</div>
                        <div class="text-2xl">$<span class="text-blue-600">{{ total| number }}</span></div>
                    </div>
                </div>
            </div>
            <div>
                <button
                    type="button"
                    class="w-full mt-4 font-bold text-2xl bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="checkout()"
                    :disabled="total === 0"
                >
                    CHECKOUT ${{ total | number }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                default: null
            }
        },
        data: () => ({
            coupon: {
                info: null,
                code: null,
                show: false,
                success: true
            },
        }),
        computed: {
            orders() {
                return this.items.map(item => {
                    item.adjustedPrice = this.calculateAdjustedPrice(item)
                    return item
                })
            },
            subtotal() {
                var subtotal = 0;
                this.orders.forEach(function(item) {
                    subtotal += item.adjustedPrice
                });
                return subtotal
            },
            total() {
                var discount = 0
                if (this.coupon.info) {
                    if (this.isCouponUnitPercentage) {
                        discount = this.subtotal * (this.coupon.info.discount / 100)
                    } else {
                        discount = this.coupon.info.discount
                    }
                }
                return this.subtotal - discount
            },
            couponUnit() {
                var unit = ''
                if (this.isCouponUnitPercentage)
                    unit = '%'
                return unit
            },
            isCouponUnitPercentage() {
                return this.coupon.info && this.coupon.info.unit === 'percentage'
            }
        },
        methods: {
            calculateAdjustedPrice(item) {
                var price = parseFloat(item.price) * item.quantity;
                return item.adjustedPrice = price + (price * (parseFloat(item.tax)/100))
            },
            removeCoupon() {
                this.coupon.info = null
            },
            getCoupon() {
                axios.get(`/api/coupons/${this.coupon.code}`)
                    .then((response) => {
                        this.coupon.success = true
                        if (response.status === 200) {
                            this.coupon.info = response.data.data
                            this.coupon.code = null
                        }
                    }).catch((error) => {
                        this.coupon.success = false
                    });
            },
            closeCoupon() {
                this.coupon.show = false
                this.coupon.code = null
            },
            checkout() {
                var items = this.orders.map((item) => {
                    return {
                        id: item.id,
                        quantity: item.quantity
                    }
                })
                var data = {
                    items,
                    coupon: this.coupon.info?.code
                }
                axios.post(`/api/checkout`, data)
                    .then((response) => {
                        if (response.status == 201) {
                            var order = response.data.data
                            this.$router.push({ name: 'order-summary', params: { code: order.code  }})
                        }
                    }).catch((error) => {
                        this.coupon.success = false
                    });
            },
            removeItem(index) {
                this.items.splice(index, 1)
            }
        }
    }
</script>
