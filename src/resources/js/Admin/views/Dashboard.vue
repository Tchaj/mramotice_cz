<template>
    <div class="content--fix">
        <h1 class="custom-heading-1">Vítejte v administraci</h1>
        <div class="dashboard-wrapper">
            <!-- Top counters -->
            <div class="dashboard-count-col shop-primary-background">
                <div class="count-inner-row">
                    <div class="count-inner-col">{{ $t("user_orders") }}</div>
                    <div class="count-inner-col">
                        {{ dashboardData.total_orders }}
                    </div>
                </div>
                <div class="count-inner-row">
                    <div class="count-inner-col" style="width:100%">
                        <div class="count-progress-outer">
                            <div
                                class="count-progress-inner"
                                :style="{ width: ordersProgress + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="count-inner-row">
                    <div class="count-inner-col">
                        {{ $t("user_orders_today") }}
                    </div>
                    <div class="count-inner-col">
                        {{ dashboardData.orders_today }}
                    </div>
                </div>
            </div>
            <div class="dashboard-count-col shop-secondary-background">
                <div class="count-inner-row">
                    <div class="count-inner-col">{{ $t("user_count") }}</div>
                    <div class="count-inner-col">
                        {{ dashboardData.registered_users }}
                    </div>
                </div>
                <div class="count-inner-row">
                    <div class="count-inner-col" style="width:100%">
                        <div class="count-progress-outer">
                            <div
                                class="count-progress-inner"
                                :style="{ width: usersProgress + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="count-inner-row">
                    <div class="count-inner-col">
                        {{ $t("user_count_today") }}
                    </div>
                    <div class="count-inner-col">
                        {{ dashboardData.registered_users_today }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats -->
        <div class="dashboard-wrapper">
            <!-- Stats -->
            <div class="dashboard-stat-col">
                <h4
                    class="custom-heading-4 pl-5"
                    style="width:100%"
                    v-if="dashboardData.bestsellers.length"
                >
                    {{ $t("bestsellers") }}
                </h4>
                <div class="shop-card-list mt-3">
                    <div
                        class="shop-card-list-outer"
                        v-for="(i,index) in dashboardData.bestsellers"
                        :key="index"
                    >
                        <div class="shop-card-list-item">
                            <div class="card-list-item__avatar">
                                <div
                                    class="avatar-image-placeholder"
                                    :style="{
                                        background: `url('${i.thumbnail}')`
                                    }"
                                ></div>
                            </div>
                            <div class="card-list-item__product-info">
                                <div class="product-info__name">
                                    {{ i.name }}
                                </div>
                                <div class="product-info__manufacturer">
                                    {{ i.manufacturer_name }}
                                </div>
                            </div>
                            <div class="card-list-item__product-price">
                                {{ i.price.toLocaleString() }} Kč
                            </div>
                            <div class="card-list-item__product-detail">
                                <v-btn
                                    color="primary"
                                    small
                                    @click="
                                        $router.push({
                                            name: 'Items-detail',
                                            params: { itemId: i.id }
                                        })
                                    "
                                >Detail</v-btn
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <h4
                    class="custom-heading-4 mt-5 pl-5"
                    style="width:100%"
                    v-if="dashboardData.bestsellers_today.length"
                >
                    {{ $t("today_stats") }}
                </h4>
                <div class="shop-card-list mt-3">
                    <div
                        class="shop-card-list-outer"
                        v-for="(i, index) in dashboardData.bestsellers_today"
                        :key="index"
                    >
                        <div class="shop-card-list-item">
                            <div class="card-list-item__avatar">
                                <div
                                    class="avatar-image-placeholder"
                                    :style="{
                                        background: `url('${i.thumbnail}')`
                                    }"
                                ></div>
                            </div>
                            <div class="card-list-item__product-info">
                                <div class="product-info__name">
                                    {{ i.name }}
                                </div>
                                <div class="product-info__manufacturer">
                                    {{ i.manufacturer_name }}
                                </div>
                            </div>
                            <div class="card-list-item__product-price">
                                {{ i.price.toLocaleString() }} Kč
                            </div>
                            <div class="card-list-item__product-detail">
                                <v-btn
                                    color="primary"
                                    small
                                    @click="
                                        $router.push({
                                            name: 'Items-detail',
                                            params: { itemId: i.id }
                                        })
                                    "
                                >Detail</v-btn
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- latest orders -->
            <div class="dashboard-stat-col">
                <h4 class="custom-heading-4 pl-5" style="width:100%">
                    {{ $t("last_orders") }}
                </h4>
                <div class="shop-card-list shop-card-list--smaller mt-3">
                    <div
                        class="shop-card-list-outer"
                        v-for="(i,index) in dashboardData.recent_orders"
                        :key="index"
                    >
                        <div class="shop-card-list-item">
                            <div class="card-list-item__avatar"></div>
                            <div class="card-list-item__product-info">
                                <div class="product-info__name">
                                    {{ i.customer_full_name }}
                                </div>
                                <div class="product-info__manufacturer">
                                    {{ i.created_at }}
                                </div>
                            </div>
                            <div class="card-list-item__product-price">
                                {{
                                i.total_price > 0
                                ? i.total_price.toLocaleString()
                                : 0
                                }}
                                Kč
                            </div>
                            <div class="card-list-item__product-detail">
                                <v-btn
                                    color="primary"
                                    small
                                    @click="
                                        $router.push({
                                            name: 'Orders-detail',
                                            params: { orderId: i.id }
                                        })
                                    "
                                >Detail</v-btn
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import WebService from "../services/WebService";
export default {
    async created() {
        this.$service("title", "Dashboard");
        await this.dashboard();
    },
    data() {
        return {
            dashboardData: {},
            ordersProgress: 100,
            usersProgress: 0
        };
    },
    methods: {
        async dashboard() {
            try {
                this.$service("processing", true);
                const dashboard = await WebService.dashboard();
                this.dashboardData = dashboard;

                let ordersProgress = 0;
                let totalOrders = dashboard.total_orders;
                let ordersToday = dashboard.orders_today;
                if (totalOrders > 0) {
                    if (ordersToday > 0) {
                        ordersProgress = (ordersToday / totalOrders) * 100;
                    } else {
                        ordersProgress = 100;
                    }
                }

                let usersProgress = 0;
                let totalUsers = dashboard.registered_users;
                let usersToday = dashboard.registered_uses_today;
                if (totalUsers > 0) {
                    if (usersToday > 0) {
                        usersProgress = (usersToday / totalUsers) * 100;
                    } else {
                        usersProgress = 100;
                    }
                }

                this.ordersProgress = ordersProgress;
                this.usersProgress = usersProgress;
                this.$service("processing", false);
            } catch (e) {
                console.log(e);
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error", "warning"));
            }
        },
        progress(totalVal, val) {
            let percentage = 100;
            return `${percentage}%`;
        }
    }
};
</script>

<style></style>
