
( function() {
    validarformulario();

    function validarformulario() {
        const btnLogin = document.querySelector('#btnLogin');
        btnLogin.addEventListener('click', e => {
        e.preventDefault();
        
            let correo = document.querySelector('#Correo').value.trim();
            let clave = document.querySelector('#Clave').value.trim();
            
            if( correo == '' || clave == '' ) {
                mostrarAlerta('Usuario y Clave son Obligatorios', 'error', document.querySelector('#mensaje') );
                return;
            } 

            iniciarSesion(correo, clave);
        }); //Cierre del Event Listener    
    } 
    async function iniciarSesion(correo, clave){
        const datos = new FormData();
        datos.append('Correo', correo);
        datos.append('Clave', clave);

        try {
            const respuesta = await fetch('http://localhost:3000/', {
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
                window.location.href = '/panelprincipal';
            }
        } catch (error) {
            console.log(error);
        }
    }

})();