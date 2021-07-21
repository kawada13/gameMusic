<template>
  <div>

     <!-- ローディング中 -->
    <div class="my-5" v-if="loading">
      <Loader />
    </div>

    <div class="" v-if="!loading">
      <div class="card-group mb-2">
        <div class="card text-center">
          <div class="card-body earnings">
            <h5 class="card-title">申請可能金額</h5>
            <h1 class="font-weight-bold text-danger"><i class="fas fa-yen-sign"></i>{{ earning | comma }}</h1>
          </div>
        </div>
        <div class="card text-center">
          <div class="card-body cumulative">
            <h5 class="card-title">累計の売上金額</h5>
            <h1 class="font-weight-bold"><i class="fas fa-yen-sign"></i>{{ cumulative | comma }}</h1>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!loading">
      
    

     <div class="d-flex justify-content-end fee">
          <a class="font-weight-bold text-primary saleshistory" @click="$router.push({ name: 'sales' })">売上履歴<i class="fas fa-chevron-right pl-2"></i></a>
    </div>
     <div class="mb-5 d-flex justify-content-end fee">
          <a class="font-weight-bold text-danger saleshistory" @click="$router.push({ name: 'guide-payment' })">振込申請・手数料について<i class="fas fa-chevron-right pl-2"></i></a>
    </div>

     <div class="transfer_account_information my-4">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title font-weight-bold text-primary">振込先口座</h4>

            <div class="button text-center mt-4" v-if="!transferAccount.id">
              <p>振込申請には、振込口座設定をお願いします。</p>
              <button type="button" class="btn btn-primary font-weight-bold text-white" @click="$router.push({ name: 'transfer-account-setting' })">振込先口座を登録</button>
            </div>

            <div class="mt-4" v-if="transferAccount.id">
              <table class="table">
                <tbody>
                  <tr>
                    <th scope="row">銀行名</th>
                    <td>{{transferAccount.bank_name}}</td>
                  </tr>
                  <tr>
                    <th scope="row">支店コード</th>
                    <td>{{transferAccount.bank_code}}</td>
                  </tr>
                  <tr>
                    <th scope="row">口座種別</th>
                    <td>{{transferAccount.deposit_type}}</td>
                  </tr>
                  <tr>
                    <th scope="row">口座番号</th>
                    <td>{{transferAccount.account_number}}</td>
                  </tr>
                  <tr>
                    <th scope="row">口座名義</th>
                    <td>{{transferAccount.account_holder}}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="button text-right" v-if="transferAccount.id">
              <button type="button" class="btn btn-primary font-weight-bold text-white" @click="$router.push({ name: 'transfer-account-setting' })">振込先口座を編集</button>
            </div>

          </div>
        </div>
      </div>


     <div class="transfer_account_information my-4" v-if="earning > 999">
        <div class="card">
          <div class="card-body d-flex justify-content-center">


            <v-dialog
            v-model="dialog"
            width="500"
          >
            <template v-slot:activator="{ on, attrs }">
              <button
                v-bind="attrs"
                v-on="on"
                class="btn btn-primary font-weight-bold text-white"
              >
                振込申請をする
              </button>
            </template>

            <v-card class="transfer_payment">
              <v-card-title class="text-h5 d-flex justify-content-around modal_audio">
                <div class="product_title">
                  振込申請
                </div>
                <div class="batsu" @click="dialog = false"><i class="fas fa-times size"></i></div>
              </v-card-title>
              <v-divider></v-divider>

              <v-card-text class="modal_audio_detail">
                <p class="application">申請金額：<i class="fas fa-yen-sign"></i>{{ earning|comma }}</p>
                <hr>
                <p class="transfere">振込金額：<i class="fas fa-yen-sign"></i>{{ transfer|comma }}</p>
                <p>※ 振込金額の計算は<a class="link"  @click="$router.push({ name: 'guide-payment' })">こちら</a>をもとに計算されております。</p>



              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  text
                  style="font-weight: bold;"
                >
                  申請する
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-app style="height: 0;"></v-app>

          </div>

        </div>
      </div>

     <div class="transfer_account_information my-4" v-else>
        <div class="card text-center">
          <p class="mt-3 text-danger">※ 申請可能金額が¥1,000以上の時のみ、振込申請が可能です。</p>
          <p class="text-danger">詳しくは<a class="link text-primary "  @click="$router.push({ name: 'guide-payment' })">こちら</a>からご確認お願いします。</p>
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
      sales:[],
      transferAccount: {},
      dialog: false,
    }
  },
  computed: {
    cumulative() { //累計金額
      // まずはpriceだけ取り出した配列を作成
      let price = this.sales.map(o => o.price)
      // その中身の合計を出す
      let total = price.reduce((sum, element) => sum + element, 0);
      return total
    },
    earning() { // 申請可能金額

      // まずはstatusが0(金額を引き出してない)もののみをfilter
      let notWithdraw = this.sales.filter(o => o.status == 0);
      // priceだけ取り出した配列を作成
      let price = notWithdraw.map(o => o.price);
      // その中身の合計を出す
      let total = price.reduce((sum, element) => sum + element, 0);

       return total
    },
    transfer() { // 振込金額
     if(this.earning < 50000) {
       let amount = Math.ceil((this.earning - (this.earning * 0.036)) * 0.9);
       let result = amount - 220;
       return result;
     } else {
       let amount = Math.ceil((this.earning - (this.earning * 0.036)) * 0.9);
       let result = amount - 440;
       return result;
     }
      
    }
  },
  methods: {
    async getSalesData() {
      try{
        this.loading = true
        await this.$store.dispatch('purchase/getSales')
        this.sales = this.$store.state.purchase.sales

      }
      catch(e){
        // console.log(e);
        this.loading = false
      }
      finally{
        this.loading = false
      }
    },
    async getTransferAccountData() {

      try{
        this.loading = true
        await this.$store.dispatch('transferAccount/show')
        this.transferAccount = this.$store.state.transferAccount.transferAccountInformation
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
      this.getSalesData(),
      this.getTransferAccountData(),
    ])
  },

}
</script>

