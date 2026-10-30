<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Emisión de Asistencia – Avanta</title>
  <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">
  <style>
    /* ============================================================
       VARIABLES Y RESET
    ============================================================ */
    :root {
      --azul: #04114A;
      --surface: #111B54;
      --electrico: #0F63FF;
      --cyan: #2FE7FF;
      --blanco: #FFFFFF;
      --borde: #2F3A75;
      --muted: #98A9C4;
      --ok: #35FF57;
      --err: #ff6b6b;
      --warn: #FFD166;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background: #FFFFFF;
      color: #04114A;
      font-size: 15px;
      line-height: 1.55;
    }

    /* ============================================================
       NAVBAR UNIFICADA (IGUAL A BITÁCORA SQL)
    ============================================================ */
    .navbar {
      background-color: var(--azul);
      border-bottom: 1px solid var(--borde);
      padding: 10px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #FFFFFF;
      width: 100%;
      box-sizing: border-box;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 1px;
    }

    .navbar-brand img {
      height: 65px;
      width: auto;
      display: block;
    }

    .navbar-brand span {
      font-size: 25px;
      font-weight: 300;
      color: var(--cyan);
      letter-spacing: 0.5px;
    }

    .nav-right-container {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    .nav-links-menu {
      display: flex;
      gap: 20px;
    }

    .nav-links-menu a {
      color: var(--muted);
      text-decoration: none;
      font-size: 15px;
      font-weight: 500;
      transition: color .3s;
    }

    .nav-links-menu a.active {
      color: var(--cyan);
      font-weight: 700;
    }

    .nav-links-menu a:hover {
      color: var(--blanco);
    }

    .btn-logout {
      background: transparent;
      border: 1px solid var(--cyan);
      color: var(--cyan);
      padding: 8px 16px;
      font-size: 14px;
      font-weight: 500;
      border-radius: 4px;
      cursor: pointer;
      transition: all .3s;
      font-family: inherit;
    }

    .btn-logout:hover {
      background: var(--cyan);
      color: var(--azul);
    }

    /* ============================================================
       LAYOUT & HEADER
    ============================================================ */
    main {
      max-width: 960px;
      margin: 0 auto;
      padding: 30px 20px 60px;
    }

    .page-header-card {
      background: var(--surface);
      border: 1px solid var(--borde);
      border-radius: 12px;
      padding: 24px 32px;
      margin-bottom: 22px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    }

    .page-header-card h1 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--cyan);
      margin-bottom: 4px;
      letter-spacing: .5px;
    }

    .page-header-card .lead {
      color: var(--muted);
      font-size: .95rem;
      margin-bottom: 0;
    }

    section {
      background: var(--surface);
      border: 1px solid var(--borde);
      border-radius: 12px;
      padding: 28px 32px;
      margin-bottom: 22px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    }

    h2 {
      font-size: 1.3rem;
      color: var(--blanco);
      margin-bottom: 4px;
      font-weight: 700;
    }

    .hint {
      color: var(--muted);
      font-size: .88rem;
      margin-bottom: 18px;
    }

    /* ============================================================
       GRIDS / FILAS
    ============================================================ */
    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .grid-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
    }

    .grid-4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
    }

    @media(max-width:768px) {

      .grid-2,
      .grid-3,
      .grid-4 {
        grid-template-columns: 1fr;
      }
    }

    /* ============================================================
       CAMPOS CON SOMBRA Y ESTILO AVANTA
    ============================================================ */
    .field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .field label {
      font-size: .88rem;
      font-weight: 700;
      color: var(--cyan);
      letter-spacing: .3px;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 6px;
    }

    .field .badge-static {
      font-size: .75rem;
      font-weight: 700;
      background: rgba(47, 231, 255, .15);
      color: var(--cyan);
      border: 1px solid rgba(47, 231, 255, .35);
      padding: 2px 8px;
      border-radius: 12px;
      vertical-align: middle;
      box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px 14px;
      background: rgba(4, 17, 74, .85);
      border: 1px solid var(--borde);
      border-radius: 7px;
      color: var(--blanco);
      font-size: .95rem;
      font-family: inherit;
      box-shadow: 0 4px 10px rgba(0, 0, 0, .4), inset 0 1px 2px rgba(0, 0, 0, .3);
      transition: all .25s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: var(--cyan);
      box-shadow: 0 0 0 3px rgba(47, 231, 255, .3), 0 6px 16px rgba(0, 0, 0, .55);
    }

    input[readonly],
    input:disabled,
    select:disabled {
      background: rgba(4, 17, 74, .4);
      color: var(--muted);
      cursor: not-allowed;
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, .3);
    }

    select option {
      background: #111B54;
      color: #fff;
    }

    /* ============================================================
       SEPARADOR FIELDSET
    ============================================================ */
    fieldset {
      border: 1px solid var(--borde);
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 18px;
      background: rgba(4, 17, 74, .25);
      box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    legend {
      font-size: .85rem;
      font-weight: 800;
      padding: 0 10px;
      color: var(--cyan);
      text-transform: uppercase;
      letter-spacing: .8px;
    }

    /* ============================================================
       BOTÓN ENVIAR
    ============================================================ */
    #btnEmitir {
      width: 100%;
      background: var(--electrico);
      color: var(--blanco);
      border: none;
      padding: 14px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 8px;
      transition: all .3s;
      font-family: inherit;
    }

    #btnEmitir:hover:not(:disabled) {
      background: var(--cyan);
      color: var(--azul);
    }

    #btnEmitir:disabled {
      opacity: .55;
      cursor: not-allowed;
    }

    /* ============================================================
       ESTADO / ALERTAS
    ============================================================ */
    #statusBar {
      margin-top: 16px;
      padding: 12px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: .95rem;
      display: none;
    }

    #statusBar.ok {
      background: rgba(53, 255, 87, .1);
      color: var(--ok);
      border: 1px solid var(--ok);
      display: block;
    }

    #statusBar.err {
      background: rgba(255, 107, 107, .1);
      color: var(--err);
      border: 1px solid var(--err);
      display: block;
    }

    #statusBar.info {
      background: rgba(15, 99, 255, .15);
      color: #7eb8ff;
      border: 1px solid var(--electrico);
      display: block;
    }

    /* ============================================================
       LOG RAW
    ============================================================ */
    details {
      margin-top: 20px;
      border-top: 1px solid #E2E8F0;
      padding-top: 14px;
    }

    summary {
      cursor: pointer;
      color: #04114A;
      font-size: .88rem;
      font-weight: 600;
    }

    #log {
      background: #02092B;
      color: #35FF57;
      padding: 14px;
      border-radius: 6px;
      font-family: monospace;
      font-size: .82rem;
      overflow-x: auto;
      margin-top: 10px;
      white-space: pre;
      max-height: 320px;
      overflow-y: auto;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="navbar-brand">
      <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Logotipo Avanta">
      @role('admin') <span>| Panel</span> @endrole
    </div>

    <div class="nav-right-container">
      <div class="nav-links-menu">
        <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('asistencias') }}" class="{{ Request::is('asistencias*') ? 'active' : '' }}">Emitir Asistencia</a>
        @role('admin')
        <a href="{{ route('admin.usuarios') }}" class="{{ Request::is('admin/usuarios*') ? 'active' : '' }}">Usuarios</a>
        <a href="{{ route('admin.bitacora') }}" class="{{ Request::is('admin/bitacora*') ? 'active' : '' }}">Bitácora SQL</a>
        @endrole
      </div>
      <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-logout">Cerrar Sesión</button>
      </form>
    </div>
  </nav>

  <!-- MAIN -->
  <main>
    <div class="page-header-card">
      <h1>Emisión de Asistencia</h1>
      <p class="lead">Completa el formulario para registrar y emitir una nueva transacción de asistencia (EMI).</p>
    </div>

    <form id="formEmision" novalidate>
      @csrf

      <!-- ────────────────────────────────────────
           SECCIÓN 1 – DATOS DE LA TRANSACCIÓN
      ──────────────────────────────────────── -->
      <section>
        <h2>Transacción</h2>
        <p class="hint">Datos principales de la emisión. Los campos con <span style="color:var(--cyan)">🔒</span> son predefinidos o de sistema.</p>

        <div class="grid-2">

          <div class="field">
            <label for="transactionId">
              No. de Crédito / ID de Transacción
              <span class="badge-static" title="Número único por cliente, no se puede repetir">🔒 Único</span>
            </label>
            <input type="text" id="transactionId" name="transactionId"
              value="CRE-99990001" placeholder="Ej. CRE-99990001" required
              pattern="[A-Za-z0-9\-]+"
              title="Solo letras, números y guiones">
          </div>

          <div class="field">
            <label for="tranTypeDescription">Descripción de la Transacción</label>
            <input type="text" id="tranTypeDescription" name="tranTypeDescription"
              value="Emisión de asistencia" required>
          </div>

          <div class="field">
            <label for="countryId">País <span class="badge-static">📡 Catálogo</span></label>
            <select id="countryId" name="countryId" required>
              <option value="GT" selected>GT – Guatemala</option>
              <option value="SV">SV – El Salvador</option>
            </select>
          </div>

          <div class="field">
            <label for="planId">Plan de Cobertura <span class="badge-static">🔒 Por País y Grupo</span></label>
            <select id="planId" name="planId" required>
              <option value="1887" selected>1887 – Asistencia + Saldo Deudor + Desembolso GT</option>
              <option value="1902">1902 – Asistencia + Saldo Deudor GT</option>
            </select>
          </div>

          <div class="field">
            <label for="sellerId">Vendedor / Asesor <span class="badge-static">🔒 Usuario activo</span></label>
            <input type="text" id="sellerId" name="sellerId"
              value="{{ strtolower(explode(' ', Auth::user()->name)[0]) }}"
              readonly
              title="Tomado del nombre del usuario en sesión">
          </div>

          <div class="field">
            <label for="salesChannel">Canal de Ventas <span class="badge-static">🔒 ORG</span></label>
            <input type="text" id="salesChannel" name="salesChannel" value="ORG" readonly>
          </div>

          <div class="field">
            <label for="sponsorId">Patrocinador <span class="badge-static">🔒 AVANTA</span></label>
            <input type="text" id="sponsorId" name="sponsorId" value="AVANTA" readonly>
          </div>

          <div class="field">
            <label for="issuedDate">Fecha de Emisión</label>
            <input type="date" id="issuedDate" name="issuedDate" required>
          </div>

          <div class="field">
            <label for="tranDate">Fecha de Transacción</label>
            <input type="date" id="tranDate" name="tranDate" required>
          </div>

          <div class="field">
            <label for="admissionEffectiveDate">Fecha Efectiva de Admisión</label>
            <input type="date" id="admissionEffectiveDate" name="admissionEffectiveDate" required>
          </div>

          <div class="field">
            <label for="expirationDate">
              Fecha de Expiración
              <span class="badge-static">Calculada (+1 año)</span>
            </label>
            <input type="date" id="expirationDate" name="expirationDate" required>
          </div>

        </div>
      </section>

      <!-- ────────────────────────────────────────
           SECCIÓN 2 – UBICACIÓN DINÁMICA (CATÁLOGOS)
      ──────────────────────────────────────── -->
      <section>
        <h2>Ubicación</h2>
        <p class="hint">Departamento y municipio cargados dinámicamente según el país seleccionado.</p>

        <div class="grid-2">
          <div class="field">
            <label for="stateId">Departamento <span class="badge-static">📡 Catálogo</span></label>
            <select id="stateId" name="stateId" required>
              <option value="1" selected>1 – Guatemala</option>
              <option value="2">2 – Sacatepéquez</option>
              <option value="3">3 – Chimaltenango</option>
              <option value="4">4 – El Progreso</option>
              <option value="5">5 – Escuintla</option>
              <option value="6">6 – Santa Rosa</option>
              <option value="7">7 – Sololá</option>
              <option value="8">8 – Totonicapán</option>
              <option value="9">9 – Quetzaltenango</option>
              <option value="10">10 – Suchitepéquez</option>
              <option value="11">11 – Retalhuleu</option>
              <option value="12">12 – San Marcos</option>
              <option value="13">13 – Huehuetenango</option>
              <option value="14">14 – Quiché</option>
              <option value="15">15 – Baja Verapaz</option>
              <option value="16">16 – Alta Verapaz</option>
              <option value="17">17 – Petén</option>
              <option value="18">18 – Izabal</option>
              <option value="19">19 – Zacapa</option>
              <option value="20">20 – Chiquimula</option>
              <option value="21">21 – Jalapa</option>
              <option value="22">22 – Jutiapa</option>
            </select>
          </div>

          <div class="field">
            <label for="districtId">Municipio / Distrito <span class="badge-static">📡 Catálogo</span></label>
            <select id="districtId" name="districtId" required>
              <option value="1">1 – Guatemala (Ciudad Capital)</option>
              <option value="2">2 – Santa Catarina Pinula</option>
              <option value="3">3 – San José Pinula</option>
              <option value="4" selected>4 – San José del Golfo</option>
              <option value="5">5 – Palencia</option>
              <option value="6">6 – Chinautla</option>
              <option value="7">7 – San Pedro Ayampuc</option>
              <option value="8">8 – Mixco</option>
              <option value="9">9 – San Pedro Sacatepéquez</option>
              <option value="10">10 – San Juan Sacatepéquez</option>
              <option value="11">11 – San Raymundo</option>
              <option value="12">12 – Chuarrancho</option>
              <option value="13">13 – Fraijanes</option>
              <option value="14">14 – Amatitlán</option>
              <option value="15">15 – Villa Nueva</option>
              <option value="16">16 – Villa Canales</option>
              <option value="17">17 – San Miguel Petapa</option>
            </select>
          </div>
        </div>
      </section>

      <!-- ────────────────────────────────────────
           SECCIÓN 3 – CLIENTE (CUSTOMERS)
      ──────────────────────────────────────── -->
      <section>
        <h2>Datos del Titular</h2>
        <p class="hint">Información del miembro principal de la asistencia (type: MI).</p>

        <fieldset>
          <legend>Identificación</legend>
          <div class="grid-3">

            <div class="field">
              <label for="nationalIdType">Tipo de Documento</label>
              <select id="nationalIdType" name="nationalIdType">
                <option value="DPI" selected>DPI</option>
                <option value="PASAPORTE">Pasaporte</option>
              </select>
            </div>

            <div class="field">
              <label for="nationalId">Número de Documento</label>
              <input type="text" id="nationalId" name="nationalId"
                value="123485156" placeholder="Ej. 123485156" required>
            </div>

            <div class="field">
              <label for="birthDate">Fecha de Nacimiento</label>
              <input type="date" id="birthDate" name="birthDate" value="1985-08-05" required>
            </div>

          </div>
        </fieldset>

        <fieldset>
          <legend>Nombre</legend>
          <div class="grid-4">
            <div class="field">
              <label for="firstName">Primer Nombre</label>
              <input type="text" id="firstName" name="firstName"
                value="José" placeholder="José" required>
            </div>
            <div class="field">
              <label for="middleName">Segundo Nombre</label>
              <input type="text" id="middleName" name="middleName" value="J" placeholder="J">
            </div>
            <div class="field">
              <label for="lastName">Primer Apellido</label>
              <input type="text" id="lastName" name="lastName"
                value="Pérez" placeholder="Pérez" required>
            </div>
            <div class="field">
              <label for="secondLastName">Segundo Apellido</label>
              <input type="text" id="secondLastName" name="secondLastName" value="P" placeholder="P">
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Contacto</legend>
          <div class="grid-3">
            <div class="field">
              <label for="emailAddress">Correo Electrónico <span class="badge-static">Requerido</span></label>
              <input type="email" id="emailAddress" name="emailAddress"
                value="jperez@asegurosa.com" placeholder="titular@ejemplo.com" required>
            </div>
            <div class="field">
              <label for="phoneNumber1">Teléfono Principal <span class="badge-static">Requerido</span></label>
              <input type="tel" id="phoneNumber1" name="phoneNumber1"
                value="50212345678" placeholder="Ej. 50212345678"
                pattern="[0-9\+\-\s]{8,20}"
                title="Ingresa un número telefónico válido (mínimo 8 dígitos)" required>
            </div>
            <div class="field">
              <label for="phoneNumber2">Teléfono 2 (opcional)</label>
              <input type="tel" id="phoneNumber2" name="phoneNumber2">
            </div>
          </div>
        </fieldset>

        <fieldset>
          <legend>Otros datos</legend>
          <div class="grid-3">
            <div class="field">
              <label for="address">Dirección</label>
              <input type="text" id="address" name="address"
                value="Ciudad" placeholder="Ciudad" required>
            </div>
            <div class="field">
              <label for="gender">Género</label>
              <select id="gender" name="gender">
                <option value="1" selected>Masculino</option>
                <option value="2">Femenino</option>
              </select>
            </div>
            <div class="field">
              <label for="company">Empresa (opcional)</label>
              <input type="text" id="company" name="company" placeholder="Nombre de empresa">
            </div>
          </div>
        </fieldset>
      </section>

      <!-- ────────────────────────────────────────
           SECCIÓN 4 – DATOS ADICIONALES
      ──────────────────────────────────────── -->
      <section>
        <h2>Datos Adicionales</h2>
        <p class="hint">Información del vendedor y la ubicación de venta.</p>

        <div class="grid-2">
          <div class="field">
            <label for="salesman_name">Nombre del Vendedor</label>
            <input type="text" id="salesman_name" name="salesman_name"
              value="Nombre Vendedor" placeholder="Nombre completo del vendedor" required>
          </div>
          <div class="field">
            <label for="location_name">Nombre de la Agencia / Locación</label>
            <input type="text" id="location_name" name="location_name"
              value="01" placeholder="Ej. 01" required>
          </div>
        </div>
      </section>

      <!-- ────────────────────────────────────────
           SECCIÓN 5 – DATOS ADICIONALES (EXTRA DATA)
      ──────────────────────────────────────── -->
      <section>
        <h2>Datos Adicionales (Extra Data)</h2>
        <p class="hint">Parámetros complementarios requeridos para la transacción.</p>

        <div class="grid-2">
          <div class="field">
            <label for="extraDataKey">Tipo de Clave (Key)</label>
            <select id="extraDataKey" name="extraDataKey">
              <option value="price" selected>price – Precio / Tarifa (480.00)</option>
              <option value="saldo">saldo – Saldo del Crédito (1180800)</option>
              <option value="ambos">ambos – Enviar saldo + price</option>
            </select>
          </div>

          <!-- Campo para cuando es 'price' o 'saldo' individual -->
          <div class="field" id="singleValueContainer">
            <label for="extraDataValue" id="extraDataValueLabel">Precio / Tarifa (price)</label>
            <input type="text" id="extraDataValue" name="extraDataValue"
              value="480.00" placeholder="Ej. 480.00" required>
          </div>
        </div>

        <!-- Contenedor visible solo cuando se selecciona 'ambos' -->
        <div class="grid-2" id="bothValuesContainer" style="display: none; margin-top: 15px;">
          <div class="field">
            <label for="extraDataSaldo">Saldo del Crédito (saldo)</label>
            <input type="text" id="extraDataSaldo" value="1180800" placeholder="Ej. 1180800">
          </div>
          <div class="field">
            <label for="extraDataPrice">Precio / Tarifa (price)</label>
            <input type="text" id="extraDataPrice" value="480.00" placeholder="Ej. 480.00">
          </div>
        </div>
      </section>

      <!-- ────────────────────────────────────────
           SECCIÓN 6 – DATOS DEL VEHÍCULO
      ──────────────────────────────────────── -->
      <section>
        <h2>Datos del Vehículo</h2>
        <p class="hint">Información del vehículo amparado por el contrato de asistencia.</p>

        <div class="grid-4">
          <div class="field">
            <label for="carBrand">Marca</label>
            <input type="text" id="carBrand" name="carBrand" value="JEEP" placeholder="Ej. JEEP" required>
          </div>
          <div class="field">
            <label for="carModel">Línea / Modelo</label>
            <input type="text" id="carModel" name="carModel" value="RENEGADE" placeholder="Ej. RENEGADE" required>
          </div>
          <div class="field">
            <label for="carType">Tipo de Vehículo</label>
            <input type="text" id="carType" name="carType" value="CAMIONETA" placeholder="Ej. CAMIONETA" required>
          </div>
          <div class="field">
            <label for="carYear">Año</label>
            <input type="text" id="carYear" name="carYear" value="2025" placeholder="Ej. 2025" required>
          </div>
        </div>

        <div class="grid-4" style="margin-top: 16px;">
          <div class="field">
            <label for="carPlate">Número de Placa</label>
            <input type="text" id="carPlate" name="carPlate" value="JAF0432" placeholder="Ej. JAF0432" required>
          </div>
          <div class="field">
            <label for="carColor">Color</label>
            <input type="text" id="carColor" name="carColor" value="PLATEADO" placeholder="Ej. PLATEADO" required>
          </div>
          <div class="field">
            <label for="carSerialNumber">Número de Serie / Chasis</label>
            <input type="text" id="carSerialNumber" name="carSerialNumber" value="ZACCJBDT1FPB50454" placeholder="Ej. ZACCJBDT1FPB50454" required>
          </div>
          <div class="field">
            <label for="carEngineNumber">Número de Motor</label>
            <input type="text" id="carEngineNumber" name="carEngineNumber" value="ZACCJBDT1FPB50454" placeholder="Ej. ZACCJBDT1FPB50454" required>
          </div>
        </div>
      </section>

      <!-- ────────────────────────────────────────
           BOTÓN Y STATUS
      ──────────────────────────────────────── -->
      <button type="submit" id="btnEmitir">⚡ Emitir Transacción</button>

      <div id="statusBar"></div>

      <details open style="margin-top: 20px;">
        <summary>Ver respuesta del servidor (JSON)</summary>
        <pre id="log">— presiona "Emitir Transacción" para ver la respuesta aquí —</pre>
      </details>

    </form>
  </main>

  <script>
    /* ============================================================
       INICIALIZAR FECHAS POR DEFECTO
    ============================================================ */
    (function() {
      const today = new Date();
      const yyyy = today.getFullYear();
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const dd = String(today.getDate()).padStart(2, '0');
      const iso = `${yyyy}-${mm}-${dd}`;

      const nextYear = `${yyyy + 1}-${mm}-${dd}`;

      document.getElementById('issuedDate').value = iso;
      document.getElementById('tranDate').value = iso;
      document.getElementById('admissionEffectiveDate').value = iso;
      document.getElementById('expirationDate').value = nextYear;
    })();

    /* ============================================================
       HELPERS
    ============================================================ */
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const status = document.getElementById('statusBar');
    const logEl = document.getElementById('log');
    const btn = document.getElementById('btnEmitir');

    function showStatus(msg, type = 'info') {
      status.textContent = msg;
      status.className = type;
    }

    function setLog(data) {
      logEl.textContent = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
    }

    /* ============================================================
       CATÁLOGOS DINÁMICOS DE PAÍSES, DEPARTAMENTOS Y MUNICIPIOS (APIs)
       Exclusivo para Guatemala (GT) y El Salvador (SV)
    ============================================================ */
    const DEPARTAMENTOS_LOCALES = {
      GT: [{
          id: 1,
          name: 'Guatemala'
        },
        {
          id: 2,
          name: 'Sacatepéquez'
        },
        {
          id: 3,
          name: 'Chimaltenango'
        },
        {
          id: 4,
          name: 'El Progreso'
        },
        {
          id: 5,
          name: 'Escuintla'
        },
        {
          id: 6,
          name: 'Santa Rosa'
        },
        {
          id: 7,
          name: 'Sololá'
        },
        {
          id: 8,
          name: 'Totonicapán'
        },
        {
          id: 9,
          name: 'Quetzaltenango'
        },
        {
          id: 10,
          name: 'Suchitepéquez'
        },
        {
          id: 11,
          name: 'Retalhuleu'
        },
        {
          id: 12,
          name: 'San Marcos'
        },
        {
          id: 13,
          name: 'Huehuetenango'
        },
        {
          id: 14,
          name: 'Quiché'
        },
        {
          id: 15,
          name: 'Baja Verapaz'
        },
        {
          id: 16,
          name: 'Alta Verapaz'
        },
        {
          id: 17,
          name: 'Petén'
        },
        {
          id: 18,
          name: 'Izabal'
        },
        {
          id: 19,
          name: 'Zacapa'
        },
        {
          id: 20,
          name: 'Chiquimula'
        },
        {
          id: 21,
          name: 'Jalapa'
        },
        {
          id: 22,
          name: 'Jutiapa'
        }
      ],
      SV: [{
          id: 1,
          name: 'San Salvador'
        },
        {
          id: 2,
          name: 'Santa Ana'
        },
        {
          id: 3,
          name: 'San Miguel'
        },
        {
          id: 4,
          name: 'La Libertad'
        },
        {
          id: 5,
          name: 'Sonsonate'
        },
        {
          id: 6,
          name: 'Usulután'
        },
        {
          id: 7,
          name: 'Ahuachapán'
        },
        {
          id: 8,
          name: 'La Paz'
        },
        {
          id: 9,
          name: 'Cabañas'
        },
        {
          id: 10,
          name: 'Chalatenango'
        },
        {
          id: 11,
          name: 'Cuscatlán'
        },
        {
          id: 12,
          name: 'Morazán'
        },
        {
          id: 13,
          name: 'San Vicente'
        },
        {
          id: 14,
          name: 'La Unión'
        }
      ]
    };

    const DISTRITOS_LOCALES = {
      GT: {
        1: [{
            id: 1,
            name: 'Guatemala (Ciudad Capital)'
          },
          {
            id: 2,
            name: 'Santa Catarina Pinula'
          },
          {
            id: 3,
            name: 'San José Pinula'
          },
          {
            id: 4,
            name: 'San José del Golfo'
          },
          {
            id: 5,
            name: 'Palencia'
          },
          {
            id: 6,
            name: 'Chinautla'
          },
          {
            id: 7,
            name: 'San Pedro Ayampuc'
          },
          {
            id: 8,
            name: 'Mixco'
          },
          {
            id: 9,
            name: 'San Pedro Sacatepéquez'
          },
          {
            id: 10,
            name: 'San Juan Sacatepéquez'
          },
          {
            id: 11,
            name: 'San Raymundo'
          },
          {
            id: 12,
            name: 'Chuarrancho'
          },
          {
            id: 13,
            name: 'Fraijanes'
          },
          {
            id: 14,
            name: 'Amatitlán'
          },
          {
            id: 15,
            name: 'Villa Nueva'
          },
          {
            id: 16,
            name: 'Villa Canales'
          },
          {
            id: 17,
            name: 'San Miguel Petapa'
          }
        ],
        2: [{
            id: 1,
            name: 'Antigua Guatemala'
          },
          {
            id: 2,
            name: 'Jocotenango'
          },
          {
            id: 3,
            name: 'Pastores'
          },
          {
            id: 4,
            name: 'Sumpango'
          },
          {
            id: 5,
            name: 'Santo Domingo Xenacoj'
          },
          {
            id: 6,
            name: 'Santiago Sacatepéquez'
          },
          {
            id: 7,
            name: 'San Bartolomé Milpas Altas'
          },
          {
            id: 8,
            name: 'San Lucas Sacatepéquez'
          },
          {
            id: 9,
            name: 'Santa Lucía Milpas Altas'
          },
          {
            id: 10,
            name: 'Ciudad Vieja'
          }
        ],
        3: [{
            id: 1,
            name: 'Chimaltenango'
          },
          {
            id: 2,
            name: 'San José Poaquil'
          },
          {
            id: 3,
            name: 'San Martín Jilotepeque'
          },
          {
            id: 4,
            name: 'Comalapa'
          },
          {
            id: 5,
            name: 'Tecpán Guatemala'
          },
          {
            id: 6,
            name: 'Patzún'
          },
          {
            id: 7,
            name: 'El Tejar'
          }
        ],
        4: [{
            id: 1,
            name: 'Guastatoya'
          },
          {
            id: 2,
            name: 'Morazán'
          },
          {
            id: 3,
            name: 'San Agustín Acasaguastlán'
          },
          {
            id: 4,
            name: 'Sanarate'
          }
        ],
        5: [{
            id: 1,
            name: 'Escuintla'
          },
          {
            id: 2,
            name: 'Santa Lucía Cotzumalguapa'
          },
          {
            id: 3,
            name: 'La Democracia'
          },
          {
            id: 4,
            name: 'Siquinalá'
          },
          {
            id: 5,
            name: 'Tiquisate'
          },
          {
            id: 6,
            name: 'Palín'
          },
          {
            id: 7,
            name: 'Puerto San José'
          }
        ],
        9: [{
            id: 1,
            name: 'Quetzaltenango'
          },
          {
            id: 2,
            name: 'Salcajá'
          },
          {
            id: 3,
            name: 'Olintepeque'
          },
          {
            id: 4,
            name: 'Coatepeque'
          },
          {
            id: 5,
            name: 'La Esperanza'
          }
        ]
      },
      SV: {
        1: [{
            id: 1,
            name: 'San Salvador Centro'
          },
          {
            id: 2,
            name: 'Soyapango'
          },
          {
            id: 3,
            name: 'Ilopango'
          },
          {
            id: 4,
            name: 'Mejicanos'
          },
          {
            id: 5,
            name: 'Apopa'
          },
          {
            id: 6,
            name: 'Ciudad Delgado'
          },
          {
            id: 7,
            name: 'San Marcos'
          },
          {
            id: 8,
            name: 'Cuscatancingo'
          },
          {
            id: 9,
            name: 'San Martín'
          },
          {
            id: 10,
            name: 'Ayutuxtepeque'
          },
          {
            id: 11,
            name: 'Tonacatepeque'
          },
          {
            id: 12,
            name: 'Nejapa'
          }
        ],
        2: [{
            id: 1,
            name: 'Santa Ana Centro'
          },
          {
            id: 2,
            name: 'Chalchuapa'
          },
          {
            id: 3,
            name: 'Metapán'
          },
          {
            id: 4,
            name: 'Coatepeque'
          },
          {
            id: 5,
            name: 'El Congo'
          }
        ],
        3: [{
            id: 1,
            name: 'San Miguel Centro'
          },
          {
            id: 2,
            name: 'Chinameca'
          },
          {
            id: 3,
            name: 'Ciudad Barrios'
          },
          {
            id: 4,
            name: 'Moncagua'
          }
        ],
        4: [{
            id: 1,
            name: 'Santa Tecla (Sur)'
          },
          {
            id: 2,
            name: 'Antiguo Cuscatlán (Este)'
          },
          {
            id: 3,
            name: 'Colón (Oeste)'
          },
          {
            id: 4,
            name: 'San Juan Opico'
          },
          {
            id: 5,
            name: 'Quezaltepeque (Norte)'
          },
          {
            id: 6,
            name: 'Puerto de La Libertad (Costa)'
          },
          {
            id: 7,
            name: 'Zaragoza'
          }
        ],
        5: [{
            id: 1,
            name: 'Sonsonate Centro'
          },
          {
            id: 2,
            name: 'Acajutla'
          },
          {
            id: 3,
            name: 'Izalco'
          },
          {
            id: 4,
            name: 'Nahuizalco'
          },
          {
            id: 5,
            name: 'Juayúa'
          }
        ]
      }
    };

    function renderStatesOptions(list) {
      const stateSelect = document.getElementById('stateId');
      const prevVal = stateSelect.value;
      stateSelect.innerHTML = '';
      list.forEach(s => {
        const id = s.id ?? s.stateId ?? '';
        const name = s.name ?? s.description ?? `Departamento ${id}`;
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = `${id} – ${name}`;
        if (String(id) === String(prevVal)) opt.selected = true;
        stateSelect.appendChild(opt);
      });
      if (!stateSelect.value && stateSelect.options.length > 0) {
        stateSelect.selectedIndex = 0;
      }
    }

    function renderDistrictsOptions(list, preferredId = null) {
      const distSelect = document.getElementById('districtId');
      distSelect.innerHTML = '';
      if (!list || list.length === 0) {
        list = [{
            id: 1,
            name: 'Cabecera Municipal'
          },
          {
            id: 2,
            name: 'Distrito Norte'
          },
          {
            id: 3,
            name: 'Distrito Sur'
          },
          {
            id: 4,
            name: 'Distrito Central'
          },
        ];
      }

      list.forEach(d => {
        const id = d.id ?? d.districtId ?? '';
        const name = d.name ?? d.description ?? `Municipio ${id}`;
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = `${id} – ${name}`;
        if (preferredId !== null && String(id) === String(preferredId)) {
          opt.selected = true;
        }
        distSelect.appendChild(opt);
      });

      if (!distSelect.value && distSelect.options.length > 0) {
        distSelect.selectedIndex = 0;
      }
    }

    async function loadDistricts(countryCode, stateId) {
      const code = (countryCode || 'GT').toUpperCase();
      const stateNum = parseInt(stateId || 1, 10);
      const distSelect = document.getElementById('districtId');
      const prevVal = distSelect.value;

      // 1. Render inmediato desde la base local
      const localList = (DISTRITOS_LOCALES[code] && DISTRITOS_LOCALES[code][stateNum]) || [{
          id: 1,
          name: 'Cabecera Municipal'
        },
        {
          id: 2,
          name: 'Distrito Norte'
        },
        {
          id: 3,
          name: 'Distrito Sur'
        },
        {
          id: 4,
          name: 'Distrito Central'
        },
        {
          id: 5,
          name: 'Distrito Oriente'
        }
      ];

      const defaultSelection = prevVal || (code === 'GT' && stateNum === 1 ? 4 : 1);
      renderDistrictsOptions(localList, defaultSelection);

      // 2. Refresca desde el endpoint de la API
      try {
        const res = await fetch(`/asistencias/catalogo/countries/${encodeURIComponent(code)}/states/${encodeURIComponent(stateNum)}/districts`);
        if (res.ok) {
          const data = await res.json();
          const list = Array.isArray(data) ? data : (data.data || data.items || []);
          if (list.length > 0) {
            renderDistrictsOptions(list, distSelect.value || defaultSelection);
          }
        }
      } catch (e) {
        console.warn('Usando catálogo municipal predeterminado');
      }
    }

    async function loadStates(countryCode) {
      const code = (countryCode || 'GT').toUpperCase();
      // Render inmediato según el país seleccionado (GT: 22 depts, SV: 14 depts)
      const baseList = DEPARTAMENTOS_LOCALES[code] || DEPARTAMENTOS_LOCALES.GT;
      renderStatesOptions(baseList);

      const stateSelect = document.getElementById('stateId');

      // Refresca desde el endpoint de catálogo de estados de la API
      try {
        const res = await fetch(`/asistencias/catalogo/countries/${encodeURIComponent(code)}/states`);
        if (res.ok) {
          const data = await res.json();
          const list = Array.isArray(data) ? data : (data.data || data.items || []);
          if (list.length > 0) {
            renderStatesOptions(list);
          }
        }
      } catch (e) {
        console.warn('Usando catálogo departamental predeterminado');
      }

      // Al cambiar departamento o país, cargamos los distritos del departamento activo
      loadDistricts(code, stateSelect.value || 1);
    }

    async function loadCountries() {
      const countrySelect = document.getElementById('countryId');
      try {
        const res = await fetch('/asistencias/catalogo/countries');
        if (res.ok) {
          const data = await res.json();
          const list = Array.isArray(data) ? data : (data.data || data.items || []);
          // Exclusivo Guatemala (GT) y El Salvador (SV)
          const validList = list.filter(c => {
            const code = (c.code || c.countryId || c.id || '').toUpperCase();
            return code === 'GT' || code === 'SV';
          });

          if (validList.length > 0) {
            const currentVal = countrySelect.value || 'GT';
            countrySelect.innerHTML = '';
            validList.forEach(c => {
              const code = (c.code || c.countryId || c.id).toUpperCase();
              const name = c.name || c.description || (code === 'GT' ? 'Guatemala' : 'El Salvador');
              const opt = document.createElement('option');
              opt.value = code;
              opt.textContent = `${code} – ${name}`;
              if (code === currentVal) opt.selected = true;
              countrySelect.appendChild(opt);
            });
          }
        }
      } catch (e) {
        console.warn('Usando catálogo base de países (GT, SV)');
      }

      loadStates(countrySelect.value || 'GT');
    }

    const PLANES_POR_PAIS = {
      GT: [{
          id: 1887,
          name: '1887 – Asistencia + Saldo Deudor + Desembolso GT'
        },
        {
          id: 1902,
          name: '1902 – Asistencia + Saldo Deudor GT'
        }
      ],
      SV: [{
          id: 1888,
          name: '1888 – Asistencia + Saldo Deudor + Desembolso SV'
        },
        {
          id: 1903,
          name: '1903 – Asistencia + Saldo Deudor SV'
        }
      ]
    };

    function updatePlans(countryCode) {
      const planSelect = document.getElementById('planId');
      if (!planSelect) return;
      const code = (countryCode || 'GT').toUpperCase();
      const plans = PLANES_POR_PAIS[code] || PLANES_POR_PAIS.GT;
      const prevVal = planSelect.value;
      planSelect.innerHTML = '';
      plans.forEach((p, idx) => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.name;
        if (p.id.toString() === prevVal || idx === 0) {
          opt.selected = true;
        }
        planSelect.appendChild(opt);
      });
    }

    document.getElementById('countryId').addEventListener('change', function() {
      loadStates(this.value);
      updatePlans(this.value);
    });

    document.getElementById('stateId').addEventListener('change', function() {
      const countryVal = document.getElementById('countryId').value;
      loadDistricts(countryVal, this.value);
    });

    // Cargar al iniciar
    loadCountries();
    updatePlans('GT');

    /* ============================================================
       EXTRA DATA DINÁMICO (price / saldo / ambos)
    ============================================================ */
    const extraDataKeySelect = document.getElementById('extraDataKey');
    const singleValueContainer = document.getElementById('singleValueContainer');
    const bothValuesContainer = document.getElementById('bothValuesContainer');
    const extraDataValue = document.getElementById('extraDataValue');
    const extraDataValueLabel = document.getElementById('extraDataValueLabel');

    if (extraDataKeySelect) {
      extraDataKeySelect.addEventListener('change', function() {
        const mode = this.value;
        if (mode === 'ambos') {
          singleValueContainer.style.display = 'none';
          bothValuesContainer.style.display = 'grid';
          extraDataValue.removeAttribute('required');
        } else {
          bothValuesContainer.style.display = 'none';
          singleValueContainer.style.display = 'block';
          extraDataValue.setAttribute('required', 'required');
          if (mode === 'saldo') {
            extraDataValueLabel.textContent = 'Saldo del Crédito (saldo)';
            extraDataValue.value = '1180800';
            extraDataValue.placeholder = 'Ej. 1180800';
          } else {
            extraDataValueLabel.textContent = 'Precio / Tarifa (price)';
            extraDataValue.value = '480.00';
            extraDataValue.placeholder = 'Ej. 480.00';
          }
        }
      });
    }

    /* ============================================================
       LLAMADA AL BACKEND LARAVEL
    ============================================================ */
    async function callBackend(url, payload) {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
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
        message: data?.message || `Error HTTP ${res.status}`,
        data,
        status: res.status
      };

      return data;
    }

    /* ============================================================
       SUBMIT
    ============================================================ */
    document.getElementById('formEmision').addEventListener('submit', async function(ev) {
      ev.preventDefault();

      const f = this;

      // Validación HTML5 nativa
      if (!f.checkValidity()) {
        f.reportValidity();
        return;
      }

      // 🛡️ Validación de fechas: expiración debe ser posterior a emisión
      const issued = new Date(document.getElementById('issuedDate').value);
      const expiry = new Date(document.getElementById('expirationDate').value);
      const tran = new Date(document.getElementById('tranDate').value);

      if (expiry <= issued) {
        showStatus('❌ La fecha de expiración debe ser posterior a la fecha de emisión.', 'err');
        document.getElementById('expirationDate').focus();
        return;
      }
      if (tran < issued) {
        showStatus('❌ La fecha de transacción no puede ser anterior a la fecha de emisión.', 'err');
        document.getElementById('tranDate').focus();
        return;
      }

      btn.disabled = true;
      showStatus('Procesando transacción…', 'info');

      /* ----- Armar el payload según el contrato de la API ---- */
      const g = (id) => document.getElementById(id).value.trim();

      const payload = {
        transactionType: 'EMI',
        transactionId: g('transactionId'),
        tranTypeDescription: g('tranTypeDescription'),
        countryId: g('countryId'),
        planId: parseInt(g('planId'), 10),
        sellerId: g('sellerId'),
        issuedDate: g('issuedDate'),
        tranDate: g('tranDate'),
        admissionEffectiveDate: g('admissionEffectiveDate'),
        expirationDate: g('expirationDate'),
        sponsorId: g('sponsorId'),
        salesChannel: g('salesChannel'),
        stateId: parseInt(g('stateId'), 10),
        districtId: parseInt(g('districtId'), 10),

        customers: [{
          type: 'MI',
          nationalId: g('nationalId'),
          firstName: g('firstName'),
          middleName: g('middleName'),
          lastName: g('lastName'),
          secondLastName: g('secondLastName'),
          birthDate: g('birthDate'),
          emailAddress: g('emailAddress'),
          phoneNumber1: g('phoneNumber1'),
          phoneNumber2: g('phoneNumber2'),
          phoneNumber3: '',
          phoneNumber4: '',
          address: g('address'),
          gender: parseInt(g('gender'), 10),
          company: g('company'),
        }],

        additionalData: {
          salesman_name: g('salesman_name'),
          location_name: g('location_name'),
        },

        extraData: (function() {
          const keyMode = document.getElementById('extraDataKey').value;
          if (keyMode === 'ambos') {
            return [
              { key: 'saldo', value: (document.getElementById('extraDataSaldo').value || '').trim() },
              { key: 'price', value: (document.getElementById('extraDataPrice').value || '').trim() }
            ];
          }
          return [
            { key: keyMode, value: (document.getElementById('extraDataValue').value || '').trim() }
          ];
        })(),

        extraDataCar: {
          brand: g('carBrand'),
          model: g('carModel'),
          type: g('carType'),
          year: g('carYear'),
          plate: g('carPlate'),
          color: g('carColor'),
          serialNumber: g('carSerialNumber'),
          engineNumber: g('carEngineNumber'),
        }
      };

      try {
        const data = await callBackend('/asistencias/emitir', payload);
        setLog(data);
        showStatus(
          `✅ Transacción emitida correctamente. ID: ${data.transactionId ?? payload.transactionId} ${data._simulado ? '(Modo Prueba Activo)' : ''}`,
          'ok'
        );
      } catch (e) {
        setLog(e.data ?? e);
        showStatus(`❌ ${e.message}`, 'err');
      } finally {
        btn.disabled = false;
      }
    });
  </script>

</body>

</html>