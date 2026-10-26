<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Solicitud de asistencia</title>
  <style>
    :root {
      --bg: #111522;          /* Fondo azul muy oscuro de la paleta */
      --surface: #1E263B;     /* Fondo de las secciones y tarjetas */
      --text: #DCE3EF;        /* El gris más claro para textos principales */
      --muted: #536684;       /* Gris azulado para textos secundarios y hints */
      --line: #303D59;        /* Azul medio para los bordes de inputs y líneas */
      --accent: #98A9C4;      /* Azul claro destacado para botones y enfoques */
      --accent-text: #111522; /* Texto oscuro sobre botones claros para legibilidad */
      --ok: #5fd39a;          /* Verde suave para estados exitosos */
      --err: #ff6b6b;         /* Rojo suave para errores */
      --code: #0d1114;        /* Fondo oscuro para etiquetas <pre> de código */
      --code-text: #cdd9e5;   /* Texto claro dentro de bloques de código */
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      font: 15px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      max-width: 860px;
      margin: 0 auto;
      padding: 20px 16px 0;
    }

    header span {
      color: var(--muted);
      font-size: 14px;
    }

    main {
      max-width: 860px;
      margin: 0 auto;
      padding: 16px 16px 64px;
    }

    h1 {
      font-size: 24px;
      margin: 0 0 4px;
      color: var(--text);
    }

    .lead {
      color: var(--muted);
      margin: 0 0 24px;
    }

    section {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 16px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    h2 {
      font-size: 17px;
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
      color: var(--accent); /* Cambiado a color destacado para resaltar los campos */
    }

    input,
    select {
      font: inherit;
      color: var(--text);
      background: rgba(17, 21, 34, 0.6); /* Fondo semi-transparente oscuro */
      border: 1px solid var(--line);
      border-radius: 6px;
      padding: 8px 10px;
      min-height: 38px;
      transition: all 0.3s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(152, 169, 196, 0.15);
    }

    input.invalid,
    select.invalid {
      border-color: var(--err);
    }

    input:focus-visible,
    select:focus-visible,
    button:focus-visible {
      outline: 2px solid var(--accent);
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

    button {
      font: inherit;
      font-weight: 600;
      background: var(--accent);
      color: var(--accent-text);
      border: 0;
      border-radius: 6px;
      padding: 9px 18px;
      cursor: pointer;
      margin-top: 16px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover:not(:disabled) {
      background-color: var(--text-light);
      transform: translateY(-1px);
    }

    button.link {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
      margin: 0;
      padding: 6px 12px;
      transition: all 0.3s ease;
    }

    button.link:hover:not(:disabled) {
      color: var(--text-light);
      border-color: var(--text-light);
      background-color: rgba(152, 169, 196, 0.05);
      transform: none;
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

    details summary {
      cursor: pointer;
      color: var(--muted);
      font-size: 13px;
      margin-top: 12px;
      transition: color 0.2s ease;
    }

    details summary:hover {
      color: var(--accent);
    }
</style>

  </style>
</head>

<body>
  <header>
    <span>Sesión: {{ auth()->user()->name }}</span>
    <div class="row">
      <a href="{{ route('dashboard') }}" style="color:var(--accent)">Inicio</a>
      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button class="link" type="submit">Cerrar sesión</button>
      </form>
    </div>
  </header>

  <main>
    <h1>Solicitud de asistencia</h1>
    <p class="lead">Elige el plan y la categoría, completa los datos y crea el caso.</p>

    @if (config('services.seguro.mock'))
    <section id="panelMock">
      <h2>Modo simulación</h2>
      <p class="hint">La API es falsa (no sale a internet). Elige qué debe responder para ver cómo reacciona el formulario.</p>
      <label style="max-width:520px">Escenario
        <select id="escenario">
          @foreach (\App\Support\SeguroMock::ESCENARIOS as $clave => $texto)
          <option value="{{ $clave }}">{{ $texto }}</option>
          @endforeach
        </select>
      </label>
    </section>
    @endif

    <section>
      <<h2>Plan y categoría</h2>
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
      el.textContent = msg;
      el.className = 'status ' + type;
    };

    // Llamada a nuestro Laravel (él se encarga del token de la API externa)
    async function call(url, options = {}) {
      const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        ...(options.headers || {})
      };
      const esc = document.getElementById('escenario'); // solo existe en modo simulación
      if (esc) headers['X-Mock-Scenario'] = esc.value;
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
        message: 'La sesión expiró. Recarga la página.',
        status: 419
      };
      if (!res.ok) throw {
        message: data?.message || `Error ${res.status}`,
        errors: data?.errors,
        status: res.status
      };
      return data;
    }

    // Al cambiar de escenario, olvidamos el token guardado en simulación
    const escSel = document.getElementById('escenario');
    if (escSel) escSel.addEventListener('change', () => call('/asistencias/reiniciar-token', {
      method: 'POST'
    }).catch(() => {}));

    // ---------- Carga Automática de Categorías ----------
    const planInput = $('planId');
    const catSelect = $('categoria');
    let debounceTimer;

    planInput.addEventListener('change', () => {
      clearTimeout(debounceTimer);
      const planId = planInput.value; // <-- ASÍ DEBE QUEDAR 

      if (!planId) {
        catSelect.innerHTML = '<option value="">— escribe un ID primero —</option>';
        catSelect.disabled = true;
        setStatus('st1', '', '');
        return;
      }

      setStatus('st1', 'Buscando categorías...', '');

      // Espera 500ms después de que el usuario deja de escribir
      debounceTimer = setTimeout(async () => {
        try {
          // Llama a la ruta exacta de tu servidor Laravel
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
            setStatus('st1', `${list.length} categorías cargadas.`, 'ok');
          } else {
            catSelect.innerHTML = '<option value="">— sin categorías disponibles —</option>';
            catSelect.disabled = true;
            setStatus('st1', 'El plan no tiene categorías.', 'err');
          }
        } catch (e) {
          catSelect.innerHTML = '<option value="">— error al cargar —</option>';
          catSelect.disabled = true;
          setStatus('st1', e.message || 'Error al obtener categorías.', 'err');
        }
      }, 500);
    });

    // ---------- Enviar Formulario y Crear caso ----------
    const clearErrors = () => {
      document.querySelectorAll('.field-err').forEach((n) => n.remove());
      document.querySelectorAll('.invalid').forEach((n) => n.classList.remove('invalid'));
    };

    $('formCaso').addEventListener('submit', async (ev) => {
      ev.preventDefault();
      clearErrors();
      if (!catSelect.value) return setStatus('st2', 'Elige una categoría arriba.', 'err');

      const payload = Object.fromEntries(new FormData($('formCaso')).entries());
      payload.assistance_id = catSelect.value;
      payload.assistance_plan_id = planInput.value;

      $('btnCaso').disabled = true;
      setStatus('st2', 'Enviando…');
      try {
        const data = await call('/asistencias', {
          method: 'POST',
          body: JSON.stringify(payload)
        });
        $('log').textContent = JSON.stringify(data, null, 2);
        setStatus('st2', 'Caso creado correctamente.', 'ok');
      } catch (e) {
        if (e.errors) {
          Object.entries(e.errors).forEach(([field, msgs]) => {
            const input = $('formCaso').elements[field];
            if (!input) return;
            input.classList.add('invalid');
            const span = document.createElement('span');
            span.className = 'field-err';
            span.textContent = msgs[0];
            input.parentElement.appendChild(span);
          });
          setStatus('st2', 'Revisa los campos marcados.', 'err');
        } else {
          $('log').textContent = JSON.stringify(e, null, 2);
          setStatus('st2', e.message, 'err');
        }
      } finally {
        $('btnCaso').disabled = false;
      }
    });
  </script>

</body>

</html>