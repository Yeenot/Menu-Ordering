<template>
    <div>
        <div class="flex mt-4">
            <div class="flex-grow">

                <!-- Actions -->
                <div>
                    <!-- Categories -->
                    <label class="font-medium">Menu</label>
                    <select
                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        v-model="category"
                    >
                        <option value="">All</option>
                        <option
                            v-for="(category, index) in categories" :key="index"
                            :value="index"
                        >
                            {{ category.name }}
                        </option>
                    </select>

                    <!-- Search -->
                    <input
                        class="mt-4 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        type="text"
                        v-model="search"
                        @keydown="onSearchKeydown()"
                        placeholder="Search...">
                </div>

                <div class="grid grid-cols-3 mt-6">
                    <div v-for="(item, index) in items" :key="index">
                        <item :info="item" @order="addToOrder" />
                    </div>
                </div>
            </div>
            <div class="flex-none w-1/3">
                <div class="">
                    <Orders :items="orderItems" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        categories: [],
        items: [],
        category: '',
        search: null,
        searchKeydownTimeout: null,
        orderItems: []
    }),
    mounted() {
        this.getCategories();
    },
    watch: {
        category() {
            this.getItems()
        }
    },
    methods: {
        getCategories() {
            var params = {
                search: this.search
            }
            axios.get(`/api/categories`, { params })
                .then((response) => {
                    if (response.status === 200) {
                        this.categories = response.data.data
                        this.category = ''
                        this.getItems()
                    }
                });
        },
        getItems() {
            var params = {
                search: this.search,
                category: this.categories[this.category]?.name
            }
            axios.get(`/api/items`, { params })
                .then((response) => {
                    if (response.status === 200) {
                        this.items = response.data.data;
                    }
                });
        },
        onSearchKeydown() {
            var vm = this;
            if (vm.searchKeydownTimeout)
                clearTimeout(vm.searchKeydownTimeout)

            vm.searchKeydownTimeout = setTimeout(function() {
                vm.getItems()
            }, 1000)
        },
        addToOrder(item) {
            var index = _.findIndex(this.orderItems, { id: item.id });

            if (index === -1)
                this.orderItems.push(item);
            else
                this.orderItems[index].quantity = this.orderItems[index].quantity + item.quantity
        }
    }
}
</script>