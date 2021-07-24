<template>

  <div>

    <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <div class="card purchase_audio_title">
        <h3 class="card-header font-weight-bold application_title tt">
          振込申請履歴
        </h3>


        <div class="no_user my-4 text-center" v-if="this.paginateData.applications.length == 0">
          <p>現在振込申請履歴がありません。</p>
        </div>

        <div class="d-flex justify-content-center my-3 uriage" v-for="(application, i) in getItems" :key="i">
          <div class="card mx-2" style="width: 100%;">
            <div class="card-body uriage_detail">
              <p class="price text-danger">{{ application.price|comma }}円</p>
              <p class="card-subtitle mb-2 text-muted" >申請日：{{application.created_at | fromiso}}</p>
            </div>
          </div>
        </div>

          <!-- ページネーション -->
    <div class="pagination mt-5 d-flex justify-content-center">
      <div v-if="paginateData.applications.length">
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
      applications:[],
      loading: false,
      paginateData: {
        applications: [],
        parPage: 10, //1ページに表示する件数
        currentPage: 1
      },
    }
  },
  computed: {
    getItems() { //ページネーション用(1ページに表示する数)
        let current = this.paginateData.currentPage * this.paginateData.parPage;
        let start = current - this.paginateData.parPage;
        return this.paginateData.applications.slice(start, current);
    },
    getPageCount() {// ページネーション用(全体のページ数)
        return Math.ceil(this.paginateData.applications.length / this.paginateData.parPage);
    },
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async getApplicationData() {
      this.loading = true
      await axios.get(`/api/application/histroy`)
    .then(res => {
      this.paginateData.applications = res.data.transferRecords
      this.loading = false
    })
    .catch(e => {
      console.log(e);
      this.loading = false
    })
    }
  },
  created() {
    Promise.all([
      this.getApplicationData(),
    ])
  },


}
</script>

<style scoped>

.uriage_detail  {
  padding: 10px;
}
.uriage_detail p {
  margin-bottom: 0;
}

.uriage_detail .price {
  font-weight: bold;
  font-size: 25px;
}

.application_title {
  color: #34495e;
}
.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}
</style>