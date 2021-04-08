<?php
session_start();
include '../template/privatepage_params.php';
include_once("../dal.php");
Session();
?>
        <h1 class="mt-4">Visualizza lo stato degli ultimi interventi</h1>
        <br>
        <form>
  <div style="float:centre;">
  <button class="btn btn-primary"  ></button> <!-- type="submit"-->
  </div>
</form>
            </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>

  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>


</body>

</html>