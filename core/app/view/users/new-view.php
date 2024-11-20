<?php
$user_types = UserTypeData::getAll();
?>
<div class="row">
  <div class="col-md-12">
    <h1>Agregar Usuario</h1>
    <br>
    <form class="form-horizontal" method="post" action="index.php?action=users/add" role="form" enctype="multipart/form-data">

      <!-- Nombre -->
      <div class="form-group">
        <label for="name" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" required>
        </div>
      </div>

      <!-- Apellido -->
      <div class="form-group">
        <label for="lastname" class="col-lg-2 control-label">Apellido*</label>
        <div class="col-md-6">
          <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Apellido" required>
        </div>
      </div>

      

      <!-- Correo Electrónico -->
      <div class="form-group">
        <label for="email" class="col-lg-2 control-label">Correo Electrónico*</label>
        <div class="col-md-6">
          <input type="email" name="email" class="form-control" id="email" placeholder="Correo Electrónico" required>
        </div>
      </div>

      

      <!-- Tipo de Usuario -->
      <div class="form-group">
        <label for="user_type" class="col-lg-2 control-label">Tipo de Usuario*</label>
        <div class="col-md-6">
          <select class="form-control" name="user_type" id="user_type" required>
            <option value="">-- SELECCIONE --</option>
            <?php foreach ($user_types as $type) : ?>
              <option value="<?php echo $type->id; ?>"><?php echo $type->description; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
          <label for="image" class="col-lg-2 control-label">Imagen de perfil</label>
          <div class="col-md-6">
              <input type="file" name="image" class="form-control" id="image" accept="image/*">
          </div>
      </div>


          
      <!-- Fecha de Nacimiento -->
      <div class="form-group">
        <label for="dob" class="col-lg-2 control-label">Fecha de Nacimiento*</label>
        <div class="col-md-6">
          <input type="date" name="dob" class="form-control" id="dob" required>
        </div>
      </div>

      <!-- Nombre de Usuario -->
      <div class="form-group">
        <label for="username" class="col-lg-2 control-label">Nombre de Usuario*</label>
        <div class="col-md-6">
          <input type="text" name="username" class="form-control" id="username" placeholder="Nombre de Usuario" readonly>
        </div>
      </div>

      <!-- Contraseña -->
      <div class="form-group">
        <label for="password" class="col-lg-2 control-label">Contraseña*</label>
        <div class="col-md-6">
          <input type="text" name="password" class="form-control" id="password" placeholder="Contraseña" readonly>
        </div>
      </div>    
      <!-- Botón de Enviar -->
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Agregar Usuario</button>
        </div>
      </div>

    </form>
  </div>
</div>

<!-- Script para generar Nombre de Usuario y Contraseña -->
<script>
  function generateUsernameAndPassword() {
    const name = document.getElementById("name").value.trim();
    const lastname = document.getElementById("lastname").value.trim();
    const dob = document.getElementById("dob").value;

    if (name && lastname && dob) {
      // Generar nombre de usuario
      const initials = name.charAt(0).toUpperCase() + lastname.split(" ").map(word => word.charAt(0).toUpperCase()).join("");
      const dateParts = dob.split("-"); // Formato AAAA-MM-DD
      const dobShort = dateParts[2] + dateParts[1] + dateParts[0].slice(-2); // DDMMAA
      const username = initials + dobShort;

      // Generar contraseña aleatoria
      const password = Array(8)
        .fill("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()")
        .map(x => x[Math.floor(Math.random() * x.length)])
        .join("");

      // Asignar valores a los campos
      document.getElementById("username").value = username;
      document.getElementById("password").value = password;
    }
  }

  // Añadir eventos a los campos necesarios
  document.getElementById("name").addEventListener("input", generateUsernameAndPassword);
  document.getElementById("lastname").addEventListener("input", generateUsernameAndPassword);
  document.getElementById("dob").addEventListener("change", generateUsernameAndPassword);
</script>