$(".close_icon").on("click", function () {
  $(this).parents(".hide_content");
});

const delay = 500;

$(".progress-bar").each(function (i) {
  $(this)
    .delay(delay * i)
    .animate({ width: `${$(this).attr("aria-valuenow")}%` }, delay);
  $(this)
    .prop("Counter", 0)
    .animate(
      { Counter: $(this).text() },
      {
        duration: delay,
        easing: "swing",
        step: function (now) {
          $(this).text(`${Math.ceil(now)}%`);
        },
      },
    );
});

$(".sidebar_icon").on("click", () => {
  $(".sidebar").toggleClass("active_sidebar");
});

$(".sidebar_close_icon i").on("click", () => {
  $(".sidebar").removeClass("active_sidebar");
});

$(".troggle_icon").on("click", () => {
  $(".setting_navbar_bar").toggleClass("active_menu");
});

$(".custom_select").click(function () {
  if ($(this).hasClass("active")) {
    $(this).removeClass("active");
  } else {
    $(".custom_select.active").removeClass("active");
    $(this).addClass("active");
  }
});

$(document).click((event) => {
  if (!$(event.target).closest(".custom_select").length) {
    $("body").find(".custom_select").removeClass("active");
  }
});

$(document).click((event) => {
  if (!$(event.target).closest(".sidebar_icon, .sidebar").length) {
    $("body").find(".sidebar").removeClass("active_sidebar");
  }
});

$("#checkAll").click(() => {
  $("input:checkbox").not(this).prop("checked", this.checked);
});

$(".input-file").each(function () {
  const $input = $(this);
  const $label = $input.next(".js-labelFile");
  const labelVal = $label.html();

  $input.on("change", (element) => {
    let fileName = "";

    if (element.target.value) {
      fileName = element.target.value.split("\\").pop();
    }

    fileName
      ? $label.addClass("has-file").find(".js-fileName").html(fileName)
      : $label.removeClass("has-file").html(labelVal);
  });
});

$(".input-file2").each(function () {
  const $input = $(this);
  const $label = $input.next(".js-labelFile1");
  const labelVal = $label.html();

  $input.on("change", (element) => {
    let fileName = "";

    if (element.target.value) {
      fileName = element.target.value.split("\\").pop();
    }

    fileName
      ? $label.addClass("has-file").find(".js-fileName1").html(fileName)
      : $label.removeClass("has-file").html(labelVal);
  });
});

if ($(".lms_table_active").length) {
  $(".lms_table_active").DataTable({
    bLengthChange: false,
    bDestroy: true,
    language: {
      search: "<i class='ti-search'></i>",
      searchPlaceholder: "Quick Search",
      paginate: {
        next: "<i class='ti-arrow-right'></i>",
        previous: "<i class='ti-arrow-left'></i>",
      },
    },
    columnDefs: [{ visible: false }],
    responsive: true,
    searching: false,
  });
}

if ($(".lms_table_active2").length) {
  $(".lms_table_active2").DataTable({
    bLengthChange: false,
    bDestroy: false,
    language: {
      search: "<i class='ti-search'></i>",
      searchPlaceholder: "Quick Search",
      paginate: {
        next: "<i class='ti-arrow-right'></i>",
        previous: "<i class='ti-arrow-left'></i>",
      },
    },
    columnDefs: [{ visible: false }],
    responsive: true,
    searching: false,
    info: false,
    paging: false,
  });
}

$(".layout_style").click(function () {
  if ($(this).hasClass("layout_style_selected")) {
    $(this).removeClass("layout_style_selected");
  } else {
    $(".layout_style.layout_style_selected").removeClass(
      "layout_style_selected",
    );
    $(this).addClass("layout_style_selected");
  }
});

$(".switcher_wrap li.Horizontal").click(() => {
  $(".sidebar").addClass("hide_vertical_menu");
  $(".main_content ").addClass("main_content_padding_hide");
  $(".horizontal_menu").addClass("horizontal_menu_active");
  $(".main_content_iner").addClass("main_content_iner_padding");
  $(".footer_part").addClass("pl-0");
});

$(".switcher_wrap li.vertical").click(() => {
  $(".sidebar").removeClass("hide_vertical_menu");
  $(".main_content ").removeClass("main_content_padding_hide");
  $(".horizontal_menu").removeClass("horizontal_menu_active");
  $(".main_content_iner").removeClass("main_content_iner_padding");
  $(".footer_part").removeClass("pl-0");
});

$(".switcher_wrap li").click(function () {
  $("li").removeClass("active");
  $(this).addClass("active");
});

$(".custom_lms_choose li").click(function () {
  $("li").removeClass("selected_lang");
  $(this).addClass("selected_lang");
});

$(".spin_icon_clicker").on("click", (e) => {
  $(".switcher_slide_wrapper").toggleClass("swith_show");
  e.preventDefault();
});

$(document).ready(() => {
  $(() => {
    $(".pCard_add").click(() => {
      $(".pCard_card").toggleClass("pCard_on");
      $(".pCard_add i").toggleClass("fa-minus");
    });
  });
});
