<template>
  <div class="container">
     <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>


    <div class="card mt-2" v-if="!loading">
        <h3 class="card-header">
          登録ユーザー
        </h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">ユーザー名</th>
              <th scope="col">メール</th>
              <th scope="col">登録日時</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, i) in getItems" :key="i">
              <th scope="row">{{i + 1}}</th>
              <td><a href @click="$router.push({ name: 'usershow', params: { id: `${user.id}` }})">{{ user.name }}</a></td>
              <td>{{user.email}}</td>
              <td>{{user.created_at | fromiso}}</td>
            </tr>
          </tbody>
        </table>
    </div>

    <!-- ページネーション -->
    <div class="pagination mt-5 d-flex justify-content-center">
      <div v-if="paginateData.users.length">
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
      loading:false,
      users: [],
      paginateData: {
        users: [],
        parPage: 20, //1ページに表示する件数
        currentPage: 1
      },
    }
  },
  computed: {
    getItems() { //ページネーション用(1ページに表示する数)
        let current = this.paginateData.currentPage * this.paginateData.parPage;
        let start = current - this.paginateData.parPage;
        return this.paginateData.users.slice(start, current);
    },
    getPageCount() {// ページネーション用(全体のページ数)
        return Math.ceil(this.paginateData.users.length / this.paginateData.parPage);
    },
  },
  methods: {
    clickCallback(pageNum) { //ページネーション用
      this.paginateData.currentPage = Number(pageNum);
    },
    async getUsersData() {
      try{
        this.loading = true
        await this.$store.dispatch('user/getUsers')
        this.users = this.$store.state.user.users
        this.paginateData.users = this.$store.state.user.users

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
      this.getUsersData(),
    ])
  },

}
</script>

<style>

</style>