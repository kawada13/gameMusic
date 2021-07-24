<template>
  <div>

    <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <div class="card mt-2" v-if="!loading">
        <h3 class="card-header tt">
          お気に入り作品一覧
        </h3>
        <p class="favorite_cont ml-3 mt-3 mb-5">お気に入り件数：{{audios.length}}件</p>
        <div class="no_favorite_audio my-4" v-if="!audios.length">
          <p>現在お気に入りしているオーディオはございません。</p>
        </div>



        <!-- <div class="audios my-3" v-for="(audio,i) in audios" :key="i">
          <h4 class="audio_title " @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` } })">{{ audio.title }}</h4>
          <h6 class="card-subtitle mb-2 text-muted creater_name" @click="$router.push({ name: 'user-show', params: { id: `${audio.user.id}` }})">クリエイター：{{audio.user.name}}</h6>
          <h6 class="card-subtitle mb-2 price font-weight-bold text-danger"><i class="fas fa-yen-sign"></i>{{audio.price | comma}}</h6>
          <audio controls controlslist="nodownload" class="my-3">
            <source :src="audio.sample_audio_file">
          </audio>
        </div> -->


        <div class="d-flex justify-content-center my-3" v-for="(audio,i) in getItems" :key="i">
              <div class="card mx-2" style="width: 100%;">
                <div class="card-body buy">

                  <div class="d-flex justify-content-between first text-center">
                     <p class="title" @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` } })">{{audio.title}}</p>
                     <p class="price"><i class="fas fa-yen-sign"></i>{{audio.price | comma}}</p>
                  </div>

                  <div class="second">
                    <p class="creater" @click="$router.push({ name: 'user-show', params: { id: `${audio.user_id}` }})">クリエイター：<a class="link">{{audio.user.name}}</a></p>
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
      loading: false,
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
    async getFavoriteAudios() {
      try{
        this.loading = true
        await this.$store.dispatch('favorite/lists')
        this.audios = this.$store.state.favorite.favoriteAudios
        this.paginateData.audios = this.$store.state.favorite.favoriteAudios
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
      this.getFavoriteAudios(),
    ])
  },

}
</script>

<style scoped>
audio {
  margin-bottom: 0!important;
}

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

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}


.audios .audio_title:hover {
  cursor: pointer;
  text-decoration: underline;
}
.audios .creater_name:hover {
  cursor: pointer;
  text-decoration: underline;
}
.favorite_cont {
  font-weight: 600;
  font-size: 18px;
  color: #334e6f;
}
.no_favorite_audio {
  text-align: center;
}

@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .audios {
      margin-left: 10px;
    }

    h3 {
      font-weight: bold;
      font-size: 20px;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/
    .audios {
      margin-left: 20px;
    }

    h3 {
      font-weight: bold;
    }

}

</style>