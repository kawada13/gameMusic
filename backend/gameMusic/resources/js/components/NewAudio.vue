<template>
  <div class="new-audio">

    <div class="container">
      <div class="content-top-title">
        <h1>新着オーディオ</h1>
      </div>

      <div class="my-5" v-if="loading">
        <Loader />
      </div>

      <div class="mt-5" v-if="!loading">
        <div class="card-deck row">
          <div class="col-sm-4" v-for="(audio, i) in audios" :key="i">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title" @click="$router.push({ name: 'audio-show', params: { id: `${audio.id}` } })">{{ audio.title }}</h5>
                <div class="d-flex justify-content-start">
                  <div>
                    <img :src="audio.user.user_information.profile_image" class="rounded-circle" v-if="audio.user.user_information.profile_image">
                    <img src="/images/default_img.png" class="rounded-circle mr-2" v-else>
                  </div>
                  <div>
                    <p class="card-textt "><small class="text-muted" @click="$router.push({ name: 'user-show', params: { id: `${audio.user_id}` }})">作成者:{{audio.user.name}}</small></p>
                    <p class="card-text"><small class="text-muted" >作成日時:{{audio.updated_at | fromiso}}</small></p>
                  </div>
                </div>
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
      loading:false,
      audios:[]
    }
  },
  methods: {
    async getAudioDatas() {
      try{
        this.loading = true
        await this.$store.dispatch('audio/getAudioNew')
        this.audios = this.$store.state.audio.newAudios;
      }
      catch(e){
        this.loading = false
        // console.log(e);
      }
      finally{
        this.loading = false
      }
    }
  },
  created() {
    Promise.all([
      this.getAudioDatas(),
    ])
  },

}
</script>

<style scoped>
.new-audio {
  height: auto;
  background: #F6F6F4;
}

.content-top-title h1{
  font-weight: bold;
  color: #566985;
}
.card {
  overflow: hidden;
  width: 100%;
}
.card .card-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: bold;
}
.card .card-textt:hover {
    text-decoration: underline;
    cursor: pointer;
}
.card .card-title:hover {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: underline;
    cursor: pointer;
}
.card-textt {
  margin-bottom: 0;
}

img {
  width: 45px;
}


@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    .content-top-title {
      text-align: center;
      margin-top: 215px;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    .content-top-title {
      text-align: center;
      margin-top: 210px;
    }

}
</style>