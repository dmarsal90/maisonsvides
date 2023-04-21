(function($){
	$(document).ready(function(e){
		var writtenNumber = require('written-number');
		var correctFiles = false;
		var fileTypesPhotos = ["jpg", "jpeg", "png"];
		var fileTypesDocuments = ["pdf", "json"];
		var indexElement = 0;
		var urlDelete = '';
		var isModify = false;
		function validate(element, form) { // Function to validai
			var preventSubmit = true; // Prevent submit
			var type = jQuery(element).attr("type"); // Get the type of input
			switch (type) { // Switch type
				case 'email': // Case email
					var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i; // Regex to validate email
					if (!emailRegex.test($(element).val())) { // If email is invalid
						preventSubmit = true; // Prevent submit
					} else {
						preventSubmit = false; // Continue with send form
					}
					break;
				case 'radio':
				case 'checkbox':
					if (!element.checked) { // If is checked
						preventSubmit = true; // Prevent submit
					} else {
						preventSubmit = false; // Continue with send form
					}
					break;
				case 'file':
					if ($(element).val() === "") { // If isn't empty
						preventSubmit = true; // Prevent submit
					} else {
						preventSubmit = false; // Continue with send form
					}
					break;
				default:
					if ($(element).val() === "") { // If isn't empty
						preventSubmit = true; // Prevent submit
					} else {
						preventSubmit = false; // Continue with send form
					}
					break;
			}
			$(form).addClass("was-validated");
			return preventSubmit;
		}
		function showWaitMe(text, effect) {
			$("body").waitMe({ // Active popup to wait
				text: text, // Loading photos
				effect: effect, // Effect
			});
		}
		function showSwal(title, text, icon, cancelButton, action) {
			var swal = Swal.fire({ // Active popup
				title: title, // Title
				text: text, // Text
				icon: icon, // Icon
				confirmButtonColor: '#446B9E', // Confirm button
				showCancelButton: cancelButton, // Show cancel button true / false
				cancelButtonColor: '#e3342f',
			}).then(function(result) {
				if (result.isConfirmed && icon === 'success' && !isModify) {
					location.reload();
				}
				if (result.isConfirmed && action) {
					$.ajax({
						url: urlDelete,
						type: 'GET',
						beforeSend: function() { // Before send request
							showWaitMe('Suppression des informations, veuillez patienter...', 'facebook')
						},
						success: function(response) {
							$("body").waitMe('hide'); // Hide popup waitMe
							if(response.status) {
								location.reload();
							}
						},
						error: function(error) {
							$("body").waitMe('hide'); // Hide popup waitMe
							console.log(error);
						}
					});
				}
			});
		}
		$("[data-change-menu]").on('change', function(){
			var data = { // Saving parameters to the function in the controller
				_token: $("#_token_savemenu").text(),
				menu: $('input[name=menu]:checked', '#form-menu').val()
			};
			$.ajax({
				url: $("#form-menu").attr('action'),
				type: 'POST',
				data: data,
				beforeSend: function() { // Before send request

				},
				success: function(response) {
					$("#ok-savemenu").removeClass("d-none");
					setTimeout(function(){
						$("#ok-savemenu").addClass("d-none");
					}, 2000);
				},
				error: function(error) {
					$("#not-ok-savemenu").removeClass("d-none");
					setTimeout(function(){
						$("#not-ok-savemenu").addClass("d-none");
					}, 2000);
				}
			});
		});
		$("[data-open-menu]").on("click", function(e){ // Listener to open / hide menu only mobile
			var menu = $(this).attr("data-open-menu"); // Get name of menu
			var wrapper = $(this).attr("data-close-wrapper"); // Get name of wrapper
			$("[data-menu='"+menu+"']").toggleClass("menu--opened"); // To menu add / remove class menu--opened
			if($("[data-menu='"+menu+"']").hasClass("menu--opened")) {
				Cookies.set('open-menu', 1);
				// $("[data-wrapper='"+wrapper+"']").addClass('wrapper__container--small'); // To wrapper add / remove class wrapper__container--small
			}
			else{
				Cookies.set('open-menu', 0);
				// $("[data-wrapper='"+wrapper+"']").removeClass('wrapper__container--small'); // To wrapper add / remove class wrapper__container--small
			}
			$("[data-wrapper='"+wrapper+"']").toggleClass('wrapper__container--small'); // To wrapper add / remove class wrapper__container--small
			$(".header__logo").toggleClass('header__logo--full'); // To header__logo add / remove class header__logo--full
			if($("[data-table]").length) { // If attribute data table exists
				setTimeout(function(e){
					$("[data-table]").DataTable().columns.adjust().responsive.recalc(); // Adjust the responsive of columns of all tables
				}, 600);
			}
			actionsResize();
		});
		if($(".view-individual").length) {
			$("#menu-scrolling .nav-item .nav-link").on("click", function(e) {
				var tabCurrent = $(this).attr("aria-controls");
				Cookies.set('tab-active', tabCurrent);
			});
		}
		if($("#calendar").length) { // If calendar ID exists
			var calendarEl = document.getElementById('calendar'); // Get element calendar
			var information = {}; // Initialize variable information empty
			var urlEvents = $(calendarEl).attr('data-url');
			$.ajax({
				url: urlEvents,
				type: 'GET',
				beforeSend: function() {
					showWaitMe('Préparation du calendrier, veuillez patienter ...', 'facebook');
				},
				success: function(response){
					$("body").waitMe('hide');
					if(response.status) {
						var events = response.events;
						var emails = {};
						events.forEach(function(event) {
							var key = event.backgroundColor;
							key = key.replace('#', '');
							emails[key] = event.extendedProps.contact;
						});
						for(var i in emails) {
							$("#colors-users-calendar").append('<span class="color-user-calendar" style="background:#'+i+'"></span> '+ emails[i] + ' <input type="checkbox" data-hide-calendar="'+emails[i]+'" class="check-middle" value="true"></br>');
						}
						var calendar = new FullCalendar.Calendar(calendarEl, { // Create a new Fullcalendar instance
							initialView: 'dayGridMonth', // Initial view
							locale: 'fr', // Set the language
							timeZone: 'Europe/Brussels',
							allDaySlot: false,
							firstDay: 1,
							hiddenDays: [0,6],
							events: events, // Add the events, temporally static
							eventClick: function(info) {
                                information = info.event.extendedProps;
                                $("#calendarModal").modal('show');
                              },
                              dateClick: function(info) {
                                $("#calendarModal").modal('show');
                              }
                            });
						$('[data-hide-calendar]').on('click', function(){ // To hide ow show events of each calendar
							if ($(this).prop('checked')) { // If true hide all events of this calendar
								var name = ($(this).attr("data-hide-calendar")).replace('@gmail.com', '');
								var classn = name.replaceAll('.', '-');
								$("."+classn).hide();
							} else { // If not checked show all events of this calendar
								var name = ($(this).attr("data-hide-calendar")).replace('@gmail.com', '');
								var classn = name.replaceAll('.', '-');
								$("."+classn).show();
							}
						});
						if(!events.length) {
							showSwal('Aucune visite', response.message, 'warning', false, false);
						}
						calendar.render(); // Render calendar
					}
					else {
						showSwal('Error', response.message, 'error', false, false);
					}
				},
			});

			$("#calendarModal").on("show.bs.modal", function(e) { // Listener when the modal is open
				$("[data-event-title]").text(information.title); // Set the title
				$("[data-description]").html(information.description); // Set the description
				$("[data-address]").text(information.address); // Set the address
				$("[data-contact]").text(information.contact); // Set the name of contact
				$("[data-phone]").html("<a href='tel:"+information.phone+"'>"+information.phone+"</a>"); // Set the phone
				$("[data-email]").html("<a href='mailto:"+information.email+"'>"+information.email+"</a>"); // Set the email
				$("[data-type]").text(information.type); // Set the type
                if (information && information.coordinates && information.coordinates.lat) {
                    var srcMap = "https://maps.google.com/maps?q=+"+information.coordinates.lat+"+,+"+information.coordinates.long+"+&hl=es&z=14&output=embed&hl=fr";
                  }// URL of the estate
                   else {
                    var srcMap = "https://maps.google.com/maps?q=mi+ubicacion&hl=es&z=14&output=embed&hl=fr";
                  }

				$("[data-coordinates]").attr("src", srcMap); // Set the url on the iframe of map
			});
		}
		$("#calendargoogle").on("show.bs.modal", function(e) {
			var calendarEl = document.getElementById('calendarmodalrdv'); // Get element calendar
			var information = {}; // Initialize variable information empty
			var urlEvents = $(calendarEl).attr('data-url');
			$.ajax({
				url: urlEvents,
				type: 'GET',
				beforeSend: function() {
					$("#colors-users-calendar").empty();
					showWaitMe('Préparation du calendrier, veuillez patienter ...', 'facebook');
				},
				success: function(response){
					$("body").waitMe('hide');
					if ($("[data-time]").attr("data-time") == "00:00:00") {
						$("[data-time]").hide();
					}
					if(response.status) {
						var events = response.events;
						var emails = {};
						var options = '';
						events.forEach(function(event) {
							var key = event.backgroundColor;
							key = key.replace('#', '');
							emails[key] = event.extendedProps.contact;
						});
						for(var i in emails) {
							options += '<option>'+emails[i]+'</option>';
							$("#colors-users-calendar").append('<span class="color-user-calendar" style="background:#'+i+'"></span> '+ emails[i] + ' <input type="checkbox" data-hide-calendar="'+emails[i]+'" class="check-middle" value="true"></br>');
						}
						$("#chosen_calendar").append(options);
						var calendar = new FullCalendar.Calendar(calendarEl, { // Create a new Fullcalendar instance
							locale: 'fr', // Set the language
							timeZone: 'Europe/Brussels',
							initialView: 'timeGridWeek',
							allDaySlot: false,
							firstDay: 1,
							hiddenDays: [0,6],
							slotMinTime: "06:00:00",
							slotMaxTime: "23:00:00",
							events: events, // Add the events, temporally static
							eventClick: function(info) { // Event click
								information = info.event.extendedProps; // Save the estate info on the variable information
								$("#calendarModal").modal('show'); // Open modal with the all data of the estate clicked
							},
							dateClick: function(date, jsEvent) {
								$("#createEvent").modal("show");
								var dateSplit = date.dateStr.split("T");
								$("#date_event_click_start").val(dateSplit[0]);
								$("#date_event_click_end").val(dateSplit[0]);
								$("#time_event_click_start").val(dateSplit[1]);
								var time = new Date(date.dateStr);
								var minutes = time.getMinutes();
								time.setMinutes(minutes + 45);
								$("#time_event_click_end").val(("0" +time.getHours()).slice(-2) + ':' + time.getMinutes());
							}
						});
						// Change color to confirmed events
						aEventConfirmed.forEach(function(event){
							var eventc = calendar.getEventById(event.event_id); // an event object!
							if (eventc !== null) {
								eventc.setProp('backgroundColor', "#3cd47c");
								eventc.setProp('borderColor', "#3cd47c");
							}

						});
						$('[data-hide-calendar]').on('click', function(){ // To hide ow show events of each calendar
							console.log('click');
							if ($(this).prop('checked')) { // If true hide all events of this calendar
								var name = ($(this).attr("data-hide-calendar")).replace('@gmail.com', '');
								var classn = name.replaceAll('.', '-');
								$("."+classn).hide();
							} else { // If not checked show all events of this calendar
								var name = ($(this).attr("data-hide-calendar")).replace('@gmail.com', '');
								var classn = name.replaceAll('.', '-');
								$("."+classn).show();
							}
						});
						if(!events.length) {
							showSwal('Aucune visite', response.message, 'warning', false, false);
						}
						calendar.render(); // Render calendar
					}
					else {
						showSwal('Error', response.message, 'error', false, false);
					}
				},
			});

			$("#calendarModal").on("show.bs.modal", function(e) { // Listener when the modal is open
				$("[data-event-title]").text(information.title); // Set the title
				$("[data-description]").html(information.description); // Set the description
				$("[data-address]").text(information.address); // Set the address
				$("[data-contact]").text(information.contact); // Set the name of contact
				$("[data-phone]").html("<a href='tel:"+information.phone+"'>"+information.phone+"</a>"); // Set the phone
				$("[data-email]").html("<a href='mailto:"+information.email+"'>"+information.email+"</a>"); // Set the email
				$("[data-type]").text(information.type); // Set the type
				var srcMap = "https://maps.google.com/maps?q=+"+information.coordinates.lat+"+,+"+information.coordinates.long+"+&hl=es&z=14&output=embed&hl=fr"; // URL of the estate
				$("[data-coordinates]").attr("src", srcMap); // Set the url on the iframe of map
			});
		});
		$("#name_template_sms").on('keyup', function(){
			$("#slug-template-name-sms").val(slug($(this).val()));
		});
		$("#name_template_condition").on('keyup', function(){
			$("#slug-template-name-condition").val(slug($(this).val()));
		});
		$("#name_template_offer").on('keyup', function(){
			$("#slug-template-name-offer").val(slug($(this).val()));
		})
		if($("[data-table]").length) { // If data-table attribute exists
			var search = "";
			if(location.href.indexOf("search?s=") >= 0) {
				if(location.href.split("search?s=")[1] !== "") {
					search = location.href.split("search?s=")[1];
				}
				search = search.replace(/\+/g, " ");
			}
			var paramentersDataTable = { // Varible with the parametres for dataTables
				pageLength: 100,
				responsive: true, // Enable resposive
				colReorder: true, // Enable the reorder of columns
				destroy: true, // Allow the destroy
				language: { // Change the text to languages
					sProcessing: "En traitement ...", // Process
					sLengthMenu: "Afficher les enregistrements _MENU_", // Number of register to show
					sZeroRecords: "Aucun résultat trouvé", // Empty results
					sEmptyTable: "Aucune donnée disponible dans ce tableau", // Empty table
					sInfo: "Entrées : _END_/_TOTAL_", // Total registers
					sInfoEmpty: "Affichage des enregistrements de 0 à 0 sur un total de 0 enregistrements", // Number of register showing
					sInfoFiltered: "(filtrage d'un total de _MAX_ enregistrements)", // Number of registers on filter
					sInfoPostFix: "",
					sSearch: "Rechercher:", // Text of search
					sUrl: "",
					sInfoThousands: ",",
					sLoadingRecords: "Chargement...", // Loading data
					oPaginate: {
						sFirst: "Premier", // First page
						sLast: "Dernier", // Last page
						sNext: "Suivant", // Next page
						sPrevious: "Précédent" // Previous page
					},
					oAria: {
						sSortAscending: ": Activé pour trier la colonne par ordre croissant", // Active sort ascending
						sSortDescending: ":r Activer pour trier la colonne par ordre décroissant" // Active short descending
					},
					buttons: {
						copy: "Copier", // Copy
						colvis: "Visibilité" // Visbility
					}
				},
				oSearch: {
					sSearch: search,
				},
			}
			$("[data-table]").each(function(e){ // For each data-table
				var indexorder = 0;
				$(this).find('th').each(function(e){
					if ($(this).hasClass('table-order')) {
						indexorder = $(this).index();
						return false;
					}
				});
				paramentersDataTable['order'] = [indexorder, 'desc'];
				$(this).DataTable(paramentersDataTable); // Init the datatable
			});
			$("a[data-toggle='tab']").on('shown.bs.tab', function (e) { // Listener of change tab active
				e.target // newly activated tab
				e.relatedTarget // previous active tab
				var tabContent = $(e.target).attr("href"); // Get the ID of content tab
				$(tabContent).find("[data-table]").DataTable().destroy(); // If the content has table is destroyed
				$(tabContent).find("[data-table]").DataTable(paramentersDataTable); // Reinit the table
				if(!$(tabContent).find(".active.show").length) { // If the tab has not classes active and show
					$(tabContent).find("a[data-toggle='tab']").first().click(); // Click on first sub tab
				}
			})
		}
		$("[data-href]").on("click", function(e) { // Listener to smooth scroll
			e.preventDefault(); // Prevent default
			var scrollTop = $(this).attr("data-href"); // ID to scroll
			$("html, body").animate({
				scrollTop: $(scrollTop).offset().top - $("header").first().height() - 50, // Scrolling to element
			}, 600);
		});
		function actionsResize() { // Anctios to run on resize window
			$("body").attr("data-offset", ($("header").first().height() + 55)); // Change de offset in body to scrollspy
			if($("#menu-scrolling").length) { // If the menu of anchors estates exists
				if($(window).width() >= 992) { // If the window width is bigger that 992
					setTimeout(function(){
						var top = ($("header").first().height() - $("#menu-scrolling").outerHeight()) / 2; // Get the size of header less the menu estate height between 2
						$("#menu-scrolling").css({top: ""}); // Set the top empty
						$("#menu-scrolling").css({top: top+"px"}); // Set the menu in the middle
					}, 600);
				}
				if($("[data-menu='menu'").hasClass("menu--opened")) { // If general menu has the menu--opened
					$("#menu-scrolling").addClass("wrapper__anchors--minified"); // Add class wrapper__anchors--minified
				}
				else {
					$("#menu-scrolling").removeClass("wrapper__anchors--minified"); // Remove class wrapper__anchors--minified
				}
			}
		}
		actionsResize(); // Call funtion actionResize
		$(window).on("resize", function(e){ // Listener on window resize
			actionsResize(); // Call function antionsResize
		});
		$("[data-edit-form]").on("click", function(e){ // Listener in button to edit form
			e.preventDefault(); // Prevent default
			var nameForm = $(this).attr("data-edit-form"); // Get the name of form
			var form = document.querySelector("[data-form='"+nameForm+"']"); // Get form with query selector
			$(form).find("input, textarea, select").prop("disabled", false); // Set the property disabled in false for input, textarea, select
			$(this).hide(); // Button edit hide
			$("[data-cancel-form='"+nameForm+"']").show(); // Button cancel show
			$("[data-submit-form='"+nameForm+"']").show(); // Button submit show
		});
		$("[data-cancel-form]").on("click", function(e){ // Listener in button to cancel form
			e.preventDefault(); // Prevent default
			var nameForm = $(this).attr("data-cancel-form"); // Get name of form
			var form = document.querySelector("[data-form='"+nameForm+"']"); // Get form
			$(this).hide(); // Hide the button cancel
			$("[data-edit-form='"+nameForm+"']").show(); // Show the button edit
			$("[data-submit-form='"+nameForm+"']").hide(); // Hide the button submit
		});
		$(document).on("click", "[data-submit-form]", function(e){ // Listener in button to submit form
			e.preventDefault(); // Prevent default
			isModify = false;
			removeEventListener('beforeunload', checkforms, {capture: true});
			var parentCard = $(this).parents(".card");
			parentCard.find(".card-header").removeClass("notsaved").find("span").remove();
			$(this).attr("data-modified", false);
			var nameForm = $(this).attr("data-submit-form"); // Get name of form
			var form = document.querySelector("[data-form='"+nameForm+"']"); // Get form
			var hide = ($(this).attr("data-submit-hide") === "true") ? true : false; // Hide button true or false
			var preventSubmitt = false; // Allow submit by default
			var type = $(form).attr("method"); // Method POST or GET
			var url = $(form).attr("action"); // URL of ajax
			var data = $(form).serialize();
			var clean = ($(form).attr("data-clean") && $(form).attr("data-clean") === "true") ? true : false; // Get clean form
			var reload = ($(form).attr("data-reload") && $(form).attr("data-reload") === "true") ? true : false; // Get reload page
			var ajax = ($(form).attr("data-ajax") && $(form).attr("data-ajax") === "false") ? false : true; // Get reload page
			if(!ajax) {
				$(form).submit();
			}
			else {
				$(form).find('input[required], select[required], textarea[required]').not("[type='hidden']").each(function(event) { // Check all select, input, textarea that are required
					if(validate(this, form)) { // If prevent submit is true
						preventSubmitt = true; // Prevent submit
					}
				});
				if(!preventSubmitt) { // If submit is allowed
					$.ajax({
						url: url,
						data: data,
						type: type,
						beforeSend: function() { // Before send request
							showWaitMe('Mise à jour des informations, veuillez patienter...', 'facebook')
						},
						success: function(response) {
							$("body").waitMe('hide'); // Hide popup waitMe
							if($("[data-modified='true']").length) {
								isModify = true;
								addEventListener('beforeunload', checkforms, {capture: true});
							}
							if(response.status) {
								if(hide) { // If hide is true
									$(form).find("input, textarea, select").prop("disabled", true); // Disable input, textarea, select
									$("[data-submit-form='"+nameForm+"']").hide(); // Hide the button submit
								}
								$("[data-edit-form='"+nameForm+"']").show(); // Show the button edit
								$("[data-cancel-form='"+nameForm+"']").hide(); // Hide the button cancel
								$(form).removeClass("was-validated"); // Remove class was-validated of form
								if(!reload) { // If reload is false
									showSwal('Succès', response.message, 'success', false, false); // Show swal popup with success
									if(clean) { // If clean is true
										form.reset(); // Reset form
									}
								}
								if(reload) { // If reload is true
									location.reload(); // Reload the page
								}
							}
							if(!response.status) { // If status of response is false
								showSwal('Erreur', response.message, 'error', false, false); // Show swal popup with error
							}
						},
						error: function(error) {
							$("body").waitMe('hide'); // Hide popup waitMe
							// console.log(error);
						}
					});
				}
			}
		});
		$("[data-password]").on("click", function(e){ // Listener to change password by text
			e.preventDefault(); // Prevent default
			var nameInput = $(this).attr("data-password"); // Get name of input
			var currentIcon = $(this).find("i").attr("class"); // Get curent icon shown
			var nextIcon = $(this).attr("data-icon"); // Get the next icon to show
			var inputPassword = $("input[name='"+nameInput+"']"); // Get input type password
			var nextType = (inputPassword.attr("type") === "password") ? "text" : "password"; // Save the next type of input
			inputPassword.attr("type", nextType); // Set the type
			$(this).find("i").attr("class", nextIcon); // Set the next icon
			$(this).attr("data-icon", currentIcon); // Save the current icons
		});
		if ($("#estate_template_sms").length) {
			var idTemplateSMS = document.getElementById("estate_template_sms").value;
			$("#sendSMS").attr("data-target", "#sendSMS-"+idTemplateSMS);
			$("#sendSMS").attr("data-id-tem-sms", idTemplateSMS);
		}
		$("#estate_template_sms").on('change', function(){
			var idTemplateSMS = document.getElementById("estate_template_sms").value;
			$("#sendSMS").attr("data-target", "#sendSMS-"+idTemplateSMS);
			$("#sendSMS").attr("data-id-tem-sms", idTemplateSMS);
		});
		if ($("#estate_template_email").length) {
			var idTemplateEmail = document.getElementById("estate_template_email").value;
			$("#sendMail").attr("data-target", "#sendMail-"+idTemplateEmail);
			$("#sendMail").attr("data-target", "#sendMail-"+idTemplateEmail);
			$("#sendMail").attr("data-id-tem-email", idTemplateEmail);
		}
		$("#estate_template_email").on('change', function(){
			var idTemplateEmail = document.getElementById("estate_template_email").value;
			$("#sendMail").attr("data-target", "#sendMail-"+idTemplateEmail);
			$("#sendMail").attr("data-id-tem-email", idTemplateEmail);
		});
		function readImage(file, max, container, type) { // Function to readImage or documents to upload
			var fr = new FileReader(); // Create new instance of FileReader
			function readFile(index) { // Function to read the file
				if(index >= max) { // If the index is greater than or equal
					$("body").waitMe("hide"); // Hide the popup to wait
					return; // Exit function
				}
				else {
					var singleFile = file.files[index]; // Save the current file in variable
					fr.onload = function(e) { // Function onload
						var srcFile = e.target.result; // Get the resource in base64
						var ts = String(new Date().getTime()), i = 0, out = '';
						for(i = 0; i < ts.length; i += 2) {
							out += Number(ts.substr(i, 2)).toString(36);
						}
						var uid = out;
						var html = ""; // Create a variable html empty to put content
						html += '<div class="wrapper__document">';
						html += '<a data-delete data-temp="' + uid + '" href="">';
						html += '<i class="bi bi-x" ></i>';
						html += '</a>';
						html += '<a href="#" target="_blank" download="' + singleFile.name + '">' + singleFile.name + '</a>';
						html += '<a href="#" target="_blank" download="' + singleFile.name + '">';
						if(type === "photos") {
							html += '<img src="'+srcFile+'">';
						}
						if(type === "documents") {
							html += '<img src="/img/icons/file.svg">';
						}
						html += '</a>';
						html += '</div>';
						$("#"+container).append(html); // Append in conrainer the photos or documents
						if(type === "photos") { // If type is photos
							var indexIndicator = ($("#carouselEstatePhotos").find(".carousel-indicators > li").length) ? parseInt($("#carouselEstatePhotos").find(".carousel-indicators > li").last().attr("data-slide-to")) + 1 : 0; // Get the last indicator and plus one
							var indicator = '<li data-target="#carouselEstatePhotos" data-slide-to="'+indexIndicator+'" class=""></li>';
							var classActive = (indexIndicator === 0) ? ' active' : '';
							var slide = '<div class="carousel-item'+classActive+'">';
							slide += '<div class="block-16-9">';
							slide += '<div>';
							slide += '<img src="'+srcFile+'">';
							slide += '</div>';
							slide += '</div>';
							slide += '</div>';
							$("#carouselEstatePhotos").find(".carousel-indicators").append(indicator); // Append the new indicator to carousel
							$("#carouselEstatePhotos").find(".carousel-inner").append(slide); // Append the new slide to carousel
							if(indexIndicator >= 1) {
								$("#carouselEstatePhotos").find(".carousel-control-prev").removeClass("d-none");
								$("#carouselEstatePhotos").find(".carousel-control-next").removeClass("d-none");
								$("#carouselEstatePhotos").find(".carousel-indicators").removeClass("d-none");
							}
						}
						var token = $("#token").text(); // Variable to save the token csrf by laravel
						var srcImage = singleFile.name; // Variable to save the name of the image
						var srcFile = e.target.result; // Variable to save the route resource of the image
						var uploadImage = { // Saving parameters to the function in the controller
							_token: token,
							namePhoto: srcImage,
							photo: srcFile,
							estateid: $(file).attr("data-estate-id"),
							typefile: type,
							size: singleFile.size,
							uid: uid
						};
						$.ajax({ // Function ajax so save file in sdk and db
							url: $(file).attr("data-upload"),
							data: uploadImage,
							type: "POST",
							beforeSend: function() {},
							success: function(response) {
								if (typeof response.uid !== typeof undefined && response.uid !== false) {
									var urlmedia = urldeletemedias.replace('/0', '');
									$("[data-temp='"+response.uid+"']").attr('href', urlmedia + '/' +response.id);
								}
								readFile((index + 1)); // Read the next file
							},
							error: function(error) {
								console.log(error);
							}
						});
					};
				}
				fr.readAsDataURL(singleFile); // Red the file as base64
			}
			readFile(0); // Get the first position of files
		}
		$("[data-files]").on("change", function(e){ // Listener in input files for photos and documents
			var file = this; // Save the current input in variable
			var max = file.files.length; // Save the lenght of files
			var typeFiles = $(this).attr("data-files"); // Save the type of files
			var container = $(file).attr("data-container-files"); // Save the container
			var text = ""; // Init text empty
			if(max) { // If max is different of 0
				if(typeFiles === "photos") { // If typeFiles is equal to photos
					text = 'Type de fichier invalide. Seuls les jpeg, jpg, png sont autorisés'; // Text to show in the popup
					for(var i = 0; i < max; i++) {
						if(fileTypesPhotos.indexOf(file.files[i].type.split("/")[1]) >= 0) { // If extensions are allowed
							correctFiles = true;
						}
						else {
							correctFiles = false;
							break;
						}
					}
				}
				if(typeFiles === "documents") { // If type is equal to documents
					text = 'Type de fichier invalide. Seuls les pdf, json sont autorisés'; // Text to show in the popup
					for(var i = 0; i < max; i++) {
						if(fileTypesDocuments.indexOf(file.files[i].type.split("/")[1]) >= 0) { // If extensions are allowed
							correctFiles = true;
						}
						else {
							correctFiles = false;
							break;
						}
					}
				}
				if(correctFiles) { // If correct files is true
					showWaitMe('Préparation de(s) fichier(s), veuillez patienter ...', 'facebook');
					readImage(file, max, container, typeFiles); // Call function to read photos / documents
				}
				else {
					showSwal("Erreur", text, 'error', false, false);
				}
			}
		});
		function changePhoto(file, container) { // Function to change main photo
			var fr = new FileReader(); // Create a new instance of File Reader
			fr.onload = function(e) { // Function onload
				$("[data-img-view='"+container+"']").attr("src", e.target.result); // Change preview of photo
				$("body").waitMe('hide'); // Hide the popup to wait
				var name = 'main_photo_' + $(file).attr("data-estate-id"); // Save name with the estate id
				var token = $("#token").text(); // Variable to save the token csrf by laravel
				var srcImage = name; // Variable to save the route resource of the image
				var srcFile = e.target.result; // Variable to save the route resource of the image
				var uploadImage = { // Saving parameters to the function in the controller
					_token: token,
					namePhoto: srcImage,
					photo: srcFile,
				};
				$.ajax({ // Function ajax so save file in sdk and db
					url: $(file).attr("data-upload"),
					data: uploadImage,
					type: "POST",
					beforeSend: function() {},
					success: function(response) {
					},
					error: function(error) {
						console.log(error);
					}
				});
			};
			fr.readAsDataURL(file.files[0]); // Read the file as base64
		}
		$("[data-image]").on("change", function(e){ // Listener input to change image
			var file = this; // Save the current input file
			if(file.files.length) {
				var container = $(this).attr("data-image"); // Save the container
				if(fileTypesPhotos.indexOf(file.files[0].type.split("/")[1]) >= 0) { // If the extentions are allowed
					showWaitMe("Changement de photo, veuillez patienter ...", "facebook");
					changePhoto(file, container); // Call function to change photo
				}
				else {
					showSwal("Erreur", 'Type de fichier invalide. Seuls les jpeg, jpg, png sont autorisés', 'error', false, false);
				}
			}
		});
		$("[data-template]").on("click", function(e){ // Listener click on create template
			var body = tinyMCE.activeEditor.getContent();
			var token = $("#tokenTemplate").text(); // Variable to save the token csrf by laravel
			var uploadTemplate = { // Saving parameters to the function in the controller
				_token: token,
				templateName: $("#form_template_name").val(),
				file: slug($("#form_template_name").val()),
				templateBody: body,
				type: 'email'
			};
			$.ajax({ // Function ajax so save file in sdk and db
				url: $(this).attr("data-template"),
				data: uploadTemplate,
				type: "POST",
				beforeSend: function() {},
				success: function(response) {
				},
				error: function(error) {
					console.log(error);
				}
			});
		});
		$("#template_c").on('change', function(){ // Listener when select change
			var idTemplate = document.getElementById("template_c").value; // Get if of the template
			var template = document.getElementById("template_c");
			var nameTemplate = template.options[template.selectedIndex].text; // Get name of choisen option
			var url = $('option:selected', $(this)).attr("data-href"); // Getting url to delete template
			$("#edit_template").attr("data-target", "#editTemplate-"+idTemplate); // Add atribute data to the modal of edit the template
			var comUrl = url + '/' + idTemplate + '/' + slug(nameTemplate); // Adding parameters to the url to delete the template
			$("#delete_template").attr("href", comUrl); // Adding the url complete to attribute data
		});
		if ($("#template_c").length) {
			var idTemplate = document.getElementById("template_c").value;// Get if of the template
			var template = document.getElementById("template_c");
			var nameTemplate = template.options[template.selectedIndex].text; // Get name of choisen option
			var url = $('option:selected', $(this)).attr("data-href");// Getting url to delete template
			var comUrl = url+'/'+idTemplate + '/' + slug(nameTemplate);// Adding parameters to the url to delete the template
			$("#delete_template").attr("href", comUrl);
			$("#edit_template").attr("data-target", "#editTemplate-"+idTemplate);
			$("[data-formeditT]").on('click', function(){
				var id = $(this).attr("data-formeditT");
				var body = tinyMCE.activeEditor.getContent();
				var token = $(this).attr("data-csrf"); // Variable to save the token csrf by laravel
				var updateTemplate = { // Saving parameters to the function in the controller
					_token: token,
					templateName: $("#form_template_name-"+id).val(),
					file: slug($("#form_template_name-"+id).val()),
					templateBody: body,
					type: 'email',
					id: id
				};
				$.ajax({ // Function ajax so save file in sdk and db
					url: $(this).attr("data-url"),
					data: updateTemplate,
					type: "POST",
					beforeSend: function() {},
					success: function(response) {
					},
					error: function(error) {
						console.log(error);
					}
				});
			})
		}
		if($("[data-tiny]").length) { // If attribute data-tiny exists
			$("[data-tiny]").each(function(e){ // For each data-tiny
				var idTiny = $(this).attr("id"); // Get the ID of tiny
				var tinyHeight = parseInt($(this).attr("data-height")); // Get height of editor tiny
				tinymce.init({ // Init editor tiny
					selector: "#"+idTiny, // Selector
					height: tinyHeight, // Height
					content_style: "body {font-size: 10pt;}",
					setup: function(editor){
						editor.on('input', function(){
							var id = $(editor).attr('id');
							$("#"+id).val(tinyMCE.activeEditor.getContent());
						});
						var remarks = '<table>\
										<tr>\
											<td>Etat maison - Intérieur: </td>\
											<td>' + $("[name='interior_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat maison - Extérieur: </td>\
											<td>' + $("[name='exterior_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat maison - Le quartier: </td>\
											<td>' + $("[name='district_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points forts - Intérieur: </td>\
											<td>' + $("[name='interior_highlights']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points forts - Extérieur: </td>\
											<td>' + $("[name='exterior_highlights']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points faibles - Intérieur: </td>\
											<td>' + $("[name='interior_weak_point']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points faibles - Extérieur: </td>\
											<td>' + $("[name='exterior_weak_point']").val() + '</td>\
										</tr>\
									</table>';
						var bieninterior = '<table>\
										<tr>\
											<td>Chambres: </td>\
											<td>' + $("[name='rooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Chambres - étage: </td>\
											<td>' + $("[name='floorRooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Sdb: </td>\
											<td>' + $("[name='floorBathroom_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Sdb -	- étage: </td>\
											<td>' + $("[name='surfaceHabitable_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Surf.Habitable: </td>\
											<td>' + $("[name='floors_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etages: </td>\
											<td>' + $("[name='estimDecoration_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Estim. décoration: </td>\
											<td>' + $("[name='soilType_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Type de sol - Living: </td>\
											<td>' + $("[name='typeOfLivingFloor_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Type de sol - Chambres: </td>\
											<td>' + $("[name='typeOfLivingRooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine: </td>\
											<td>' + $("[name='kitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Type: </td>\
											<td>' + $("[name='typeOfKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Plan travail: </td>\
											<td>' + $("[name='worktopKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Age: </td>\
											<td>' + $("[name='ageKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Etat: </td>\
											<td>' + $("[name='stateKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat général: </td>\
											<td>' + $("[name='stateGeneral_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Typ.Chauff. centr.: </td>\
											<td>' + $("[name='chassisCentralType_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Typ.Châssis: </td>\
											<td>' + $("[name='chassisType_adapte']").val() + '</td>\
										</tr>\
									</table>';
						var bienexterior = '<table>\
										<tr>\
											<td>Jardin - orientation: </td>\
											<td>' + $("[name='jardinOrientation_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Jardin - surface: </td>\
											<td>' + $("[name='jardinArea_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Terrasse: </td>\
											<td>' + $("[name='terrace_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Balcon: </td>\
											<td>' + $("[name='balcony_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Véranda: </td>\
											<td>' + $("[name='veranda_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Panneaux solaires: </td>\
											<td>' + $("[name='solarPanels_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Panneaux solaires - Installés le: </td>\
											<td>' + $("[name='solarPanelInstaldDate_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat général: </td>\
											<td>' + $("[name='stateGeneralExterior_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking: </td>\
											<td>' + $("[name='parking_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking	- Combien de place(s): </td>\
											<td>' + $("[name='parkingHowManyPlaces_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking - Type(s): </td>\
											<td>' + $("[name='typeParking_adapte']").val() + '</td>\
										</tr>\
									</table>';
						editor.on('init', function(){
							var id = $(editor).attr('id');
							if (id == "tinyEditOffer") {
								var content = editor.getContent();
								content = content.replace('[address-estate]', $("[name='address_estate']").val());
								content = content.replace('[localisation-estate]', $("[name='address_estate']").val());
								content = content.replace('[description-estate]', $("[name='estate_description']").val());
								content = content.replace('[details-interior-estate]', bieninterior);
								content = content.replace('[details-exterior-estate]', bienexterior);
								content = content.replace('[remarks-estate]', remarks);
								editor.setContent(content);
								$("#"+id).val(content);
							}
							// if (id == "tinyEditOfferPDF") {
							// 	var content = editor.getContent();
							// 	var dataNotary = document.getElementById("data_notary").value;
							// 	if (dataNotary === '') {
							// 		content = content.replace('<li>Notaire de l&rsquo;acqu&eacute;reur : [notary]</li>', '');
							// 	} else {
							// 		content = content.replace('[notary]', dataNotary);
							// 	}
							// 	editor.setContent(content);
							// 	$("#"+id).val(content);
							// }
							$("[data-id-tem-email]").on('click', function(){
								var idemail = $(this).attr("data-id-tem-email");
								var lid = "tinySendMail-"+idemail;
								console.log(lid);
								if (id == lid) {
									console.log('hi...');
									var content = editor.getContent();
									content = content.replace('[address-estate]', $("[name='address_estate']").val());
									content = content.replace('[localisation-estate]', $("[name='address_estate']").val());
									content = content.replace('[description-estate]', $("[name='estate_description']").val());
									content = content.replace('[details-interior-estate]', bieninterior);
									content = content.replace('[details-exterior-estate]', bienexterior);
									content = content.replace('[remarks-estate]', remarks);
									editor.setContent(content);
									$("#"+id).val(content);
								}
							});
							$("[data-id-tem-sms]").on('click', function(){
								var idsms = $(this).attr("data-id-tem-sms");
								var lid = "form_phone_message-"+idsms;
								var content = $("#"+lid).val();
								content = content.replace('[address-estate]', $("[name='address_estate']").val());
								content = content.replace('[localisation-estate]', $("[name='address_estate']").val());
								content = content.replace('[description-estate]', $("[name='estate_description']").val());
								content = content.replace('[details-interior-estate]', bieninterior);
								content = content.replace('[details-exterior-estate]', bienexterior);
								content = content.replace('[remarks-estate]', remarks);
								editor.setContent(content);
								$("#"+lid).val(content);
							});
						});
					}
				});
			});
		}
		$("[data-step]").on("click", function(e){ // Listener to steps
			e.preventDefault(); // Prevent default
			var currentStep = $(this).attr("data-step"); // Save current step
			var nextStep = $(this).attr("data-next-step"); // Save next step
			$("#"+currentStep).hide(); // Hide current step
			$("#"+nextStep).show(); // Show next step
		});
		$(document).on("click", "[data-delete]", function(e){ // Listener to delete a any element
			e.preventDefault(); // Prevent Default
			urlDelete = $(this).attr("href"); // Get URL of element to delete
			showSwal('Effacer', 'Tu es sûr ?', 'question', true, true); // Call popup confirm
		});
		if($("#setVisit").length) { // If setvisit ID exists
			var formVisit = ""; // Init formVisit
			var visitDatePicker = datepicker('#visitDate', { // Init the datepicker to select hours
				dateFormat: 'Y-m-d',
				position: 'c', // Calendar floating
				customDays: ['Dim', 'Lun','Mar','Mer','Jeu','Ven','Sam'], // Custom name days
				customMonths: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'], // Custom name of months
				onSelect: function(instance, date) { // Event select
					var value = date.toLocaleDateString(); // Convert the value of date to locale date string
					var day = ("0" + date.getDate()).slice(-2);
					var month = ("0" + (date.getMonth() + 1)).slice(-2);
					var year = date.getFullYear();
					var visit = day+"-"+month+"-"+year;
					$("[data-form='"+formVisit+"']").find("input[name='date_visit']").val(visit); // Set the value in the input to date
					$("[data-form='"+formVisit+"']").find("[data-date]").text(value); // Set the valie in the text
				}
			});
			$("#setVisit").on("show.bs.modal", function(e){ // Listerner when the popup to set the visit
				formVisit = $(e.relatedTarget).attr("data-form"); // Get the name form
				$("#visitStart").val(""); // Set the value empty on input start
				$("#visitEnd").val(""); // Set the value empty on input end
				visitDatePicker.setDate(); // Set the value empty on input date
			});
			$("#visitStart").timepicker({ // Init the timepicker to start
				timeFormat: "H:i:s", // Time format
				step: 15, // Step
				minTime: '06:00am',
				maxTime: '08:00pm',
			});
			$("#visitEnd").timepicker({ // Init the timepicker to end
				timeFormat: "H:i:s", // Time format
				step: 15, // Step
				minTime: '06:00am',
				maxTime: '08:00pm',
			});
			$("#visitStart").on("change", function(e){ // Listener when the value on input change
				var value = $(this).val(); // Get value
				if(value !== "") { // If value isn't empty
					$("[data-form='"+formVisit+"']").find("input[name='date_start']").val(value); // Set the value on input start
					$("[data-form='"+formVisit+"']").find("[data-start]").text(value); // Set the value text start
				}
			});
			$("#visitEnd").on("change", function(e){ // Listener when the value on input change
				var value = $(this).val(); // Get value
				if(value !== "") { // If value isn't empty
					$("[data-form='"+formVisit+"']").find("input[name='date_end']").val(value); // Set the value on input start
					$("[data-form='"+formVisit+"']").find("[data-end]").text(value); // Set the value text start
				}
			});
			$("[data-dates]").on('click', function(){
				var dateOne = '';
				var dateTwo = '';
				var dateThree = '';
				if ($("#0").length) {
					dateOne = $("#0").text();
				}
				if ($("#1").length) {
					dateTwo = $("#1").text();
				}
				if ($("#2").length) {
					dateThree = $("#2").text();
				}
				var content = '<a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-top:30px; text-decoration: none; cursor: pointer;" href="'+ $("#route_confirm").val() +'">Vérifier les heures disponibles</a>';
				// Put data of the template choised to RDV
				var id = $("#nom_template_rdv").val();
				$("#subject_rdv").val($("#subject_rdv_"+id).val());
				var val = $("#template_rdv_"+id).val();
				val = val.replace('[visiting-schedule]', content);
				$("#tinysendInvitation").val(val);
				tinymce.get("tinysendInvitation").setContent(val);
			});
			$("#nom_template_rdv").on('change', function(){
				var dateOne = '';
				var dateTwo = '';
				var dateThree = '';
				if ($("#0").length) {
					dateOne = $("#0").text();
				}
				if ($("#1").length) {
					dateTwo = $("#1").text();
				}
				if ($("#2").length) {
					dateThree = $("#2").text();
				}
				var content = '<a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-top:30px; text-decoration: none; cursor: pointer;" href="'+ $("#route_confirm").val() +'">Vérifier les heures disponibles</a>';
				// Put data of the template choised to RDV
				var id = $("#nom_template_rdv").val();
				$("#subject_rdv").val($("#subject_rdv_"+id).val());
				var val = $("#template_rdv_"+id).val();
				val = val.replace('[visiting-schedule]', content);
				$("#tinysendInvitation").val(val);
				tinymce.get("tinysendInvitation").setContent(val);
			});
			$("[data-register-visit]").on("click", function(e){ // Listener to add event of visit
				e.preventDefault(); // Prevent default
				var dataForm = $(this).attr("data-register-visit"); // Get name of form
				var form = document.querySelector("form[data-form='"+dataForm+"']"); // Get form
				var data = $(form).serialize(); // Get all data of form
				var date = $("[data-form='"+dataForm+"']").find("input[name='date_visit']").val(); // Get the date
				var start = $("[data-form='"+dataForm+"']").find("input[name='date_start']").val(); // Get the start
				var end = $("[data-form='"+dataForm+"']").find("input[name='date_end']").val(); // Get the end
				if(date !== "" && start !== "" && end !== "") { // If any data is empty
					$.ajax({
						url: $(form).attr("action"), // Se the url to request
						data: data, // Set the data to send
						type: $(form).attr("method"), // Set the method
						beforeSend: function() { // Function before send
							showWaitMe('Inscription à la visite, veuillez patienter ...', 'facebook'); // Show waitMe popup
						},
						success: function(response) { // Function success response
							$("body").waitMe("hide"); // Hide waitMe popup
							if(response.status) { // If status is true
								showSwal('Succès', response.message, 'success', false, false); // Show swal popup with success
							}
							if(!response.status) { // if status is false
								showSwal('Erreur', response.message, 'error', false, false); // Show swal popup with error
							}
						},
						error: function(error) { // Function error
							$("body").waitMe("hide"); // Hide waitMe popup
							console.log(error);
						}
					});
				}
				else {
					showSwal('Erreur', 'Vérifier les données de visite (date, heure de début et de fin)', 'error', false, false); // Show swal popup with the error of data is empty
				}
			});
		}
		$("#editUser").on("show.bs.modal", function(e){
			var modal = $(this);
			var relatedTarget = e.relatedTarget;
			var idUser = parseInt($(e.relatedTarget).attr("data-user-id"));
			var aUser = null;
			var aManager = [];
			aUsers.forEach(function(user, index) {
				if(idUser === user.id) {
					aUser = user;
					return;
				}
			});
			aAgentManager.forEach(function(manager, index) {
				if(idUser === manager.maneger_id) {
					aManager.push(manager.agent_id);
				}
			});
			modal.find("input[name='id']").val(aUser.id);
			modal.find("input[name='name']").val(aUser.name);
			modal.find("input[name='firstname']").val(aUser.firstname);
			modal.find("input[name='email']").val(aUser.email);
			modal.find("input[name='google_email']").val(aUser.google_email);
			modal.find("input[name='login']").val(aUser.username);
			modal.find("select[name='type'] option").removeAttr("selected");
			modal.find("select[name='type'] option[value='"+aUser.type+"']").prop("selected", true);
			modal.find("input[name='old_password']").val("");
			modal.find("input[name='new_password']").val("");
			if (aUser.type == 2) {
				$("#choose_agents").show();
				aManager.forEach(function(manager, index){
					aUsers.forEach(function(user, index) {
						if(user.id == manager) {
							$("#agents").find('option[value="'+user.id+'"]').remove();
							$("#agents").append('<option selected value="'+user.id+'">'+user.name+'</option>');
							return;
						}
					});
				});
			} else {
				$("#choose_agents").hide();
			}
			if ($("#typeUser").val() == 2 || $("#typeUser").val() == 1) { // If is a manager (secretary)
				$("[data-ed-us]").show();
			} else {
				$("[data-ed-us]").hide();
			}
			if ($("#typeUser").val() == 3) { // If is a agent
				if (aUser.id == $("#currentUser").val()) {
					$("[data-ed-us]").show();
				} else {
					$("[data-ed-us]").hide();
				}
			}

		});
		if ($("#choose_agents").length) {
			$("#choose_agents").hide();
		}
		function slug(str) { // Function to convert the category name in slug
			// remove accents, swap ñ for n, etc
			var from = "àáäâèéëêìíïîòóöôùúüûñç’'´";
			var to	 = "aaaaeeeeiiiioooouuuunc---";
			for (var i=0, l=from.length ; i<l ; i++) {
				str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
			}
			return str.toString().toLowerCase()
				.replace(/\s+/g, '-') // Replace spaces with -
				.replace(/[^\w\-]+/g, '') // Remove all non-word chars
				.replace(/\-\-+/g, '-') // Replace multiple - with single -
				.replace(/^-+/, '') // Trim - from start of text
				.replace(/-+$/, '');
		}
		$("[data-category]").on('keyup', function(){ // Listener when write the category name
			var dataCategory = $(this).attr('data-category');
			var name = $(this).val(); // Get value (category name)
			$("[data-slug='"+dataCategory+"']").val(slug(name)); //Set name created in slug
		});
		$("#editCategory").on("show.bs.modal", function(e){
			var modal = $(this);
			var relatedTarget = e.relatedTarget;
			var idCategory = parseInt($(e.relatedTarget).attr("data-category-id"));
			var aCategory = null;
			aCategories.forEach(function(category, index) {
				if(idCategory === category.id) {
					aCategory = category;
					return;
				}
			});
			modal.find("span[data-category-name]").text(aCategory.name);
			modal.find("input[name='idCategory']").val(aCategory.id);
			modal.find("input[name='name']").val(aCategory.name);
			modal.find("input[name='slug']").val(aCategory.slug);
			modal.find("select[name='parent'] option").removeAttr("selected");
			modal.find("select[name='parent'] option[value='"+aCategory.parent+"']").prop("selected", true);
		});
		// Get the names of the days of the a date
		function days(day) {
			var name = '';
			if (day == 0) {
				name = 'Le dimanche';
			}
			if (day == 1) {
				name = 'Lundi';
			}
			if (day == 2) {
				name = 'Le mardi';
			}
			if (day == 3) {
				name = 'Le mercredi';
			}
			if (day == 4) {
				name = 'Le jeudi';
			}
			if (day == 5) {
				name = 'Le vendredi';
			}
			if (day == 6) {
				name = 'Le samedi';
			}
			return name;
		}
		// If the checkbox is checked save information of localisation to show in PDF
		$("#estate_include_location").on('click', function(){
			if ($('#estate_include_location').prop('checked') ) {
				var localisation = '<table>\
									<tr>\
										<td>Rue: </td>\
										<td>' + $("[name='estate__street']").val() + '</td>\
									</tr>\
									<tr>\
										<td>N°:</td>\
										<td>' + $("[name='estate__number']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Bt:</td>\
										<td>' + $("[name='estate__box']").val() + '</td>\
									</tr>\
									<tr>\
										<td>CP:</td>\
										<td>' + $("[name='estate__code_postal']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Ville:</td>\
										<td>' + $("[name='estate__city']").val() + '</td>\
									</tr>\
								</table>';
				// Save data
				$("#inf_localisation").val(localisation);
			} else {
				// Without value
				$("#inf_localisation").val('');
			}
		});
		// If the checkbox is checked save information of seller to show in PDF
		$("#estate_include_petitioner").on('click', function(){
			if ($('#estate_include_petitioner').prop('checked')) {
				var requereur = '<table>\
									<tr>\
										<td>Contact: </td>\
										<td>' + $("[name='seller_name']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Tel:</td>\
										<td>' + $("[name='seller_phone']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Mail: </td>\
										<td>' + $("[name='seller_email']").val() + '</td>\
									</tr>\
								</table>';
				// Save data
				$("#inf_seller").val(requereur);
			} else {
				// Without value
				$("#inf_seller").val('');
			}
		});
		if ($("#estate__agent").length) {
			var assinngp = document.getElementById("estate__agent");
			assinngp = assinngp.options[assinngp.selectedIndex].text;
			$("#usename1").append(assinngp);
		}
		$("#estate__agent").on('change', function(){
			$("#usename1").empty();
			var assinngp = document.getElementById("estate__agent");
			assinngp = assinngp.options[assinngp.selectedIndex].text;
			$("#usename1").append(assinngp);
		});
		// If the checkbox is checked save information of assing to show in PDF
		$("#estate_include_assign").on('click', function(){
			if ($('#estate_include_assign').prop('checked')) {
				var assinngp = document.getElementById("estate__agent");
				assinngp = assinngp.options[assinngp.selectedIndex].text;
				var assing = '<table>\
									<tr>\
										<td>A assigner à: </td>\
										<td>' + assinngp + '</td>\
									</tr>\
								</table>';
				// Save data
				$("#inf_assign").val(assing);
			} else {
				// Without value
				$("#inf_assign").val('');
			}
		});
		// If the checkbox is checked save information of research to show in PDF
		$("#include_estate_info_additional").on('click', function(){
			if ($('#include_estate_info_additional').prop('checked')) {
				var cherche = '<table>\
									<tr>\
										<td>Cherche un autre bien ?: </td>\
										<td>' + $("[name='seller_looking_property']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Désire rester locataire ?: </td>\
										<td>' + $("[name='seller_want_stay_tenant']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Quand procéder à l’achat ?: </td>\
										<td>' + $("[name='seller_when_to_buy']").val() + '</td>\
									</tr>\
									<tr>\
										<td>Information complémentaire: </td>\
										<td>' + $("[name='estate__information_additional']").val() + '</td>\
									</tr>\
								</table>';
				// Save data
				$("#inf_cherche").val(cherche);
			} else {
				// Without value
				$("#inf_cherche").val('');
			}
		});
		// If the checkbox is checked save information of comment to show in PDF
		$("#estate_include_comment_free").on('click', function(){
			if ($('#estate_include_comment_free').prop('checked')) {
				var commentlibre = '<table>\
									<tr>\
										<td>Commentaire libre du requéreur: </td>\
										<td>' + $("[name='estate_comment_free']").val() + '</td>\
									</tr>\
								</table>';
				$("#inf_comment").val(commentlibre);
			} else {
				$("#inf_comment").val('');
			}
		});
		// If the checkbox is checked save information of description to show in PDF
		$("#estateDetails_include_description").on('click', function(){
			if ($("#estateDetails_include_description").prop('checked')) {
				var biendescription = '<table>\
											<tr>\
												<td>Description: </td>\
												<td>' + $("[name='estate_description']").val() + '</td>\
											</tr>\
										</table>';
				// Save data
				$("#inf_description").val(biendescription);
			} else {
				// Without value
				$("#inf_description").val('');
			}
		});
		// If the checkbox is checked save information of interior data to show in PDF
		$("#estate_include_details_internal").on('click', function(){
			if ($("#estate_include_details_internal").prop('checked')) {
				var bieninterior = '<table>\
										<tr>\
											<td>Chambres: </td>\
											<td>' + $("[name='rooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Chambres - étage: </td>\
											<td>' + $("[name='floorRooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Sdb: </td>\
											<td>' + $("[name='floorBathroom_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Sdb -	- étage: </td>\
											<td>' + $("[name='surfaceHabitable_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Surf.Habitable: </td>\
											<td>' + $("[name='floors_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etages: </td>\
											<td>' + $("[name='estimDecoration_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Estim. décoration: </td>\
											<td>' + $("[name='soilType_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Type de sol - Living: </td>\
											<td>' + $("[name='typeOfLivingFloor_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Type de sol - Chambres: </td>\
											<td>' + $("[name='typeOfLivingRooms_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine: </td>\
											<td>' + $("[name='kitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Type: </td>\
											<td>' + $("[name='typeOfKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Plan travail: </td>\
											<td>' + $("[name='worktopKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Age: </td>\
											<td>' + $("[name='ageKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Cuisine - Etat: </td>\
											<td>' + $("[name='stateKitchen_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat général: </td>\
											<td>' + $("[name='stateGeneral_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Typ.Chauff. centr.: </td>\
											<td>' + $("[name='chassisCentralType_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Typ.Châssis: </td>\
											<td>' + $("[name='chassisType_adapte']").val() + '</td>\
										</tr>\
									</table>';
				// Save data
				$("#inf_interior").val(bieninterior);
			} else {
				// Without value
				$("#inf_interior").val('');
			}
		});
		// If the checkbox is checked save information of exterior data to show in PDF
		$("#estate_include_details_external").on('click', function(){
			if ($("#estate_include_details_external").prop('checked')) {
				var bienexterior = '<table>\
										<tr>\
											<td>Jardin - orientation: </td>\
											<td>' + $("[name='jardinOrientation_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Jardin - surface: </td>\
											<td>' + $("[name='jardinArea_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Terrasse: </td>\
											<td>' + $("[name='terrace_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Balcon: </td>\
											<td>' + $("[name='balcony_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Véranda: </td>\
											<td>' + $("[name='veranda_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Panneaux solaires: </td>\
											<td>' + $("[name='solarPanels_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Panneaux solaires - Installés le: </td>\
											<td>' + $("[name='solarPanelInstaldDate_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat général: </td>\
											<td>' + $("[name='stateGeneralExterior_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking: </td>\
											<td>' + $("[name='parking_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking	- Combien de place(s): </td>\
											<td>' + $("[name='parkingHowManyPlaces_adapte']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Parking - Type(s): </td>\
											<td>' + $("[name='typeParking_adapte']").val() + '</td>\
										</tr>\
									</table>';
				// Save data
				$("#inf_exterior").val(bienexterior);
			} else {
				// Without value
				$("#inf_exterior").val('');
			}
		});
		// If the checkbox is checked save information of problems to show in PDF
		$("#estate_include_details_external").on('click', function(){
			if ($("#estate_include_problems").prop('checked')) {
				var problemes = '<table>\
										<tr>\
											<td>Problème signalé dans le formulaire: </td>\
											<td>' + $("[name='estate_problem_signal']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Suivi résolution: </td>\
											<td>' + $("[name='estate_new_problem']").val() + '</td>\
										</tr>\
									</table>';
				// Save data
				$("#inf_problems").val(problemes);
			} else {
				// Without value
				$("#inf_problems").val('');
			}
		});
		// If the checkbox is checked save information of remarks to show in PDF
		$("#estate_include_visit_remarks").on('click', function(){
			if ($("#estate_include_visit_remarks").prop('checked')) {
				var remarks = '<table>\
										<tr>\
											<td>Etat maison - Intérieur: </td>\
											<td>' + $("[name='interior_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat maison - Extérieur: </td>\
											<td>' + $("[name='exterior_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Etat maison - Le quartier: </td>\
											<td>' + $("[name='district_state']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points forts - Intérieur: </td>\
											<td>' + $("[name='interior_highlights']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points forts - Extérieur: </td>\
											<td>' + $("[name='exterior_highlights']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points faibles - Intérieur: </td>\
											<td>' + $("[name='interior_weak_point']").val() + '</td>\
										</tr>\
										<tr>\
											<td>Points faibles - Extérieur: </td>\
											<td>' + $("[name='exterior_weak_point']").val() + '</td>\
										</tr>\
									</table>';
				// Save data
				$("#inf_remarks").val(remarks);
			} else {
				// Without value
				$("#inf_remarks").val('');
			}
		});
		// Always send data of offer to the PDF
		var offer = '<table>\
						<tr>\
							<td>Prix offert par We Sold: </td>\
							<td>' + $("[name='price_wesold']").val() + '</td>\
						</tr>\
						<tr>\
							<td>Texte à ajouter à l’offre: </td>\
							<td>' + $("[name='other_offer']").val() + '</td>\
						</tr>\
					</table>';
		$("#inf_offer").val(offer);
		// If the checkbox is checked save information of photos and documents to show in PDF
		$("#estate_include_documents_photos").on('click', function(){
			if ($("#estate_include_documents_photos").prop('checked')) {
				$("#show_photos_doc").val(1);
			} else {
				$("#show_photos_doc").val(0);
			}
		});
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		});
		$("#text_add_offer").on('change', function() {
			$("#other_offer").empty();
			tinymce.get("other_offer").setContent('');
			var textOffer = document.getElementById("text_add_offer").value;
			var valu = $("#offre-"+textOffer).val();
			$("#other_offer").html(valu);
			tinymce.get("other_offer").setContent(valu);
		});
		// Change data of notary in tinyMCE
		$("#data_notary").on('change', function(){
			$("#notary_data").empty(); // Clean notary data
			var dataNotary = document.getElementById("data_notary").value;
			$("#notary_data").append(dataNotary);
			var myContent = tinymce.get("tinyEditOfferPDF").getContent();
		});
		$("#condition").on('change', function(){
			$("#tinyconditionOffer").empty();
			tinymce.get("tinyconditionOffer").setContent('');
			$("#tinyconditionOffer").val($(this).val());
			tinymce.get("tinyconditionOffer").setContent($(this).val());
		})
		$("[data-save-data]").on('click', function(){
			// Get data of the notary
			var dataNotary = document.getElementById("data_notary").value;
			// Get data of condition
			var dataCondition = tinymce.get("tinyconditionOffer").getContent();
			// Get data validate offer
			var validateoffer = $("[name='number_offer']").val();
			// Get text à ajouter à l'offre
			var text = tinymce.get("other_offer").getContent();
			// Clean val if inputs
			$("#notaire_o").val();
			$("#condition_offer_o").val();
			$("#validity_o").val();
			$("#texteadded_o").val();
			// Put val of notary in input to save data in the DB
			$("#notaire_o").val(dataNotary);
			$("#condition_offer_o").val(dataCondition);
			$("#validity_o").val(validateoffer);
			$("#texteadded_o").val(text);

			validateoffer = new Date(validateoffer);
			// Get price we sold
			var pricewesold = $("[name='price_wesold']").val();
			console.log(pricewesold);
			var months =	['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']; // Custom name of months
			validateoffer = ("0" + validateoffer.getDate()).slice(-2) + ' ' + months[(validateoffer.getMonth() + 1)] +' '+ validateoffer.getFullYear();

			var body = $("#text-original-offer").val();
			newBody = body.replace('[texte]', text);
			if (dataCondition === '') {
				newBody = newBody.replace('<li><strong><u>[condition]</u></strong></li>', '');
			} else {
				newBody = newBody.replace('[condition]', dataCondition);
			}
			if (dataNotary === '') {
				newBody = newBody.replace('<li>Notaire de l&rsquo;acqu&eacute;reur : [notary]</li>', '');
			} else {
				newBody = newBody.replace('[notary]', dataNotary);
			}
			newBody = newBody.replace('[validate]', validateoffer);
			newBody = newBody.replace('[pricewesold]', pricewesold);
			newBody = newBody.replace('[price-letter]', writtenNumber(pricewesold, {lang: 'fr'}).toUpperCase());

			$("#tinyEditOfferPDF").empty();
			tinymce.get("tinyEditOfferPDF").setContent('');
			$("#tinyEditOfferPDF").val(newBody);
			tinymce.get("tinyEditOfferPDF").setContent(newBody);

			// put values in inputs
			$("#ps").val($("[name='price_seller']").val());
			$("#pw").val(pricewesold);
			$("#pm").val($("[name='price_market']").val());
		});

		$("#add-email").on('click', function(){
			var newemail = $("#new_email").val();
			$("#see_emails_added").append('<input type="hidden" name="emails[]" value="'+newemail+'">');
			$("#see_email").append(newemail+'<br>');
		});

		$("#text_add_email_offer").on('change', function() {
			$("#tinyCorps").empty();
			tinymce.get("tinyEditOfferPDF").setContent('');
			// Get data of the template to email
			var dataCorps = document.getElementById("text_add_email_offer").value;
			var val = $("[data-email-template-"+dataCorps+"]").val();
			$("#tinyCorps").val(val);
			tinymce.get("tinyCorps").setContent(val);
		});
		// Put point in price and €
		$("[data-input-price]").on('input', function(){
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
			var value = $(this).val();
			if(value.length >= 4) {
				var auxNum = "";
				var maxLength = value.length;
				var splitLength = maxLength - 3;
				for(var i = (value.length - 1); i >= 0; i--) {
					if(i === splitLength) {
						auxNum = "."+value[i] + auxNum;
						splitLength = splitLength - 3;
					}
					else {
						auxNum = value[i] + auxNum;
					}
				}
				if((value.length % 3) === 0) {
					auxNum = auxNum.replace(".","");
				}
				$(this).val(auxNum);
			}
			$("#price_sellerr").val(value);
		});
		// Put point in price and €
		$("[data-input-price-ad]").on('input', function(){
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
			var value = $(this).val();
			if(value.length >= 4) {
				var auxNum = "";
				var maxLength = value.length;
				var splitLength = maxLength - 3;
				for(var i = (value.length - 1); i >= 0; i--) {
					if(i === splitLength) {
						auxNum = "."+value[i] + auxNum;
						splitLength = splitLength - 3;
					}
					else {
						auxNum = value[i] + auxNum;
					}
				}
				if((value.length % 3) === 0) {
					auxNum = auxNum.replace(".","");
				}
				$(this).val(auxNum);
			}
			$("#estate_ads_price").val(value);
		});
		// Put point in price and €
		$("[data-input-price-market]").on('input', function(){
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
			var value = $(this).val();
			if(value.length >= 4) {
				var auxNum = "";
				var maxLength = value.length;
				var splitLength = maxLength - 3;
				for(var i = (value.length - 1); i >= 0; i--) {
					if(i === splitLength) {
						auxNum = "."+value[i] + auxNum;
						splitLength = splitLength - 3;
					}
					else {
						auxNum = value[i] + auxNum;
					}
				}
				if((value.length % 3) === 0) {
					auxNum = auxNum.replace(".","");
				}
				$(this).val(auxNum);
			}
			$("#price_market").val(value);
		});
		// Put point in price and €
		$("[data-input-price-we-sold]").on('input', function(){
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
			var value = $(this).val();
			if(value.length >= 4) {
				var auxNum = "";
				var maxLength = value.length;
				var splitLength = maxLength - 3;
				for(var i = (value.length - 1); i >= 0; i--) {
					if(i === splitLength) {
						auxNum = "."+value[i] + auxNum;
						splitLength = splitLength - 3;
					}
					else {
						auxNum = value[i] + auxNum;
					}
				}
				if((value.length % 3) === 0) {
					auxNum = auxNum.replace(".","");
				}
				$(this).val(auxNum);
			}
			$("#price_wesold").val(value);
		});
		// Put point in price and €
		$("[data-input-estimation]").on('input', function(){
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
			var value = $(this).val();
			if(value.length >= 4) {
				var auxNum = "";
				var maxLength = value.length;
				var splitLength = maxLength - 3;
				for(var i = (value.length - 1); i >= 0; i--) {
					if(i === splitLength) {
						auxNum = "."+value[i] + auxNum;
						splitLength = splitLength - 3;
					}
					else {
						auxNum = value[i] + auxNum;
					}
				}
				if((value.length % 3) === 0) {
					auxNum = auxNum.replace(".","");
				}
				$(this).val(auxNum);
			}
			$("#his_estimate").val(value);
		});
		// Hide tables of temnplates
		if ($("#temem").length) {
			$("#temem").hide();
		}
		if ($("#temsms").length) {
			$("#temsms").hide();
		}
		if ($("#temtask").length) {
			$("#temtask").hide();
		}
		if ($("#temcon").length) {
			$("#temcon").hide();
		}
		if ($("#tempro").length) {
			$("#tempro").hide();
		}
		if ($("#temtext").length) {
			$("#temtext").hide();
		}
		// When the user select a value of type of template
		$("#newtemplate").on('change', function(){
			$("#templates_create").attr("action", "");
			var link = $(this).find("option[value='" + $(this).val() + "']").attr("data-link");
			$("#templates_create").attr("action", link);
			// Get type of template to email
			var type = document.getElementById("newtemplate").value;
			if (type == 1) {
				$("#temem").show();
				$("#temsms").hide();
				$("#temtask").hide();
				$("#tempro").hide();
				$("#temcon").hide();
				$("#temtext").hide();
				$("#type_template").val('email');
			}
			if (type == 2) {
				$("#temem").hide();
				$("#temsms").show();
				$("#temtask").hide();
				$("#tempro").hide();
				$("#temcon").hide();
				$("#temtext").hide();
				$("#type_template").val('sms');
			}
			if (type == 3) {
				$("#temem").hide();
				$("#temsms").hide();
				$("#temtask").show();
				$("#tempro").hide();
				$("#temcon").hide();
				$("#temtext").hide();
				$("#type_template").val('task');
			}
			if (type == 4) {
				$("#temem").hide();
				$("#temsms").hide();
				$("#temtask").hide();
				$("#tempro").show();
				$("#temcon").hide();
				$("#temtext").hide();
				$("#type_template").val('process');
			}
			if (type == 5) {
				$("#temem").hide();
				$("#temsms").hide();
				$("#temtask").hide();
				$("#tempro").hide();
				$("#temcon").show();
				$("#temtext").hide();
				$("#type_template").val('condition');
			}
			if (type == 6) {
				$("#temem").hide();
				$("#temsms").hide();
				$("#temtask").hide();
				$("#tempro").hide();
				$("#temcon").hide();
				$("#temtext").show();
				$("#type_template").val('text-offer');
			}
		});
		$("[name='templateName']").on('keyup', function(){
			$("[name='file']").val(slug($(this).val())); // Put the template name as slug to save the file
		});
		$("[data-send-form]").on('click', function(){ // When click send link in the main form
			var link = $(this).attr("data-send-form"); // Get link of attribute data
			$("#templates_create").attr("action", link); // Put the link in the form
		});
		// Edit
		$("[data-reminder]").on('click', function(){
			var id = $(this).attr("data-reminder");
			var reminders, content;

		})

		if ($("[data-edit-process]").length) {
			var val = $("[data-edit-process]").val();
		}
		$("[data-edit-process").on('change', function(){
			var id = $(this).attr('id');
			console.log(id);
			var l = document.getElementById(id).value; // Get value of select
			console.log();
			if (l == 'sms') {
				$("#rappel_sms").show();
				$("#rappel_email").hide();
				$("#rappel_task").hide();
				document.getElementById("add_rappel").disabled = false;
			}
			if (l == 'email') {
				$("#rappel_sms").hide();
				$("#rappel_email").show();
				$("#rappel_task").hide();
				document.getElementById("add_rappel").disabled = false;
			}
			if (l == 'task') {
				$("#rappel_sms").hide();
				$("#rappel_email").hide();
				$("#rappel_task").show();
				document.getElementById("add_rappel").disabled = true;
			}
		});
		$("#type_rappel").on('change', function(){
			var l = document.getElementById("type_rappel").value; // Get value of select
			if (l == 'sms') {
				$("#rappel_sms").show();
				$("#rappel_email").hide();
				$("#rappel_task").hide();
				$("#rappel_email").attr('disabled', true);
				$("#rappel_task").attr('disabled', true);
				document.getElementById("add_rappel").disabled = false;
			}
			if (l == 'email') {
				$("#rappel_sms").hide();
				$("#rappel_email").show();
				$("#rappel_task").hide();
				$("#rappel_sms").attr('disabled', true);
				$("#rappel_task").attr('disabled', true);
				document.getElementById("add_rappel").disabled = false;
			}
			if (l == 'task') {
				$("#rappel_sms").hide();
				$("#rappel_email").hide();
				$("#rappel_task").show();
				$("#rappel_sms").attr('disabled', true);
				$("#rappel_email").attr('disabled', true);
				document.getElementById("add_rappel").disabled = true;
			}
		});
		if ($("#rappel_sms").length) {
			$("#rappel_sms").hide();
			$("#rappel_task").hide();

		}
		$("#add_rappel").on('click', function(){
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			var d = new Date();
			var n = d.getTime();
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			$("#new_rappels").append('\
				<div id="'+ n +'">\
					<br><div class="row">\
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
							<strong>Rappel</strong>\
						</div>\
					</div><hr>\
					<div class="row">\
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">\
							Type\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">\
							<select name="type_rappel[]" data-template="' + n + '" id="type_rappel_' + n + '" class="form-control">\
								<option value="email">Mail</option>\
								<option value="sms">SMS</option>\
								<option value="task">Task</option>\
							</select>\
						</div>\
					</div><br>\
					<div class="row">\
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">\
							Template\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">\
							<select name="type_rappel_choised[]" class="form-control" id="rappel_email_'+ n +'">'
								+ optionsTemplateEmail +
							'</select>\
							<select name="type_rappel_choised[]" class="form-control" id="rappel_sms_'+ n +'">'
								+ optionsTemplateSMS +
							'</select>\
							<select name="type_rappel_choised[]" class="form-control" id="rappel_task_'+ n +'">'
								+ optionsTemplateTask +
							'</select>\
						</div>\
					</div><br>\
					<div class="row">\
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-12 col-xl-7">\
							Nombre de jours ouvrables après l’envoie précédent\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-12 col-xl-5">\
							<input type="number" name="days[]" class="form-control">\
						</div>\
					</div><br>\
					<div class="text-left">\
						<button type="button" data-id-delete="'+ n +'" class="btn btn-danger">Supprimer ce rappel</button>\
					</div>\
				</div>\
			');
			$("#rappel_email_" + n).show(); // Show templates of email
			$("#rappel_sms_" + n).hide(); // Hide templates of sms
			$("#rappel_task_" + n).hide(); // Hide templates of task
		});
		// When change option of select
		$(document).on("change", "[data-template]", function(){
			var id = $(this).attr("data-template"); // Take id to show or hide
			if ($(this).val() == 'email') { // If value of the option is email
				console.log('is email');
				$("#rappel_email_" + id).show(); // Show templates of email
				$("#rappel_sms_" + id).hide(); // Hide templates of sms
				$("#rappel_task_" + id).hide(); // Hide templates of task
				$("#rappel_sms_" + id).atrr('disabled'); // Disabled fields templates of sms
				$("#rappel_task_" + id).atrr('disabled'); // Disabled fields templates of task
			}
			if ($(this).val() == 'sms') { // If value of the option is sms
				console.log('is sms');
				$("#rappel_email_" + id).hide(); // Hide templates of email
				$("#rappel_sms_" + id).show(); // Show templates of sms
				$("#rappel_task_" + id).hide(); // Hide templates of task
				$("#rappel_email_" + id).atrr('disabled'); // Disabled fields templates of email
				$("#rappel_task_" + id).atrr('disabled'); // Disabled fields templates of task
			}
			if ($(this).val() == 'task') { // If value of the option is task
				console.log('is task');
				$("#rappel_email_" + id).hide(); // Hide templates of email
				$("#rappel_sms_" + id).hide(); // Hide templates of sms
				$("#rappel_task_" + id).show(); // Show templates of task
				$("#rappel_email_" + id).atrr('disabled'); // Disabled fields templates of email
				$("#rappel_sms_" + id).atrr('disabled'); // Disabled fields templates of sms
			}
		});
		$(document).on('click', "[data-id-delete]", function(){ // If click in delete...
			console.log()
			$("#"+$(this).attr("data-id-delete")).remove(); // Remove the reminder
		});
		if ($("#manager_id").length) { // If exist the id manager_id
			$("#manager_id").hide(); // Hide the id manager_id
			$("#agents_id").hide(); // Hide agents_id
		}
		$("#type_user").on('change', function(){ // When select change
			var type = $(this).val(); // Get type of user
			if (type == 3) { // If the type is 3
				$("#manager_id").show();
			} else {
				$("#manager_id").hide();
			}
			if (type == 2) { // If the type is 2
				$("#agents_id").show();
			} else {
				$("#agents_id").hide();
			}
		})
		if ($("#date_manual").length) { // If exist id 'date_manual'
			$("#date_manual").hide(); // Hide this div
		}
		$("#change_date").on('click', function(){ // When click on checkbox
			var checked = document.getElementById('change_date').checked; //True or false
			if (checked) { // If is true
				$("#date_manual").show();
			}
		});
		$("#change_date_two").on('click', function(){ // When click on checkbox
			var checked = document.getElementById('change_date_two').checked; //True or false
			if (checked) {
				$("#date_manual").hide();
			}
		});
		if ($(".charge_template").length) { // If exist id 'date_manual'
			$(".charge_template").hide(); // Hide this div
		}
		// Add reminder with a templat
		$("#addReminderWPC").on('click', function(){
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			var d = new Date();
			var n = d.getTime();
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			$("#remindersadded").append(
					'<div class="withoutProcessCharged" id="'+ n +'">\
						<div class="row mb-2">\
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
								<span class="ffhnm">\
									Rappel\
								</span><hr>\
							</div>\
						</div>\
						<div class="row mb-2">\
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
								<span class="ffhnl">Type</span>\
							</div>\
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
								<select name="type_rappel[]" data-disabled-add-reminder="withoutProcessCharged" data-type-template-d="'+ n +'" data-template="' + n + '" id="type_rappel_' + n + '" class="form-control">\
									<option value="email">Mail</option>\
									<option value="sms">SMS</option>\
									<option value="task">Task</option>\
								</select>\
							</div>\
						</div>\
						<div class="row mb-2">\
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
								<span class="ffhnl">Template</span>\
							</div>\
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
								<select name="type_rappel_choised[]" data-name-reminder="'+n+'" class="form-control" id="rappel_email_'+ n +'">'
									+ optionsTemplateEmail +
								'</select>\
								<select name="type_rappel_choised[]" data-name-reminder="'+n+'" class="form-control" id="rappel_sms_'+ n +'">'
									+ optionsTemplateSMS +
								'</select>\
								<select name="type_rappel_choised[]" data-name-reminder="'+n+'" class="form-control" id="rappel_task_'+ n +'">'
									+ optionsTemplateTask +
								'</select>\
							</div>\
						</div>\
						<div class="row mb-2" id="subject_template_d_'+n+'">\
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
								<span class="ffhnl">Sujet</span>\
							</div>\
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
								<input type="text" name="subject_template_d[]" class="form-control">\
							</div>\
						</div>\
						<div class="row mb-2">\
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
								<span class="ffhnl">Contenu</span>\
							</div>\
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
								<textarea id="contenu-d-' + n + '" data-contenu-reminder="'+ n +'" class="form-control" name="content_reminder[]" rows="4"></textarea>\
							</div>\
						</div>\
						<div class="row mb-2">\
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
								<span class="ffhnl">Nombre de jours ouvrables après l’envoie de base</span>\
							</div>\
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">\
								<input type="number" name="days_reminder[]" class="form-control">\
							</div>\
							<div class="text-left col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-7">\
								<button type="button" class="btn btn-danger" data-id-delete="'+ n +'">Supprimer ce rappel</button>\
							</div>\
						</div>\
					</div>'
				);
			$("#rappel_email_" + n).show(); // Show templates of email
			$("#rappel_sms_" + n).hide(); // Hide templates of sms
			$("#rappel_task_" + n).hide(); // Hide templates of task
			$("#rappel_email_" + n).click(); // Show templates of email
		});
		$(document).on('change', "[data-disabled-add-reminder]", function(){
			$("#addReminderWPC").removeAttr('disabled');
			var container = $(this).attr('data-disabled-add-reminder');
			var isfirst = ($(this).parents('.withoutProcessCharged').attr("id") && $(this).parents('.withoutProcessCharged').attr("id") === 'firsth') ? true : false;
			if ($(this).val() === 'task') {
				$("#addReminderWPC").prop('disabled', true);
				$(this).parents('.withoutProcessCharged').nextAll('.withoutProcessCharged').remove();
				$("#remindersadded").find('.withoutProcessCharged').remove();
				if (container === 'withProcessCharged') {
					$("#remindersadded").find('.withoutProcessCharged').remove();
					$(this).parents('.withProcessCharged').nextAll('.withProcessCharged').remove();
				}
				if (isfirst) {
					$("#remindersadded").find('.withoutProcessCharged').remove();
				}
			}
			if ($(this).val() === 'sms') {
				var val = $(this).attr("data-type-template-d");
				$("#subject_template_d_"+val).hide();
			} else {
				var val = $(this).attr("data-type-template-d");
				$("#subject_template_d_"+val).show();
			}
		});
		if ($("#type_template_r").length) {
			var type = document.getElementById("type_template_r").value;
			var optionsTemplateEmail;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			$("#file_template_name").html(optionsTemplateEmail);
		}
		$("#type_template_r").on('change', function(){
			$("#file_template_name").html('')
			var val = document.getElementById("type_template_r").value;
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			var subject;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			if (val == "email") {
				$("#file_template_name").html(optionsTemplateEmail);
				$("#content-subject").show();
			}
			if (val == "sms") {
				$("#file_template_name").html(optionsTemplateSMS);
				$("#content-subject").hide();
			}
			if (val == "task") {
				$("#file_template_name").html(optionsTemplateTask);
				$("#content-subject").show();
			}
		});
		$("#type_tem_create").on('change', function(){
			$("#file_template_name_create").html('')
			var val = document.getElementById("type_tem_create").value;
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			var subject;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			if (val == "email") {
				$("#file_template_name_create").html(optionsTemplateEmail);
				$(".content-s-subject").show();
			}
			if (val == "sms") {
				console.log('is sms');
				$("#file_template_name_create").html(optionsTemplateSMS);
				$(".content-s-subject").hide();
			}
			if (val == "task") {
				$("#file_template_name_create").html(optionsTemplateTask);
				$(".content-s-subject").show();
			}
		});
		if ($(".content-templates").length) {
			$(".content-templates").hide();
		}
		if ($("#file_template_name").length) {
			// Get subject of the template
			var val = document.getElementById("file_template_name").value;
			aTemplates.forEach(function(template){
				if (val == template.file) {
					$("#subject_template").val(template.subject);
					$("[data-id-template='"+val+"']").show();
				}
			});
		}
		$("#file_template_name").on('change', function(){
			$("#subject_template").empty();
			// Get subject of the template
			var val = document.getElementById("file_template_name").value;
			$("[data-id-template]").hide();
			aTemplates.forEach(function(template){
				if (val == template.file) {
					$("#subject_template").val(template.subject);
					$("[data-id-template='"+val+"']").show();
				}
			});
		});
		if ($("#type_tem_create").length) {
			var type = document.getElementById("type_tem_create").value;
			var optionsTemplateEmail;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			$("#file_template_name_create").html(optionsTemplateEmail);
		}
		$("#file_template_name_create").on('change', function(){
			$("#content_reminder").html('')
			var val = document.getElementById("file_template_name_create").value;
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			var subject;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			if (val == "email") {
				$("#content_reminder[]").html(optionsTemplateEmail);
			}
			if (val == "sms") {
				$("#content_reminder[]").html(optionsTemplateSMS);
			}
			if (val == "task") {
				$("#content_reminder[]").html(optionsTemplateTask);
			}
		});
		if ($(".content-templates_rap").length) {
			$(".content-templates_rap").hide();
		}
		if ($("#file_template_name_create").length) {
			// Get subject of the template
			var val = document.getElementById("file_template_name_create").value;
			aTemplates.forEach(function(template){
				if (val == template.file) {
					$("[data-id-template-reminder='"+val+"']").show();
					$("#subject_template_s").val(template.subject);
					$("#auxSubject").append('<input type="hidden" name="subject_template_reminder" value="'+template.subject+'">');
				}
			});
		}
		$("#file_template_name_create").on('change', function(){
			$("#subject_template_s").empty();
			$("#auxSubject").empty();
			// Get subject of the template
			var val = document.getElementById("file_template_name_create").value;
			$("[data-id-template-reminder]").hide();
			aTemplates.forEach(function(template){
				if (val == template.file) {
					$("[data-id-template-reminder='"+val+"']").show();
					$("#subject_template_s").val(template.subject);
					$("#auxSubject").append('<input type="hidden" name="subject_template_reminder" value="'+template.subject+'">');
				}
			});
		});
		$(document).on('change',	"[data-name-reminder]", function(){
			// Get subject of the template
			var val = $(this).val();
			var textareacontent = $(this).attr('data-name-reminder');
			var content = $("[data-id-template='"+val+"']").val();
			$("[data-contenu-reminder='"+textareacontent+"']").val(content);
		});
		$(document).on('click',	"[data-name-reminder]", function(){
			// Get subject of the template
			var val = $(this).val();
			var textareacontent = $(this).attr('data-name-reminder');
			var content = $("[data-id-template='"+val+"']").val();
			$("[data-contenu-reminder='"+textareacontent+"']").val(content);
		});
		$("[data-id-template-reminder]").on('change', function(){
			var id = $(this).attr("data-id-template-reminder"); // Get id
		});
		if ($(".withProcessCharged").length) {
			$(".withProcessCharged").hide();
		}
		$("#template_process").on('change', function(){
			$(".withProcessCharged").empty();
			var val = document.getElementById("template_process").value;
			var optionsTemplateEmail;
			var optionsTemplateSMS;
			var optionsTemplateTask;
			aTemplates.forEach(function(template){
				if(template.type == "email") {
					optionsTemplateEmail += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "sms") {
					optionsTemplateSMS += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
				if(template.type == "task") {
					optionsTemplateTask += '<option value="'+ template.file +'">'+template.name+'</option><br>';
				}
			});
			$(".withProcessCharged").show();
			var allReminders;
			aTemplatesReminders.forEach(function(reminders){
				if (reminders.id == val) {
					var rem = reminders.reminder;
					var type;
					rem.forEach(function(remin){
						var selectedemail = (remin.type_template === 'email') ? 'selected' : '';
						var selectedsms = (remin.type_template === 'sms') ? 'selected' : '';
						var selectedtask = (remin.type_template === 'task') ? 'selected' : '';
						var displayemail = (remin.type_template === 'email') ? 'data-call-click' : 'style="display: none;"';
						var displaysms = (remin.type_template === 'sms') ? 'data-call-click' : 'style="display: none;"';
						var displaytask = (remin.type_template === 'task') ? 'data-call-click' : 'style="display: none;"';
						var subje = '<div class="row mb-2">\
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
												<span class="ffhnl">Sujet</span>\
											</div>\
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
												<input type="text" name="subject_template_d[]" class="form-control">\
											</div>\
										</div>';
						if (remin.type_template === 'sms') {
							subje = '';
						}
						var inputdays = '';
						if (remin.position + 1 != 1) {
							inputdays = '<div class="row mb-2">\
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">\
											<span class="ffhnl">Nombre de jours ouvrables après l’envoie de base</span>\
										</div>\
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">\
											<input type="number" name="days_reminder[]" value="'+remin.days+'" class="form-control">\
										</div>\
										<div class="text-left col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-7">\
											<button type="button" class="btn btn-danger" data-id-delete="'+ remin.position +'">Supprimer ce rappel</button>\
										</div>\
									</div>';
						}
						allReminders += '<div class="withProcessCharged" id="'+ remin.position +'">\
									<div class="row mb-2">\
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
											<span class="ffhnm">\
												Rappel '+ (remin.position + 1)+'\
											</span><hr>\
										</div>\
									</div>\
									<div class="row mb-2">\
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
											<span class="ffhnl">Type</span>\
										</div>\
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
											<select name="type_rappel[]" data-disabled-add-reminder="withProcessCharged" data-template="' + remin.position + '" id="type_rappel_' + remin.position + '" class="form-control">\
												<option value="email" '+ selectedemail +'>Mail</option>\
												<option value="sms" '+ selectedsms +'>SMS</option>\
												<option value="task" '+ selectedtask +'>Task</option>\
											</select>\
										</div>\
									</div>\
									<div class="row mb-2">\
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
											<span class="ffhnl">Template</span>\
										</div>\
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
											<select name="type_rappel_choised[]" ' + displayemail +' data-name-reminder="'+remin.position+'" class="form-control" id="rappel_email_'+ remin.position +'">'
												+ optionsTemplateEmail +
											'</select>\
											<select name="type_rappel_choised[]" ' + displaysms +' data-name-reminder="'+remin.position+'" class="form-control" id="rappel_sms_'+ remin.position +'">'
												+ optionsTemplateSMS +
											'</select>\
											<select name="type_rappel_choised[]" ' + displaytask +' data-name-reminder="'+remin.position+'" class="form-control" id="rappel_task_'+ remin.position +'">'
												+ optionsTemplateTask +
											'</select>\
										</div>\
									</div>\
									<div class="row mb-2">\
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
											<span class="ffhnl">Contenu</span>\
										</div>\
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
											<textarea class="form-control" data-contenu-reminder="'+remin.position+'" name="content_reminder[]" rows="4"></textarea>\
										</div>\
									</div>'
									+ subje + inputdays +
									'</div>';
					});
					allReminders = allReminders.replace('undefined', '');
					$(".withProcessCharged").append(allReminders);
					$("[data-call-click]").each(function(e){
						$(this).click();
					})
				}
			});
		});
		$("#charge_process").on('click', function(){ // When click on checkbox
			var checked = document.getElementById('charge_process').checked; //True or false
			if (checked) { // If is true
				$(".charge_template").show();
				$(".withoutProcessCharged").hide();
				$(".withProcessCharged").show();
			} else {
				$(".charge_template").hide();
				$(".withoutProcessCharged").show();
				$(".withProcessCharged").hide();
			}
		});
		$("#charge_process_task").on('click', function(){ // When click on checkbox
			var checked = document.getElementById('charge_process_task').checked; //True or false
			if (checked) { // If is true
				$(".charge_template").show();
				$(".withoutProcessCharged").hide();
				$(".withProcessCharged").show();
			} else {
				$(".charge_template").hide();
				$(".withoutProcessCharged").show();
				$(".withProcessCharged").hide();
			}
		});
		$("#template_process_task").on('change', function(){
			var id = $(this).val();
			var subject = $("[data-subject-task-"+id+"]").val();
			var text = $("[data-file-template-"+id+"]").val();
			$("[data-put-subject]").empty();
			$("[data-put-text]").empty();
			$("[data-put-subject]").val(subject);
			$("[data-put-text]").val(text);
		});
		$("#change_status").on('change', function(){
			var a	= $('option:selected', $(this)).attr('data-link-task');
			$("#action-task").attr("href", a);
		});
		// Add new strong point interior
		$("#add-point-strong-interior").on('click', function() {
			$("#strong-points-i").append('<input type="text" name="interior_highlights[]" class="form-control mb-2" disabled value="">');
		});
		// Add new strong point exterior
		$("#add-point-strong-exterior").on('click', function() {
			$("#strong-points-e").append('<input type="text" name="exterior_highlights[]" class="form-control mb-2" disabled value="">');
		});
		// Add new weak point exterior
		$("#add-point-weak-interior").on('click', function() {
			$("#weak-points-i").append('<input type="text" name="interior_weak_point[]" class="form-control mb-2" disabled value="">');
		});
		// Add new weak point exterior
		$("#add-point-weak-exterior").on('click', function() {
			$("#weak-points-e").append('<input type="text" name="exterior_weak_point[]" class="form-control mb-2" disabled value="">');
		});
		function getQueryVariable(variable) { //function to get variable of url
			var query = window.location.search.substring(1);
			var vars = query.split("&");
			for (let i = 0; i < vars.length; i++) {
				let pair = vars[i].split("=");
				if (pair[0].toUpperCase() == variable.toUpperCase()) {
					return pair[1];
				}
			}
			return null;
		}
		if (getQueryVariable("modal") == "true") { // If variable modal exists and its true
			$("#editReminder-"+getQueryVariable("id")).modal("show"); // Show modal of the task
		}
		// Put data of the template choised
		if ($("#nom_template").length) {
			var id = $("#nom_template").val();
			$("#subjectrdv").val($("#subject"+id).val());
			var val = $("#template"+id).val();
			$("#tinyConfirm").val(val);
			tinymce.get("tinyConfirm").setContent(val);
		}
		$("#nom_template").on('change', function(){
			$("#tinyConfirm").empty();
			tinymce.get("tinyConfirm").setContent('');
			var id = $(this).val();
			$("#subjectrdv").val($("#subject"+id).val());
			var val = $("#template"+id).val();
			var date = '<span>- Visit le ' + $("#date_confirm").val() + ' De ' + $("#date_confirm_start").val() + ' à ' + $("#date_confirm_end").val() + '</span>';
			$("#tinyConfirm").val(date + val);
			tinymce.get("tinyConfirm").setContent(date + val);
		});
		// Put data of the template choised to demande tel
		if ($("#nom_template_tel").length) {
			var id = $("#nom_template_tel").val();
			$("#form_phone_subject").val($("#subject_tel_"+id).val());
			var val = $("#template_tel_"+id).val();
			$("#tinyAskPhone").val(val);
			tinymce.get("tinyAskPhone").setContent(val);
		}
		$("#nom_template_tel").on('change', function(){
			$("#tinyAskPhone").empty();
			tinymce.get("tinyAskPhone").setContent('');
			var id = $(this).val();
			$("#form_phone_subject").val($("#subject_tel_"+id).val());
			var val = $("#template_tel_"+id).val();
			$("#tinyAskPhone").val(val);
			tinymce.get("tinyAskPhone").setContent(val);
		});
		// Put data of the template choised
		$("#nom_template_sms").on('change', function(){
			$("#confirmSMS").empty();
			var id = $(this).val();
			var val = $("#template"+id).val();
			var date = '- Visit le ' + $("#date_confirm").val() + ' De ' + $("#date_confirm_start").val() + ' à ' + $("#date_confirm_end").val();
			$("#confirmSMS").val(date + ' ' + val);
		});
		$("[data-confirm-email]").on('click', function() {
			$("#tinyConfirm").empty();
			var valTiny = tinymce.get("tinyConfirm").getContent();
			tinymce.get("tinyConfirm").setContent('');
			var date = '- Visit le ' + $("#date_confirm").val() + ' De ' + $("#date_confirm_start").val() + ' à ' + $("#date_confirm_end").val();
			$("#tinyConfirm").val(date + valTiny);
			tinymce.get("tinyConfirm").setContent(date + valTiny);
			$("#modal_date").val($("#date_confirm").val());
			$("#modal_date_confirm_start").val($("#date_confirm_start").val());
			$("#modal_modal_date_confirm_end").val($("#date_confirm_end").val());
		});
		$("[data-confirm-sms]").on('click', function(){
			var id = $("#nom_template_sms").val();
			var val = $("#template"+id).val();
			var date = '- Visit le ' + $("#date_confirm").val() + ' De ' + $("#date_confirm_start").val() + ' à ' + $("#date_confirm_end").val();
			$("#confirmSMS").val(date + ' ' + val);
			$("#modal_date_sms").val($("#date_confirm").val());
			$("#modal_date_confirm_start_sms").val($("#date_confirm_start").val());
			$("#modal_modal_date_confirm_end_sms").val($("#date_confirm_end").val());
		});
		// Add new row of rappel in modal edit
		$(".add-newreminder-edit").on('click', function(){
			var d = new Date();
			var n = d.getTime();
			$(".add-reminder-edit").append('\
				<div id="'+n+'">\
					<div class="row mb-2" >\
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">\
							<span class="ffhnm">\
								Rappel\
							</span><hr>\
						</div>\
					</div>\
					<div class="row mb-2">\
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
							<span class="ffhnl">Date</span>\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
							<input type="date" name="date_edit[]" class="form-control" value="">\
						</div>\
					</div>\
					<div class="row mb-2">\
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
							<span class="ffhnl">Type</span>\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
							<select class="form-control" name="type_template_edit[]">\
								<option value="email">Mail</option>\
								<option value="sms">SMS</option>\
								<option value="task">Tâches</option>\
							</select>\
						</div>\
					</div>\
					<div class="row mb-2" id="content-subject">\
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
							<span class="ffhnl">Suject</span>\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
							<input type="text" class="form-control" name="subject_edit[]" placeholder="Objet du mail" value="">\
						</div>\
					</div>\
					<div class="row mb-2">\
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">\
							<span class="ffhnl">Corps</span>\
						</div>\
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">\
							<textarea class="form-control" name="content_edit[]" rows="4" placeholder="Texte du mail bla bla"></textarea>\
						</div>\
					</div>\
					<div class="mb-4">\
						<button type="button" class="btn btn-danger" data-id-del="'+n+'">Supprimer ce rappel</button>\
					</div>\
				</div>');
		});
		$(document).on("click", "[data-id-del]", function(e) {
			$("#"+$(this).attr("data-id-del")).remove();
			$("[data-id-reminder='"+$(this).attr("data-id-del")+"']").remove();
		});
		// Listener when click on change time to send reminder
		$("[data-change-time]").on('click', function(e) {
			e.preventDefault(); // Prevent default
			var route = $(this).attr('data-change-time');
			var time = $("#time_to_send_reminder").val();
			route = route.replace("value", time);
			$("#changetime_rappel").attr('action', route);
		});
		// When select one date
		$("#date_special").on('change', function(){
			var date = $(this).val();
			var dateSplit = date.split("-");
			var months =	['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']; // Custom name of months
			var dateComplete = dateSplit[2] + ' ' + months[parseInt(dateSplit[1]) - 1];
			$("#dates_added").append('<li id="' + dateSplit[2] + '-' + dateSplit[1] + '">' + dateComplete + ' <svg data-delete-date="' + dateSplit[2] + '-' + dateSplit[1] + '" class="minus-date" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 16 16"><path d="M14 1a1 1 0 011 1v12a1 1 0 01-1 1H2a1 1 0 01-1-1V2a1 1 0 011-1zM2 0a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2z"/><path d="M4 8a.5.5 0 01.5-.5h7a.5.5 0 010 1h-7A.5.5 0 014 8z"/></svg><span><input type="hidden" name="dates[]" value="'+ dateSplit[2] + '-' + dateSplit[1]+'"></li>');
		});
		// Delete date on change time to send reminder
		$(document).on("click", "[data-delete-date]", function(e) {
			$("#"+$(this).attr("data-delete-date")).remove();
		});
		// DETAILS - Type du bien
		if ($("#type_de_bien").length) {
			var options_type = $("#type_de_bien").html();
			$("#type_de_bien_adapte").append(options_type);
			if ($("#type_de_bien").val() == 'Maison' || $("#type_de_bien_adapte").val() == 'Maison') {
				$("#type-bien-maison").show();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").hide();
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($("#type_de_bien").val() == 'Appartement' || $("#type_de_bien_adapte").val() == 'Appartement') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").show();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").hide();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($("#type_de_bien").val() == 'Immeuble de rapport' || $("#type_de_bien_adapte").val() == 'Immeuble de rapport') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").show();
				$("#type-bien-autre").hide();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($("#type_de_bien").val() == 'Autre' || $("#type_de_bien_adapte").val() == 'Autre') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").show();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', true);
			}
		}
		$("#type_de_bien").on('change', function(){
			$("#type_de_bien_adapte").append('');
			var options_type = $("#type_de_bien").html();
			$("#type_de_bien_adapte").append(options_type);
			if ($(this).val() == 'Maison') {
				$("#type-bien-maison").show();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").hide();
				$("[data-disabled-maison]").attr('disabled', false);
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($(this).val() == 'Appartement') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").show();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").hide();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-appartment]").attr('disabled', false);
				$("[data-disabled-immeuble]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($(this).val() == 'Immeuble de rapport') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").show();
				$("#type-bien-autre").hide();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', false);
				$("[data-disabled-autre]").attr('disabled', true);
			}
			if ($(this).val() == 'Autre') {
				$("#type-bien-maison").hide();
				$("#type-bien-appartement").hide();
				$("#type-bien-immeuble").hide();
				$("#type-bien-autre").show();
				$("[data-disabled-maison]").attr('disabled', true);
				$("[data-disabled-appartment]").attr('disabled', true);
				$("[data-disabled-immeuble]").attr('disabled', true);
				$("[data-disabled-autre]").attr('disabled', false);
			}
		});
		if ($("[data-problem-bien]").length) {
			if ($("[data-problem-bien]").val() == 'Non') {
				$("[data-textarea-problem]").addClass('d-none');
			} else {
				$("[data-textarea-problem]").removeClass('d-none');
			}
		}
		// When select change if exist a problem
		$("[data-problem-bien]").on('change', function(){
			if ($(this).val() == 'Non') {
				$("[data-textarea-problem]").addClass('d-none');
			} else {
				$("[data-textarea-problem]").removeClass('d-none');
			}
		});
		if ($("[data-chuaffage]").length) {
			if ($(this).val() == 'Non') {
				$("[data-oui-chauffage]").addClass('d-none');
				$("[data-oui-chauffage]").attr('disabled', true);
			} else {
				$("[data-oui-chauffage]").removeClass('d-none');
				$("[data-oui-chauffage]").attr('disabled', false);
			}
		}
		// When select change if exist chauffage
		$("[data-chuaffage]").on('change', function(){
			if ($(this).val() == 'Non') {
				$("[data-oui-chauffage]").addClass('d-none');
				$("[data-oui-chauffage]").attr('disabled', true);
			} else {
				$("[data-oui-chauffage]").removeClass('d-none');
				$("[data-oui-chauffage]").attr('disabled', false);
			}
		});
		// When select change if exist panneauz solaires
		$("[data-panels-sol]").on('change', function(){
			if ($(this).val() == 'Non') {
				$("[data-oui-pannels]").addClass('d-none');
				$("[data-oui-pannels]").attr('disabled', true);
			} else {
				$("[data-oui-pannels]").removeClass('d-none');
				$("[data-oui-pannels]").attr('disabled', false);
			}
		});
		if ($("[data-parking]").length) {
			if ($("[data-parking]").val() == 'Non') {
				$("[data-oui-parking]").addClass('d-none');
				$("[data-oui-parking]").attr('disabled', true);
			} else {
				$("[data-oui-parking]").removeClass('d-none');
				$("[data-oui-parking]").attr('disabled', false);
			}
		}
		// When select change if exist parking
		$("[data-parking]").on('change', function(){
			if ($(this).val() == 'Non') {
				$("[data-oui-parking]").addClass('d-none');
				$("[data-oui-parking]").attr('disabled', true);
			} else {
				$("[data-oui-parking]").removeClass('d-none');
				$("[data-oui-parking]").attr('disabled', false);
			}
		});
		if ($("[data-purchase]").length) {
			if ($("[data-purchase]").val() == 'Préciser') {
				$("[data-preciser]").removeClass('d-none');
				$("[data-preciser]").attr('disabled', true);
			} else {
				$("[data-preciser]").addClass('d-none');
				$("[data-preciser").attr('disabled', true);
			}
		}
		$("[data-purchase]").on('keyup', function(){
			if ($("[data-purchase]").val() == 'Préciser') {
				$("[data-preciser]").removeClass('d-none');
				$("[data-preciser]").attr('disabled', true);
			} else {
				$("[data-preciser]").addClass('d-none');
				$("[data-preciser]").attr('disabled', true);
			}
		});
		// If seller is autre
		if ($("#data-who-is-seller").length) {
			if ($("#data-who-is-seller").val() == 'Autre') {

			}
		}
		// SLider
		if ($("#desires_to_sell").length) {
			$("#percentage").append($("#desires_to_sell").val() + '%');
		}
		$("#desires_to_sell").on('mousemove', function() {
			$("#desires_to_sell").empty();
			$("#percentage").empty();
			$("#percentage").append($(this).val() + '%');
			$("#desires_to_sell").val($(this).val());
		});
		$("#desires_to_sell").on('touchmove', function() {
			$("#desires_to_sell").empty();
			$("#percentage").empty();
			$("#percentage").append($(this).val() + '%');
			$("#desires_to_sell").val($(this).val());
		});
		// GUARDAR CAMBIOS
		var checkforms = function(event){
			event.preventDefault();
			$("[data-modified='true']").each(function(){
				var parentCard = $(this).parents(".card");
				parentCard.find(".card-header").addClass("notsaved").append("<span>Enregistrez vos modifications<i class='bi bi-exclamation-triangle'></i></span>");
			});
			return event.returnValue = "Aucune modification n'a été enregistrée! Voulez-vous les enregistrer?";
		}
		$("#send_reminder_half_past_eight").on('click', function(){
			var url = $(this).attr("data-url-eight");

			var isChecked = document.getElementById('send_reminder_half_past_eight').checked;
			if (isChecked) {
				url = url.replace("response", "1");
				$.ajax({
					url: url,
					type: 'GET',
					beforeSend: function() { // Before send request

					},
					success: function(response) {
						//showSwal('Succès', response.message, 'success'); // Call popup
					},
					error: function(error) {
						console.log(error);
					}
				});
			} else {
				url = url.replace("response", "0");
				$.ajax({
					url: url,
					type: 'GET',
					beforeSend: function() { // Before send request
					},
					success: function(response) {
						//showSwal('Error', response.message, 'error'); // Call popup
					},
					error: function(error) {
						console.log(error);
					}
				});
			}
		});
		// Save subject template to the offer
		$("#subject_text").on('keydown', function(){
			var url = $(this).attr("data-url");
			var timeout = setTimeout(() => {
			var data = { // Saving parameters to the function in the controller
						_token: $("#_token").text(),
						subject_text: $(this).val(),
						id_template: $("#subject_id_template").val(),
					};
			$.ajax({
					url: url,
					type: 'POST',
					data: data,
					beforeSend: function() { // Before send request

					},
					success: function(response) {
						$("#ok").removeClass("d-none");
						setTimeout(function(){
						$("#ok").addClass("d-none");
						}, 2000);
						//showSwal('Succès', response.message, 'success'); // Call popup
					},
					error: function(error) {
						console.log(error);
					}
				});
				clearTimeout(timeout)
			},1000);
		});
		// When select some template to edit rappel type task
		$("[data-choisir-template]").on('change', function(){
			var id = $(this).val();
			var subject = $("[data-det-subject-task-"+id+"]").val();
			var text = $("[data-det-file-template-"+id+"]").val();
			$("[data-subject-edit]").empty();
			$("[data-content-edit]").empty();
			$("[data-subject-edit]").val(subject);
			$("[data-content-edit]").val(text);
		});
		$("#text_add_offer").on('change', function() {
			$("#other_offer").empty();
			tinymce.get("other_offer").setContent('');
			var textOffer = document.getElementById("text_add_offer").value;
			var valu = $("#offre-"+textOffer).val();
			$("#other_offer").html(valu);
			tinymce.get("other_offer").setContent(valu);
		});
		$("[data-nom-template]").on('change', function() {
			// Empty fields
			$("[data-sub]").empty();
			$("#tinycreatenewticket").empty();
			tinymce.get("tinycreatenewticket").setContent('');

			// Get data to fill fields
			var id =$(this).val();
			var subject = $("[data-subject-email-"+id+"]").val();
			var text = $("[data-template-email-"+id+"]").val();

			//Fill fields
			$("[data-sub]").val(subject);
			tinymce.get("tinycreatenewticket").setContent(text);
			var content = tinymce.get("tinycreatenewticket").getContent();
			$("#tinycreatenewticket").val(content);
		});
		if ($("[data-both]").length) {
			$("[data-by-the-same]").hide();
			$("[data-by-agence]").hide();
			$("[data-both]").hide();
			var val = $("[data-sale]").val();
			if (val == 'Par agence') {
				$("[data-by-the-same]").hide();
				$("[data-by-agence]").show();
				$("[data-both]").hide();
			}
			if (val == 'Par lui même') {
				$("[data-by-the-same]").show();
				$("[data-by-agence]").hide();
				$("[data-both]").hide();
			}
			if (val == 'Le deux') {
				$("[data-by-the-same]").show();
				$("[data-by-agence]").show();
				$("[data-both]").show();
			}
			if (val == 'Non') {
				$("[data-by-the-same]").hide();
				$("[data-by-agence]").hide();
				$("[data-both]").hide();
			}
		}
		// Change of select to choise sale
		$("[data-sale]").on('change', function(){
			var val = $(this).val();
			if (val == 'Par agence') {
				$("[data-by-the-same]").hide();
				$("[data-by-agence]").show();
				$("[data-both]").hide();
			}
			if (val == 'Par lui même') {
				$("[data-by-the-same]").show();
				$("[data-by-agence]").hide();
				$("[data-both]").hide();
			}
			if (val == 'Le deux') {
				$("[data-by-the-same]").show();
				$("[data-by-agence]").show();
				$("[data-both]").show();
			}
			if (val == 'Non') {
				$("[data-by-the-same]").hide();
				$("[data-by-agence]").hide();
				$("[data-both]").hide();
			}
		});
		if ($("[data-modified]").length) {
			$("[data-modified]").hide();
			$("[data-modified-modal]").hide();
			$("[data-cancel]").hide();
		}
		$("[data-change-input]").on('keyup', function() {
			$(this).parents('form').find("[data-modified]").attr("data-modified", true).show();
			$(this).parents('form').find("[data-modified-modal]").attr("data-modified-modal", true).show();
			$(this).parents('form').find("[data-cancel]").show();
			if (!isModify) {
				addEventListener('beforeunload', checkforms, {capture: true});
			}
			isModify = true;
		});
		$("[data-change-select]").on('change', function() {
			$(this).parents('form').find("[data-modified]").attr("data-modified", true).show();
			$(this).parents('form').find("[data-modified-modal]").attr("data-modified-modal", true).show();
			$(this).parents('form').find("[data-cancel]").show();
			if (!isModify) {
				addEventListener('beforeunload', checkforms, {capture: true});
			}
			isModify = true;
		});
		$("[data-change-radio]").on('click', function() {
			$(this).parents('form').find("[data-modified]").attr("data-modified", true).show();
			$(this).parents('form').find("[data-modified-modal]").attr("data-modified-modal", true).show();
			$(this).parents('form').find("[data-cancel]").show();
			if (!isModify) {
				addEventListener('beforeunload', checkforms, {capture: true});
			}
			isModify = true;
		});
		$("[data-cancel]").on('click', function(){
			$(this).parents('form').find("[data-modified]").attr("data-modified", false).show();
			$(this).parents('form').find("[data-modified-modal]").attr("data-modified-modal", false).show();
			$("[data-modified]").hide();
			$("[data-modified-modal]").hide();
			$("[data-cancel]").hide();
			if (isModify) {
				removeEventListener('beforeunload', checkforms, {capture: true});
			}
			isModify = false;
			var parentCard = $(this).parents(".card");
			parentCard.find(".card-header").removeClass("notsaved").find("span").remove();
		});
		$(".modal").on('hide.bs.modal', function(e){
			if ($(e.target).find("[data-modified-modal='true']").length) {
				setTimeout(function(){
					$('#' + $(e.target).attr('id')).modal("show");
				}, 500);
				var swal = Swal.fire({ // Active popup
					title: "Attention", // Title
					text: "Aucune modification n'a été enregistrée! Voulez-vous les enregistrer?", // Text
					icon: 'warning', // Icon
					confirmButtonColor: '#446B9E', // Confirm button
					showCancelButton: true, // Show cancel button true / false
					cancelButtonColor: '#e3342f',
				}).then(function(result) {
					if (result.isConfirmed) {
						$("[data-modified-modal='true']").each(function(){
							$(this).click();
						});
					}
				});
			}
		});
		$("[data-terminer]").on('click', function(e){
			var status = document.getElementById('status-visite').checked;
			var url = $(this).attr("data-terminer");
			url = url.replace('option', status);
			$(this).attr('href', '');
			$(this).attr('href', url);
		});
		$("[data-send-offer]").on('click', function(){
			var content = tinymce.get("tinyCorps").getContent();
			var url = $("[data-pdf-offer]").attr("href");
			var btnurl = '<a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #34b7eb; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-top:30px; text-decoration: none; cursor: pointer;" href="'+ url +'">Voir l\'offre</a>';
			content = content.replace('[link-pdf]', btnurl);
			$("#tinyCorps").val(content);
			tinymce.get("tinyCorps").setContent(content);
		});
		// Open popup to see data of a user
		var urlProfile = location.href;
		if (urlProfile.indexOf('?tab-name=settings-users') >= 0) {
			var id = $("#user_id_open_modal").val();
			$('#tab-main .nav-link[href="#' + 'settings-users' + '"]').tab('show');
			console.log('[data-user-id="'+id+'"]');
			$('[data-user-id="'+id+'"]').click();
		}
		function load_page(url){
			$('#editUser').load(url,function(){});
		}
	});
})(jQuery);
