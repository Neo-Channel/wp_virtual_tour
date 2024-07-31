"use strict";
;(function() {
  // check if we are on api page
  var pnrmApiDiv = document.querySelector('.pnrm-api');
  if(!pnrmApiDiv) {
    return;
  }
  console.log('panorom api page running');

  var inputBtnSubmit = document.querySelector('.pnrm-api #submit');
  var inputTextTranslationActivate = document.querySelector('.pnrm-api #text-translation-activate');
  var inputTextTranslationARelease = document.querySelector('.pnrm-api #text-translation-release');
  var checkboxRelease = document.querySelector('.pnrm-api #input-checkbox-release');
  var tdShowRelease = document.querySelector('.pnrm-api .td-show-release');
  var trReleaseRow = document.querySelector('.pnrm-api .tr-release-row');

  

  // event listeners

  tdShowRelease.onclick = function() {
    trReleaseRow.classList.toggle('show');
  }

  checkboxRelease.onchange = function() {
    if (checkboxRelease.checked) {
      // inputBtnSubmit.value = 'Release';
      inputBtnSubmit.value = inputTextTranslationARelease.value;
    }
    else {
      // inputBtnSubmit.value = "Activate";
      inputBtnSubmit.value = inputTextTranslationActivate.value;
    }
  }


})();