<template>
  <div>

    <div class="my-5" v-if="loading">
      <Loader />
    </div>

    <div class="recruitment-index" v-if="!loading">
      <div class="container">
        <div class="row my-3">


          <!-- 検索(左側) -->
        <div class="col-sm-4 col-xs-12" >
          <div class="card">
            <div class="card-body">
              <input type="text" class="form-control py-4" placeholder="キーワードを入力してください" v-model="form.keyword">
              <div class="buttn mt-4">
                <button class="btn btn-primary search text-white" type="submit" @click="search">検索する</button>
                <button class="btn btn-secondary reset text-white" type="submit" @click="reset">リセット</button>
              </div>
            </div>
          </div>
        </div>




          <div class="col-sm-8 col-xs-12">
            <h2 class="search_result_title">検索結果：<span class="text-primary">{{serchResult}}</span></h2>
            <hr>
            <div>
              <p class="search_result_text" v-if="paginateData.recruitments.length">検索に一致する募集が{{paginateData.recruitments.length}}件ありました。</p>
              <p class="search_result_text" v-if="!paginateData.recruitments.length">検索に一致する募集が見つかりませんでした。申し訳ございません。</p>
            </div>
            <div class="mt-5">
              <div class="card-deck row">
                <div class="col-sm-6" v-for="(recruitment, i) in getItems" :key="i">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title" @click="$router.push({ name: 'recruitment_show', params: { id: `${recruitment.id}` }})">{{ recruitment.title }}</h5>
                      <p class="card-text user_name"><small class="text-muted" @click="$router.push({ name: 'user-show', params: { id: `${recruitment.user_id}` }})">ユーザー名：{{recruitment.user.name}}</small></p>
                      <p class="card-text"><small class="text-muted">作成日時：{{recruitment.created_at | fromiso}}</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <!-- ページネーション -->
            <div class="pagination mt-5 d-flex justify-content-center">
              <div v-if="paginateData.recruitments.length">
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
     </div>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      paginateData: {
        recruitments: [],
        parPage: 10, //1ページに表示する件数
        currentPage: 1
      },
      form:{
        keyword: ''
      },
      serchTitle:{ //検索結果を表示させるためのもの
        keyword: '',
      },
      serchResult: '', //検索結果のタイトル表示
    }
  },
  computed: {
    getItems() { //ページネーション用(1ページに表示する数)
        let current = this.paginateData.currentPage * this.paginateData.parPage;
        let start = current - this.paginateData.parPage;
        return this.paginateData.recruitments.slice(start, current);
    },
    getPageCount() {// ページネーション用(全体のページ数)
        return Math.ceil(this.paginateData.recruitments.length / this.paginateData.parPage);
    },
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async search() {
      this.setSerchTitle();

      this.$router.push({ query: {
        keyword: this.form.keyword,
      } })

      this.setSerchResult()

      try {
         this.loading = true
          await this.$store.dispatch('recruitment/getSearchRecruitments', this.form)
          this.paginateData.recruitments = this.$store.state.recruitment.recruitments
        }
        catch(e){
          console.log(e);
          this.loading = true
        }
        finally{
          this.loading = false
        }

    },
    async reset() {
      // フォームのリセット
      this.form = {
        keyword: '',
      }
      // フォームタイトルのリセット
      this.serchTitle = {
        keyword: '',
      }
      this.$router.push({ query: {
        keyword: '',
        } })
    },
    setSerchTitle() {
      // キーワード
      this.serchTitle.keyword = this.form.keyword
    },
    setSerchResult() {
      let result = []

      if(!this.form.keyword) {
        this.serchResult = '全募集一覧'
        return
      }

      if(this.serchTitle.keyword) {
        result.push(this.serchTitle.keyword)
      }
      this.serchResult = result.join(' | ')
    },
    async getRecruitmentsData() {
      try{
        this.loading = true
        await this.$store.dispatch('recruitment/getSearchRecruitments', this.form)
        this.paginateData.recruitments = this.$store.state.recruitment.recruitments
      }
      catch(e){
        this.loading = false
        console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    setFrom() {
      // キーワード
      this.form.keyword = this.$route.query.keyword 
    },
    searchSet() {
      if(this.$route.query.keyword) {
        this.serchTitle.keyword = this.$route.query.keyword
      }
    },
  },
  created() {
    Promise.all([
      this.setFrom(),
      this.searchSet(),
      this.setSerchResult(),
      this.getRecruitmentsData(),
    ])
  },

}
</script>

<style scoped>


.search_result_text{
  font-weight: bold;
  text-align: center;
}

.recruitment_text {
  font-weight: 700;
  font-size: 14px;
}

.user_name {
  cursor: pointer;
}
.user_name:hover {
  text-decoration: underline;
}

.card-title:hover {
  cursor: pointer;
  text-decoration: underline;
}


@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .recruitment_title {
      font-weight: 700;
      font-size: 24px;
      color: #3490dc;
    }

    .search_result_title {
      font-weight: bold;
      color: #566985;
      font-size: 24px;
    }
    .search_result_title span{
      font-weight: bold;
      color: #566985;
      font-size: 20px;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .recruitment_title {
      font-weight: 700;
      font-size: 28px;
      color: #3490dc;
    }

    .search_result_title {
      font-weight: bold;
      color: #566985;
      font-size: 32px;
    }
    .search_result_title span{
      font-weight: bold;
      color: #566985;
      font-size: 28px;
    }

}


</style>