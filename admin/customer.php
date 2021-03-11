  <?php include '../template/privatepage.php'; ?>
  <h1 class="mt-4">Ticket</h1>
  <br>
  <form>
    <div class="form-group">
      <label for="exampleFormControlInput1">Oggetto</label>
      <input class="form-control" type="text">
    </div>
    <div class="form-group">
      <label for="exampleFormControlSelect1">Tipologia</label>
      <select class="form-control" id="exampleFormControlSelect1">
        <option>--</option>
        <option>Domanda</option>
        <option>Incidente</option>
        <option>Problema</option>
        <option>feature request</option>
      </select>
    </div>
    <div class="form-group">
      <label for="exampleFormControlSelect1">Priorità</label>
      <select class="form-control" id="exampleFormControlSelect1">
        <option>--</option>
        <option>Bassa</option>
        <option>Media</option>
        <option>Alta</option>
        <option>Urgente</option>
      </select>
    </div>

    <div class="form-group">
      <label for="exampleFormControlTextarea1">Descrizione</label>
      <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
    <div style="float: right;">
      <button class="btn btn-primary" type="submit">Cancella</button>
      <button id="btnShowModal" type="button" class="btn btn-primary" type="submit">Conferma</button><!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ticket creato con successo</h5>
          <button id="btnClose" type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>

    </div>
  </div>
   <script type="text/javascript">
$(document).ready(function(){
  $("#btnShowModal").click(function(){
    $("#exampleModal").modal("show");
  });
  $("#btnClose").click(function(){
    $("#exampleModal").modal("toggle");
  });
});

  </script>
  
  </form>

  <!-- /#page-content-wrapper -->

  </div>
  </body>

  </html>