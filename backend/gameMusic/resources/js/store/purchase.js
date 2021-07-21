import router from '../router/index';

const state = {
  isPurchase: false,
  purchases: [], //ログインユーザーの購入オーディオ一覧
  sales: [],
  payoutAudio: {},
  allPurchases: []
}

const getters = {}

const mutations = {
  // セット購入すみかどうか
  setIsPurchase(state, data) {
    state.isPurchase = data.is_purchase
  },
  // セット購入オーディオ一覧
  setPurchases(state, data) {
    state.purchases = data.purchases
  },
  // セット購入オーディオ一覧
  setSales(state, data) {
    state.sales = data.sales_records
  },
  // セット購入オーディオ一覧
  setPayoutAudio(state, data) {
    state.payoutAudio = data.audio
  },
  // セット全ての購入データ
  setAllPurchases(state, data) {
    state.allPurchases.purchaseRecords = data.purchase_records
    state.allPurchases.transferRecords = data.transferRecords
  },
}

const actions = {
  // あるオーディオを購入済かどうかを取得
  async getIspurchase({ commit }, id) {
    await axios.get(`/api/${id}/isPurchase`)
    .then(res => {
      commit('setIsPurchase', res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
  // ログインユーザーの購入オーディオ一覧取得
  async getPurchases({ commit }) {
    await axios.get(`/api/purchases`)
    .then(res => {
      // console.log(res.data);
      commit('setPurchases', res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
  // ログインユーザーが出品した商品のうち、購入されたデータ一覧を取得
  async getSales({ commit }) {
    await axios.get(`/api/sales`)
    .then(res => {
      // console.log(res.data);
      commit('setSales', res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
  // 全ての購入履歴データ取得
  async getAllPurchase({ commit }) {
    await axios.get(`/api/allPurchases`)
    .then(res => {
      // console.log(res.data);
      commit('setAllPurchases', res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
  // 振込申請ページ用で使うオーディオデータ取得
  async getPayoutAudio({ commit }, id) {
    await axios.get(`/api/audio/${id}/payout`)
    .then(res => {
      commit('setPayoutAudio', res.data);
    })
    .catch(e => {
      console.log(e);
      router.replace({ name: 'sales'})
    })
  },
  // 振込申請
  async payout({ commit }, data) {
    await axios.post(`/api/payout`, data)
    .then(res => {
      console.log(res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
  // 管理者が入金する
  async adminPayment({ commit }, data) {
    await axios.post(`/api/payment`, data)
    .then(res => {
      // console.log(res.data);
    })
    .catch(e => {
      console.log(e);
    })
  },
}




export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}