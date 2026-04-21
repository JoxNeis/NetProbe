$(document).ready(function () {
  const params = new URLSearchParams(window.location.search);
  let tabs = params.get("t");
  let page = "layout/" + tabs + ".php";
  $.get(page, function (data) {
    $(".parameter").after(data);
  });
});
