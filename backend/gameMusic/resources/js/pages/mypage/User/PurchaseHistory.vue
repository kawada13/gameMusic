<template>
  <div>
        <!-- ローディング中 -->
        <div class="my-5" v-if="loading">
          <Loader />
        </div>

        <div class="card mt-2" v-if="!loading">
            <h3 class="card-header tt">
              購入履歴
            </h3>
            <p class="purchase_count ml-4 mt-3 mb-5">購入した商品：{{audios.length}}件</p>
            <div class="no_audio my-4" v-if="!audios.length">
              <p style="color: #34495e;">現在購入されたオーディオはございません。</p>
            </div>


            <div class="d-flex justify-content-center my-3" v-for="(audio,i) in getItems" :key="i">
              <div class="card mx-2" style="width: 100%;">
                <div class="card-body buy">

                  <div class="d-flex justify-content-between first text-center">
                     <p class="title" @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` } })">{{audio.title}}</p>
                     <p class="price"><i class="fas fa-yen-sign"></i>{{audio.price | comma}}</p>
                  </div>

                  <div class="second">
                    <p class="creater" @click="$router.push({ name: 'user-show', params: { id: `${audio.user_id}` }})">クリエイター：<a class="link">{{audio.user.name}}</a></p>
                    <h6 class="card-subtitle mb-2 text-muted creater_name">購入日:{{audio.purchase_records[0].created_at | fromiso}}</h6>
                  </div>

                  <div class="third d-flex justify-content-end">
                    <a type="button" class="btn btn-danger font-weight-bold text-white" id="download" @click="download(audio.audio_file, audio.title)"><i class="fas fa-download mr-2"></i>ダウンロード</a>
                  </div>

                </div>
              </div>
            </div>

                <!-- ページネーション -->
            <div class="pagination mt-5 d-flex justify-content-center">
              <div v-if="paginateData.audios.length">
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
      audios:[],
      loading:false,
      paginateData: {
        audios:[],
        parPage: 10, //1ページに表示する件数
        currentPage: 1
      },
    }
  },
  computed: {
    getItems() { //ページネーション用(1ページに表示する数)
        let current = this.paginateData.currentPage * this.paginateData.parPage;
        let start = current - this.paginateData.parPage;
        return this.paginateData.audios.slice(start, current);
    },
    getPageCount() {// ページネーション用(全体のページ数)
        return Math.ceil(this.paginateData.audios.length / this.paginateData.parPage);
    },
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async download(url, name) {
      await axios.post(`/api/audios/download`, {url: url})
      .then(res => {
        // console.log(res.data);
        const url = '/mp3/logo.mp3';
        const fileName = `${name}.mp3`;
        let link = document.createElement('a');
        link.href= url;
        link.download = fileName;
        link.click();
      })
      .catch(e => {
        console.log(e);
      })
    },

    async getPurchases() {

      try{
        this.loading = true
        await this.$store.dispatch('purchase/getPurchases')
        this.audios = this.$store.state.purchase.purchases
        this.paginateData.audios = this.$store.state.purchase.purchases

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
      this.getPurchases(),
    ])
  },

}
</script>

<style scoped>

.link {
  color: #81A6CB;
  font-weight: bold;
}
.link:hover {
  color: #62A1D7;
  cursor: pointer;
  text-decoration: underline;
}

.second .creater {
  font-size: 14px;
  color: #34495e;
  margin-bottom: 0;
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

.purchase_audio_body button {
  margin: 0!important;
}

.no_audio {
  text-align: center;
}

.audio_price {
  cursor: auto!important;
}
.audio_price:hover {
   background: white!important;
   color: black!important;
   border: #6CB2EB ;
}

.purchase_count {
  font-weight: 600;
  font-size: 18px;
  color: #334e6f;
}

.audio_download {
  padding: 0;
  height: 40px;
  width: 136px;
}
.audio_download audio{
  height: 20px;
  width: 20px;
  margin: 0!important;
}

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}

.buy {
  padding: 15px 20px;
}




@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .purchase_audio_title h3 {
      font-weight: bold;
      font-size: 20px;
      color: #34495e;
    }
    h3 {
      font-weight: bold;
      font-size: 20px;
    }



}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .purchase_audio_title h3 {
      font-weight: bold;
      color: #34495e;
    }

    h3 {
      font-weight: bold;
    }

}


</style>