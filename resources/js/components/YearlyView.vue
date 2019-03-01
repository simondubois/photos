
<template>
    <div
        v-touch:swipe.left="goNext"
        v-touch:swipe.right="goPrev"
        class="row no-gutters"
    >
        <square-photo
            v-for="month in datesInPeriod(start, end, 'month')"
            :key="month.unix()"
            :date="month"
            :text="month.format('MMM')"
            class="col-6 col-sm-3 col-xl-2"
            to="/monthly"
        />
    </div>
</template>



<script>

    export default {
        mixins: [
            require('../mixins.js').datesInPeriod,
            require('../mixins.js').keyboardNavigation,
        ],
        computed: {
            ...require('vuex').mapGetters([
                'currentDate',
            ]),
            end() {
                return this.currentDate.clone().endOf('year');
            },
            start() {
                return this.currentDate.clone().month(0);
            },
        },
        beforeRouteEnter: (to, from, next) => next(vm => vm.setNavigation('year')),
        methods: require('vuex').mapActions([
            'goNext',
            'goPrev',
            'setNavigation',
        ]),
    };

</script>
