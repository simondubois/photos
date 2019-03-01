
<template>
    <div class="p-1 p-sm-2 p-xl-3">
        <div
            :class="{ valid }"
            :style="{ 'background-image': 'url(' + url + ')' }"
            class="card bg-dark text-white shadow-lg"
            @load:error="error = true"
            @click="valid ? goTo(date).then(() => $router.push(to)) : null"
        >
            <div class="card-img-overlay p-0 d-flex align-items-center justify-content-center">
                <h1 class="card-title m-auto">
                    {{ text }}
                </h1>
            </div>
        </div>
    </div>
</template>



<script>

    export default {
        props: {
            date: {
                type: Object,
                required: true,
            },
            text: {
                type: String,
                required: true,
            },
            to: {
                type: String,
                required: true,
            },
        },
        data: () => ({
            error: false,
            size: 1140,
        }),
        computed: {
            ...require('vuex').mapGetters([
                'canDate',
                'currentDate',
                'navigation',
            ]),
            url() {
                if (this.size === null || this.valid === false) {
                    return '';
                }
                return '/'
                    + this.date.format(this.navigation === 'year' ? 'Y/MM' : 'Y/MM/DD')
                    + '?width=' + this.size
                    + '&height=' + this.size
                    + (this.date.isSame(this.currentDate, this.navigation) ? '' : '&grayscale=1');
            },
            valid() {
                return this.canDate(this.date) && this.error === false;
            },
        },
        watch: {
            url() {
                this.error = false;
            },
        },
        mounted() {
            // this.size = this.$el.offsetWidth * window.devicePixelRatio;
        },
        methods: require('vuex').mapActions([
            'goTo',
            'setNavigation',
        ]),
    };

</script>



<style lang="scss" scoped>

    .valid {

        .card-img-overlay {
            .card-title {
                display: none;
            }
            &:hover {
                background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
                cursor: pointer;
                .card-title {
                    display: block;
                }
            }
        }
    }

    .card {
        padding-bottom: 100%;
        background-position:  center;
        background-position: fixed;
        background-repeat: no-repeat;
        background-size: cover;
    }

</style>
