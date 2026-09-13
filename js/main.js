/* ==========================================================================
   Company Pulse - Client-Side JavaScript & AJAX (Week 2/3)
   ========================================================================== */

function checkUser(user) {
  if (user.value === '') {
    document.getElementById('info').innerHTML = '';
    return;
  }

  // AJAX call to checkuser.php
  let params = "user=" + encodeURIComponent(user.value);
  let request = new XMLHttpRequest();

  request.open("POST", "checkuser.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
      document.getElementById('info').innerHTML = this.responseText;
    }
  };

  request.send(params);
}