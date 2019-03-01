<template>
    <div class="sticky-top py-1 py-sm-2 py-xl-3 mb-1 mb-sm-2 mb-xl-3 bg-light shadow">
        <div class="d-flex align-items-center container">
            <button
                v-if="canPrev"
                class="align-middle btn btn-outline-info"
                @click="goPrev"
            >
                &lArr;
            </button>
            <div class="mx-auto">
                <router-link
                    :class="$route.path === '/daily' ? 'btn-success': 'btn-outline-success'"
                    class="btn"
                    to="/daily"
                >
                    {{ currentDate.format('dddd Do') }}
                </router-link>
                <router-link
                    :class="$route.path === '/monthly' ? 'btn-success': 'btn-outline-success'"
                    class="btn ml-1"
                    to="/monthly"
                >
                    {{ currentDate.format('MMMM') }}
                </router-link>
                <router-link
                    :class="$route.path === '/yearly' ? 'btn-success': 'btn-outline-success'"
                    class="btn ml-1"
                    to="/yearly"
                >
                    {{ currentDate.format('YYYY') }}
                </router-link>
                <router-link
                    v-if="canRandom"
                    :class="$route.path === '/random' ? 'btn-warning': 'btn-outline-warning'"
                    class="btn ml-1"
                    to="/random"
                >
                    &#9654;
                </router-link>
            </div>
            <button
                v-if="canNext"
                class="align-middle btn btn-outline-info"
                @click="goNext"
            >
                &rArr;
            </button>
        </div>
    </div>
</template>



<script>

    export default {
        computed: require('vuex').mapGetters([
            'canNext',
            'canPrev',
            'canRandom',
            'currentDate',
        ]),
        methods: require('vuex').mapActions([
            'goNext',
            'goPrev',
        ]),
    };

</script>
