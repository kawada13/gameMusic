<template>
  <div>

    <div class="" v-if="!loading">
      <div class="card listing_audio">
        <h2 class="card-header tt">
          出品オーディオ
        </h2>
        <div class="no_audio mt-4" v-if="!audios.length">
          <p>登録されているオーディオはまだありません。</p>
        </div>

        <div class="d-flex justify-content-center my-3" v-for="(audio,i) in getItems" :key="i">
              <div class="card mx-2" style="width: 100%;">
                <div class="card-body buy">

                  <div class="d-flex justify-content-between first text-center">
                     <p class="title" @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` } })">{{audio.title}}</p>
                     <p class="price"><i class="fas fa-yen-sign"></i>{{audio.price | comma}}</p>
                  </div>

                  <div class="second">
                    <h6 class="card-subtitle mb-2 text-muted creater_name">出品日:{{audio.created_at | fromiso}}</h6>
                  </div>

                  <div class="third d-flex justify-content-end">
                    <button type="button" class="btn btn-success text-white ml-2" @click="$router.push({ name: 'audio-edit', params: { id: `${audio.id}` }})">編集</button>
                    <button type="button" class="btn btn-danger text-white ml-2" @click="del(audio.id)">削除</button>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      options: { //トーストオプション
          duration: 1500,
          type: 'success'
      },
      loading: false,
      audios:[],
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
            await this.$router.go({path: this.$router.currentRoute.path, force: true})
            this.toasted();
            // this.getExhibitedAudiosData()
            this.loading = false
          }
       }else {
         return
       }

    },
    async getExhibitedAudiosData() {
      try{
        this.loading = true
        await this.$store.dispatch('audio/getExhibitedAudios')
        this.audios = this.$store.state.audio.userAudios;
        this.paginateData.audios = this.$store.state.audio.userAudios;
      }
      catch(e){
        console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    toasted(){
      this.$toasted.show('削除しました', this.options);
    }
  },
  created() {
    Promise.all([
      this.getExhibitedAudiosData(),
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


.listing_audio .card-header {
  font-weight: bold;
}
.listing_audio button {
  margin-left: 0!important;
  margin-right: 15px;
}
.card .audio_title:hover {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: underline;
    cursor: pointer;
}

.no_audio {
  text-align: center;
}

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}


@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .listing i {
      margin-left: 209px;
    }

    .card-header {
      font-size: 25px;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .listing i {
      margin-left: 148px;
    }
}

</style>