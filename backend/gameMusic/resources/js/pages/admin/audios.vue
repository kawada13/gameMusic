<template>
  <div class="container">

     <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <div class="card mt-2" v-if="!loading">
        <h3 class="card-header">
          登録オーディオ
        </h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">タイトル</th>
              <th scope="col">作成者</th>
              <th scope="col">登録日時</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(audio, i) in getItems" :key="i">
              <th scope="row">{{i + 1}}</th>
              <td><a href @click="$router.push({ name: 'audioshow', params: { id: `${audio.id}` }})">{{ audio.title }}</a></td>
              <td><a href @click="$router.push({ name: 'usershow', params: { id: `${audio.user_id}` }})">{{ audio.user.name }}</a></td>
              <td>{{audio.created_at | fromiso}}</td>
              <td class="btn btn-danger" @click="del(audio.id)">削除</td>
            </tr>
          </tbody>
        </table>
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
</template>

<script>
export default {
  data() {
    return {
      audios: [],
      loading: false,
      paginateData: {
        audios: [],
        parPage: 20, //1ページに表示する件数
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
    async del(id) {
       let conf = confirm('本当に削除しますか？');

       if(conf) {
          console.log('削除');
          try {
            this.loading = true
            await this.$store.dispatch('audio/getExhibitedAudioDelete', id)
          }
          catch(e){
            console.log(e);
            this.loading = false
          }
          finally{
            this.getAudiosData()
            this.loading = false
          }
       }else {
         return
       }

    },
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async getAudiosData() {
       this.isLogined = false
      try{
        this.loading = true
        await this.$store.dispatch('audio/getAllAudios')
        this.audios = this.$store.state.audio.allAudios
        this.paginateData.audios = this.$store.state.audio.allAudios

      }
      catch(e){
        // console.log(e);
        this.loading = false
      }
      finally{
        this.loading = false
      }
    },
  },

  created() {
    Promise.all([
      this.getAudiosData(),
    ])
  },

}
</script>

<style>

</style>