<template>
<div>


  <!-- ローディング中 -->
  <div class="my-5" v-if="loading">
    <Loader />
  </div>


  <div v-if="!loading">
    <div class="profile_title mb-4" v-if="!userloading">
        <h2 style="color: #34495e;">{{user.user.name}}さんの作品一覧</h2>
    </div>
      <!-- 作品一覧 -->
      <div class="card mt-2">
        <div class="card-header tt">
          オーディオ一覧
        </div>
        <div class="audios ml-4 my-3" v-for="(audio,i) in getItems" :key="i">
          <Audio
            :audioId="audio.id"
            :audioTitle="audio.title"
            :sampleAudioFile="audio.sample_audio_file"
            :isLogined="isLogined"
          />
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
</div>
</template>

<script>
import Audio from '../../components/Audio'

export default {
  components: {
    Audio,
  },
  data() {
    return {
      userloading: false,
      loading: false,
      paginateData: {
        audios: [],
        parPage: 10, //1ページに表示する件数
        currentPage: 1
      },
      user: {},
      isLogined: false
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
    isFavorite() {
      // そもそもログインしていなければ
      if(!this.user.authId) {
        return false
      }
        return true
    }
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async getAudioDatas() {
      try{
        this.loading = true
        await this.$store.dispatch('audio/getAudios', this.$route.params.id)
        this.paginateData.audios = this.$store.state.audio.user_audios
      }
      catch(e){
        // console.log(e);
        this.loading = false
      }
      finally{
        this.loading = false
      }
    },
    async getUserData() {
      try{
        this.userloading = true
        await this.$store.dispatch('user/getUserShow', this.$route.params.id)
        this.user = this.$store.state.user.user

        // 今時点でログインしているかどうかを確認
        if(!this.user.authId) {
        this.isLogined = false
        } else {
          this.isLogined = true
        }
      }
      catch(e){
        // console.log(e);
        this.userloading = false
      }
      finally{
        this.userloading = false
      }
    }
  },
  created() {
    Promise.all([
      this.getAudioDatas(),
      this.getUserData(),
    ])
  },

}
</script>

<style scoped>
.audios .audio_title:hover {
  cursor: pointer;
  text-decoration: underline;
}
.audios .creater_name:hover {
  cursor: pointer;
  text-decoration: underline;
}

audio {
  margin-bottom: 0!important;
}

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}



@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .profile_title h2 {
      font-size: 26px;
      font-weight: bold;
    }


}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .profile_title h2 {
      font-weight: bold;
    }

}


</style>