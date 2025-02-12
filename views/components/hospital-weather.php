<a
  class="weatherwidget-io"
  href="https://forecast7.com/es/8d97n71d27/tucani/"
  data-label_1="Tucaní"
  data-label_2="Mérida"
  data-mode="Current"
  data-days="3"
  data-theme="weather_one"></a>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const id = "weatherwidget-io-js";
    const fjs = document.getElementsByTagName("script")[0];

    if (!document.getElementById(id)) {
      const js = document.createElement("script");

      js.id = id;
      js.src = "https://weatherwidget.io/js/widget.min.js";
      fjs?.parentNode?.insertBefore(js, fjs);
    }
  });
</script>
