<template>
  <div class="new-audio">

    <div class="container">

      <div class="content-top-title">
        <h1>使い方</h1>
      </div>
      
      <div class="mt-5 explanation">
        <div class="card-deck row">
          <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">検索する</h4>
                <p class="card-text">検索ボックスにて、「サウンド」、「イメージ」、「シーン」などから選び、または気になるワードで自由に検索してみてください。</p>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">アーティストから購入</h4>
                <p class="card-text">オーディオ詳細画面から、好きな楽曲を購入できます。まずは会員登録し、気になる楽曲をお気に入り登録するなど、自身のタイミングで購入しましょう。購入後はマイページよりフル音源をダウンロードできます。</p>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">募集する</h4>
                <p class="card-text">自身が欲しい楽曲を募集することができます。クリエイターにも分かりやすいように具体的な募集をかけてみましょう。クリエイターの方は気になる募集があればその方にメッセージを送ってやりとりができます。</p>
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
    }
  },

}
</script>

<style scoped>
.new-audio {
  height: auto;
  background: #F6F6F4;
}
.content-top-title {
  text-align: center;
  margin-top: 60px;
}
.content-top-title h1{
  font-weight: bold;
  color: #566985;
}
.card .card-title{
  font-weight: bold;
  text-align: center;
}
.explanation {
  margin-bottom: 120px;
}
</style>