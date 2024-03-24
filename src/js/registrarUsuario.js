( function() {

    validarformulario();


    function validarformulario() {
        const btnLogin = document.querySelector('#btnRegUsuario');
            btnLogin.addEventListener('click', e => {
            e.preventDefault();
            
            //Nombre-Correo-Clave-ClaveConfirmar
            let validador = [];
            let Nombre = document.querySelector('#Nombre').value.trim();
            let Correo = document.querySelector('#Correo').value.trim();
            let Clave = document.querySelector('#Clave').value.trim();
            let ClaveConfirmar = document.querySelector('#ClaveC').value.trim();
            //Imagen de Perfil
            let inputImagenPerfil = document.getElementById('ImagenPerfil');
            let Archivo = inputImagenPerfil.files[0];
            
            
            if( Nombre === '' ){
                validador[validador.length] = 'El Nombre es Obligatorio';
            }
            if( Correo === '' || !validarEmail(Correo) ){
                validador[validador.length] = 'Un Correo Valido es Obligatorio';
            }
            if( Clave === '' || Clave.length < 6 ){
                validador[validador.length] = 'La Clave es Obligatoria y debe tener al menos 6 caracteres';
            }
            if( ClaveConfirmar === '' ){
                validador[validador.length] = 'La Confirmacion de la Clave es Obligatoria';
            }
            if( Clave !== ClaveConfirmar ){
                validador[validador.length] = 'Las Claves no Coinciden';
            }
    
            if (validador.length > 0) {
                for (let msg of validador) {
                    mostrarVariasAlertas(msg, 'error', document.querySelector('#mensaje'));
                }
                return;
            }
    
            registrarUsuario(Nombre, Correo, Clave, Archivo);
            
        }); //Cierre del Event Listener
    }
    async function registrarUsuario(Nombre, Correo, Clave, Archivo) {
        const datos = new FormData();
        datos.append('Nombre', Nombre);
        datos.append('Correo', Correo);
        datos.append('Clave', Clave);
        datos.append('ClaveC', Clave);
        datos.append('ImagenPerfil', Archivo);
    
        try {
            const respuesta = await fetch('http://localhost:3000/crearcta', {
                method: 'POST',
                body: datos
            });
            let resultado = await respuesta.json();
            console.log(resultado);
            if(resultado.Tipo === 'error') {
                mostrarAlerta(resultado.Mensaje, resultado.Tipo, document.querySelector('#mensaje'));
                return;
            }
            if(resultado.Tipo === 'msjExito') {
                alert('Registro de Usuario Exitoso');
                window.location.href = '/';
            }
    
        } catch (error) {
            console.log(error);
        }
    
    
    }

})();