/* ==========================================================================
   Company Pulse - Client-Side JavaScript & AJAX (Week 2/3)
   ========================================================================== */

function checkUser(user) {
  const info = document.getElementById('info');
  const name = user.value.trim();

  if (name === '') {
    info.innerHTML = '';
    return;
  }

  // AJAX call to checkuser.php
  let params = "user=" + encodeURIComponent(name);
  let request = new XMLHttpRequest();

  request.open("POST", "checkuser.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.onreadystatechange = function() {
    if (this.readyState === 4) {
      info.innerHTML = this.status === 200 ? this.responseText : '';
    }
  };

  request.send(params);
}

document.addEventListener('DOMContentLoaded', function() {
  // Signup: check username availability shortly after the user stops typing
  const username = document.getElementById('username');
  if (username) {
    let timer;
    username.addEventListener('input', function() {
      clearTimeout(timer);
      timer = setTimeout(function() { checkUser(username); }, 400);
    });
  }

  // Members: filter the list as the user types
  const search = document.getElementById('member-search');
  if (search) {
    const rows = document.querySelectorAll('#member-list .member-row');
    const noResults = document.getElementById('no-results');
    search.addEventListener('input', function() {
      const term = search.value.trim().toLowerCase();
      let visible = 0;
      rows.forEach(function(row) {
        const match = row.dataset.name.indexOf(term) !== -1;
        row.hidden = !match;
        if (match) visible++;
      });
      noResults.hidden = visible !== 0;
    });
  }

  // Ask before any destructive link (e.g. erasing a message)
  document.querySelectorAll('a[data-confirm]').forEach(function(link) {
    link.addEventListener('click', function(event) {
      if (!window.confirm(link.dataset.confirm)) event.preventDefault();
    });
  });
});
