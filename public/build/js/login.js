document.querySelector("#btnLogin").addEventListener("click",e=>{e.preventDefault();let o=document.querySelector("#Correo").value.trim(),t=document.querySelector("#Clave").value.trim();""!=o&&""!=t?async function(e,o){const t=new FormData;t.append("Correo",e),t.append("Clave",o);try{const e=await fetch("http://localhost:3000/",{method:"POST",body:t});let o=await e.json();if(console.log(o),"error"===o.Tipo)return void mostrarAlerta(o.Mensaje,o.Tipo,document.querySelector("#mensaje"));"msjExito"===o.Tipo&&(window.location.href="/panelprincipal")}catch(e){console.log(e)}}(o,t):mostrarAlerta("Usuario y Clave son Obligatorios","error",document.querySelector("#mensaje"))});