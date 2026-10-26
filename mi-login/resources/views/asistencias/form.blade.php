<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Solicitud de asistencia</title>
  <style>
    :root {
      /* --- Colores Corporativos Oficiales (Pág. 15-16) --- */
      --bg: #04114A;
      /* Azul Profundo oficial */
      --surface: #111B54;
      /* Fondo de contenedores corporativos sólido (Sin Glassmorphism) */
      --text: #FFFFFF;
      --muted: #98A9C4;
      --line: #2F3A75;
      --accent: #0F63FF;
      /* Azul Eléctrico oficial */
      --accent-text: #FFFFFF;
      --accent-cyan: #2FE7FF;
      /* Celeste oficial */
      --ok: #35FF57;
      /* Verde oficial corporativo */
      --err: #ff6b6b;
      --code: #02092B;
      --code-text: #cdd9e5;

      /* --- Tipografía Estricta (Pág. 18) --- */
      --font-avanta: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-avanta);
      font-size: 15px;
      line-height: 1.5;
    }

    /* --- Header de Control e Identidad de Marca --- */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      max-width: 860px;
      margin: 0 auto;
      padding: 30px 16px 15px 16px;
    }

    /* Envoltura del enlace para aplicar la animación interactiva del logo */
    header a {
      display: inline-block;
      transition: transform 0.2s ease-in-out;
      will-change: transform;
      line-height: 0;
    }

    /* Efecto hover: el isotipo crece sutilmente un aproximado de 2px */
    header a:hover {
      transform: scale(1.025);
    }

    /* Elemento que contiene la imagen del isotipo a gran escala */
    header .brand-logo {
      height: 84px;
      /* Isotipo limpio a gran escala */
      width: auto;
      display: block;
    }

    /* Contenedor unificado para la barra de acciones a la derecha */
    header .header-controls {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    /* Caja de sesión con bordes estructurados y tamaño aumentado */
    header .session-box {
      background-color: rgba(17, 27, 84, 0.4);
      border: 1px solid var(--line);
      border-radius: 20px;
      padding: 8px 18px;
      font-size: 15px;
      font-weight: 300;
      color: var(--muted);
    }

    header .session-box strong {
      color: var(--text);
      font-weight: 500;
    }

    /* --- Estructura de Paneles Sólidos --- */
    main {
      max-width: 860px;
      margin: 0 auto;
      padding: 16px 16px 64px;
    }

    h1 {
      font-size: 24px;
      font-weight: 700;
      margin: 0 0 4px;
      color: var(--text);
    }

    .lead {
      color: var(--muted);
      margin: 0 0 24px;
    }

    section {
      background: var(--surface);
      /* Fondo opaco sólido original del Brandbook */
      border: 1px solid var(--line);
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 16px;
    }

    h2 {
      font-size: 17px;
      font-weight: 700;
      margin: 0 0 4px;
      color: var(--text);
    }

    .hint {
      color: var(--muted);
      font-size: 13px;
      margin: 0 0 14px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 12px 16px;
    }

    label {
      display: flex;
      flex-direction: column;
      gap: 4px;
      font-size: 13px;
      color: var(--accent-cyan);
    }

    input,
    select {
      font: inherit;
      color: var(--text);
      background: rgba(4, 17, 74, 0.7);
      border: 1px solid var(--line);
      border-radius: 6px;
      padding: 8px 10px;
      min-height: 38px;
      font-weight: 300;
      transition: all 0.3s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: var(--accent-cyan);
    }

    input.invalid,
    select.invalid {
      border-color: var(--err);
    }

    input:focus-visible,
    select:focus-visible,
    button:focus-visible {
      outline: 2px solid var(--accent-cyan);
      outline-offset: 1px;
    }

    .field-err {
      color: var(--err);
      font-size: 12px;
    }

    fieldset {
      border: 0;
      padding: 0;
      margin: 18px 0 0;
    }

    legend {
      font-weight: 600;
      font-size: 14px;
      padding: 0;
      margin-bottom: 10px;
      color: var(--text);
    }

    /* --- Botones y Elementos de Acción --- */
    button {
      font: inherit;
      font-weight: 500;
      /* Jerarquía para acciones de Avanta: Medium */
      background: var(--accent);
      color: var(--accent-text);
      border: 0;
      border-radius: 6px;
      padding: 9px 18px;
      cursor: pointer;
      margin-top: 16px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    button:hover:not(:disabled) {
      background-color: var(--ok);
      color: var(--bg);
      font-weight: 700;
    }

    button.link {
      font-family: var(--font-avanta);
      background: transparent;
      color: var(--text-muted);
      border: 1px solid var(--line);
      margin: 0;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    button.link:hover:not(:disabled) {
      border-color: var(--accent-cyan);
      background-color: rgba(47, 231, 255, 0.05);
      color: var(--text);
    }

    button:disabled {
      opacity: .4;
      cursor: not-allowed;
    }

    .row {
      display: flex;
      gap: 10px;
      align-items: flex-end;
      flex-wrap: wrap;
    }

    .status {
      margin-top: 12px;
      font-size: 14px;
      min-height: 20px;
    }

    .status.ok {
      color: var(--ok);
    }

    .status.err {
      color: var(--err);
    }

    pre {
      background: var(--code);
      color: var(--code-text);
      padding: 12px;
      border-radius: 6px;
      overflow: auto;
      font-size: 12px;
      max-height: 260px;
      margin: 12px 0 0;
      border: 1px solid var(--line);
    }

    /* Control responsivo para el encabezado */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding-top: 20px;
      }

      header .header-controls {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
      } 
    }
  </style>
</head>
<!-- Favicon oficial integrado correctamente -->
<link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">

<body>
  <header>
    <!-- El logo limpio ahora es el botón de Inicio -->
    <a href="{{ route('dashboard') }}" style="display: block; line-height: 0;">
      <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Avanta Isotipo" class="brand-logo">
    </a>

    <div class="header-controls">
      <div class="session-box">
        Sesión: <strong>{{ auth()->user()->name }}</strong>
      </div>

      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button class="link" type="submit">Cerrar sesión</button>
      </form>
    </div>
  </header>

  <main>
    <h1>Solicitud de asistencia</h1>
    <p class="lead">Elige el plan y la categoría, completa los datos y crea el caso.</p>

    <!-- SECCIÓN ELIMINADA: El bloque de Modo Simulación / Mock ha sido removido por completo -->

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

        <fieldset>
          <legend>Ubicación</legend>
          <div class="grid">
            <label>País (ID) <input name="country_id" type="number" value="1"></label>
            <label>Departamento (state_id) <input name="state_id" type="number" value="1"></label>
            <label>Municipio (state_district_id) <input name="state_district_id" type="number" value="101"></label>
            <label>Zona <input name="zone" type="number" value="16"></label>
            <label>Colonia / barrio <input name="suburb"></label>
            <label>Calle <input name="street" value="0"></label>
            <label>Área <input name="area" value="Ciudad de Guatemala"></label>
            <label>Código postal <input name="postal_code" value="00000"></label>
            <label>Referencia <input name="reference"></label>
          </div>
        </fieldset>

        <button id="btnCaso" type="submit">Crear caso</button>
      </form>
      <div id="st2" class="status" role="status"></div>
      <details>
        <summary>Ver respuesta de la API</summary>
        <pre id="log">—</pre>
      </details>
    </section>
  </main>


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