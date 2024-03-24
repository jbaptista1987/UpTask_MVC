( function() {

    validarformulario();

    function validarformulario() {
        const btnLogin = document.querySelector('#btnOlvidarPass');
        btnLogin.addEventListener('click', e => {
        e.preventDefault();

        let Correo = document.querySelector('#Correo').value.trim(); 
        if( Correo === '' || !validarEmail(Correo) ){
            mostrarAlerta('Un Correo Valido es Obligatorio', 'error', document.querySelector('#mensaje') );
            return;
        }

        recuperarPass(Correo);
        }); //Cierre del Event Listener 
    } //Cierre de la Funcion

    async function recuperarPass(Correo){
        const datos = new FormData();
        datos.append('Correo', Correo);

        try {
            const respuesta = await fetch('http://localhost:3000/olvidarpass', {
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
                mostrarAlerta(resultado.Mensaje, resultado.Tipo, document.querySelector('#mensaje'));
                setTimeout(() => {
                    window.location.href = '/';
                }, 2500);
            }
        } catch (error) {
            console.log(error);
        }
    }

})();