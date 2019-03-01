
<template>
    <img
        :alt="date ? date.format('LL') : ''"
        :src="error ? '/404.svg' : url"
        :style="{ 'max-height': size + 'px' }"
        class="shadow-lg"
        @error="error = true ; $emit('load:error')"
    >
</template>



<script>

    export default {
        props: {
            date: {
                type: Object,
                default: null,
            },
        },
        data: () => ({
            error: false,
            size: 1140,
        }),
        computed: {
            url() {
                if (this.size === null || this.date === null) {
                    return '';
                }
                return '/' + this.date.format('Y/MM/DD') + '?width=' + this.size + '&height=' + this.size;
            },
        },
        watch: {
            url() {
                this.error = false;
            },
        },
        mounted() {
            // this.size = Math.min(this.$el.parentElement.offsetWidth, window.innerHeight) * window.devicePixelRatio;
        },
    };

</script>
