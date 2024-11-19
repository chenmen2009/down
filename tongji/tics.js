function visita() {
  $.ajax({
    url: "./tongji/visit.php",
    dataType: "jsonp",
  });
}
window.setTimeout("security()", 3000);
function security() {
  $.ajax({
    url: "./tongji/security.php",
    dataType: "jsonp",
  });
}
