function advancedSearchdate() {
  let Sdate = document.getElementById("d1").value;
  let department = document.getElementById("dept").value;
  

  document.getElementById("data-table1").innerHTML =
    "<tr><td colspan='4'>Loading...</td></tr>";

  let xhr = new XMLHttpRequest();
  let params =
    "date=" +
    encodeURIComponent(Sdate) +
    "&department=" +
    encodeURIComponent(department);

  xhr.open("GET", "php/filter_date_info.php?" + params, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();

      document.getElementById("data-table1").innerHTML =
        response || "<tr><td colspan='4'>No records found</td></tr>";
      clearFilterFields();
    } else {
      console.error("Error: " + xhr.statusText);
      document.getElementById("data-table1").innerHTML =
        "<tr><td colspan='4'>An error occurred</td></tr>";
    }
  };
  xhr.onerror = function () {
    console.error("Request failed");
    document.getElementById("data-table1").innerHTML =
      "<tr><td colspan='4'>An error occurred</td></tr>";
  };
  xhr.send();
}

function clearFilterFields() {
  document.getElementById("d1").value = "";
  document.getElementById("dept").value = "";
}

