<template>

<div>

  <!-- ローディング中 -->
  <div class="mt-5" v-if="loading">
    <Loader />
  </div>

  <div v-if="!loading">
   <div class="head">
      <h4 style="color: #34495e;">オーディオ商品登録</h4>
    </div>

    <div class="mb-5 d-flex justify-content-end fee">
        <a class="font-weight-bold text-danger saleshistory" @click="$router.push({ name: 'guide-payment' })">出品・手数料等について<i class="fas fa-chevron-right pl-2"></i></a>
    </div>



    <div class="form_creater_edit my-5">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="upload">

            <div class="form-group">
              <div><label for="title" class="weight">タイトル<span class="badge badge-danger ml-2">必須</span></label></div>
              <input type="text" class="form-control form-control-lg" id="title" v-model="formInfo.title">
              <div class="d-flex justify-content-start"><small class="form-text text-muted">タイトルは100文字以内です。</small></div>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.title.required">
                入力は必須です！
              </div>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.title.size">
                100文字以内でお願いします！
              </div>
            </div>

            <div class="form-group">
              <label for="content" class="weight">説明</label>
              <textarea class="form-control" id="content" v-model="formInfo.content" rows="3"></textarea>
              <div class="d-flex justify-content-start"><small class="form-text text-muted">説明文はは250文字以内です。</small></div>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.content.size">
                250文字以内でお願いします！
              </div>
            </div>

            <div class="form-group">
              <div><label for="price" class="weight">価格<span class="badge badge-danger ml-2">必須</span></label></div>
              <input type="number" class="form-control form-control-lg" id="price" v-model="formInfo.price">
              <div class="d-flex justify-content-start"><small class="form-text text-muted">設定価格は¥100~になります。</small></div>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.price.required">
                入力は必須です！
              </div>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.price.min">
                設定価格は¥100~でお願いします！
              </div>
            </div>

            <div class="form-group">
              <div><label class="weight">オーディオをアップロード</label><span class="badge badge-danger ml-2">必須</span></div>
              <label class="input-group-btn">
                <span class="btn btn-secondary">
                    ファイルを選択<input type="file" style="display:none" @change="fileSelected" accept="audio/*">
                </span>
                <span>{{formInfo.audio.url}}</span>
              </label>
              <div class="d-flex justify-content-start"><small class="form-text text-muted">ファイル形式は MP3 のみアップロードできます。</small></div>
              <div class="d-flex justify-content-start"><small class="form-text text-muted">ファイルの上限サイズは7MBです。</small></div>
              <div class="alert alert-danger" role="alert" v-if="errors.audio_url.required">
                ファイルが選択されていません!
              </div>
              <div class="alert alert-danger" role="alert" v-if="errors.audio_url.isFile">
                ファイル形式は MP3 ファイルのみです!
              </div>
              <div class="alert alert-danger" role="alert" v-if="errors.audio_url.size">
                ファイルの上限サイズ7MBを超えています!
              </div>
            </div>

            <div class="form-group select">
              <div><label for="sound" class="weight">サウンド<span class="badge badge-danger ml-2">必須</span></label></div>
              <select class="custom-select select-music" id="inputGroupSelect" v-model="formInfo.sound">
                <option v-for="(sound,i) in sounds" :key="i" :value="sound.id">{{sound.name}}</option>
              </select>
              <div class="alert alert-danger mt-2" role="alert" v-if="errors.sound.required">
                選択は必須です！
              </div>
            </div>

            <div class="form-group">
              <div class="types">
                <div class="card-body sound_title d-flex title justify-content-start">
                <p class="">イメージ(複数選択できます)</p>
                </div>
                <div class="card-body detail">
                  <label v-for="(understanding,i) in understandings" :key="i"><input type="checkbox" :value="understanding.id" v-model="formInfo.understanding"><span>{{understanding.name}}</span></label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="types">
                <div class="card-body sound_title d-flex title justify-content-start">
                <p class="">シーン(複数選択できます)</p>
                </div>
                <div class="card-body detail">
                  <label v-for="(use,i) in uses" :key="i"><input type="checkbox" :value="use.id" v-model="formInfo.use"><span>{{use.name}}</span></label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="types">
                <div class="card-body sound_title d-flex title justify-content-start">
                <p class="">使用機材(複数選択できます)</p>
                </div>
                <div class="card-body detail">
                  <label v-for="(instrument,i) in instruments" :key="i"><input type="checkbox" :value="instrument.id" v-model="formInfo.instrument"><span>{{instrument.name}}</span></label>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary my-4 store mr-5">保存<i class="fas fa-chevron-right pl-2"></i></button>
            <button type="button" class="btn btn-primary my-4 cancel" @click="cancel">戻る</button>
          </form>
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
      errors: { //バリデーションエラー
        title: {
          required: false,
          size: false,
        },
        content: {
          size: false,
        },
        price: {
          required: false,
          min: false,
        },
        audio_url: {
          required: false,
          isFlie: false,
          size: false
        },
        sound: {
          required: false
        }
      },
      formInfo: {
        title: '',
        content: '',
        price: '',
        audio:{
          url: '', //ファイル名
          file_info:'' //ファイル情報
        },
        sound:'',
        understanding:[],
        use: [],
        instrument: []
      },



      sounds: [ //サウンド選択肢
      ],
      understandings: [], //understandings関連選択肢
      uses: [],
      instruments: [],
    }
  },
  methods: {
    fileSelected(e) {
      this.formInfo.audio.file_info = e.target.files[0]

      // エラー初期化
      this.errors.audio_url.isFile = false
      // 代入(名前表示のためのみ)
      this.formInfo.audio.url = this.formInfo.audio.file_info.name

      // バリデーション初期化
      this.errors.audio_url.isFile = false
      this.errors.audio_url.size = false

      // mp3のみを許可するバリデーション
      if (this.formInfo.audio.file_info.type != 'audio/mpeg') {
        this.errors.audio_url.isFile = true
      }
      // ファイルサイズ7MB以下のみを許可するバリデーション
      if (this.formInfo.audio.file_info.size > 7000000) {
        this.errors.audio_url.size = true
      }
    },
    async upload() {

      // 初期化
      this.errors.title.required = false
      this.errors.title.size = false
      this.errors.content.size = false
      this.errors.price.required = false
      this.errors.price.min = false
      this.errors.audio_url.required = false
      this.errors.audio_url.isFlie = false
      this.errors.audio_url.size = false
      this.errors.sound.required = false

      // バリデーション
      this.validate();
      if(this.errors.title.required || this.errors.title.size || this.errors.content.required || this.errors.content.size ||this.errors.price.required ||this.errors.price.min || this.errors.audio_url.required || this.errors.audio_url.isFile || this.errors.audio_url.size)
      {
        return
      }
      console.log('アップロード！');

      let data = new FormData();
      data.append("title", this.formInfo.title);
      data.append("content", this.formInfo.content);
      data.append("price", this.formInfo.price);
      data.append("audio_file", this.formInfo.audio.file_info);
      data.append("sound_id", this.formInfo.sound);
      data.append("understanding", this.formInfo.understanding);
      data.append("use", this.formInfo.use);
      data.append("instrument", this.formInfo.instrument);


      try {
        this.loading = true
        await this.$store.dispatch('audio/createAudio', data)
      }
      catch(e){
        this.loading = false
      }
      finally{
        this.toasted();
        this.$router.push({ name: 'exhibited-audios' })
        this.loading = false
      }


    },
    validate() {

      if (!this.formInfo.title) {
        this.errors.title.required = true
      }
      if (this.formInfo.title.length > 100) {
        this.errors.title.size = true
      }
      if (this.formInfo.content.length > 250) {
        this.errors.content.size = true
      }
      if (!this.formInfo.price) {
        this.errors.price.required = true
      }
      if (this.formInfo.price < 100) {
        this.errors.price.min = true
      }
      if (!this.formInfo.audio.url) {
        this.errors.audio_url.required = true
      }
      if (!this.formInfo.sound) {
        this.errors.sound.required = true
      }

    },
    cancel() {
      this.$router.push({ name: 'exhibited-audios'})
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
    toasted(){
      this.$toasted.show('保存しました', this.options);
    }
  },
  created() {
    Promise.all([
      this.getSoundData(),
      this.getUnderstandingData(),
      this.getUseData(),
      this.getInstrumentData(),
    ])
  },

}
</script>


