<template>
  <div>
      <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <!-- <div class="" v-if="!loading">
      <div class="card-group mb-2">
        <div class="card text-center">
          <div class="card-body earnings">
            <h5 class="card-title">申請可能金額</h5>
            <h1><i class="fas fa-yen-sign"></i>{{ earning | comma }}</h1>
          </div>
        </div>
        <div class="card text-center">
          <div class="card-body cumulative">
            <h5 class="card-title">累計の売上金額</h5>
            <h1><i class="fas fa-yen-sign"></i>{{ cumulative | comma }}</h1>
          </div>
        </div>
      </div> -->

      


      <div class="card purchase_audio_title">
        <h3 class="card-header">
          売上履歴
        </h3>

        <!-- <div class="mb-5 d-flex justify-content-end fee">
          <a class="font-weight-bold text-danger" @click="$router.push({ name: 'guide-payment' })">振込申請・手数料について</a>
        </div> -->

        <div class="no_user my-4 text-center" v-if="sales.length == 0">
          <p>現在売上履歴がありません。</p>
        </div>

        <!-- <div class="card-body purchase_audio_body" v-for="(sale, i) in sales" :key="i">
          <h5 class="card-title audio_title" @click="$router.push({ name: 'audio-show', params: { id: `${sale.audio_id}` } })">{{sale.audio.title}}</h5>
          <h6 class="card-subtitle mb-2 text-muted creater_name" @click="$router.push({ name: 'user-show', params: { id: `${sale.user_id}` }})">購入ユーザー名：{{sale.user.name}}</h6>
          <h6 class="card-subtitle mb-2 text-muted" >日付：{{sale.created_at | fromiso}}</h6>
          <h6 class="card-subtitle mb-2 price font-weight-bold text-danger"><i class="fas fa-yen-sign"></i>{{sale.price | comma}}</h6>
          
          <div class="application_button">
            <button type="button" class="btn btn-danger text-white" @click="$router.push({ name: 'payout', params: { id: `${sale.audio_id}` }})" v-if="sale.status == 0">
              振込申請をする
            </button>
            <button type="button" class="btn btn-primary text-white withdrawn" v-if="sale.status == 1">
              申請中
            </button>
            <button type="button" class="btn btn-secondary text-white withdrawn" v-if="sale.status == 2">
              出金済み
            </button>
          </div>

        </div> -->

        <div class="d-flex justify-content-center my-3 uriage" v-for="(sale, i) in getItems" :key="i">
          <div class="card" style="width: 37rem;">
            <div class="card-body uriage_detail">
              <p class="card-title subtitle mb-2 audio_title" @click="$router.push({ name: 'audio-show', params: { id: `${sale.audio_id}` } })">{{sale.audio.title}}</p>
              <p class="card-title subtitle">売上金</p>
              <p class="card-title font-weight-bold text-danger mb-2"><i class="fas fa-yen-sign"></i>{{sale.price | comma}}</p>
              <p class="card-title subtitle">日時</p>
              <p class="card-title mb-2">{{sale.created_at | fromiso}}</p>
              <p class="card-title subtitle">購入ユーザー</p>
              <p class="audio_title" @click="$router.push({ name: 'user-show', params: { id: `${sale.user_id}` }})">{{sale.user.name}}</p>
              <button type="button" class="btn btn-danger text-white" @click="$router.push({ name: 'payout', params: { id: `${sale.audio_id}` }})" v-if="sale.status == 0">
              振込申請をする
            </button>
            </div>
          </div>
        </div>

        <!-- ページネーション -->
    <div class="pagination mt-5 d-flex justify-content-center">
      <div v-if="paginateData.sales.length">
        <paginate
        :page-count="getPageCount"
        :page-range="3"
        :margin-pages="2"
        :click-handler="clickCallback"
        :prev-text="'＜'"
        :next-text="'＞'"
        :containerClass="'pagination'"
        :page-class="'page-item'"
        :page-link-class="'page-link'"
        :prev-class="'page-item'"
        :prev-link-class="'page-link'"
        :next-class="'page-item'"
        :next-link-class="'page-link'"
        >
        </paginate>
      </div>
    </div>


    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      sales:[],
      dialog: false,
      paginateData: {
        sales: [],
        parPage: 10, //1ページに表示する件数
        currentPage: 1
      },
    }
  },
  computed: {
    getItems() { //ページネーション用(1ページに表示する数)
        let current = this.paginateData.currentPage * this.paginateData.parPage;
        let start = current - this.paginateData.parPage;
        return this.paginateData.sales.slice(start, current);
    },
    getPageCount() {// ページネーション用(全体のページ数)
        return Math.ceil(this.paginateData.sales.length / this.paginateData.parPage);
    },
    cumulative() { //累計金額
      // まずはpriceだけ取り出した配列を作成
      let price = this.sales.map(o => o.price)
      // その中身の合計を出す
      let total = price.reduce((sum, element) => sum + element, 0);
      return total
    },
    earning() { // 申請可能金額

      // まずはstatusが0(金額を引き出してない)もののみをfilter
      let notWithdraw = this.sales.filter(o => o.status == 0);
      // priceだけ取り出した配列を作成
      let price = notWithdraw.map(o => o.price);
      // その中身の合計を出す
      let total = price.reduce((sum, element) => sum + element, 0);

       return total
    }
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async getSalesData() {
      try{
        this.loading = true
        await this.$store.dispatch('purchase/getSales')
        this.sales = this.$store.state.purchase.sales
        this.paginateData.sales = this.$store.state.purchase.sales

      }
      catch(e){
        // console.log(e);
        this.loading = false
      }
      finally{
        this.loading = false
      }
    }
  },
  created() {
    Promise.all([
      this.getSalesData(),
    ])
  },

}
</script>

<style scoped>

.uriage_detail p {
  margin-bottom: 0;
}
.uriage_detail .subtitle {
  font-weight: bold;
  font-size: 18px;
}
.uriage_detail .audio_title:hover {
  cursor: pointer;
  text-decoration: underline;
}

.uriage {
  color: #34495e;
}

.fee:hover {
  cursor: pointer;
}

.earnings h1 {
  font-weight: bold;
  color: hsla(357, 100%, 37%, 0.979);
}
.cumulative h1 {
  font-weight: bold;
  color: black;
}


.purchase_audio_body .audio_title:hover {
  cursor: pointer;
  text-decoration: underline;
}
.creater_name:hover {
  cursor: pointer;
  text-decoration: underline;
}

.audio_price {
  font-size: 23px;
  color: hsla(357, 100%, 37%, 0.979);
  font-weight: bold;
}
.withdrawn {
  cursor: auto!important;
}

@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .purchase_audio_title h3 {
      font-weight: bold;
      font-size: 20px;
      color: #34495e;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .purchase_audio_title h3 {
      font-weight: bold;
      color: #34495e;
    }


}


</style>