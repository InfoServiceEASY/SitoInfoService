// Menu toggle script
function menuacomparsa() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
}


//line
function chart() {
    var ctxL = document.getElementById("lineChart").getContext('2d');
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
    });
    return myLineChart;
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