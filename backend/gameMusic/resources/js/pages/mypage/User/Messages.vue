<template>
  <div>
    <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <div class="card mt-2" v-if="!loading">
        <h3 class="card-header tt">
          メッセージ一覧
        </h3>
        <div class="no_user my-4 text-center" v-if="chatRooms.length == 0">
          <p>現在メッセージのやりとりがありません。</p>
        </div>

        <ul class="list-group list-group-flush follows">
          <li class="list-group-item" v-for="(chatRoom, i) in chatRooms" :key="i">

            <div class="row">

              <div class="col-sm-9 col-xs-12">
                <div class="d-flex justify-content-start">


                  <div v-if="chatRoom.user.user_information.profile_image" class="profile_image">
                    <img :src="chatRoom.user.user_information.profile_image" class="rounded-circle">
                  </div>
                  <div v-else class="profile_image">
                    <img src="/images/default_img.png" class="rounded-circle">
                  </div>

                  <div class="d-flex align-items-center ml-2">
                    <span class="creater_name">{{ chatRoom.user.name }}</span>
                  </div>
                </div>
              </div>

              <div class="col-sm-3 col-xs-12 d-flex align-items-center">
                <a type="button" class="btn btn-primary text-light" @click="$router.push({ name: 'message', params: { id: `${chatRoom.user_id}` }})">メッセージ<span class="badge badge-success ml-2" v-if="chatRoom.count !== 0">{{chatRoom.count}}</span></a>
              </div>

            </div>
          </li>
        </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading :false,
      chatRooms: []
    }
  },
  methods: {
    async getChatRoomsData() {
      try{
        this.loading = true
        await this.$store.dispatch('chat/getChatRooms')
        this.chatRooms = this.$store.state.chat.chatRooms
      }
      catch(e){
        this.loading = false
        console.log(e);
      }
      finally{
        this.loading = false
      }
    },
  },
  created() {
    Promise.all([
      this.getChatRoomsData(),
    ])
    Echo.channel('ChatRoomChannel')
        .listen('ChatPusher', (e) => {
            this.getChatRoomsData();

        });
  },

}
</script>

<style scoped>
.profile_image img {
  height: 45px;
}
.creater_name {
  font-weight: 700;
  font-size: 25px;
}

.tt {
  color: #34495e;
  font-weight: bold;
  background-color: #D9F0FE;
}

@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/

    h3 {
      font-weight: bold;
      font-size: 20px;
    }


}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/

    h3 {
      font-weight: bold;
    }

}
</style>




