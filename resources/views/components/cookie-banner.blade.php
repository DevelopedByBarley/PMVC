<div class="container-fluid p-5 d-flex d-none position-fixed bottom-0 bg-slate-800 text-white" id="cookie-banner-container" style="z-index: 999 !important;">
  <div class="row">
    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center">
      <p class="pr-font-200 text-lg text-center text-lg-start">A weboldalon "cookie-kat" ("sütiket") használunk a legjobb felhasználói élmény biztosítására. A honlap további használatához a sütik használatát el kell fogadni, mely beállítás bármikor megváltoztatható a böngésző beállításaiban. <a class="important text-warning" href="/cookie-info" id="fom">További információ </a></p>
    </div>
    <div class="col-12 col-lg-6 mt-3 mt-lg-0 text-center">
      <button role="button" id="banner-cookie-consent-accept-necessary" class="btn btn-outline-light mt-2">Nélkülözhetetlen cookiek elfogadása</button>
      <button role="button" id="banner-cookie-consent-accept-all" class="btn btn-outline-light mt-2">Mindet cookie elfogadása</button>
      <button role="button" data-bs-toggle="modal" data-bs-target="#cookie-modal" class="btn btn-outline-light mt-2">
        Cookie-k beállítása
      </button>
    </div>
  </div>
</div>


<?php require_once view_path('components/cookie-modal')?>