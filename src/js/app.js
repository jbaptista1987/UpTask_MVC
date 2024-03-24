document.addEventListener('DOMContentLoaded', function(){

if( document.querySelector('#menu_mobile') ){
    const sidebar = document.querySelector('.sidebar_nav');
    const btnMobileMenu = document.querySelector('#menu_mobile');
    btnMobileMenu.addEventListener('click', function(){
        sidebar.classList.toggle('mostrar');
    });//Final del Event Listener de Presionar el Boton Menu Mobile
}


}); // Final de la carga del DOM