<template>
  <div>
      <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>

      <div class="card purchase_audio_title">
        <h3 class="card-header tt">
          売上履歴
        </h3>


        <div class="no_user my-4 text-center" v-if="sales.length == 0">
          <p>現在売上履歴がありません。</p>
        </div>


        <!-- <div class="d-flex justify-content-center my-3 uriage" v-for="(sale, i) in getItems" :key="i">
          <div class="card mx-2" style="width: 100%;">
            <div class="card-body uriage_detail">
              <p class="card-title subtitle mb-2 audio_title" @click="$router.push({ name: 'audio-show', params: { id: `${sale.audio_id}` } })">{{sale.audio.title}}</p>
              <p class="card-title subtitle">売上金</p>
              <p class="card-title font-weight-bold text-danger mb-2"><i class="fas fa-yen-sign"></i>{{sale.price | comma}}</p>
              <p class="card-title subtitle">日時</p>
              <p class="card-title mb-2">{{sale.created_at | fromiso}}</p>
              <p class="card-title subtitle">購入ユーザー</p>
              <p class="audio_title" @click="$router.push({ name: 'user-show', params: { id: `${sale.user_id}` }})">{{sale.user.name}}</p>
            </div>
          </div>
        </div> -->


        <div class="d-flex justify-content-center my-3" v-for="(sale, i) in getItems" :key="i">
            <div class="card mx-2" style="width: 100%;">
              <div class="card-body buy">

                <div class="d-flex justify-content-between first text-center">
                    <p class="title" @click="$router.push({ name: 'audio-show', params: { id: `${sale.audio_id}` } })">{{sale.audio.title}}</p>
                    <p class="price"><i class="fas fa-yen-sign"></i>{{sale.price | comma}}</p>
                </div>

                <div class="second">
                  <p class="creater" @click="$router.push({ name: 'user-show', params: { id: `${sale.user_id}` }})">購入ユーザー：<a class="link">{{sale.user.name}}</a></p>
                  <h6 class="card-subtitle mb-2 text-muted creater_name">日時:{{sale.created_at | fromiso}}</h6>
                </div>

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

.second .creater {
  font-size: 14px;
  color: #34495e;
  margin-bottom: 0;
}
.second a:hover {
  font-size: 14px;
  color: #34495e;
  margin-bottom: 0;
  cursor: pointer;
  text-decoration: underline;
}


.first p {
  font-size: 20px;
}

.first .title {
  color: #34495e;
  font-weight: bold;
  overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.first .title:hover {
  color: #34495e;
  font-weight: bold;
  cursor: pointer;
  text-decoration: underline;
}
.first .price {
  color: red;
  font-weight: bold;
  min-width :100px;
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



.audio_price {
  font-size: 23px;
  color: hsla(357, 100%, 37%, 0.979);
  font-weight: bold;
}
.withdrawn {
  cursor: auto!important;
}

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
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