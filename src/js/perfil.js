( function() {

    validarformulario();


    function validarformulario() {
        const btnLogin = document.querySelector('#btnActUsuario');
            btnLogin.addEventListener('click', e => {
            e.preventDefault();
            
            //Nombre-Correo-Clave-ClaveConfirmar
            let validador = [];
            let Nombre = document.querySelector('#Nombre').value.trim();
            let Correo = document.querySelector('#Correo').value.trim();
            
            //Imagen de Perfil
            let inputImagenPerfil = document.getElementById('ImagenPerfil');
            let Archivo = inputImagenPerfil.files[0];
            
            if( Nombre === '' ){
                validador[validador.length] = '* El Nombre es Obligatorio';
            }
            if( Correo === '' || !validarEmail(Correo) ){
                validador[validador.length] = '* Un Correo Valido es Obligatorio';
            }
            if (!Archivo || !Archivo.type.startsWith('image/')){
                validador[validador.length] = '* Una Imagen de Perfil es Obligatoria';
            }
    
            if (validador.length > 0) {
                for (let msg of validador) {
                    mostrarVariasAlertas(msg, 'error', document.querySelector('#mensaje'));
                }
                return;
            }
    
            actUsuario(Nombre, Correo, Archivo);
            
        }); //Cierre del Event Listener
    }
    async function actUsuario(Nombre, Correo, Archivo) {
        const datos = new FormData();
        datos.append('Nombre', Nombre);
        datos.append('Correo', Correo);
        datos.append('ImagenPerfil', Archivo);
    
        try {
            const respuesta = await fetch('http://localhost:3000/perfil', {
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
                alert('Perfil Actualizado');
                window.location.href = '/panelprincipal';
            }
    
        } catch (error) {
            console.log(error);
        }
    
    
    }

})();