document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contactForm');
  const inputs = form && form.querySelectorAll('input');
  const btn = document.getElementById('contactSend');

  function isValid(inputs) {
    let valid = true;
    inputs.forEach(element => {
      element.checkValidity();
      if (!element.validity.valid) {
        valid = false;
      }
    })
    return valid;
  }

  function fetchFormData(form) {
    let urlEncodedData = "",
        urlEncodedDataPairs = [];

    const text = form.querySelector('textarea');

    // Turn the data object into an array of URL-encoded key/value pairs.
    inputs.forEach(element => {
      urlEncodedDataPairs.push( encodeURIComponent( element.getAttribute('name') ) + '=' + encodeURIComponent( element.checked ? '1' : element.value ) );
    })

    urlEncodedDataPairs.push( encodeURIComponent( text.getAttribute('name') ) + '=' + encodeURIComponent( text.value ) );

    // Combine the pairs into a single string and replace all %-encoded spaces to
    // the '+' character; matches the behavior of browser form submissions.
    urlEncodedData = urlEncodedDataPairs.join( '&' ).replace( /%20/g, '+' );
    return urlEncodedData;
  }

  function emailSent() {
    const msg = document.getElementById('contactSentMessage');
    msg.classList.remove('hidden');
    msg.classList.add('visible');
  }

  function sendData(data) {
    console.log( 'Sending data' );

    const xhr = new XMLHttpRequest();

    // Set up our request
    xhr.open( 'POST', 'scripts/send_email.php', true );

    // Add the required HTTP header for form data POST requests
    xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );

    xhr.onreadystatechange = function() { // Call a function when the state changes.
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        if (this.responseText === 'success') {
          emailSent();
        }
        // Request finished. Do processing here.
        console.log(this.responseText);
      }
    }

    // Finally, send our data.
    xhr.send(data);
  }

  if (inputs) {
    inputs.forEach(element => {
      element.oninvalid = function(e) {
        e.target.setCustomValidity("");
        if (!e.target.validity.valid) {
          if (e.target.type == 'email' && e.target.value && e.target.value.length > 0) {
            e.target.setCustomValidity("Sähköpostiosoite ei kelpaa");
          } else {
            e.target.setCustomValidity("Tämä kenttä ei voi olla tyhjä");
          }
        }
      };
      element.oninput = function(e) {
        e.target.setCustomValidity("");
      };
    });
  }

  if (inputs && btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      if (isValid(inputs)) {
        const data = fetchFormData(form);
        sendData(data);
      }
    })
  }
})
