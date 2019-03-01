
<template>
    <div class="container my-3 my-sm-4 my-xl-5 text-center">
        <div class="d-flex">
            <div class="mr-3 mr-sm-4 mr-xl-5">
                {{ currentDate.fromNow() }}
            </div>
            <div class="flex-fill progress mb-3 mb-sm-4 mb-xl-5 shadow">
                <div
                    :style="{ width: progress + '%' }"
                    :class="{ 'progress-bar-striped' : progress }"
                    class="progress-bar progress-bar-animated bg-warning"
                />
            </div>
        </div>
        <responsive-photo
            :date="currentDate"
            class="img-fluid img-thumbnail"
        />
        <responsive-photo
            :date="nextDate"
            class="d-none"
            @load:error="nextDate = getRandomDate()"
        />
    </div>
</template>



<script>

    export default {
        data: () => ({
            timeout: null,
            nextDate: null,
            progress: 0,
        }),
        computed: require('vuex').mapGetters([
            'currentDate',
            'maxDate',
            'minDate',
        ]),
        watch: {
            currentDate: {
                handler() {
                    setTimeout(() => {
                        this.progress = 100;
                    }, 100);
                    this.nextDate = this.getRandomDate();
                    this.timeout = setTimeout(this.goNext, 5000)
                },
                immediate: true,
            },
        },
        beforeRouteEnter: (to, from, next) => next(vm => vm.setNavigation(null)),
        destroyed() {
            clearTimeout(this.timeout);
        },
        methods: {
            ...require('vuex').mapActions([
                'goTo',
                'setNavigation',
            ]),
            goNext() {
                this.progress = 0;
                this.goTo(this.nextDate);
            },
            getRandomDate() {
                let randomDays = 0;
                while (randomDays === 0) {
                    randomDays = Math.floor(Math.random() * this.maxDate.diff(this.minDate, 'day'));
                }
                return this.minDate.clone().add(randomDays, 'day');
            },
        },
    };

</script>



<style scoped>

    .progress-bar {
        transition: none;
    }

    .progress-bar-striped {
        transition: width 5s linear;
    }

</style>
