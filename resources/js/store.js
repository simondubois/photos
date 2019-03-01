
const moment = require('moment')
moment.locale(document.documentElement.lang);

const state = {
    curDate: moment(document.head.querySelector("[name=max-date]").content),
    navigation: 'day',
    maxDate: moment(document.head.querySelector("[name=max-date]").content),
    minDate: moment(document.head.querySelector("[name=min-date]").content),
};

const getters = {
    canDate: (state) => date => date.isBetween(state.minDate, state.maxDate, undefined, '[]'),
    canNext: (state, getters) => state.navigation && getters.canDate(getters.nextDate),
    canPrev: (state, getters) => state.navigation && getters.canDate(getters.prevDate),
    canRandom: state => state.minDate.isBefore(state.maxDate),
    currentDate: state => state.curDate,
    maxDate: state => state.maxDate,
    minDate: state => state.minDate,
    navigation: state => state.navigation,
    nextDate: state => state.curDate.clone().add(1, state.navigation),
    prevDate: state => state.curDate.clone().subtract(1, state.navigation),
};

const mutations = {
    curDate: (state, date) => state.curDate = date.clone(),
    navigation: (state, navigation) => state.navigation = navigation,
};

const actions = {
    goNext: ({ dispatch, getters }) => dispatch('goTo', getters.nextDate),
    goPrev: ({ dispatch, getters }) => dispatch('goTo', getters.prevDate),
    goTo({ commit, getters }, date) {
        if (getters.canDate(date)) {
            commit('curDate', date);
        }
    },
    setNavigation: ({ commit }, navigation) => commit('navigation', navigation),
};

export {
    state,
    getters,
    mutations,
    actions,
};
