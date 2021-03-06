import Vue from 'vue'
import Vuex from 'vuex'

import error from './error'
import auth from './auth'
import user from './user'
import soundType from './soundType'
import audio from './audio'
import transferAccount from './transferAccount'
import favorite from './favorite'
import follow from './follow'
import purchase from './purchase'
import chat from './chat'
import recruitment from './recruitment'


Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    error,
    auth,
    user,
    soundType,
    audio,
    transferAccount,
    favorite,
    follow,
    purchase,
    chat,
    recruitment
  }
})

export default store