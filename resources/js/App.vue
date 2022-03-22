<template>
  <div>
    <Header></Header>
    <main class="container">
      <div class="container">
        <Router-view></Router-view>
      </div>
    </main>
    <Footer></Footer>
  </div>
</template>

<script>
  import Header from "./components/Header.vue";
  import Footer from "./components/Footer.vue";

  export default {
    components: {
      Header,
      Footer,
    },
  };
</script>
