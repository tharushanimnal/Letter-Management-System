function advSearch() {
  let incrementDate = document.getElementById("d1").value;
  let department = document.getElementById("dept").value;

  document.getElementById("data-table").innerHTML =
    "<tr><td colspan='4'>Loading...</td></tr>";

  let xhr = new XMLHttpRequest();
  let params =
    "date=" +
    encodeURIComponent(incrementDate) +
    "&department=" +
    encodeURIComponent(department);

  xhr.open("GET", "php/receive_admin.php?" + params, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();

      document.getElementById("data-table").innerHTML =
        response || "<tr><td colspan='4'>No records found</td></tr>";
    } else {
      console.error("Error: " + xhr.statusText);
      document.getElementById("data-table").innerHTML =
        "<tr><td colspan='4'>An error occurred</td></tr>";
    }

    document.getElementById("d1").value = "";
    document.getElementById("dept").value = "";
  };
  xhr.onerror = function () {
    console.error("Request failed");
    document.getElementById("data-table").innerHTML =
      "<tr><td colspan='4'>An error occurred</td></tr>";

    document.getElementById("d1").value = "";
    document.getElementById("dept").value = "";
  };
  xhr.send();
}

function fetchAllData() {
  document.getElementById("data-table").innerHTML =
    "<tr><td colspan='4'>Loading...</td></tr>";

  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/receive_admin.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();
      document.getElementById("data-table").innerHTML =
        response || "<tr><td colspan='4'>No records found</td></tr>";
    } else {
      console.error("Error: " + xhr.statusText);
      document.getElementById("data-table").innerHTML =
        "<tr><td colspan='4'>An error occurred</td></tr>";
    }
  };
  xhr.onerror = function () {
    console.error("Request failed");
    document.getElementById("data-table").innerHTML =
      "<tr><td colspan='4'>An error occurred</td></tr>";
  };
  xhr.send();
}

document.addEventListener("DOMContentLoaded", function () {
  fetchAllData();
});
