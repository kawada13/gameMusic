<template>
<div>
  
  <!-- ローディング中 -->
  <div class="my-5" v-if="loading">
    <Loader />
  </div>
  <div class="audio-index" v-if="!loading">
    <div class="container">
      <div class="row my-3">

        <!-- 検索(左側) -->
        <div class="col-sm-4 col-xs-12" >
          <div class="card">
            <div class="card-body">
              <input type="text" class="form-control py-4" placeholder="キーワードを入力してください" v-model="form.keyword">
              <select class="custom-select select-music mt-3" id="inputGroupSelect" v-model="form.sound">
                <option value="" disabled selected>サウンドを選択</option>
                <option v-for="(sound,i) in sounds" :key="i" :value="sound.id">{{sound.name}}</option>
              </select>
              <select class="custom-select mt-3" id="inputGroupSelect" v-model="form.understanding">
                <option value="" disabled selected>イメージを選択</option>
                <option v-for="(understanding,i) in understandings" :key="i" :value="understanding.id">{{understanding. name}}</option>
              </select>
              <select class="custom-select mt-3" id="inputGroupSelect" v-model="form.use">
                <option value="" disabled selected>用途を選択</option>
                <option v-for="(use,i) in uses" :key="i" :value="use.id">{{use.name}}</option>
              </select>
              <select class="custom-select mt-3" id="inputGroupSelect" v-model="form.instrument">
                <option value="" disabled selected>使用機材を選択</option>
                <option v-for="(instrument,i) in instruments" :key="i" :value="instrument.id">{{instrument.name}}</option>
              </select>
              <div class="buttn mt-4">
                <button class="btn btn-primary search" type="submit" @click="search">検索する</button>
                <button class="btn btn-secondary reset" type="submit" @click="reset">リセット</button>
              </div>
            </div>
          </div>
        </div>

        <!-- 検索結果一覧(右側) -->
        <div class="col-sm-8 col-xs-12">
          <h2 class="search_result_title">検索結果：<span class="text-primary">{{serchResult}}</span></h2>
          <hr>
          <div v-if="!audioLoading">
            <p class="search_result_text" v-if="paginateData.audios.length">検索に一致するオーディオが{{paginateData.audios.length}}件ありました。</p>
            <p class="search_result_text" v-if="!paginateData.audios.length">検索に一致するオーディオが見つかりませんでした。申し訳ございません。</p>
          </div>
          <div class="mt-5">
            <div class="card-deck row">
              <div class="col-sm-6" v-for="(audio, i) in getItems" :key="i">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title" @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` }  })">{{ audio.title }}</h5>
                    <div class="d-flex justify-content-start">
                      <div>
                        <img :src="audio.user.user_information.profile_image" class="rounded-circle" v-if="audio.user.user_information.profile_image">
                        <img src="/images/default_img.png" class="rounded-circle mr-2" v-else>
                      </div>
                      <div>
                        <p class="card-textt "><small class="text-muted" @click="$router.push({ name: 'user-show', params: { id: `${audio.user_id}` }})">作成者:{{audio.user.name}}</small></p>
                        <p class="card-texttt"><small class="text-muted" >作成日時:{{audio.updated_at | fromiso}}</small></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ページネーション -->
          <div class="pagination mt-5 d-flex justify-content-center" v-if="!audioLoading">
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
  </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      audioLoading: false,
      sounds: [],
      understandings: [],
      uses: [],
      instruments: [],
      form: { //実際にAPI通信に使うやつ
        keyword: '',
        sound: '',
        understanding: '',
        use: '',
        instrument: '',
      },
      serchTitle:{ //検索結果を表示させるためのもの
        keyword: '',
        sound: '',
        understanding: '',
        use: '',
        instrument: '',
      },
      serchResult: '', //検索結果のタイトル表示
      paginateData: {
        audios: [],
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
    }
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    reset() {
      // フォームのリセット
      this.form = {
        keyword: '',
        sound: '',
        understanding: '',
        use: '',
        instrument: '',
      }
      // フォームタイトルのリセット
      this.serchTitle = {
        keyword: '',
        sound: '',
        understanding: '',
        use: '',
        instrument: '',
      }
      this.$router.push({ query: {
        keyword: '',
        sound: '',
        understanding: '',
        use: '',
        instrument: '',
        } })
    },
    async search() {
      this.setSerchTitle();

      this.$router.push({ query: {
        keyword: this.form.keyword,
        sound: this.serchTitle.sound,
        understanding: this.serchTitle.understanding,
        use: this.serchTitle.use,
        instrument: this.serchTitle.instrument,
        } })
       this.setSerchResult()
       try {
         this.loading = true
          await this.$store.dispatch('audio/getSearchAudios', this.form)
          this.paginateData.audios = this.$store.state.audio.audios
        }
        catch(e){
          console.log(e);
          this.loading = true
        }
        finally{
          this.loading = false
        }
    },
    setSerchResult() {
      let result = []

      if(!this.form.keyword && !this.form.sound && !this.form.understanding && !this.form.use && !this.form.instrument) {
        this.serchResult = '全オーディオ一覧'
        return
      }

      if(this.serchTitle.keyword) {
        result.push(this.serchTitle.keyword)
      }
      if(this.serchTitle.sound) {
        result.push(this.serchTitle.sound)
      }
      if(this.serchTitle.understanding) {
        result.push(this.serchTitle.understanding)
      }
      if(this.serchTitle.use) {
        result.push(this.serchTitle.use)
      }
      if(this.serchTitle.instrument) {
        result.push(this.serchTitle.instrument)
      }
      this.serchResult = result.join(' | ')
    },
    async getSoundData() {
      try{
        this.loading = true
        await this.$store.dispatch('soundType/getSound')
        this.sounds = this.$store.state.soundType.sound;
      }
      catch(e){
        // console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    async getUnderstandingData() {
      try{
        this.loading = true
        await this.$store.dispatch('soundType/getUnderstanding')
        this.understandings = this.$store.state.soundType.understanding;
      }
      catch(e){
        // console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    async getUseData() {
      try{
        this.loading = true
        await this.$store.dispatch('soundType/getUse')
        this.uses = this.$store.state.soundType.use;
      }
      catch(e){
        // console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    async getInstrumentData() {
      try{
        this.loading = true
        await this.$store.dispatch('soundType/getInstrument')
        this.instruments = this.$store.state.soundType.instrument;
      }
      catch(e){
        // console.log(e);
      }
      finally{
        this.loading = false
      }
    },
    async getAudioDatas() {
      try{
        this.audioLoading = true
        await this.$store.dispatch('audio/getSearchAudios', this.form)
        this.paginateData.audios = this.$store.state.audio.audios
      }
      catch(e){
        // console.log(e);
        this.audioLoading = false
      }
      finally{
        this.audioLoading = false
      }
    },
    searchSet() {
      if(this.$route.query.keyword) {
        this.serchTitle.keyword = this.$route.query.keyword
      }
      if(this.$route.query.sound) {
        this.serchTitle.sound = this.$route.query.sound
      }
      if(this.$route.query.understanding) {
        this.serchTitle.understanding = this.$route.query.understanding
      }
      if(this.$route.query.use) {
        this.serchTitle.use = this.$route.query.use
      }
      if(this.$route.query.instrument) {
        this.serchTitle.instrument = this.$route.query.instrument
      }

    },
    // めちゃくちゃ強引なセットパラメーター笑⇦後でリファクタする必要あり
    setSerchTitle() {
      // キーワード
      this.serchTitle.keyword = this.form.keyword
      // サウンド
      if(this.form.sound == 1) {
        this.serchTitle.sound = 'BGM'
      }
      if(this.form.sound == 2) {
        this.serchTitle.sound = 'SE'
      }
      if(this.form.sound == 3) {
        this.serchTitle.sound = 'ジングル'
      }
      if(this.form.sound == 4) {
        this.serchTitle.sound = '声'
      }
      // イメージ
      if(this.form.understanding == 1) {
        this.serchTitle.understanding = '華やか'
      }
      if(this.form.understanding == 2) {
        this.serchTitle.understanding = '悲しい'
      }
      if(this.form.understanding == 3) {
        this.serchTitle.understanding = '楽しい'
      }
      if(this.form.understanding == 4) {
        this.serchTitle.understanding = '未来'
      }
      if(this.form.understanding == 5) {
        this.serchTitle.understanding = 'ホラー'
      }
      if(this.form.understanding == 6) {
        this.serchTitle.understanding = '驚き'
      }
      // 用途
      if(this.form.use == 1) {
        this.serchTitle.use = 'バトル'
      }
      if(this.form.use == 2) {
        this.serchTitle.use = '恋愛'
      }
      if(this.form.use == 3) {
        this.serchTitle.use = '過去'
      }
      if(this.form.use == 4) {
        this.serchTitle.use = '未来'
      }
      if(this.form.use == 5) {
        this.serchTitle.use = '日常'
      }
      if(this.form.use == 6) {
        this.serchTitle.use = '探索'
      }
      // 使用機材
      if(this.form.instrument == 1) {
        this.serchTitle.instrument = 'アコースティックギター'
      }
      if(this.form.instrument == 2) {
        this.serchTitle.instrument = 'エレキギター'
      }
      if(this.form.instrument == 3) {
        this.serchTitle.instrument = 'ベース'
      }
      if(this.form.instrument == 4) {
        this.serchTitle.instrument = 'ドラム'
      }
      if(this.form.instrument == 5) {
        this.serchTitle.instrument = 'ピアノ'
      }
      if(this.form.instrument == 6) {
        this.serchTitle.instrument = 'オルガン'
      }
      if(this.form.instrument == 7) {
        this.serchTitle.instrument = 'フルート'
      }
      if(this.form.instrument == 8) {
        this.serchTitle.instrument = 'サックス'
      }
      if(this.form.instrument == 9) {
        this.serchTitle.instrument = 'バイオリン'
      }
      if(this.form.instrument == 10) {
        this.serchTitle.instrument = 'その他'
      }
    },
    setFrom() {
      // キーワード
      this.form.keyword = this.$route.query.keyword 
      // サウンド
      if(this.serchTitle.sound == 'BGM') {
        this.form.sound = 1
      }
      if(this.serchTitle.sound == 'SE') {
        this.form.sound = 2
      }
      if(this.serchTitle.sound == 'ジングル') {
        this.form.sound = 3
      }
      if(this.serchTitle.sound == '声') {
        this.form.sound = 4
      }
      // イメージ
      if(this.serchTitle.understanding == '華やか') {
        this.form.understanding = 1
      }
      if(this.serchTitle.understanding == '悲しい') {
        this.form.understanding = 2
      }
      if(this.serchTitle.understanding == '楽しい') {
        this.form.understanding = 3
      }
      if(this.serchTitle.understanding == '未来') {
        this.form.understanding = 4
      }
      if(this.serchTitle.understanding == 'ホラー') {
        this.form.understanding = 5
      }
      if(this.serchTitle.understanding == '驚き') {
        this.form.understanding = 6
      }
      // 用途
      if(this.serchTitle.use == 'バトル') {
        this.form.use = 1
      }
      if(this.serchTitle.use == '恋愛') {
        this.form.use = 2
      }
      if(this.serchTitle.use == '過去') {
        this.form.use = 3
      }
      if(this.serchTitle.use == '未来') {
        this.form.use = 4
      }
      if(this.serchTitle.use == '日常') {
        this.form.use = 5
      }
      if(this.serchTitle.use == '探索') {
        this.form.use = 6
      }
      // 使用機材
      if(this.serchTitle.instrument == 'アコースティックギター') {
        this.form.instrument = 1
      }
      if(this.serchTitle.instrument == 'エレキギター') {
        this.form.instrument = 2
      }
      if(this.serchTitle.instrument == 'ベース') {
        this.form.instrument = 3
      }
      if(this.serchTitle.instrument == 'ドラム') {
        this.form.instrument = 4
      }
      if(this.serchTitle.instrument == 'ピアノ') {
        this.form.instrument = 5
      }
      if(this.serchTitle.instrument == 'オルガン') {
        this.form.instrument = 6
      }
      if(this.serchTitle.instrument == 'フルート') {
        this.form.instrument = 7
      }
      if(this.serchTitle.instrument == 'サックス') {
        this.form.instrument = 8
      }
      if(this.serchTitle.instrument == 'バイオリン') {
        this.form.instrument = 9
      }
      if(this.serchTitle.instrument == 'その他') {
        this.form.instrument = 10
      }
    }
  },
  created() {
    Promise.all([
      // こっから下は強引にやってるやつ
      this.searchSet(), //searchTitleにリロードしても値を保持させる
      this.setFrom(), //formにリロードしても値を保持させる
      this.setSerchResult(), //検索結果タイトルの値を保持させる
      // 選択肢のデータを取ってくる
      this.getSoundData(),
      this.getUnderstandingData(),
      this.getUseData(),
      this.getInstrumentData(),
      this.getAudioDatas(),
    ])
  },
}
</script>

<style scoped>
.audio-index {
  height: auto;
  background: #F6F6F4;
}
.buttn{
  text-align: center;
}
.buttn .search {
  color: white;
  font-weight: bold;
  margin-right: 12px;
}
.buttn .reset {
  font-weight: bold;
  color: white;
}


.search_result_text{
  font-weight: bold;
  text-align: center;
}

.card .card-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: bold;
}
.card .card-title:hover {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: underline;
    cursor: pointer;
}
.card .card-text:hover {
    text-decoration: underline;
    cursor: pointer;
}

.card-textt {
  margin-bottom: 0;
}
.card-textt:hover {
  margin-bottom: 0;
  cursor: pointer;
  text-decoration: underline;
}

img {
  width: 45px;
}

.card-texttt:hover {
  cursor: auto;
  text-decoration: none;
}

@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

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