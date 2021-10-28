document.addEventListener('DOMContentLoaded', function() {
  var elements = document.getElementsByTagName('input');
  for (var i = 0; i < elements.length; i++) {
      elements[i].oninvalid = function(e) {
          e.target.setCustomValidity("");
          if (!e.target.validity.valid) {
              e.target.setCustomValidity("Tämä kenttä ei voi olla tyhjä");
          }
      };
      elements[i].oninput = function(e) {
          e.target.setCustomValidity("");
      };
  }
})
