var searchInput = document.getElementById("search-input");
var suggestionsDiv = document.getElementById("suggestions");
var timeout = null;

searchInput.addEventListener("input", function () {
  clearTimeout(timeout);
  var term = this.value;

  if (term.length < 2) {
    suggestionsDiv.innerHTML = "";
    suggestionsDiv.style.display = "none";
    return;
  }

  timeout = setTimeout(function () {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?ajax=1&term=" + encodeURIComponent(term), true);

    xhr.onload = function () {
      if (xhr.status === 200) {
        var suggestions = JSON.parse(xhr.responseText);

        if (suggestions.length > 0) {
          var html = "<ul>";
          suggestions.forEach(function (suggestion) {
            html +=
              "<li onclick=\"selectSuggestion('" +
              suggestion.replace(/'/g, "\\'") +
              "')\">" +
              suggestion +
              "</li>";
          });
          html += "</ul>";

          suggestionsDiv.innerHTML = html;
          suggestionsDiv.style.display = "block";
        } else {
          suggestionsDiv.innerHTML = "";
          suggestionsDiv.style.display = "none";
        }
      }
    };

    xhr.send();
  }, 300);
});

function selectSuggestion(text) {
  searchInput.value = text;
  suggestionsDiv.innerHTML = "";
  suggestionsDiv.style.display = "none";
}

document.addEventListener("click", function (e) {
  if (e.target !== searchInput) {
    suggestionsDiv.innerHTML = "";
    suggestionsDiv.style.display = "none";
  }
});