<style scoped>
.fee a:hover {
  cursor: pointer;
}
.head h4 {
  font-weight: bold;
}
.store{
  color: white;
  font-weight: bold;
  padding: 12px 30px;
  border-radius: 20px;
}
.cancel{
  color: white;
  font-weight: bold;
  padding: 12px 30px;
  border-radius: 20px;
  background: #4DB7FE;
}
.weight {
  font-weight: bold;
}

.types .title {
  padding: 10px 0 0 0;
}
.types .title p{
  font-weight: bold;
}


.detail {
  padding-left: 0;
  padding-bottom: 0;

}
.detail label {
    margin-right: 5px; /* ボタン同士の間隔 */
    margin-bottom: 20px;
}
.detail label input {
    display: none; /* デフォルトのinputは非表示にする */
}
.detail label span {
    color: #333; /* 文字色を黒に */
    font-size: 14px; /* 文字サイズを14pxに */
    border: 1px solid #333; /* 淵の線を指定 */
    border-radius: 20px; /* 角丸を入れて、左右が丸いボタンにする */
    padding: 5px 20px; /* 上下左右に余白をトル */
    cursor: pointer;
}
.detail label input:checked + span {
    color: #FFF; /* 文字色を白に */
    background: #333; /* 背景色を薄い赤に */
    border: 1px solid #333; /* 淵の線を薄い赤に */
    cursor: pointer;
}

</style>