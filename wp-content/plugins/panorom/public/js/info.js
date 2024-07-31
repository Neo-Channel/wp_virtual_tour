"use strict";
;(function() {
  // check if we are on tour page
  var pnrmInfoDiv = document.querySelector('.pnrm-info');
  if(!pnrmInfoDiv) {
    return;
  }
  console.log('panorom info page running');

  var btnStart = document.querySelector('.pnrm-info .btn-start');




  // event listeners

  btnStart.onclick = function() {
    window.location.search = 'page=panorom-editor';
  }


})();