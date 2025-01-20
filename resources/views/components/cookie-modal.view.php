<!-- Modal -->
<div class="modal top fade" id="cookie-modal" tabindex="-1" aria-labelledby="cookieconsentLabel3" aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content d-block text-start  bg-gray-50 dark-bg-slate-800">
      <div class="modal-header d-block ">
        <h5 class="modal-title" id="cookieconsentLabel3">Sütik és Adatvédelem</h5>
        <p>
          Ez a weboldal sütiket használ annak biztosítására, hogy a legjobb élményt kapja az oldalunkon.
        </p>
        <p>
          <a href="/cookie-info">Tudjon meg többet a sütikről</a>
        </p>
      </div>
      <div class="modal-body">
        <!-- Szükséges jelölőnégyzet -->
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="necessary-checkbox" checked onclick="return false;" />
          <label class="form-check-label" for="necessary">
            <p>
              <strong>Szükséges sütik</strong>
              <muted>segítenek a weboldal alapvető funkcionalitásában, pl. megjegyzik, ha hozzájárult a sütik használatához.</muted>
            </p>
          </label>
        </div>
        <!-- Analitikai jelölőnégyzet -->
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="analytical-checkbox" />
          <label class="form-check-label" for="analytical">
            <p>
              <strong>Analitikai sütik</strong>
              <muted>lehetővé teszik a statisztikák gyűjtését a weboldal használatáról és forgalmáról, hogy javíthassuk azt.</muted>
            </p>
          </label>
        </div>
        <!-- Marketing jelölőnégyzet -->
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="marketing-checkbox" />
          <label class="form-check-label" for="marketing">
            <p>
              <strong>Marketing sütik</strong>
              <muted>lehetővé teszik, hogy relevánsabb közösségi média tartalmat és hirdetéseket mutassunk Önnek weboldalunkon és más platformokon.</muted>
            </p>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button id="submit-consent-modal" type="button" class="btn btn-outline-success" data-mdb-dismiss="modal">
          Elfogad
        </button>
      </div>
    </div>
  </div>
</div>