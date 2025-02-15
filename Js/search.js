function advSearch() {
  let subId = document.getElementById("subId").value;
  let letId = document.getElementById("letId").value;
  let incrementDate = document.getElementById("d1").value;
  let department = document.getElementById("dept").value;

  document.getElementById("data-table").innerHTML =
    "<tr><td colspan='6'>Loading...</td></tr>";

  let xhr = new XMLHttpRequest();
  let params =
    "date=" +
    encodeURIComponent(incrementDate) +
    "&department=" +
    encodeURIComponent(department) +
    "&subId=" +
    encodeURIComponent(subId) +
    "&letId=" +
    encodeURIComponent(letId);

  xhr.open("GET", "php/filter.php?" + params, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();
      document.getElementById("data-table").innerHTML =
        response || "<tr><td colspan='6'>No records found</td></tr>";

        document.getElementById("d1").value = "";
        document.getElementById("dept").value = "";
        document.getElementById("letId").value = "";
        document.getElementById("subId").value = "";
    } else {
      console.error("Error: " + xhr.statusText);
      document.getElementById("data-table").innerHTML =
        "<tr><td colspan='6'>An error occurred</td></tr>";
    }
  };
  xhr.onerror = function () {
    console.error("Request failed");
    document.getElementById("data-table").innerHTML =
      "<tr><td colspan='6'>An error occurred</td></tr>";
  };
  xhr.send();
}
