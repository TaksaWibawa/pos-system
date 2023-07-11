function filter() {
  var tableRows = document.querySelectorAll("table tbody tr");
  var year = document.getElementById("year").value;
  var month = document.getElementById("month").value;

  if (tableRows) {
    for (var i = 0; i < tableRows.length; i++) {
      var date = tableRows[i].querySelector("td:nth-child(2)").textContent;
      var [rowYear, rowMonth, _] = date.split("-"); // Split the date into year, month, and day

      if (rowYear === year && rowMonth === month) {
        tableRows[i].style.display = "table-row";
      } else {
        tableRows[i].style.display = "none";
      }
    }
  }
}

function filterProduct() {
  var tableRows = document.querySelectorAll("table tbody tr");
  var product = document.getElementById("product").value;

  if (tableRows) {
    for (var i = 0; i < tableRows.length; i++) {
      var productName = tableRows[i]
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();

      if (productName.includes(product.toLowerCase())) {
        tableRows[i].style.display = "table-row";
      } else {
        tableRows[i].style.display = "none";
      }
    }
  }
}
