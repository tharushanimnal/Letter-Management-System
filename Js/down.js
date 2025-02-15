function advSearchDown() {
  let incrementDate = document.getElementById("d1").value;
  if (!incrementDate) {
    alert("Please select a date");
    return;
  }

  document.getElementById("data-table2").innerHTML =
    "<tr><td colspan='4'>Loading...</td></tr>";

  let xhr = new XMLHttpRequest();
  let params =
    "date=" +
    encodeURIComponent(incrementDate);

  xhr.open("GET", "php/filter_download.php?" + params, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();

      document.getElementById("data-table2").innerHTML =
        response || "<tr><td colspan='4'>No records found</td></tr>";
      clearFilterFields();
    } else {
      console.error("Error: " + xhr.statusText);
      document.getElementById("data-table2").innerHTML =
        "<tr><td colspan='4'>An error occurred</td></tr>";
    }
  };
  xhr.onerror = function () {
    console.error("Request failed");
    document.getElementById("data-table2").innerHTML =
      "<tr><td colspan='4'>An error occurred</td></tr>";
  };
  xhr.send();
}

function clearFilterFields() {
  document.getElementById("d1").value = "";
}
