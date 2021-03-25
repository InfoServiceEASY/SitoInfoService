// Menu toggle script
function menuacomparsa() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
}


//line
function chart(date) {
    /*  var ctxL = document.getElementById("lineChart").getContext('2d');
      var myLineChart = new Chart(ctxL, {
          type: 'line',
          data: {
              labels: ["January", "February", "March", "April", "May", "June", "July"],
              datasets: [{
                  label: "Ticket Aperti",
                  data: [0, 2, 0, 2, 0, 0],
                  backgroundColor: [
                      'rgba(105, 0, 132, .2)',
                  ],
                  borderColor: [
                      'rgba(200, 99, 132, .7)',
                  ],
                  borderWidth: 2
              }]
          },
          options: {
              responsive: true
          }
      });*/

    var arrayAcaso = [];
    date.forEach(element => {
        arrayAcaso.push(new Date(element).toISOString().split('T')[0]);
    });
    const config = {
        type: 'ticket aperti',
        data: {
            labels: arrayAcaso,
            datasets: [{
                label: 'Line',
                //     data: [2, 5, 3],
                borderColor: '#D4213D',
                fill: false,
            }, ],
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                }, ],
            },
            pan: {
                enabled: true,
                mode: 'xy',
            },
            zoom: {
                enabled: true,
                mode: 'xy', // or 'x' for "drag" version
            },
        },
    };
    new Chart(document.getElementById('chart'), config);
}

function sidebar(arr) {
    var div = document.getElementById("sidebar");
    var link;
    var href = "";
    for (const element of arr) {
        link = document.createElement('a');
        link.className = "list-group-item list-group-item-action bg-light";
        href = element + ".php";
        link.href = href;
        link.innerHTML = element;
        div.appendChild(link);
    }
}

function EsciDallaPagina() {
    var modal = document.getElementById('id01');
    modal.style.display = 'block';
    window.onclick = function(event) {
        if (event.target == modal) {
            window.history.back();
        }
    }
}