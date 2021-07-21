<template>
  <div class="container">

    <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>

    <div v-if="!loading">
      <h2>出金管理</h2>

      <ul id="myTabs" class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#home"  aria-controls="home" role="tab" data-toggle="tab" class="nav-link active">出金申請中</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" class="nav-link">出金済</a></li>
      </ul>

      <div class="tab-content p-2">

        <div role="tabpanel" class="tab-pane active fade show" id="home">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">申請ユーザー</th>
                <th scope="col">申請日時</th>
                <th scope="col">申請金額</th>
                <th scope="col">アクション</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(pretransferRecord, i) in pretransferRecords" :key="i">
                <th scope="row">{{i + 1}}</th>
                <td><a href @click="$router.push({ name: 'usershow', params: { id: `${pretransferRecord.user_id}` }})">{{pretransferRecord.user.name}}</a></td>
                <td>{{pretransferRecord.created_at | fromiso}}</td>
                <td><i class="fas fa-yen-sign"></i>{{pretransferRecord.price | comma}}</td>
                <td><button type="button" class="btn btn-danger text-white font-weight-bold" @click="payment(pretransferRecord.user_id, pretransferRecord.id)">入金する</button></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="profile">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">申請ユーザー</th>
                <th scope="col">申請日時</th>
                <th scope="col">申請金額</th>
                <th scope="col">入金日時</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(transferRecord, i) in transferRecorded" :key="i">
                <th scope="row">{{i + 1}}</th>
                <td><a href @click="$router.push({ name: 'usershow', params: { id: `${transferRecord.user_id}` }})">{{transferRecord.user.name}}</a></td>
                <td>{{transferRecord.created_at | fromiso}}</td>
                <td><i class="fas fa-yen-sign"></i>{{transferRecord.price | comma}}</td>
                <td>{{transferRecord.updated_at | fromiso}}</td>
              </tr>
            </tbody>
          </table>
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
      purchaseRecords: [], //全ての購入履歴データ
      transferRecords:[], //全ての申請データ
      pretransferRecords:[], //振込前の申請データ
      transferRecorded:[], //振込後の申請データ
      options: {
          duration: 1500,
          type: 'info'
      }

    }
  },
  methods: {
    async getPurchaseData() {
      try{
        this.loading = true
        await this.$store.dispatch('purchase/getAllPurchase')


        //全ての購入履歴データ
        this.purchaseRecords = this.$store.state.purchase.allPurchases.purchaseRecords

        //全ての申請履歴データ
        this.transferRecords = this.$store.state.purchase.allPurchases.transferRecords

        // 振込前の申請データ
        this.pretransferRecords = this.transferRecords.filter(o => o.status == 0)
        // 振込後の申請データ
        this.transferRecorded = this.transferRecords.filter(o => o.status == 1)

      }
      catch(e){
        // console.log(e);
        this.loading = false
      }
      finally{
        this.loading = false
      }
    },
    async payment(userId, transferRecordId) {
      let res = confirm("「入金済」にステータスを変更してもよろしいですか？");

      if( res == true ) {
        try{
          this.loading = true
          await this.$store.dispatch('purchase/adminPayment', {userId: userId, transferRecordId: transferRecordId})
        }
        catch(e){
          // console.log(e);
          this.loading = false
        }
        finally{
          this.loading = false
          this.toasted()
          this.getPurchaseData()
        }
      } else {
        return
      }

    },
    toasted() {
      this.$toasted.show('保存しました。', this.options);
    },
  },
  created() {
    Promise.all([
      this.getPurchaseData(),
    ])
  },

}
</script>

<style>

</style>