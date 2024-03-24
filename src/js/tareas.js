( function() {
    let arrayTareas = [];
    let arrayTareasFiltradas = [];
    const modal = document.createElement('DIV');
    mostrarTareasProyecto();
    

    async function mostrarTareasProyecto(){
        try {
            const URL = 'http://localhost:3000/api/tareas?Codigo=' + obtenerCodigoURL();
            const respuesta = await fetch(URL);
            const resultado = await respuesta.json();
            //console.log(resultado);
            arrayTareas = resultado;
            enlistartareas();
           
        } catch (error) {
            console.log(error);
        }
    }
        
    function enlistartareas(){
        limpiarTareas();
        const estadosTarea ={
            0: 'Pendiente',
            1: 'Completada'
        }
        const listado = document.querySelector('#listado_tareas');
        const contenedorTitulo = document.createElement('LI');
        contenedorTitulo.classList.add('tarea'); 
        contenedorTitulo.classList.add('textoCentrado');

        const tituloTarea = document.createElement('P');
        tituloTarea.textContent = 'Tarea';
        
        const tituloFechaPrevistaFin = document.createElement('P');
        tituloFechaPrevistaFin.textContent = 'Fecha Prevista Fin'; 

        const tituloFechaRealFin = document.createElement('P');
        tituloFechaRealFin.textContent = 'Fecha Real Fin'; 

        const tituloAcciones = document.createElement('P');
        tituloAcciones.textContent = 'Acciones';

        contenedorTitulo.appendChild(tituloTarea);
        contenedorTitulo.appendChild(tituloFechaPrevistaFin);
        contenedorTitulo.appendChild(tituloFechaRealFin);
        contenedorTitulo.appendChild(tituloAcciones);

        listado.appendChild(contenedorTitulo);

        totalPendientes();
        totalCompletadas();

        let arrayEnlistarTareas = arrayTareasFiltradas.length ? arrayTareasFiltradas : arrayTareas;

        if ( arrayEnlistarTareas.length > 0 ) {
            arrayEnlistarTareas.forEach( tareaB => {
                
                const contenedorTarea = document.createElement('LI');
                contenedorTarea.dataset.tareaID = tareaB.ID;
                contenedorTarea.classList.add('tarea');
            
                let nombreTarea = document.createElement('P');
                nombreTarea.textContent = tareaB.Tarea;
                nombreTarea.ondblclick = function(){
                    mostrarFormulario(true, tareaB);
                }

                let Fecha_Prevista_Fin = document.createElement('P');
                Fecha_Prevista_Fin.textContent = FechaFormateada(tareaB.Fecha_Prevista_Fin);
                Fecha_Prevista_Fin.classList.add('textoCentrado');

                let Fecha_Real_Fin = document.createElement('P');
                let Fecha_RFin = '';
                if(tareaB.Fecha_Real_Fin  === '0000-00-00') {
                    Fecha_RFin = "Sin Finalizar";
                }else{
                    Fecha_RFin = FechaFormateada(tareaB.Fecha_Real_Fin);
                }
                Fecha_Real_Fin.textContent = Fecha_RFin;
                Fecha_Real_Fin.classList.add('textoCentrado');
                

                const opcionesDiv = document.createElement('DIV');
                opcionesDiv.classList.add('opciones');

                //botones Estado
                const btnEstadoTarea = document.createElement('BUTTON');
                btnEstadoTarea.classList.add('estado_tarea');
                btnEstadoTarea.classList.add(`${estadosTarea[tareaB.Estado]}`); 
                btnEstadoTarea.textContent = estadosTarea[tareaB.Estado];
                btnEstadoTarea.dataset.estadoTarea = tareaB.Estado;
                btnEstadoTarea.ondblclick = function() {
                    confirmarSwalEdoTarea({...tareaB}); //Saco una copia ya que JS modifica los estados de manera automatica y muta mis datos sin yo pedirlo
                }
                

                //botones Eliminar
                const btnEliminarTarea = document.createElement('BUTTON');
                btnEliminarTarea.classList.add('eliminar_tarea');
                btnEliminarTarea.dataset.tareaID = tareaB.ID;
                btnEliminarTarea.textContent = 'Eliminar';
                btnEliminarTarea.ondblclick = function() {
                    ConfirmarEliminarTarea({...tareaB}); //Saco una copia ya que JS modifica los estados de manera automatica y muta mis datos sin yo pedirlo
                }
                
                opcionesDiv.appendChild(btnEstadoTarea);
                opcionesDiv.appendChild(btnEliminarTarea);

                contenedorTarea.appendChild(nombreTarea);
                contenedorTarea.appendChild(Fecha_Prevista_Fin);
                contenedorTarea.appendChild(Fecha_Real_Fin);
                contenedorTarea.appendChild(opcionesDiv);

                listado.appendChild(contenedorTarea);
            });
            
        }else{
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas Asignadas al Presente Proyecto';
            textoNoTareas.classList.add('no_tarea');
            listado.appendChild(textoNoTareas);
        }
        
    }
    function limpiarTareas(){
        const listado = document.querySelector('#listado_tareas');
        while( listado.firstChild ){
            listado.removeChild(listado.firstChild);
        }
    }

    //Boton para mostrar el MOdal de Agregar Tareas
    const btnAgregarTarea = document.querySelector('#agregar_tarea');
    btnAgregarTarea.addEventListener('click', function(){
        mostrarFormulario(false);
    });

    function mostrarFormulario(editar = false, tareaB = {}){
        
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva_tarea">
                <legend>${editar ? 'Editar la Tarea' : 'Añade una Nueva Tarea'}</legend>
                <div class="campo">
                    <label for="Tarea">Tarea:</label>
                    <input type="text" name="Tarea" id="Tarea" placeholder="Tarea a Realizar" value="${tareaB.Tarea ? tareaB.Tarea : ''}"/>
                </div>
                <div class="campo">
                    <label for="Fecha_Prevista_Fin">Fecha Prevista Fin:</label>
                    <input type="date" name="Fecha_Prevista_Fin" id="Fecha_Prevista_Fin" value="${tareaB.Fecha_Prevista_Fin ? tareaB.Fecha_Prevista_Fin : ''}"/>
                </div>
                <div id="mensajeModal"></div>
                <div class="opciones">
                    <input type="submit" class="submit_nueva_tarea" value="${editar ? 'Editar Tarea' : 'Añadir Tarea'}">
                    <button type="button" class="cerrar_modal">Cerrar</button>
                </div>
            </form>
        `;
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 100);

        modal.addEventListener('click', function(e) {
            e.preventDefault();
            if( e.target.classList.contains('cerrar_modal') ) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 1000);
            }
            if( e.target.classList.contains('submit_nueva_tarea') && e.target.value === 'Editar Tarea' ){
                let tareaActualizada = document.querySelector('#Tarea').value.trim();
                let Fecha_Prevista_Fin_Actualizada = document.querySelector('#Fecha_Prevista_Fin').value
                editarTarea(tareaB.ID, tareaB.ProyectoID, tareaB.Estado, tareaActualizada, Fecha_Prevista_Fin_Actualizada, tareaB.Fecha_Real_Fin);
            }
            if( e.target.classList.contains('submit_nueva_tarea') && e.target.value === 'Añadir Tarea' ){
                agregarNuevaTarea();
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function agregarNuevaTarea() {
        let tarea = document.querySelector('#Tarea').value.trim();
        let Fecha_Prevista_Fin = document.querySelector('#Fecha_Prevista_Fin').value;
        if (tarea !== '') {
            if (document.querySelector('.errorLlenado')) {
                document.querySelector('.errorLlenado').remove();
            }
            insertartarea(tarea, Fecha_Prevista_Fin);
        } else {
            mostrarAlerta('El Nombre de la Tarea de Obligatorio', 'error', document.querySelector('#mensajeModal'));
            return;
        }
    }
    async function insertartarea(tarea, Fecha_Prevista_Fin){
        try {
            const datos = new FormData();
            datos.append('Tarea', tarea);
            datos.append('Fecha_Prevista_Fin', Fecha_Prevista_Fin);
            datos.append('Fecha_Real_Fin', '0000-00-00');
            datos.append('Codigo', obtenerCodigoURL());       
            
            const URL = 'http://localhost:3000/api/tareas';
            const respuesta = await fetch(URL, {
              method: 'POST',
              body: datos
            });
            //console.log(respuesta); Para ver si hay conexion Ok Estatus 200
            const tareainsertada = await respuesta.json();
            console.log(tareainsertada);
            
            if(tareainsertada.Tipo === 'msjExito'){
                mostrarAlerta(tareainsertada.Mensaje, tareainsertada.Tipo, document.querySelector('#mensajeModal') );
                setTimeout(() => {
                    modal.remove();
                }, 1500);

                //Agregar el Objeto de Tareas
                let ObjTarea = {
                    ID: tareainsertada.ID,
                    Tarea: tarea,
                    Fecha_Prevista_Fin: tareainsertada.Fecha_Prevista_Fin,
                    Fecha_Real_Fin: tareainsertada.Fecha_Real_Fin,
                    Estado: 0,
                    ProyectoID: tareainsertada.ProyectoID
                }
                arrayTareas = [...arrayTareas, ObjTarea];
                enlistartareas();
            }
            
          } catch (error) {
            console.log(error);
          }
    }

    function obtenerCodigoURL(){
        let project = Object.fromEntries(
            new URLSearchParams(window.location.search)
        );
        return project.Codigo;
    }

    function confirmarSwalEdoTarea(tareaB){
        Swal.fire({
            title: "<strong>Fianlizar Tarea</strong>",
            icon: "info",
            html: `
              <label for="Fecha_Real_Fin">Fecha Real Fin:</label>
              <input type="date" name="Fecha_Real_Fin" id="Fecha_Real_Fin" maxlength="10">
            `,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: true,
            confirmButtonText: `
              <i class="fa fa-thumbs-up"> Ok </i>
            `,
            confirmButtonAriaLabel: "Thumbs up, great!",
            cancelButtonText: `
              <i class="fa fa-thumbs-down"> No </i>
            `,
            cancelButtonAriaLabel: "Thumbs down",
            willOpen: () => {
                const confirmButton = Swal.getConfirmButton();
                confirmButton.addEventListener('click', () => {
                  if( document.querySelector('#Fecha_Real_Fin').value !== ''){
                    tareaB.Fecha_Real_Fin = document.querySelector('#Fecha_Real_Fin').value;
                    cambiarEstadoDeTarea(tareaB);
                  }
                });
              }
          });
    }
    function cambiarEstadoDeTarea(tareaB){
        let nuevoEstado = tareaB.Estado === '0' ? '1' : '0';
        tareaB.Estado = nuevoEstado;
        actualizarTareaEnBD(tareaB);
    }
    async function actualizarTareaEnBD(tareaActualizada){
        const { ID, Tarea, ProyectoID, Fecha_Prevista_Fin, Fecha_Real_Fin, Estado } = tareaActualizada;
        const datos = new FormData();
        datos.append('ID', ID);
        datos.append('Tarea', Tarea);
        datos.append('ProyectoID', ProyectoID);
        datos.append('Fecha_Prevista_Fin', Fecha_Prevista_Fin);
        datos.append('Fecha_Real_Fin', Fecha_Real_Fin);
        datos.append('Estado', Estado);
        datos.append('Codigo', obtenerCodigoURL());
        /*for( let valor of datos.values() ) {
            console.log(valor);
        }*/


        try {
            const respuesta = await fetch('http://localhost:3000/api/tareas/actualizar', {
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
                setTimeout( () => 
                document.querySelector('.errorLlenado').remove(), 
                2500);

                arrayTareas = arrayTareas.map(tareaMemoria => {
                    if( tareaMemoria.ID === ID   ){
                        tareaMemoria.Estado = Estado;
                        tareaMemoria.Fecha_Prevista_Fin = Fecha_Prevista_Fin;
                        tareaMemoria.Fecha_Real_Fin = Fecha_Prevista_Fin;
                    }
                    return tareaMemoria;
                })
                enlistartareas();

            }
    
        } catch (error) {
            console.log(error);
        }
    }

    function ConfirmarEliminarTarea(tareaEliminar){
        Swal.fire({
          title: '¿Desea eliminar la Tarea?',
          showCancelButton: true,
          confirmButtonText: 'Si',
          cancelButtonText: 'No'
        }).then((result) => {
           if( result.isConfirmed ){
            eliminarTarea(tareaEliminar);
           } 
        }).catch((err) => {
            console.log(err);
        });
    }
    async function eliminarTarea(tareaEliminar){
        const { ID } = tareaEliminar;
        const datos = new FormData();
        datos.append('ID', ID);
        datos.append('Codigo', obtenerCodigoURL());
        

        try {
            const respuesta = await fetch('http://localhost:3000/api/tareas/eliminar', {
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
                Swal.fire('Eliminada!', resultado.Mensaje, 'success');

                arrayTareas = arrayTareas.filter(tareaMemoria => tareaMemoria.ID !== ID)
                enlistartareas();

            }
    
        } catch (error) {
            console.log(error);
        }
    }

    function editarTarea(ID, ProyectoID, Estado, tareaActualizada, Fecha_Prevista_Fin, Fecha_Real_Fin){
        if( tareaActualizada !== '' ){
            if( document.querySelector('.errorLlenado') ) {
                document.querySelector('.errorLlenado').remove();
            }
            guardarCambiosTarea(ID, ProyectoID, Estado, tareaActualizada, Fecha_Prevista_Fin, Fecha_Real_Fin);
        }else{
            mostrarAlerta('El Nombre de la Tarea de Obligatorio', 'error', document.querySelector('#mensajeModal') );
            return;
        }
    }
    async function guardarCambiosTarea(ID, ProyectoID, Estado, tareaActualizada, Fecha_Prevista_Fin, Fecha_Real_Fin) {
        const datos = new FormData();
        datos.append('ID', ID);
        datos.append('Tarea', tareaActualizada);
        datos.append('ProyectoID', ProyectoID);
        datos.append('Fecha_Prevista_Fin', Fecha_Prevista_Fin);
        datos.append('Fecha_Real_Fin', Fecha_Real_Fin);
        datos.append('Estado', Estado);
        datos.append('Codigo', obtenerCodigoURL());

        try {
            const respuesta = await fetch('http://localhost:3000/api/tareas/actualizar', {
                method: 'POST',
                body: datos
            });
            let resultado = await respuesta.json();
            //console.log(resultado);
            if(resultado.Tipo === 'error') {
                mostrarAlerta(resultado.Mensaje, resultado.Tipo, document.querySelector('#mensaje'));
                return;
            }
            if(resultado.Tipo === 'msjExito') {
                mostrarAlerta(resultado.Mensaje, resultado.Tipo, document.querySelector('#mensajeModal'));
                setTimeout(() => {
                    document.querySelector('.errorLlenado').remove();
                    modal.remove();
                }, 2500);
                //console.log(arrayTareas);
                arrayTareas = arrayTareas.map(tareaMemoria => {
                    if( tareaMemoria.ID === ID   ){
                        tareaMemoria.Tarea = tareaActualizada;
                        tareaMemoria.Fecha_Prevista_Fin = Fecha_Prevista_Fin;
                        tareaMemoria.Fecha_Real_Fin = Fecha_Real_Fin;
                    }
                    return tareaMemoria;
                })
                enlistartareas();

            }
    
        } catch (error) {
            console.log(error);
        }
    }

    //Filtros de Busqueda
    let selFiltros = document.querySelectorAll('#filtros input[type="radio"]');
    selFiltros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    })
    function filtrarTareas(e){
        let filtroSeleccionado = e.target.value;
        
        if( filtroSeleccionado !== '' ){
            arrayTareasFiltradas = arrayTareas.filter(tarea => tarea.Estado == filtroSeleccionado);
        }else{
            arrayTareasFiltradas = [];
        }
        enlistartareas();
    }

    function totalPendientes(){
        let totalPendientes = arrayTareas.filter(tarea => tarea.Estado == '0');
        const rbPendientes = document.querySelector('#filtroPendientes');

        if( totalPendientes.length == '0' ){
            rbPendientes.disabled = true;
        }else{
            rbPendientes.disabled = false;
        }
    }
    function totalCompletadas(){
        let totalCompletadas = arrayTareas.filter(tarea => tarea.Estado == '1');
        const rbCompletadas= document.querySelector('#filtroCompletadas');

        if( totalCompletadas.length == '0' ){
            rbCompletadas.disabled = true;
        }else{
            rbCompletadas.disabled = false;
        }
    }
    
})();
