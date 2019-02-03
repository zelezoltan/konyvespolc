// Segédfüggvények
const $ = (s) => document.querySelector(s);

function delegate(parent, children, type, handler) {
  function delegatedFunction(event) {
    let target = event.target;
    if (target.matches(`${parent} ${children}, ${parent} ${children} *`)) {
      while (!target.matches(children)) {
        target = target.parentNode;
      }

      // event.delegatedTarget = target;
      // handler(event);
      return handler.call(target, event);
    }
  }
  $(parent).addEventListener(type, delegatedFunction);
}

function ajax(opts) { 
  let mod      = opts.mod      || 'GET',
      url      = opts.url      || '',
      getadat  = opts.getadat  || '',
      postadat = opts.postadat || '',
      siker    = opts.siker    || function(){},
      hiba     = opts.hiba     || function(){};

  mod = mod.toUpperCase();
  url = url+'?'+getadat;
  const xhr = new XMLHttpRequest();
  xhr.open(mod, url, true);
  if (mod === 'POST') {
    xhr.setRequestHeader('Content-Type', 
      'application/x-www-form-urlencoded');
  }
  xhr.addEventListener('readystatechange', function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        siker(xhr, xhr.responseText);
      } else {
        hiba(xhr);
      }
    }
  }, false);
  xhr.send(mod == 'POST' ? postadat : null);
  return xhr;
}

delegate('.pagination', '.prev', 'click', (e) => {
    e.preventDefault();
    const page = parseInt($('table').getAttribute('data-page'))-1;
    ajax({
        mod: 'GET',
        url: 'getbooks.php',
        getadat: 'page=' + page,
        siker: function(xhr, json){
            renderTableBody(JSON.parse(json), page);
            renderPagination(page);
            history.replaceState(null, null, '?page=' + page);
            $('table').setAttribute('data-page', page);
        },
        hiba: function(xhr, json){
            console.log('hiba', json, xhr);
        }
    });
});

delegate('.pagination', '.next', 'click', (e) => {
    e.preventDefault();
    const page = parseInt($('table').getAttribute('data-page'))+1;
    ajax({
        mod: 'GET',
        url: 'getbooks.php',
        getadat: 'page=' + page,
        siker: function(xhr, json){
            renderTableBody(JSON.parse(json), page);
            renderPagination(page);
            history.replaceState(null, null, '?page=' + page);
            $('table').setAttribute('data-page', page);
        },
        hiba: function(xhr, json){
            console.log('hiba', json, xhr);
        }
    });
});

function renderTableBody(books, page){
    let html = '';
    for(let book of books){
        html+=`<tr data-id="${book.id}">`;
        html+=`<td>${book.author}</td>`;
        html+=`<td>${book.title}</td>`;
        html+=`<td>${book.category}</td>`;
        html+=`<td>${book.isread == "1" ? 'igen' : 'nem'}</td>`;
        html+=`<td><a class="btn btn-info" data-id="${book.id}" href="book.php?bookId=${book.id}">Módosítás</a>
        <a class="btn btn-danger" data-id="${book.id}" href="delete.php?id=${book.id}&page=${page}">Törlés</a>
        </td>`;
    }
    $('tbody').innerHTML = html;
}

function renderPagination(page){
    let html = '';
    if(page>1){
        html += '<li class="page-item"><a class="page-link prev" href="#">Előző</a></li>';
    }
    const count = parseInt($('table').getAttribute('data-count'));
    if(page*5<count){
        html+= '<li class="page-item"><a class="page-link next" href="#">Következő</a></li>';
    }
    $('.pagination').innerHTML = html;
}