
<template>
    <div class="container my-3 my-sm-4 my-xl-5 text-center">
        <responsive-photo
            :date="prevDate"
            class="d-none"
        />
        <responsive-photo
            v-touch:swipe.left="goNext"
            v-touch:swipe.right="goPrev"
            :date="currentDate"
            class="img-fluid img-thumbnail"
        />
        <responsive-photo
            :date="nextDate"
            class="d-none"
        />
        <div
            v-if="years.length > 1"
            class="row no-gutters justify-content-center mt-3 mt-sm-4 mt-xl-5"
        >
            <square-photo
                v-for="year in years"
                :key="year.unix()"
                :date="year"
                :text="year.format('Y')"
                class="col-3 col-sm-2 small"
                to="/day"
            />
        </div>
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
                'minDate',
                'maxDate',
                'nextDate',
                'prevDate',
            ]),
            maxYear() {
                const maxYear = this.currentDate.clone().year(this.maxDate.year());
                if (maxYear.isAfter(this.maxDate)) {
                    maxYear.subtract(1, 'year');
                }
                if (maxYear.isBefore(this.minDate)) {
                    maxYear.year(this.currentDate.year);
                }
                return maxYear;
            },
            minYear() {
                const minYear = this.currentDate.clone().year(this.minDate.year());
                if (minYear.isBefore(this.minDate)) {
                    minYear.add(1, 'year');
                }
                if (minYear.isAfter(this.maxDate)) {
                    minYear.year(this.currentDate.year);
                }
                return minYear;
            },
            years() {
                return this.datesInPeriod(this.minYear, this.maxYear, 'year').reverse();
            }
        },
        beforeRouteEnter: (to, from, next) => next(vm => vm.setNavigation('day')),
        methods: require('vuex').mapActions([
            'goNext',
            'goPrev',
            'setNavigation',
        ]),
    };

</script>



<style scoped>

    .small >>> h1 {
        font-size: inherit;
    }

</style>
