import "./bootstrap";

import Vue from "vue";
// import App from "./App.vue";
// import router from "./router";
// import "bootstrap/dist/css/bootstrap.css";
import ArticleLike from "./components/ArticleLike";
import ArticleTagsInput from "./components/ArticleTagsInput";

const app = new Vue({
  el: "#app",
  //   router,
  components: {
    // App,
    ArticleLike,
    ArticleTagsInput,
  },
  //   template: "<App />",
});

// Vue.config.productionTip = false;

// new Vue({
//   router,
//   render: (h) => h(App),
// }).$mount("#app");
