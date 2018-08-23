'use strict';
var isPushEnabled = false;

document.querySelector('.hamburger').addEventListener('click', (e)=>{
  let ev = e || event || window.event;
  ev.target.classList.toggle('is-active');
  document.querySelector('.main-container').classList.toggle('hide');
  document.querySelector('.vertical-navBar').classList.toggle('show');
  let toggleMenu = () => {
    document.querySelector('.hamburger').classList.remove('is-active');
    document.querySelector('.main-container').classList.remove('hide');
    document.querySelector('.vertical-navBar').classList.remove('show');
    window.removeEventListener('swipeleft', toggleMenu);
  }
  if(document.querySelector('.vertical-navBar').classList.contains('show')){
    window.addEventListener('swipeleft', toggleMenu);
  }else{
    window.removeEventListener('swipeleft', toggleMenu);
  }
});
