
const keyboardNavigation = {
    mounted() {
        window.addEventListener('keydown', this.keyboardFired);
    },
    destroyed() {
        window.removeEventListener('keydown', this.keyboardFired);
    },
    methods: {
        keyboardFired(event) {
            if (event.key === 'ArrowLeft') {
                this.goPrev();
            } else if (event.key === 'ArrowRight') {
                this.goNext();
            }
        },
    },
};

const datesInPeriod = {
    methods: {
        datesInPeriod(start, end, granularity) {
            const dates = [];
            for (const date = start.clone(); date.isBefore(end, granularity) || date.isSame(end, granularity); date.add(1, granularity)) {
                dates.push(date.clone());
            }
            return dates;
        },
    },
};

export {
    datesInPeriod,
    keyboardNavigation,
}
