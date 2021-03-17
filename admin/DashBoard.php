 <?php
  include '../template/privatepage.php'; ?>
 <div class="containerone">
   <div class="containerr">
     <a>
       <p>Unresolved</p>
     </a> 0
   </div>
   <div class="containerr">
     <a>
       <p>solved</p>
     </a>
     0
   </div>
   <div class="containerr">
     <a>
       <p>Overdue</p>
     </a>
     0
   </div>
   <div class="containerr">
     <a>
       <p>Unassigned</p>
     </a>
     0
   </div>
   <div class="containerr">
     <a>
       <p>Open</p>
     </a>
     0
   </div>
   <div class="containerr">
     <a>
       <p>On Hold</p>
     </a>
     0
   </div>
 </div>
 <br>
 <div class="container my-4">

   <hr class="my-4">

   <div>
     <canvas id="lineChart"></canvas>
   </div>

 </div>
 <div style="height: 400px;" class="containerone">
   <div style="margin-right: 0.57%;width:49%;  margin-top: 20px;" class="containerr">
     <p style="float: left;">Unresolved tickets</p>
     <a href="#">View details</a>

     <img style="margin-top: 60px;" src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" alt="unresolved" width="200" height="200">
   </div>
   <div style="text-align: left;margin-right: 0.57%;width:49%;margin-top: 20px;" class="containerr">
     <p style="float: left;">Your Satisfaction</p>
     <a href="#">View details</a>

     <img style="margin-top: 40px; width:80%" src="../assets/img/Soddisfazioni.PNG">
     <!--
    <div>
      <br>
      <p >positive</p>
      <p style="float: left; font-size:30px">0%</p>
    <img  src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" 
    alt="unresolved" width="50px">
    <p>negative</p>
      <span>0%</span>
    <img style="margin-top: 60px;" src="
    https://eucfassetsgreen.freshdesk.com/production/a/assets/images/empty-states/unresolved-empty-eb60bb2b7b369cedbde7f34f11ec516e84dee3f466fd453f4bc621dcea912c98.svg" 
    alt="unresolved">
    </div>-->
   </div>
 </div>

 <style>
   a {
     padding: 5px;
     float: right;
   }

   .containerone {
     box-sizing: border-box;
     background-color: rgb(235, 239, 243);
     height: 100px;
   }

   div.containerr {
     background-color: white;
     height: 80%;
     margin-right: 0.57%;
     margin-top: 10px;
     text-align: center;
     float: left;
     width: 16%;
     position: relative;
   }

   div.containerr:first-child {
     margin-left: 0.57%;
   }

   .containerr:hover {
     box-shadow: 0 5px 15px rgba(0, 0, 0, 0.8);
   }
 </style>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
 <script>
   chart();
 </script>

 </body>