// Menu toggle script
function menuacomparsa() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
}


//line
function chart(date) {
    var data = [];
    var dataSeries = { type: "line" };
    var dataPoints = [];
    for (var i = 0; i < date.length; i += 1) {
        dataPoints.push({
            x: new Date(date[i]["data"]),
            y: date[i]["somma"]
        });
    }
    dataSeries.dataPoints = dataPoints;
    data.push(dataSeries);

    //Better to construct options first and then pass it as a parameter
    var options = {
        zoomEnabled: true,
        animationEnabled: true,
        title: {
            text: "Ticket aperti"
        },
        axisY: {
            lineThickness: 1
        },
        pointSize: 20,
        data: data // random data
    };

    var chart = new CanvasJS.Chart("lineChart", options);
    var startTime = new Date();
    chart.render();
    var endTime = new Date();
    document.getElementById("exportChart").addEventListener("click", function() {
        chart.exportChart({ format: "jpg" });
    });
}

function reload(form, id) {
    var val = form.scelta.options[form.scelta.options.selectedIndex].value;
    self.location = 'AssegnaTicket.php?id=' + id + '&scelta=' + val;
}

function sidebar(arr, posizione) {
    var div = document.getElementById("sidebar");
    var link;
    var href = "";
    link = document.createElement('a');
    link.className = "list-group-item list-group-item-action bg-light";
    link.href = "/private/dashboard.php";
    link.innerHTML = "dashboard";
    div.appendChild(link);
    for (const element of arr) {
        link = document.createElement('a');
        link.className = "list-group-item list-group-item-action bg-light";
        href = "../" + posizione + "/" + element + ".php";
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

function sendEmail(email, username) {
    Email.send({
            Host: "smtp.gmail.com",
            Username: "infoservicehelps@gmail.com",
            Password: "maroc100",
            To: email,
            From: "infoservicehelps@gmail.com",
            Subject: 'Signup | Verification',
            Body: "Please click this link to activate your account:\n\
            http://localhost:8000/verify.php?email=" + email + "&usr=" + username,
        })
        .then(function(message) {});
}