<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Solicitud de asistencia</title>
  <style>
    /* ==========================================================================
           🛡️ ESTILOS DE LA NAVBAR UNIFICADA CORPORATIVA Y DISEÑO BASE
           ========================================================================== */
    :root {
      --avanta-azul-profundo: #04114A;
      --avanta-azul-electrico: #0F63FF;
      --avanta-celeste: #2FE7FF;
      --avanta-blanco: #FFFFFF;
      --border-color: #2F3A75;
      --text-muted: #98A9C4;
      
      /* Variables del segundo bloque integradas de forma segura */
      --bg: #04114A;
      --surface: #111B54;
      --text: #FFFFFF;
      --muted: #98A9C4;
      --line: #2F3A75;
      --accent: #0F63FF;
      --accent-text: #FFFFFF;
      --accent-cyan: #2FE7FF;
      --ok: #35FF57;
      --err: #ff6b6b;
      --code: #02092B;
      --code-text: #cdd9e5;
      --font-avanta: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-avanta);
      background-color: var(--avanta-azul-profundo);
      color: var(--avanta-blanco);
      font-size: 15px;
      line-height: 1.5;
    }

    .navbar {
      background-color: var(--avanta-azul-profundo);
      border-bottom: 1px solid var(--border-color);
      padding: 12px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .navbar-brand img {
      height: 54px;
      width: auto;
      display: block;
    }

    .navbar-brand span {
      font-size: 22px;
      font-weight: 300;
      color: var(--avanta-celeste);
      letter-spacing: 0.5px;
    }

    .nav-right-container {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: nowrap;
    }

    .nav-links-menu {
      display: flex;
      gap: 20px;
    }

    .nav-links-menu a {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 15px;
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-links-menu a.active {
      color: var(--avanta-celeste);
      font-weight: 700;
    }

    .nav-links-menu a:hover {
      color: var(--avanta-blanco);
    }

    .btn-logout {
      background: transparent;
      border: 1px solid var(--avanta-celeste);
      color: var(--avanta-celeste);
      padding: 8px 16px;
      font-size: 14px;
      font-weight: 500;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s;
      font-family: inherit;
    }

    .btn-logout:hover {
      background-color: var(--avanta-celeste);
      color: var(--avanta-azul-profundo);
    }

    /* ==========================================================================
           🎨 ESTILOS MANTENIDOS EXACTAMENTE IGUAL PARA EL FORMULARIO ESTÉTICO
           ========================================================================== */
    main {
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .lead {
      color: var(--text-muted);
      font-size: 1.05rem;
      margin-bottom: 30px;
    }

    section {
      background-color: #111B54;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 28px 32px;
      margin-bottom: 25px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    h2 {
      font-size: 1.4rem;
      color: var(--avanta-blanco);
      margin-bottom: 6px;
    }

    .hint {
      color: var(--text-muted);
      font-size: 0.9rem;
      margin-bottom: 20px;
    }

    .row {
      display: flex;
      gap: 16px;
    }

    label {
      display: flex;
      flex-direction: column;
      gap: 6px;
      font-size: 0.9rem;
      color: var(--avanta-celeste);
      font-weight: 500;
      width: 100%;
    }

    .form-control,
    input,
    select {
      width: 100%;
      padding: 10px 12px;
      background-color: rgba(4, 17, 74, 0.7);
      border: 1px solid var(--border-color);
      border-radius: 6px;
      color: var(--avanta-blanco);
      font-size: 0.95rem;
      font-family: inherit;
      transition: all 0.3s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: var(--avanta-celeste);
    }

    select:disabled {
      background-color: rgba(4, 17, 74, 0.3);
      color: var(--text-muted);
      cursor: not-allowed;
    }

    fieldset {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: rgba(4, 17, 74, 0.2);
    }

    legend {
      font-size: 0.95rem;
      font-weight: 700;
      padding: 0 10px;
      color: var(--avanta-celeste);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }

    #btnCaso {
      width: 100%;
      background-color: var(--avanta-azul-electrico);
      color: var(--avanta-blanco);
      border: none;
      padding: 14px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 15px;
      transition: all 0.3s;
    }

    #btnCaso:hover {
      background-color: var(--avanta-celeste);
      color: var(--avanta-azul-profundo);
    }

    .status {
      margin-top: 10px;
      font-size: 0.9rem;
      font-weight: bold;
    }

    details {
      margin-top: 25px;
      border-top: 1px solid var(--border-color);
      padding-top: 15px;
    }

    summary {
      cursor: pointer;
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    #log {
      background-color: var(--avanta-azul-profundo);
      color: #35FF57;
      padding: 15px;
      border-radius: 6px;
      font-family: monospace;
      overflow-x: auto;
      margin-top: 10px;
    }
  </style>
</head>
<!-- Favicon oficial integrado correctamente -->
<link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Avanta - Solicitud de Asistencia</title>
  <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">
</head>

<body>

  <!-- 🛡️ NUEVA NAVBAR CORPORATIVA INTEGRADA (AQUÍ EMPIEZA LA BARRA) -->
  <nav class="navbar">
    <div class="navbar-brand">
      <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Logotipo Avanta">
      @role('admin') <span>| Panel</span> @endrole
    </div>

    <div class="nav-right-container">
      <div class="nav-links-menu">
        <a href="{{ route('dashboard') }}">Inicio</a>

        @role('admin')
        <a href="{{ route('asistencias') }}" class="active">Solicitar Asistencia</a>
        <a href="{{ route('admin.usuarios') }}">Usuarios</a>
        <a href="{{ route('admin.bitacora') }}">Bitácora SQL</a>
        @else
        <a href="{{ route('asistencias') }}" class="active">Solicitar Asistencia</a>
        @endrole
      </div>

      <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-logout">Cerrar Sesión</button>
      </form>
    </div>
  </nav>

  <!-- 📝 TU COMPONENTE ORIGINAL "MAIN" COPIADO TAL CUAL -->
  <main>
    <h1>Solicitud de asistencia</h1>
    <p class="lead">Elige el plan y la categoría, completa los datos y crea el caso.</p>

    <section>
      <h2>Plan y categoría</h2>
      <p class="hint">Selecciona uno de tus planes de asistencia autorizados para ver sus categorías.</p>
      <div class="row">
        <label style="max-width:320px">Plan de asistencia
          <select id="planId" class="form-control">
            <option value="">— selecciona un plan —</option>
            @if(isset($misPlanes) && count($misPlanes) > 0)
            @foreach($misPlanes as $p)
            <option value="{{ $p->assistance_plan_id }}">{{ $p->nombre_plan ?? 'Plan ' . $p->assistance_plan_id }}</option>
            @endforeach
            @endif
          </select>
        </label>
      </div>

      <label style="margin-top:14px;max-width:420px">Categoría
        <select id="categoria" name="assistance_id" disabled>
          <option value="">— selecciona un plan primero —</option>
        </select>
      </label>
      <div id="st1" class="status" role="status"></div>
    </section>

    <section>
      <h2>Datos del caso</h2>
      <form id="formCaso" novalidate>
        <fieldset>
          <legend>Afiliado</legend>
          <div class="grid">
            <label>Primer nombre <input name="first_name" required></label>
            <label>Segundo nombre <input name="middle_name"></label>
            <label>Primer apellido <input name="last_name" required></label>
            <label>Segundo apellido <input name="last_name_2"></label>
            <label>Documento (DPI) <input name="national_id" required></label>
            <label>Teléfono del afiliado <input name="phone1" required></label>
            <label>ID del afiliado <input name="member_id" type="number" required></label>
            <label>País (ID) <input name="member_country" type="number" value="1"></label>
          </div>
        </fieldset>

        <fieldset>
          <legend>Contacto</legend>
          <div class="grid">
            <label>Nombre de contacto <input name="contact_name" required></label>
            <label>Código telefónico <input name="country_phone_code" type="number" value="502"></label>
            <label>Teléfono <input name="phone" required></label>
            <label>Correo <input name="email" type="email" required></label>
            <label>Canal de contacto
              <select name="contact_channel">
                <option value="2">2</option>
                <option value="1">1</option>
                <option value="3">3</option>
              </select>
            </label>
          </div>
        </fieldset> 
        <script>
          const $ = (id) => document.getElementById(id);
          const csrf = document.querySelector('meta[name="csrf-token"]').content;

          const setStatus = (id, msg, type = '') => {
            const el = $(id);
            if (el) {
              el.textContent = msg;
              el.className = 'status ' + type;
            }
          };

          // Llamada segura a nuestro backend de Laravel conectado a la API real
          async function call(url, options = {}) {
            const headers = {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
              'X-Requested-With': 'XMLHttpRequest',
              ...(options.headers || {})
            };

            const res = await fetch(url, {
              ...options,
              headers
            });

            const text = await res.text();
            let data;
            try {
              data = text ? JSON.parse(text) : null;
            } catch {
              data = {
                message: text
              };
            }

            if (res.status === 419) throw {
              message: 'La sesión ha expirado por seguridad. Por favor, recarga la página.',
              status: 419
            };

            if (!res.ok) throw {
              message: data?.message || `Error ${res.status}`,
              errors: data?.errors,
              status: res.status
            };

            return data;
          }

          // ---------- Carga Automática de Categorías Reales ----------
          const planInput = $('planId') || $('plan');
          const catSelect = $('categoria');
          let debounceTimer;

          if (planInput && catSelect) {
            planInput.addEventListener('change', () => {
              clearTimeout(debounceTimer);
              const planId = planInput.value;

              if (!planId) {
                catSelect.innerHTML = '<option value="">— selecciona un plan primero —</option>';
                catSelect.disabled = true;
                setStatus('st1', '', '');
                return;
              }

              setStatus('st1', 'Buscando categorías autorizadas...', '');

              // Espera 500ms tras dejar de interactuar para mitigar ráfagas al servidor
              debounceTimer = setTimeout(async () => {
                try {
                  const data = await call(`/asistencias/categorias/${encodeURIComponent(planId)}`);
                  const list = Array.isArray(data) ? data : (data?.data || data?.items || []);

                  if (list.length > 0) {
                    catSelect.innerHTML = '<option value="">— elige una categoría —</option>';
                    list.forEach((c) => {
                      const o = document.createElement('option');
                      o.value = c.id ?? c.assistance_id ?? '';
                      o.textContent = c.name ?? c.description ?? c.nombre ?? `Categoría ${o.value}`;
                      catSelect.appendChild(o);
                    });
                    catSelect.disabled = false;
                    setStatus('st1', `${list.length} categorías cargadas correctamente.`, 'ok');
                  } else {
                    catSelect.innerHTML = '<option value="">— sin categorías disponibles —</option>';
                    catSelect.disabled = true;
                    setStatus('st1', 'Este plan no cuenta con categorías activas.', 'err');
                  }
                } catch (e) {
                  catSelect.innerHTML = '<option value="">— error al cargar —</option>';
                  catSelect.disabled = true;
                  setStatus('st1', e.message || 'Ocurrió un error al obtener las categorías.', 'err');
                }
              }, 500);
            });
          }

          // ---------- Procesamiento e Inyección del Caso de Asistencia ----------
          const clearErrors = () => {
            document.querySelectorAll('.field-err').forEach((n) => n.remove());
            document.querySelectorAll('.invalid').forEach((n) => n.classList.remove('invalid'));
          };

          const formCaso = $('formCaso') || document.querySelector('form');
          const btnCaso = $('btnCaso') || document.querySelector('button[type="submit"]');

          if (formCaso) {
            formCaso.addEventListener('submit', async (ev) => {
              ev.preventDefault();
              clearErrors();

              if (catSelect && !catSelect.value) {
                return setStatus('st2', 'Por favor, selecciona una categoría para el caso.', 'err');
              }

              const payload = Object.fromEntries(new FormData(formCaso).entries());
              if (catSelect) payload.assistance_id = catSelect.value;
              if (planInput) payload.assistance_plan_id = planInput.value;

              if (btnCaso) btnCaso.disabled = true;
              setStatus('st2', 'Procesando solicitud con la entidad de asistencia…');

              try {
                const data = await call('/asistencias', {
                  method: 'POST',
                  body: JSON.stringify(payload)
                });

                const logEl = $('log');
                if (logEl) logEl.textContent = JSON.stringify(data, null, 2);
                setStatus('st2', 'Caso de asistencia creado de forma exitosa.', 'ok');

                // Opcional: Redirigir al panel principal tras el éxito
                setTimeout(() => {
                  window.location.href = "{{ route('dashboard') }}";
                }, 2000);

              } catch (e) {
                if (e.errors) {
                  Object.entries(e.errors).forEach(([field, msgs]) => {
                    const input = formCaso.elements[field];
                    if (!input) return;
                    input.classList.add('invalid');
                    const span = document.createElement('span');
                    span.className = 'field-err';
                    span.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                    input.parentElement.appendChild(span);
                  });
                  setStatus('st2', 'Por favor, revisa los campos marcados en rojo.', 'err');
                } else {
                  const logEl = $('log');
                  if (logEl) logEl.textContent = JSON.stringify(e, null, 2);
                  setStatus('st2', e.message, 'err');
                }
              } finally {
                if (btnCaso) btnCaso.disabled = false;
              }
            });
          }
        </script>

</body>

</html>