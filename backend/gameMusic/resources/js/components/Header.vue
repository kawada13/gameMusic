<template>
  <div class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-2">
      <a class="navbar-brand header-logo" @click="$router.push({ name: 'home' })">Game Music</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <div class="navbar-nav navlink">

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-if="isAuth">
              <i class="fas fa-user-alt text-white"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" @click="$router.push({ name: 'profile' })">マイページ</a>
              <a class="dropdown-item" @click="logout()">ログアウト</a>
            </div>
          </li>


          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle aa" id="ii" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-if="isAuth">
              <button class="btn btn-default btn-lg btn-link" style="text-decoration: none;font-size:13px; padding: 0;">
                <span class="glyphicon glyphicon-envelope"><i class="far fa-bell text-white" style="font-size: 22px"></i></span>
                <span class="badge badge-light" v-if="announcements.filter(o => o.is_read == 0).length !== 0">{{announcements.filter(o => o.is_read == 0).length}}</span>
              </button>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="ii" v-if="announcements.length">

              <div v-for="(announcement, i) in announcements" :key="i">

                <!-- メッセージ表示 -->
                <a class="dropdown-item"  v-if="announcement.is_read == 0 && announcement.type == 0" @click="$router.push({ name: 'message', params: { id: `${announcement.from_user_id}` }})">
                  <p style="margin-bottom: 0; font-weight: bold;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>
                <a class="dropdown-item" v-if="announcement.is_read == 1 && announcement.type == 0" @click="$router.push({ name: 'message', params: { id: `${announcement.from_user_id}` }})">
                  <p style="margin-bottom: 0;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>

                <!-- お気に入り表示 -->
                <a class="dropdown-item"  v-if="announcement.is_read == 0 && announcement.type == 1" @click="$router.push({ name: 'audio-show', params: { id: `${announcement.to_audio}` } })">
                  <p style="margin-bottom: 0; font-weight: bold;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>
                <a class="dropdown-item" v-if="announcement.is_read == 1 && announcement.type == 1" @click="$router.push({ name: 'audio-show', params: { id: `${announcement.to_audio}` } })">
                  <p style="margin-bottom: 0;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>

                <!-- フォロー表示 -->
                <a class="dropdown-item"  v-if="announcement.is_read == 0 && announcement.type == 2" @click="$router.push({ name: 'user-show', params: { id: `${announcement.from_user_id}` }})">
                  <p style="margin-bottom: 0; font-weight: bold;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>
                <a class="dropdown-item" v-if="announcement.is_read == 1 && announcement.type == 2" @click="$router.push({ name: 'user-show', params: { id: `${announcement.from_user_id}` }})">
                  <p style="margin-bottom: 0;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>

                <!-- 購入 -->
                <a class="dropdown-item"  v-if="announcement.is_read == 0 && announcement.type == 3" @click="$router.push({ name: 'audio-show', params: { id: `${announcement.to_audio}` } })">
                  <p style="margin-bottom: 0; font-weight: bold;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>
                <a class="dropdown-item" v-if="announcement.is_read == 1 && announcement.type == 3" @click="$router.push({ name: 'audio-show', params: { id: `${announcement.to_audio}` } })">
                  <p style="margin-bottom: 0;">{{ announcement.title }}</p>
                  <small id="emailHelp" class="form-text text-muted">{{announcement.created_at | fromiso}}</small>
                </a>



              </div>
              

            </div>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="ii" v-if="!announcements.length">
              <a class="dropdown-item poyo">お知らせはまだありません。</a>
            </div>

          </li>




          <a class="nav-item nav-link text-white" @click="$router.push({ name: 'favorite-audios' })" v-if="isAuth" style="padding-left: 0;"><i class="far fa-star text-white mr-2"></i>お気に入り</a>
          <a class="nav-item nav-link text-white" @click="$router.push({ name: 'audio-create' })" v-if="isAuth"><i class="fas fa-music mr-2 text-white"></i>出品する</a>
          <a class="nav-item nav-link text-white" @click="$router.push({ name: 'register' })" v-if="isGuest">会員登録</a>
          <a class="nav-item nav-link text-white" @click="$router.push({ name: 'login' })" v-if="isGuest">ログイン</a>
          <a class="nav-item nav-link text-white" @click="guestLogin()" v-if="isGuest">ゲストユーザーログイン</a>
          <a class="nav-item nav-link text-white admin" v-if="isAdmin" @click="$router.push({ name: 'withdrawal' })">管理者ページ</a>
          <a class="nav-item nav-link text-white" @click="logout()" v-if="isAdmin">ログアウト</a>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      options: {
          duration: 1500,
          type: 'info'
      },
      chatRooms: [],
      messages:[],
      announcements: []
    }
  },
  methods: {
    async logout() {
      try{
        await this.$store.dispatch('auth/logout')
      }
      catch(e) {
        console.log(e);
      }
      finally{
        // トースト表示
        this.$router.go({path: this.$router.currentRoute.path, force: true})
        this.toasted()
      }
    },
    async guestLogin() {
     // サンクタム処理
       await axios.get('/sanctum/csrf-cookie')

      //  ログイン処理
       axios.post('/api/login', {
          email: 'guest@gmail.com',
          password: 'guestuser',
        })
        .then(response => {

            // ローカルストレージにログイン状態かどうかを保存
            localStorage.setItem("auth", "true");

            // storeにもログイン状態を保存
            this.$store.dispatch('auth/SET_IS_AUTH', true)

            // storeにユーザーデータを保存
            this.$store.dispatch('auth/setUser', response.data.user)


            // 最後にリダイレクト
            this.$router.push("/");

            // トースト表示
            this.guesttoasted();

            this.$router.go({path: this.$router.currentRoute.path, force: true})

          }
        )
        .catch(error => {
          // storeにもログイン状態を保存
          this.$store.dispatch('auth/SET_IS_AUTH', false)

          // storeにユーザーデータを保存
          this.$store.dispatch('auth/setUser', null)


          // アラート
          alert('ログインに失敗しました。');
        })

    },
    toasted() {
      this.$toasted.show('ログアウトしました', this.options);
    },
    guesttoasted() {
      this.$toasted.show('ゲストユーザーとしてログインしました。', this.options);
    },
    async getChatRoomsData() {
      try{
        await this.$store.dispatch('chat/getChatRooms')
        this.chatRooms = this.$store.state.chat.chatRooms
        this.messages = this.chatRooms.filter(o => {
          return o.count !== 0
        })
      }
      catch(e){
        console.log(e);
      }
      finally{
      }
    },
    async getAnnouncementData() {
      axios.get('/api/announcements')
      .then(res => {
        this.announcements = res.data.announcements
      })
    },
  },
  computed: {
    isAuth() {
      if(localStorage.getItem("auth")) {
        return true
      } else {
        return false
      }
    },
    isAdmin() {
      if(localStorage.getItem("admin")) {
        return true
      } else {
        return false
      }
    },
    isGuest() {
      if(!localStorage.getItem("admin") && !localStorage.getItem("auth")) {
        return true
      } else {
        return false
      }
    },
  },
  created() {
    Promise.all([
      this.getChatRoomsData(),
      this.getAnnouncementData(),
    ])
    Echo.channel('ChatRoomChannel')
        .listen('ChatPusher', (e) => {
            this.getChatRoomsData();
            this.getAnnouncementData();

        });
    Echo.channel('ChatReadChannel')
        .listen('ChatRegistered', (e) => {
            this.getAnnouncementData();
        });
  },

}


</script>


<style scoped>
.header-logo {
  font-size: 38px;
  cursor: pointer;
}
.navlink {
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
}
.btn-default >.badge-light{
   background:red;
   position:relative;
   top: -14px;
   left: -5px;
}
.aa:after {
  display: none!important;
}
.poyo:hover {
  cursor: auto;
}
</style>
