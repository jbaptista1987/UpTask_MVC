<div class="contenedor crear">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu Cuenta</p>

        <form action="/crearcta" class="formulario" method="post">
            <div class="campo">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" placeholder="Tu Nombre Completo" value="<?php echo $ObjLogin->Nombre ?>"/>
            </div>
            <div class="campo">
                <label for="Correo">Email:</label>
                <input type="email" id="Correo" name="Correo" placeholder="Tu Correo" value="<?php echo $ObjLogin->Correo ?>"/>
            </div>
            <div class="campo">
                <label for="Clave">Contraseña:</label>
                <input type="password" id="Clave" name="Clave" placeholder="Tu Contraseña"/>
            </div>
            <div class="campo">
                <label for="ClaveC">Confirmar Contraseña:</label>
                <input type="password" id="ClaveC" name="ClaveC" placeholder="Confirma Tu Contraseña"/>
            </div>
            <div class="campo">
                <label for="ImagenPerfil">Imagen Perfil:</label>
                <input type="file" id="ImagenPerfil" name="ImagenPerfil" accept="image/jpeg, image/png"/>
            </div>

            <div id="mensaje"></div>
            
            <div class="contenedor-boton-login">
                <input type="submit" id="btnRegUsuario" class="boton" value="Registrarse">
            </div>

            <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->

            <div class="acciones">
                <a href="/">Iniciar Sesion</a>
                <a href="/olvidarpass">¿Olvido Su Clave?</a>
            </div>
        </form>
    </div>
</div>

<?php
    $scriptJS = "
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/registrarUsuario.js'></script> 
    ";
?>
<!-- <script src="https://apis.google.com/js/api.js"></script> -->

<!-- <script>
  function onSignIn(googleUser) {
    // Send the authorization code to your server for verification and session creation.
    var authCode = googleUser.getAuthResponse().id_token;
    sendAuthCodeToServer(authCode);
  }

  function sendAuthCodeToServer(authCode) {
    // Send the authorization code to your server using AJAX or Fetch API.
    // Example using Fetch API:
    fetch('/check_google_auth.php', {
      method: 'POST',
      body: JSON.stringify({auth_code: authCode}),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Redirect to the user's dashboard or any protected page.
        window.location.href = '/dashboard.php';
      } else {
        // Display an error message.
        alert('Login failed. Please try again.');
      }
    });
  }
</script> -->