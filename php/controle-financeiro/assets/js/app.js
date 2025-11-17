async function api(path, opts={}){
  const res = await fetch(path, Object.assign({credentials:'same-origin', headers:{'Content-Type':'application/json'}}, opts));
  if (!res.ok) throw new Error((await res.text())||('Erro HTTP '+res.status));
  const ct = res.headers.get('content-type')||'';
  return ct.includes('application/json') ? res.json() : res.text();
}

async function requireAuth(){
  const me = await api('api/me.php');
  if(!me || !me.id){ window.location = 'login.html'; return null; }
  const userSpan = document.querySelector('.links .user');
  if (userSpan) userSpan.textContent = 'Olá, ' + me.nome;
  return me;
}

function nav(active){
  return `<header class="topnav">
    <div class="brand">💸 Controle Financeiro</div>
    <nav class="links">
      <a href="dashboard.html" class="${active==='dashboard'?'active':''}">Dashboard</a>
      <a href="lancamentos.html" class="${active==='lancamentos'?'active':''}">Lançamentos</a>
      <a href="relatorios.html" class="${active==='relatorios'?'active':''}">Relatórios</a>
      <span class="user"></span>
      <a class="sair" href="#" id="logoutLink">Sair</a>
    </nav>
  </header>`;
}

async function setupNav(active){
  const wrap = document.getElementById('nav');
  if (wrap) wrap.innerHTML = nav(active);
  const me = await requireAuth();
  if (!me) return;
  const logout = document.getElementById('logoutLink');
  if (logout) logout.addEventListener('click', async (e)=>{
    e.preventDefault();
    await api('api/logout.php');
    window.location = 'login.html';
  });
}

function money(n){ return 'R$ ' + Number(n||0).toFixed(2).replace('.',','); }
