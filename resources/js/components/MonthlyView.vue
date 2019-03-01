
<template>
    <div
        v-touch:swipe.left="goNext"
        v-touch:swipe.right="goPrev"
        class="row no-gutters"
    >
        <template v-for="day in datesInPeriod(start, end, 'day')">
            <square-photo
                :key="day.unix()"
                :class="{
                    'col-xl': true,
                    'col-6': day.day() !== 0,
                    'col-sm-4': day.day() !== 0,
                    'col-12': day.day() === 0,
                }"
                :date="day"
                :text="day.format('Do')"
                to="/daily"
            />
            <div
                v-if="day.weekday() === 6"
                :key="'br-' + day.unix()"
                class="d-none d-xl-block w-100"
            />
        </template>
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
                return this.currentDate.clone().endOf('month').endOf('week');
            },
            start() {
                return this.currentDate.clone().startOf('month').startOf('week');
            },
        },
        beforeRouteEnter: (to, from, next) => next(vm => vm.setNavigation('month')),
        methods: require('vuex').mapActions([
            'goNext',
            'goPrev',
            'setNavigation',
        ]),
    };

</script>
