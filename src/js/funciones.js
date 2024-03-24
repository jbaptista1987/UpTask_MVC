
document.addEventListener('DOMContentLoaded', function(){
  if(document.querySelector('#countdown')) {
    const countdownEl = document.getElementById('countdown');
    const targetURL = '/';
    const intervalId = setInterval(() => {
        const currentCount = parseInt(countdownEl.textContent);
        if (currentCount <= 1) {
            clearInterval(intervalId);
            window.location = targetURL;
            return;
        }
        countdownEl.textContent = currentCount - 1;
    }, 1000);
  }

  const urlBackground = [
    'http://localhost:3000/',
    'http://localhost:3000/olvidarpass',
    'http://localhost:3000/recuperarpass',
    'http://localhost:3000/crearcta',
    'http://localhost:3000/confirmarcta'
  ];
  
  const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  if (isDarkMode) {
    if (urlBackground.some(url => window.location.href.includes(url))) {
      document.body.style.backgroundImage = 'unset';
    }
  } else {
    if (urlBackground.some(url => window.location.href.includes(url))) {
      document.body.style.backgroundImage = 'url("/build/img/fondo-1.webp")';
    }
  }

});


function SoloNumeroPunto(evt){
    // Manejo de caracteres a traves del Codigo ASCII
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // Espacio.
      return true;
    } else if(code>=48 && code<=57) { // Grupo de Codigo ASCII para los numeros.
      return true;
    } else if(code==46) { // is a .
        return true;
    } else{ // Otras letras o caracteres.
      return false;
    }
}
function SoloNumero(evt){
    // Manejo de caracteres a traves del Codigo ASCII
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // Espacio.
      return true;
    } else if(code>=48 && code<=57) { // Grupo de Codigo ASCII para los numeros.
      return true;
    } else{ // Otras letras o caracteres.
      return false;
    }
}
function SoloLetras(evt){
    // Manejo de caracteres a traves del Codigo ASCII
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // retroceso.
      return true;
    } 
    else if(code==32) { // Espacio.
      return true;
    }
    else if(code>=65 && code<=90) { // Grupo de Codigo ASCII para las letras mayusculas.
      return true;
    } else if(code>=97 && code<=122) { // Grupo de Codigo ASCII para las letras minusculas.
        return true;
    } 
    else if(code>=164 && code<=165) { // ñ y Ñ.
      return true;
    }
    else{ // Otras letras o caracteres.
      return false;
    }
}
function validarTelefono(tel) {
  // Check if the string length is not 12
  if (tel.length !== 12) {
    return false;
  }

  // Check if the string matches the regular expression
  if (!/^\d{4}-\d{7}$/.test(tel)) {
    return false;
  }

  // If the string passes both checks, return true
  return true;
}
function validarEmail(email) {
  // Regular expression to validate the email format
  const expresion = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

  // Check if the email matches the regular expression
  if (expresion.test(email)) {
    return true;
  } else {
    return false;
  }
}

function mostrarAlerta(mensaje, tipo, posicion){
  if( document.querySelector('.errorLlenado') ) {
    document.querySelector('.errorLlenado').remove();
  }
  let alerta = document.createElement('DIV');
  alerta.classList.add('errorLlenado', tipo);
  alerta.textContent = mensaje;
  posicion.appendChild(alerta);
}
function mostrarVariasAlertas(mensaje, tipo, posicion){
  let alerta = document.createElement('DIV');
  alerta.classList.add('errorLlenado', tipo);
  alerta.textContent = mensaje;
  posicion.appendChild(alerta);
  setTimeout(() => {
    alerta.remove();
  }, 3000);
}

function validarEmail(email) {
  // Expresión regular para validar el formato del correo electrónico
  const expresion = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

  // Comprobar si el correo electrónico cumple con la expresión regular
  if (expresion.test(email)) {
    return true;
  } else {
    return false;
  }
}

function FechaFormateada(Fecha){
  const formatearFecha = (date, locale, options) => new Intl.DateTimeFormat(locale, options).format(date)
  const fechaSinFormato = new Date(Fecha);
  fechaSinFormato.setDate(fechaSinFormato.getDate() + 1);
  fechaFormat = formatearFecha(fechaSinFormato, 'es', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
  return fechaFormat;
}