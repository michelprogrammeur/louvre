$(function() {
    $("#select-ticket-type").hide()
    $(".reserver").hide()

    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $("#appbundle_command_visit_date").datepicker({
        dateFormat: 'dd-mm-yy',
        altField: "#alternate",
        altFormat: "yy-mm-dd",
        firstDay: 1,
        minDate: 0,
        beforeShowDay: disabledDays,
        onSelect: function () {
            $("#select-ticket-type").show()
            let val = $("#alternate").val()

            ajaxNumberTickets(val)

            blockTicketTypeAfterHour(val)
        }
    }).attr('readonly', 'readonly');

});

// Permet de bloquer le type de billet après 14h
let blockTicketTypeAfterHour = function (val) {
    let todaysDate

    let date = new Date()
    let maxHour = 13
    let nowHour = date.getHours()
    let day = date.getDate()
    let month = date.getMonth() + 1
    let year = date.getFullYear()

    if (month < 10 && day < 10) {
        todaysDate = [year, '0' + month, '0' + day].join('-');
    }else if(month < 10) {
        todaysDate = [year, '0' + month, day].join('-')
    }else if(day < 10) {
        todaysDate = [year, month, '0'+ day].join('-')
    }else {
        todaysDate = [year, month, day].join('-')
    }

    if (val == todaysDate && nowHour > maxHour) {
        $(".form_full_day").remove()
        $(".reserver").show()
    }else {
        $(".form_full_day").remove()
        $(".reserver").show()
        $("#appbundle_command_ticket_type").prepend("<option class='form_full_day' value='journee' selected='selected'>Journée</option>")
    }
}

// désactive les Mardis, Dimanches et jours fériés
let disabledDays = function (date) {
    let dates = ['1-1', '1-5', '8-5', '14-7', '15-8', '1-11', '11-11', '25-12']
    let day = date.getDate()
    let today = date.getDay()
    let month = date.getMonth() + 1

    for(let i = 0; i < dates.length; i++) {
        if($.inArray(day + "-" + month, dates) !== -1 || (new Date() + 1) > date || today === 2 || today === 0) {
            return [false]
        }
    }
    return [true]
}

// affiche le nombre de billets restants chaque jours
let ajaxNumberTickets = function(val) {
    let maxTicketsByDay = 1000,
        date = new Date(val),
        day = date.getDate(),
        month = date.getMonth() + 1,
        year = date.getFullYear(),
        format_date = [day, month, year].join('/')

    $.ajax({
        type: "GET",
        url: 'api/ticket/count/' + val,
        dataType: 'json',
        success: function (responce) {
            if ((maxTicketsByDay - responce) <= 0) {
                $('#result > div').remove()
                $("#result").append(
                    "<div class='alert alert-dismissible alert-danger'>" +
                    "<button type='button' class='close' data-dismiss='alert'>&times;</button>" +
                    "<h4>Attention :(</h4>" +
                    "<p>" + "Il y a plus aucun billet disponible pour le " + format_date + ". Merci de choisir une autre date de visite." + "</p>" +
                    "</div>"
                )
                $("#appbundle_command_visit_date").val('')
            }else {
                $('#result > div').remove()
                $("#result").append(
                    "<div class='alert alert-dismissible alert-info'>" +
                    "<button type='button' class='close' data-dismiss='alert'>&times;</button>" +
                    "<h4>Information</h4>" +
                    "<p>" + " Il reste "+ (maxTicketsByDay - responce) + " billets pour le " + format_date + "</p>" +
                    "</div>"
                )
            }
        },
        error: function() {
            console.log('erreur !!!!')
        }

    });
}
