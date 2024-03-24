( function() {

cargarPDF();

function cargarPDF(){
    const btnPDF = document.querySelector('#subirPDF');
        btnPDF.addEventListener('click', e => {
        e.preventDefault();

        let inputDocPDF = document.getElementById('InformePDF');
        let Archivo = inputDocPDF.files[0];
        if (inputDocPDF.files.length > 0){
            enviarPDFBackEnd(Archivo);
        }else{
            console.log('Debe cargar un archivo PDF');
        }
    }); //Cierre del Event Listener

}
async function enviarPDFBackEnd(Archivo){
    const datos = new FormData();
    datos.append('DocPDF', Archivo);
    datos.append('Accion', 'Subir');

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
            alert('PDF Guardado');
        }

    } catch (error) {
        console.log(error);
    }
}
})();