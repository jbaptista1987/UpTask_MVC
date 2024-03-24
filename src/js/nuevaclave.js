( function() {
    validarformulario();

    function validarformulario() {
        const btnLogin = document.querySelector('#btnNuevoPass');
        btnLogin.addEventListener('click', e => {
        e.preventDefault();
        
        let Clave = document.querySelector('#Clave').value.trim(); 
        let ClaveConfirmar = document.querySelector('#ClaveC').value.trim();
        let validador = [];
        if( Clave === '' || Clave.length < 6 ){
            validador[validador.length] ='La Clave es Obligatoria y debe tener al menos 6 caracteres';
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

        setNuevoPass(Clave);

        }); //Cierre del Event Listener 
    } //Cierre de la Funcion
    async function setNuevoPass(Clave){
        const datos = new FormData();
        datos.append('Clave', Clave);

        try {
            const respuesta = await fetch('http://localhost:3000/clavenueva', {
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
                alert('Clave Actualizada con Exito');
                setTimeout(() => {
                    window.location.href = '/panelprincipal';
                }, 2500);
            }
    
        } catch (error) {
            console.log(error);
        }
    }
    function obtenerCodigoURL(){
        let project = Object.fromEntries(
            new URLSearchParams(window.location.search)
        );
        return 'Token=' + project.Token + '&Correo=' + project.Correo;
    }


})();