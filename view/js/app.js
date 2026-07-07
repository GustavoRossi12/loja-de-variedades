const API_BASE = '../controller';

const api = {
  async request(endpoint, method = 'GET', body = null) {
    const opts = { method, headers: { 'Content-Type': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(`${API_BASE}/${endpoint}`, opts);
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Erro desconhecido');
    return data;
  },
  get: (ep) => api.request(ep),
  post: (ep, data) => api.request(ep, 'POST', data),
  put: (ep, data) => api.request(ep, 'PUT', data),
  delete: (ep) => api.request(ep, 'DELETE'),
};

function toast(msg, type = 'success') {
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.innerHTML = `<span></span><span>${msg}</span>`;
  document.getElementById('toastContainer').appendChild(el);
  setTimeout(() => el.remove(), 3000);
}

function openModal(id) { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

function formatCurrency(v) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v);
}

function formatDate(d) {
  return d ? new Date(d).toLocaleString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '-';
}

function startClock() {
  const el = document.getElementById('clock');
  if (!el) return;
  const t = () => el.textContent = new Date().toLocaleTimeString('pt-BR');
  t(); setInterval(t, 1000);
}

function setActiveNav() {
  const page = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-item').forEach(el => {
    el.classList.toggle('active', el.getAttribute('href') === page);
  });
}

function confirmDelete(msg, fn) { if (confirm(msg)) fn(); }

function loadingRows(tbody, cols = 5) {
  tbody.innerHTML = `<tr><td colspan="${cols}" class="text-center p-24 text-muted">Carregando...</td></tr>`;
}

function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;
}

document.addEventListener('DOMContentLoaded', () => {
  startClock();
  initSidebar();
  setActiveNav();
  document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
  });
});