<style scoped>

.link {
  color: red;
  font-weight: bold;
}
.link:hover {
  color: #81A6CB;
  cursor: pointer;
  text-decoration: underline;
}

.modal_audio_detail {
  padding-bottom: 0!important;
}

.modal_audio_detail .application {
  font-weight: bold;
  color: #34495e;
  font-size: 18px;
}
.modal_audio_detail .transfere {
  font-weight: bold;
  color: #34495e;
  font-size: 15px;
}

.saleshistory:hover {
  text-decoration: none;
}



.transfer_payment p{
  margin-bottom: 0;
}

.card-title {
  color: #34495e;
}

.fee a:hover {
  cursor: pointer;
}

.product_title {
  color: #34495e;
  font-weight: bold;
}


@media screen and (max-width:767px) {
    /*画面サイズが767px以下の場合読み込む（スマホ）*/


    .batsu {
      position: absolute;
      top: 17px;
      right: 18px;
      border-radius: 100%;
      color: #fff;
      width: 30px;
      height: 30px;
      line-height: 27px;
      cursor: pointer;
      background: #4DB7FE;
      transition: all .2s ease-in-out;
    }

    .batsu:hover{
        background: #333;
        border-color: #333;
        color: #FFF;
    }
    .size {
      width: 31px;
      text-align: center;
      font-size: 16px;
    }

}
@media screen and (min-width:768px){
    /*画面サイズが768px以上の場合読み込む（PC）*/


    .batsu {
      position: absolute;
      top: 14px;
      right: 30px;
      border-radius: 100%;
      color: #fff;
      width: 40px;
      height: 40px;
      line-height: 36px;
      cursor: pointer;
      background: #4DB7FE;
      transition: all .2s ease-in-out;
    }

    .batsu:hover{
        background: #333;
        border-color: #333;
        color: #FFF;
    }
    .size {
      width: 40px;
      text-align: center;
      font-size: 16px;
    }
}

</style>