<template>
  <div class="card">
  <div class="py-3">
    <h5 class="text-center title">出品・振込申請について</h5>
  </div>
  <div class="card-header sub_title">
    出品手数料について
  </div>
  <div class="card-body">
    <p>取得報酬額が<span class="underline">1,000円以上</span>となった場合に、振込申請をすることができます。</p>
    <p class="mb-3">また、音源の出品には以下の手数料がかかります。</p>
    <p class="type">決済手数料</p>
    <p class="mb-3">販売価格の3.6%（<a href="https://stripe.com/jp" target="_blank">Stripe</a>の決済手数料に準拠しています）</p>
    <p class="type">利用料</p>
    <p class="mb-3">販売価格から決済手数料を引いた額の10%</p>
    <p class="type">振込手数料</p>
    <p class="">①5万以下の申請時：振込申請1回につき220円</p>
    <p class="mb-3">②5万以下の申請時：振込申請1回につき440円</p>
  </div>
   <div class="card-header sub_title">
    売上金はいつ振り込まれるのか
  </div>
  <div class="card-body">
    <p>振込申請を行った場合、振込金額はその月の翌月の1日に確定されます。
翌月の1日時点での確定金額の全額が、月末までに（基本的には中旬頃に）指定した口座へ振込を行います。
例えば、7月に振込申請を行った場合、振込金額は8月1日に確定され、8月の中旬頃に振り込まれます。</p>
  </div>
</div>
</template>

<script>
export default {

}
</script>

<style scoped>
p {
  margin-bottom: 0;
}
.title {
  color: #34495e;
  font-weight: 700;
  font-size: 20px;
}
.sub_title {
  background-color: #D9F0FE;
  font-size: 17px;
  font-weight: bold;
  color: #34495e;
}
.type {
  font-size: 15px;
  font-weight: bold;
  color: #34495e;
}
.underline {
  background-color: #D9F0FE;
}

</style>