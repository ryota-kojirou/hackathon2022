import Vue from "vue";
import Vuex from "vuex";
//import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: [],
        member: [],
        memberID: [],
        memberName: [],
        memberPhoto: [],
        message: [],
        roomID: null,
        host: false,
        memberColor: [],
        toMembersList: [],
        guess: [],
        select_thema: '',
    },
    getters: {
        isLoggedIn: (state) => !!Object.keys(state.user).length,
    },
    mutations: {
        setUser(state, val) {
            //本人の情報
            state.user = val.user;
        },
        async setMember(state, val) {
            //同じ部屋のメンバーの情報
            state.member = val;
        },
        async setMemberID(state, val) {
            //同じ部屋のメンバーの情報
            state.memberID = val;
        },
        async setMemberName(state, val) {
            state.memberName = val;
        },
        async setMemberPhoto(state, val) {
            state.memberPhoto = val;
        },
        setMessage(state, val) {
            //メッセージをなんかする
            state.message = val.message;
        },
        setGuess(state, val) {
            //メッセージをなんかする
            state.guess = val.guess;
        },
        setRoomID(state, val) {
            //ルームIDをなんかする
            state.roomID = val.roomID;
        },
        async setSelectThema(state, val){
            state.select_thema = val;
        },
        setHost(state, val) {
            state.host = val.host;
        },
        setMemberColor(state, val) {
            state.memberColor = val.memberColor;
        },
        setToMembersList(state, val) {
            state.toMembersList = val.toMembersList;
        },
    },
    actions: {
        addMember({ commit, state }, new_member) {
            const members = state.member;
            members.push(new_member);
            commit("setMember", members);
        },
    },
    modules: {},
});