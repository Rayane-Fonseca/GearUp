  // ─── THEME TOGGLE ───
  const htmlEl = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const themeLabel = document.getElementById('themeLabel');
 
  // Load saved theme
  const savedTheme = localStorage.getItem('gearup-theme') || 'light';
  htmlEl.setAttribute('data-theme', savedTheme);
  themeLabel.textContent = savedTheme === 'dark' ? 'Dark' : 'Light';
 
  themeToggle.addEventListener('click', () => {
    const current = htmlEl.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    htmlEl.setAttribute('data-theme', next);
    themeLabel.textContent = next === 'dark' ? 'Dark' : 'Light';
    localStorage.setItem('gearup-theme', next);
  });
 
  // ─── SMOOTH SCROLL ───
  function scrollTo(id) {
    document.querySelector(id)?.scrollIntoView({ behavior: 'smooth' });
  }
 
  // ─── MODAL ───
  const modal = document.getElementById('loginModal');
 
  function openLogin() {
    modal.classList.add('open');
    document.getElementById('emailInput').focus();
  }
  function closeLogin() {
    modal.classList.remove('open');
    clearForm();
  }
 
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeLogin();
  });
 
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('open')) closeLogin();
  });
 
  function clearForm() {
    document.getElementById('emailInput').value = '';
    document.getElementById('passInput').value = '';
    document.getElementById('emailError').classList.remove('show');
    document.getElementById('passError').classList.remove('show');
    document.getElementById('loginAlert').className = 'modal-alert';
    document.getElementById('loginAlert').textContent = '';
  }
 
  // ─── AUTH ───
  // Demo credentials (in production this would be a real API)
  const DEMO_USERS = [
    { email: 'ana.silva@gearup.com.br', password: '1234', name: 'Ana Silva' },
    { email: 'joao.costa@gearup.com.br', password: '1234', name: 'João Costa' },
    { email: 'demo@empresa.com', password: 'demo', name: 'Demo User' },
  ];
 
  let currentUser = null;
 
  // Check if user is already logged in
  const savedUser = localStorage.getItem('gearup-user');
  if (savedUser) {
    currentUser = JSON.parse(savedUser);
    applyLoggedInState();
  }
 
  function handleLogin() {
    const email = document.getElementById('emailInput').value.trim();
    const pass = document.getElementById('passInput').value;
    const btn = document.getElementById('loginBtn');
    const alert = document.getElementById('loginAlert');
 
    // Clear errors
    document.getElementById('emailError').classList.remove('show');
    document.getElementById('passError').classList.remove('show');
    alert.className = 'modal-alert';
 
    let valid = true;
    if (!email || !email.includes('@')) {
      document.getElementById('emailError').classList.add('show');
      valid = false;
    }
    if (!pass || pass.length < 4) {
      document.getElementById('passError').classList.add('show');
      valid = false;
    }
    if (!valid) return;
 
    // Simulate loading
    btn.textContent = 'Entrando…';
    btn.disabled = true;
 
    setTimeout(() => {
      const user = DEMO_USERS.find(u => u.email === email && u.password === pass);
 
      if (user) {
        currentUser = user;
        localStorage.setItem('gearup-user', JSON.stringify(user));
        alert.className = 'modal-alert success';
        alert.textContent = `✅ Bem-vindo(a), ${user.name}!`;
 
        setTimeout(() => {
          closeLogin();
          applyLoggedInState();
        }, 900);
      } else {
        alert.className = 'modal-alert error';
        alert.textContent = '❌ E-mail ou senha incorretos. Tente: demo@empresa.com / demo';
        btn.textContent = 'Entrar na plataforma';
        btn.disabled = false;
      }
    }, 800);
  }
 
  function logout() {
    currentUser = null;
    localStorage.removeItem('gearup-user');
    applyLoggedOutState();
  }
 
  function applyLoggedInState() {
    // Nav
    document.getElementById('authButtons').style.display = 'none';
    const badge = document.getElementById('userBadge');
    badge.classList.add('visible');
    document.getElementById('userNameNav').textContent = currentUser.name.split(' ')[0];
    document.getElementById('userAvatar').textContent = currentUser.name.charAt(0);
 
    // Unlock cards
    document.getElementById('lock-inovacao').style.display = 'none';
    document.getElementById('lock-lideranca').style.display = 'none';
 
    // Show progress section
    document.getElementById('myProgress').classList.add('visible');
 
    // Hide private banner
    document.getElementById('privateBanner').style.display = 'none';
  }
 
  function applyLoggedOutState() {
    document.getElementById('authButtons').style.display = '';
    document.getElementById('userBadge').classList.remove('visible');
 
    // Lock cards again
    document.getElementById('lock-inovacao').style.display = '';
    document.getElementById('lock-lideranca').style.display = '';
 
    // Hide progress
    document.getElementById('myProgress').classList.remove('visible');
 
    // Show private banner
    document.getElementById('privateBanner').style.display = '';
  }
 
  // Enter key in form
  document.getElementById('passInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') handleLogin();
  });
  document.getElementById('emailInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') document.getElementById('passInput').focus();
  });