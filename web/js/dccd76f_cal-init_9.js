function initCalender() {
    var CalendarApp = function () {
        //Variables globals pour le calendrier
        this.$body = $("body"),
            this.$calendar = $('#calendar'),
            this.$event = ('#calendar-events div.calendar-events'),
            this.$categoryForm = $('#newRes'),
            this.$extEvents = $('#calendar-events'),
            this.$modal = $('#my-event'),
            this.$saveCategoryBtn = $('.save-category'),
            this.$calendarObj = null
    };

    /* on drop event listener */
    CalendarApp.prototype.onDrop = function (eventObj, date) {
        if (new Date(date.format()) < new Date()) {
            alert("Vous ne pouvez pas réserver dans le passé");
            return false;
        }
        var $this = this;
        // retrieve the dropped element's stored Event Object
        var originalEventObject = eventObj.data('eventObject');
        var $categoryClass = "bg-info";

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;

        // We have to clone the date with moment(date)
        copiedEventObject.end = moment(date).add(2, 'hours');

        copiedEventObject.dispId = this.$categoryForm.find("select[name='dispositif-Select'] option:selected").val();
        copiedEventObject.userId = this.$categoryForm.find("select[name='utilisateur-select'] option:selected").val();
        copiedEventObject.backTitle = copiedEventObject.title;
        copiedEventObject.title = "Cliquer sur la réservation";
        copiedEventObject['className'] = "bg-info";

        // render the event on the calendar
        $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            eventObj.remove();
        }
    },
        /* on click event listener */
        CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
            //On désactive le bouton de sauvegarde si editable est false
            //editable est un attribut de calEvent initialisé selon la date fin de calEvent par rapport date actuelle
            //si date fin est déja passé -> enregistrer est désactivé
            var disabled = calEvent.editable === false ? "disabled" : "";
            var $this = this;
            //formulaire de sauvegarde
            var form = $("<form></form>");
            form.append("<label>Sauvegarder la réservation</label>");
            form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.backTitle + "' disabled='disabled' /><span class='input-group-btn'><button type='submit' " + disabled + " class='btn btn-success waves-effect waves-light'><i class='fa fa-check'></i>Enregistrer</button></span></div>");
            $this.$modal.modal({
                backdrop: 'static'
            });
            $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function () {
                //Remove Event listener
                $this.$calendarObj.fullCalendar('removeEvents', function (ev) {
                    //Don't remove the persisted and passed reservations

                    //this return
                    var deletable = (ev._id == calEvent._id) && (Date.parse(calEvent.start) > Date.now());
                    console.log(deletable);
                    // Si la réservation n'est pas encore persisté -> on le supprimer du calendrier
                    if (ev._id == calEvent._id && typeof calEvent.id === 'undefined') {
                        return true;
                    }
                    // Si réservation est persisté et date debut n'est pas encore passé
                    if (deletable && typeof calEvent.id !== 'undefined') {
                        $.ajax({
                            type: 'POST',
                            url: apiPathDelete,
                            //TODO parse time
                            data: 'id=' + calEvent.id,
                            dataType: 'json',
                            success: function (data) {
                                $.toast({
                                    heading: 'Réservation supprimé',
                                    text: 'La réservation est annulée avec succée.',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'warning',
                                    hideAfter: 7000,
                                    stack: 6
                                });
                            }
                        });
                    }
                    return deletable;
                });
                $this.$modal.modal('hide');
            });

            $this.$modal.find('form').on('submit', function () {
                calEvent.title = form.find("input[type=text]").val();
                //Si le calEvent n'es pas encore persisté
                if (!calEvent.id) {
                    if (calEvent.end) {
                        $.ajax({
                            type: 'POST',
                            url: apiPathAdd,
                            //TODO parse time
                            data: 'user=' + calEvent.userId + '&dispositif=' + calEvent.dispId + '&dateDebut=' + calEvent.start.format("YYYY-MM-DD HH:mm:ss") + '&dateFin=' + calEvent.end.format("YYYY-MM-DD HH:mm:ss"),
                            dataType: 'json',
                            success: function (data) {
                                console.log(data);
                                if (data.success === 1) {
                                    //On affecte l'id au calEvent -> calEvent est persisté
                                    calEvent.id = data.id;
                                    calEvent.title = data.message;
                                    $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                                    $.toast({
                                        heading: 'Réservation persisté',
                                        text: 'La réservation est sauvegardé avec succée.',
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'success',
                                        hideAfter: 7000,
                                        stack: 6
                                    });
                                    $this.$modal.modal('hide');
                                }
                                else {
                                    $.toast({
                                        heading: "Erreur lors de l'enregistrement",
                                        text: data.message,
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'error',
                                        hideAfter: 7000,
                                        stack: 6
                                    });
                                }
                            }
                        });
                    }
                    else {
                        $.toast({
                            heading: "Erreur",
                            text: 'Validez le temps de fin du réservation.',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                    }

                }
                // Si calEvent est déja persisté -> on pass aux modifications
                else {
                    $.ajax({
                        type: 'post',
                        url: apiPathUpdate,
                        data: 'id=' + calEvent.id + '&dateDebut=' + calEvent.start.format("YYYY-MM-DD HH:mm:ss") + '&dateFin=' + calEvent.end.format("YYYY-MM-DD HH:mm:ss"),
                        dataType: 'json',
                        success: function (data) {
                            if (data.success === 1) {
                                console.log(data);
                                $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                                $this.$modal.modal('hide');
                                $.toast({
                                    heading: 'Réservation mise à jour',
                                    text: 'La réservation est modifié avec succée.',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'info',
                                    hideAfter: 6000,
                                    stack: 6
                                });
                            }
                            else {
                                $.toast({
                                    heading: 'Erreur lors du modification',
                                    text: data.message,
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 6000,
                                    stack: 6
                                });
                            }
                        }
                    });
                }
                return false;
            });
        },
        /* on select event Listener */
        CalendarApp.prototype.onSelect = function (start, end, allDay) {
            if (new Date(start.format()) < Date.now()) {
                alert("Vous ne pouvez pas réserver dans le passé !");
                return false;
            }
            var $this = this;
            $this.$modal.modal({
                backdrop: 'static'
            });

            //Un formaulaire avec dispositif selectionné et désactivé puisque on travaille sur son calendrier de réservation
            var form = $("<form></form>");
            form.append(newRes);
            var dispID = $("#newRes").find('select[name="dispositif-Select"] option:selected').val();
            var dispModele = $("#newRes").find('select[name="dispositif-Select"] option:selected').text();

            form.find('select[name="dispositif-Select"]').attr("disabled", "disabled").html('<option value="' + dispID + '" selected="selected">' + dispModele + '</option>');
            form.find('select[name="utilisateur-select"]').select2();

            $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
                form.submit();
            });
            $this.$modal.find('form').on('submit', function () {
                var userDom = form.find("select[name='utilisateur-select'] option:selected");
                var dispositifDom = form.find("select[name='dispositif-Select'] option:selected");
                var title = dispositifDom.text() + " pour " + userDom.text();
                var user = userDom.val();
                var dispositif = dispositifDom.val();
                var beginning = form.find("input[name='beginning']").val();
                var ending = form.find("input[name='ending']").val();
                if (title !== null && title.length != 0) {
                    $this.$calendarObj.fullCalendar('renderEvent', {
                        title: "Cliquer pour sauvegarder",
                        backTitle: title,
                        start: start,
                        end: end,
                        userId: user,
                        dispId: dispositif,
                        allDay: false,
                        className: "bg-info"
                    }, true);
                    $this.$modal.modal('hide');
                }
                else {
                    alert('You have to give a title to your event');
                }
                return false;

            });
            $this.$calendarObj.fullCalendar('unselect');
        },
        CalendarApp.prototype.enableDrag = function () {

            //init events
            $(this.$event).each(function () {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });
            });
        };
    /* Initializing */
    // Configuration du calendrier
    CalendarApp.prototype.init = function () {
        this.enableDrag();
        /*  Initialize the calendar  */
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());
        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:30:00', /* If we want to split day time each 15minutes */
            snapDuration: "0:30:00",
            minTime: '09:00:00',
            maxTime: '18:00:00',
            defaultView: 'agendaWeek',
            allDaySlot: false,
            buttonText: {
                today: 'Aujourdui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
            },
            dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            handleWindowResize: true,
            eventLimit: true, // for all non-agenda views
            //Prevent events to be dropped in invalid ranges
            //Exmpl : event dropped in a past time
            eventDrop: function (event, delta, revertFunc) {
                if ((new Date(event.start.format()) < new Date())) {
                    revertFunc();
                }
                var onlyExpandble = (new Date(moment(event.start).format()) > new Date()) && new Date(moment(event.start).subtract(delta.asSeconds(), 'seconds').format()) < new Date();
                // console.log("new event start");console.log(new Date(event.start.format()));
                // console.log("old event start");console.log(new Date(moment(event.start).subtract(delta.asSeconds(), 'seconds').format()));


                if (onlyExpandble) {
                    alert('Vous ne pouvez pas modifier une "réservation déja démarré" que par une prolongation :)');
                    revertFunc();
                }
                // if we do need to prevent event dragging after persisting
                // if (!!event.id) {
                //     revertFunc();
                //     alert("Vous pouvez seulement modifier la réservation par la prolongation :)");
                // }
            },
            header: {
                left: "prev,next today",
                center: 'titre',
                right: 'month,agendaWeek,agendaDay'
            },
            slotEventOverlap: false,
            //disallow adding events on the same time with other events
            eventOverlap: false,
            selectOverlap: false,
            eventSources: CalenderEvents,
            //Allow edit events on the calendar
            editable: true,
            //Hide weekends
            weekends: false,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            selectable: true,
            drop: function (date) {
                $this.onDrop($(this), date);
            },
            select: function (start, end, allDay) {
                $this.onSelect(start, end, allDay);
            },
            eventClick: function (calEvent, jsEvent, view) {
                $this.onEventClick(calEvent, jsEvent, view);
            }

        });

        //Bouton ajouter reservation -> Save (OnClick)
        // Ajouter l'evenement au conteneur
        this.$saveCategoryBtn.click(function () {
            var categoryName = $this.$categoryForm.find("select[name='dispositif-Select'] option:selected").text() + " pour " + $this.$categoryForm.find("select[name='utilisateur-select'] option:selected").text();
            if (categoryName !== null && categoryName.length != 0) {
                $this.$extEvents.html('<div class="calendar-events" style="position: relative;"><i class="fa fa-move"></i>' + categoryName + '</div>');
                $this.enableDrag();
            }
        });
    };
    //init CalendarApp
    $.CalendarApp = new CalendarApp;
    $.CalendarApp.Constructor = CalendarApp;
    $.CalendarApp.init();
}
