
jQuery(function($){
  $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
          var nextPage        = parseInt($('#pageno').val())+1;
 
             $.ajax({
                 type : 'POST',
                 url  : misha_loadmore_params.ajaxurl,
                 data : {
                          'pageno'         : nextPage,
                          'action'         : 'load_posts_by_ajax',
                },
                 success: function( data ){
                     //console.log(data);
                     if( data != '' ){  
                        $('#property_post').append( data );  // ADD PROPERTY LIST IN DIV 
                         $('#pageno').val( nextPage );         
                     } 
                 }
          });
        }
    });
   });

// ******************* Perform AJAX Search on form submit ************************//
      jQuery(function($) {
          $('#property_form_id').on('submit', function(e){
            e.preventDefault();
            $('#all_property').empty();
                $('#property_search').empty('');
                $('#property_post').empty('');
                var location_address    = $('#location_address').val(); 
                var property_status = $('#property_status').val();
                jQuery.ajax({
                   type : 'POST',
                   url  : misha_loadmore_params.ajaxurl,
                   data : { 
                          'action'           :'search_property_by_ajax',
                          'property_status'  : property_status,
                          'location_address' : location_address
                    },
                   success: function( result )
                   {
                     if(result!=='')
                     { 
                      // goListevent();
                        $('#property_post').hide();
                       // $('#property_search').hide();
                       $('#property_status').val( property_status);
                       $('#location_address').val( location_address );
                       $('#pageno').val(1);
                       $('#property_search').append( result );
                       return false;
                     }
                     else
                     {
                        $('#all_property').hide(); 
                        $('#pageno').val(1);
                       $('#property_search').hide( result );
                     }
                   } 
              });
          });

        });

jQuery(function($){
//*********  add guest option ***********//
        $('#guestcheckbox').change(function () {
             var checkbox = $(this), // Selected or current checkbox
             value = checkbox.val(); // Value of checkbox
             calculateMoney();
              let guest_amount= $("#booking_amount").val();
              if (checkbox.is(':checked'))
              {
                total_amount_calculate(guest_amount);
                $("#booking_amount").val(estimated_amount); 
                $("#add_guest").show();  
                $("table tbody").show(); 
                $("#tanent_data").show(); 
                $("#guest_data").show();
                $("#add_guest").prop('disabled', false);
              }
              else
              { $("#add_guest").prop('disabled', true);
                $("#add_guest").show();  
                $("#guest_data").hide();
                $("#tanent_data").show(); 
                $("#booking_amount").val(0); 
                $("#total_booking_amount").val(100);  
              }
        }).change();

        function calculateMoney(){
        var guest_first_array = document.getElementsByName('guest_first_name[]');
         estimated_amount=(guest_first_array.length)*20;
        let guest_amount=parseInt(estimated_amount);
         $("#booking_amount").val(estimated_amount);
        } 

//*************************total amount********************//
        function total_amount_calculate(guest_amount){
           guest_amount=parseInt(guest_amount);
           let old_total_amount= $("#total_booking_amount").val();
           let total_amount=parseInt(old_total_amount);
          total_amount=total_amount+guest_amount;
          $("#total_booking_amount").val(total_amount);
         }
//***************** include guest information ********************//
           var fname = document.getElementsByName('guest_first_name[]');
            for(var i = 0; i<fname.length; i++){
                 var first_name = fname[fname.length-1].value;
            }
        
           var lname = document.getElementsByName('guest_last_name[]');
            for(var i = 0; i<lname.length; i++){
                 var last_name = lname[lname.length-1].value;
            }
           var rows = 2;
           var estimated_amount;
     
// add guests
              $("#add_guest").click(function(){
                 var total_mount= $("#booking_amount").val();
                 var thtml = '';
                 let increament_amount=20;
               
                  thtml += '<tr  id="guest_id'+ rows +'">';
                  thtml += '<td style="font-size:15px;"><span>'+ rows +'</span></td>';
                  thtml += '<td><input type="text" class="form-control" placeholder="Enter First name" id="guest_first_name" name="guest_first_name[]"></td>';
                  thtml += '<td><input type="text" class="form-control" placeholder="Enter Last  name" id="guest_last_name" name="guest_last_name[]"></td>';
                  thtml += '<td><i class="glyphicon glyphicon-remove"  style="color:red;" id="delete_guest"  ><span class="glyphicon glyphicon-remove"></span> Remove </i></td></tr>';  
                 $("tbody").append(thtml);          
                 $("guest_include tbody tr").each(function(index) {            
                      $(this).children('td').first().text(index + 1);    //*  increment s.no number
                  });         calculateMoney();       

                 var old_total_booking_amount = $("#total_booking_amount").val();                                    //*
                 let updated_total_amount     = parseInt(old_total_booking_amount)+increament_amount;
                     $("#total_booking_amount").val(updated_total_amount);       
                     rows = parseInt(rows)+1;
                 return false;
             });
        });
 

 //**********  delete guest row  *************//
jQuery(function($){
        $(document).on('click','#delete_guest', function(e){
              var row_count = $('table tbody tr').length;
              let decrement_amount=20;
              if(row_count <= 1)
              {
                alert("Guest must be atleast one or uncheked the option");  
                return false;
              }
              else
              {
                let estimated_amount=$("#booking_amount").val();
                    estimated_amount=estimated_amount-decrement_amount;
                let old_total_amount=$("#total_booking_amount").val();
               // decreament by 20 $ in total booking and each person
                let total_amount=old_total_amount-decrement_amount;               
                let row = $(this).closest('tr');  
                let siblings = row.siblings();                      // *
                row.remove();                                       // *  indexing add auto increment
                siblings.each(function(index) {                     // *
                    $(this).children('td').first().text(index + 1); // *
                });  
                 $("#booking_amount").val(estimated_amount);   
                 $("#total_booking_amount").val(total_amount);   
              }
          });
      });

  //*********  Register booking details ***********//
jQuery(function($){
      //   $('#register').on('click', function(e){
      //        e.preventDefault(); 
      //        let tanentfirstname = $("#tanentfirstname").val();  
      //        let tanentlastname  = $("#tanentlastname").val(); 
      //        let date_range      = $('#daterange').val();
      //        let property_id     = $("#property_id").val();
      //        let submit_timer_value = $("#submit_timer_value").val();
      //        let demo            = $("#demo").val();
      //        let tmp_booking     = $("#tmp_booking").val();
      //        deadline = new Date().getTime()+(1000 * 60 * 2)
      //        var first_name;
      //        var last_name;
      //        let compare_string= /^[A-Z,a-z]+$/;
      //        var guest_first_name = $("input[name='guest_first_name[]']").map(function(){return $(this).val();}).get();          
      //        var guest_last_name = $("input[name='guest_last_name[]']").map(function(){return $(this).val();}).get();     
      //        if(submit_timer_value==0){
      //           alert("Please do check out first!!");
      //         return false;
      //         } else if(! tanentfirstname.match(compare_string)){
      //              alert("Please Enter appropriate Tanent First Name");
      //             document.getElementById('tanentfirstname').value = "";
      //             document.getElementById('tanentfirstname').style.borderColor = "red";
      //             return false;
      //         }
      //         else if(! tanentlastname.match(compare_string)){
      //          alert("Please Enter appropriate Tanent Last Name");
      //          document.getElementById('tanentfirstname').style.borderColor = "green";
      //          document.getElementById('tanentlastname').value = "";
      //          document.getElementById('tanentlastname').style.borderColor = "red";
      //             return false;
      //         }
      //         else if($('#guestcheckbox').is(":checked") && (last_name=="" || first_name=="")){         
      //                           alert("Please Fill guest details or uncheked the guest option!!");
      //                           return false;
      //         }else if(tmp_booking==0 || checkout_status==0){
      //          return false;
      //         }
      //         else if(property_id){
      //             $.ajax({
      //            type : 'POST',
      //            url  : misha_loadmore_params.ajaxurl,
      //            data : {
      //                     'action'       :'add_booking_ajax',
      //                     'property_id'  : property_id,
      //                     'book_date'    : date_range
      //           },
      //            success: function( data ){
      //             // console.log(data);
      //            var array_objects = JSON.parse(data);
      //             if(array_objects.length>0){
      //                alert("property is already booked for the day! Please choose other dates!!");
      //             return false;
      //             }else{ 
      //                  // final submit details
      //                       $.ajax({
      //                            type : 'POST',
      //                            url  : misha_loadmore_params.ajaxurl,
      //                            data : { 
      //                                   'action'     :'register_guest_detail_ajax',
      //                                   'tanentfirstname': tanentfirstname,
      //                                   'tanentlastname' : tanentlastname,
      //                                   'first_name' : guest_first_name,
      //                                   'last_name'  : guest_last_name,
      //                                   'book_date'  : date_range,
      //                                   'deadline'  : deadline,
      //                                   'property_id': property_id
      //                           },
      //                           beforeSend:function() {
      //                             $("#loader").toggle('4000');
      //                           },
      //                           success:function(result) {
      //                          // console.log(result);
      //                            if(result) 
      //                             {alert(result);
      //                               goListevent();
      //                              $("#add_guest").removeAttr("disabled");
      //                              $("#loader").hide();  
      //                              location.reload();               
      //                             }
      //                            else 
      //                            {
      //                              $("#loader").hide();
      //                            }
      //                          },
      //                       });
      //                   }    
      //            }
      //     });                
      //         }else{
      //           alert("else end");
      //         }
      // });
   
// ********************Get all bookings dates againts property*********//
$(document).ready(function() {
  check_load_event();
});
 function check_load_event()
 { 
	 gapi.load('client:auth2', check_another_load);
 }
 function check_another_load() {
	  gapi.client.init({
          apiKey: 'AIzaSyCgn3AhryzKA4Vgp5M_ucyZi6lzuaEPSho',
          clientId: '358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com',
          discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
          scope: "https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events"
        }).then(function () {
          // gapi.auth2.getAuthInstance().signIn().then(function(responce) {
            gapi.client.load('calendar', 'v3', function () {
              var request = gapi.client.calendar.events.list({
                  'calendarId'  :'cink7q1aipvdf88789vq9lb3co@group.calendar.google.com',
                 });
          request.execute(function(resp){
			     // console.log(resp);
           var desDateContainer =[];
           var splitStr = resp.items;
           // var check = JSON.stringify(resp.items);
           var list_length = splitStr.length;
           // console.log(list_length);
          if(list_length > 0)
                  { 
                    for(var i=0; i<list_length; i++){  
                         var starttime = moment(resp.items[i].start.dateTime);
                         var edate     = resp.items[i].end.dateTime;
                         var date = new Date(edate);
                         //var days = date.setDate(date.getDate() + 1);
                         var endTime   = moment(date);
                          desDateContainer[i] = {"start":starttime,"end":endTime};      
                      }
                      // console.log(desDateContainer);
                     getProperty(desDateContainer);
                  }   
		  });
	   });
    });
	 // });
  }

goListevent();
function goListevent(){
$("#check_out").prop('disabled', false);

$("#daterange").daterangepicker({
           minDate   : new Date(),
           startDate : moment().startOf('hour'),
           endDate   : moment().startOf('hour').add(36, 'hour'),
           locale    : {
                           format:'YYYY/MM/DD',
                       },
});
}
// ********************Disable perticular dates againts property*********//
 function getProperty(arr_test){
  $("#daterange").daterangepicker({
           minDate   : new Date(),
           startDate : moment().startOf('hour'),
           endDate   : moment().startOf('hour').add(36, 'hour'),
           locale    : {
                           format:'YYYY/MM/DD',
                       },
           isInvalidDate: function(date)
          {
            return arr_test.reduce(function(bool, range) {
              return bool || (date >= range.start && date <= range.end);
            }, false);
          },
   });
 }
});


function bookingOldStatus(property_id,date_range){
  $.ajax({
                   type : 'POST',
                   url  : misha_loadmore_params.ajaxurl,
                   data : {
                            'action'       :'add_booking_ajax',
                            'property_id'  : property_id,
                            'book_date'    : date_range
                  },
                   success: function( data ){
                   var array_objects = JSON.parse(data);
                   var temp_length =parseInt(array_objects.length);
                   // console.log("temp_length="+temp_length);
                   document.getElementById("already_bookings_counts").value=temp_length;  
                  }
      });
}

/***************************check out block*************************/
jQuery(function($){
   $('#daterange').change(function () {
    $("#check_out").prop('disabled', false);
   });
 });

/***************************timer entry*************************/
jQuery(function($){
$('#check_out').click(function(e){
    $("#check_out").prop('disabled', true);
        var checkout_status= $('#checkout_status').val();
        if(checkout_status==1){
          return false;
        } 
        var base_amount=100;
        var per_person_amount=20;
        var property_id= $('#property_id').val();
        var date_range = $('#daterange').val();
        var total_booking_amount = $('#total_booking_amount').val();
            total_booking_amount=parseInt(total_booking_amount);
        var gmember_count=((total_booking_amount-base_amount)/per_person_amount);
        let path = window.location.origin;
        let tanentfirstname = $("#tanentfirstname").val();  
        let tanentlastname  = $("#tanentlastname").val();      
        let compare_string= /^[A-Z,a-z]+$/;
        if(! tanentfirstname.match(compare_string)){
                   alert("Please Enter appropriate Tanent First Name");
                  document.getElementById('tanentfirstname').value = "";
                  document.getElementById('tanentfirstname').style.borderColor = "red";
                  return false;
           }
              else if(! tanentlastname.match(compare_string)){
               alert("Please Enter appropriate Tanent Last Name");
               document.getElementById('tanentfirstname').style.borderColor = "green";
               document.getElementById('tanentlastname').value = "";
               document.getElementById('tanentlastname').style.borderColor = "red";
                  return false;
              }
      else{
        $.ajax({
                   type : 'POST',
                   url  : misha_loadmore_params.ajaxurl,
                   data : {
                            'action'       :'check_old_tmp_bookings_checkout_ajax',
                            'property_id'  : property_id,
                            'book_date'    : date_range
                  },
                   success: function( data ){
                   console.log("inner"+data);
                   var array_objects = JSON.parse(data);
                   var temp_length =parseInt(array_objects.length);
                   if(temp_length>0){
                    alert("property is already booked for the day! Please choose other dates!!");
                     $("#check_out").prop('disabled', false);
                    return false;
                   }else{
                     // eventStore(); for storing in calender
                   $.ajax({
                   type : 'POST',
                   url  : misha_loadmore_params.ajaxurl,
                   data : { 
                          'action'       :'check_old_tmp_bookings_ajax',
                          'property_id'  : property_id,
                          'date_range'   : date_range,
                    },
                   success: function(result)
                   {
                    // console.log("timer"+result);
                    var array_objects = JSON.parse(result);
                      if(array_objects.length>0)
                      {
                        var db_deadline = array_objects[0].dead_line;
                        var x = setInterval(function(){ 
                         
                          var now = new Date().getTime(); 
                          var t =  db_deadline-now; 
                          var minutes = Math.floor((t % (1000 * 60 * 2)) / (1000 * 60)); 
                          var seconds = Math.floor((t % (1000 * 60)) / 1000); 
                          document.getElementById("responce_timer").innerHTML = "Booking can be available after!";  
                          document.getElementById("minute").innerHTML = minutes;  
                          document.getElementById("second").innerHTML =seconds;
                              if(t < 0 ){
                                clearInterval(x);
                                document.getElementById("minute").innerHTML ="clear" ;  
                                document.getElementById("second").innerHTML = "clear"; 
                                alert("now you can apply");
                                location.reload();
                              $("#register").prop('disabled', false);
                              $("#check_out").prop('disabled', false);
                              document.getElementById("checkout_status").value = '0';  
                              document.getElementById("tmp_booking").value = '0';  
                              }else{
                                 document.getElementById("submit_timer_value").value = 1;
                              $("#register").prop('disabled', true);
                              }
                         });
                    
                      }else{ 
                        var deadline = new Date().getTime()+(1000 * 60 * 2);
                        var initial_deadline=deadline;
                        var tmp_event_id;
                        setTimeout(function(){
                         tmp_event_id = $('#tmp_event_id').val();    
                        // alert(tmp_event_id);
                      $.ajax({
                               type : 'POST',
                               url  : misha_loadmore_params.ajaxurl,
                               data : { 
                                      'action'       :'temporary_booking_ajax',
                                      'property_id'  : property_id,
                                      'date_range'   : date_range,
                                      'deadline'     : deadline,
                                      'tmp_event_id' :tmp_event_id
                                },
                               success: function(result)
                               {
                                 // console.log("insert"+result);
                                var res = result.split("]");
                                var left=res[0];
                                var final = left.split("[");
                                var right=final[1];
                                document.getElementById("tmp_booking").value = right;  
                                document.getElementById("checkout_status").value = '1';  
                                var property_title = $('#property_title').val();
                             // alert("r"+gmember_count);
                       // setTimeout(function(){
                               $.ajax({
                                                 type : 'POST',
                                                 url  : misha_loadmore_params.ajaxurl,
                                                 data : {
                                                          'action'     :'wdm_add_user_custom_data_options',
                                                          'product_id' : property_id,
                                                          'total_booking_price' : total_booking_amount,
                                                          'property_title': property_title,
                                                          'date_range' :date_range,
                                                          'tanentfirstname':tanentfirstname,
                                                          'tanentlastname':tanentlastname,
                                                          'gmember_count':gmember_count
                                                },
                                                success: function( result ){
                                                  console.log(result);
                                            // return false;
                                                  location.href=path+'/cart';
                                               }
                                              });
                                           // }, 5000);

                                }
                       });
                       }, 1000);
                       var x = setInterval(function() 
                       { 
                        var now = new Date().getTime(); 
                        var t =  deadline-now; 
                        var minutes = Math.floor((t % (1000 * 60 * 2)) / (1000 * 60)); 
                        var seconds = Math.floor((t % (1000 * 60)) / 1000); 
                        document.getElementById("minute").innerHTML = minutes;  
                        document.getElementById("second").innerHTML =seconds;  
                        if (t < 0) { 
                                clearInterval(x); 
                                document.getElementById("minute").innerHTML ="UP" ;  
                                document.getElementById("second").innerHTML = "UP"; 
                                document.getElementById("submit_timer_value").value = 0;
                                // delete temporaray booking
                                delete_entry(initial_deadline,property_id,date_range,tmp_event_id);
                                setTimeout(function(){
                                alert("Your time is exceded!! please apply again");
                              
                                location.reload();
                                
                                return false; 
                       }, 3000); 
                             }else{
                                document.getElementById("submit_timer_value").value = 1;
                              } 
                       }, 1000); 

                     }
                  }
          });

                   }

                  }
           });

             

         // let path = window.location.origin;
         // $.ajax({
         //           type : 'POST',
         //           url  : misha_loadmore_params.ajaxurl,
         //           data : {
         //                    'action'       :'check_loged_in_user_ajax'
         //          },
         //           success: function( data ){
         //          alert(data);
         //        if(data==0)
         //        {
         //       alert("please do registion first");
         //        location.href=path+'/my-account';
               
         //        }else{
         //         $.ajax({
         //                   type : 'POST',
         //                   url  : misha_loadmore_params.ajaxurl,
         //                   data : {
         //                            'action'     :'add_to_cart_ajax',
         //                            'product_id' : property_id,
         //                            'min_price'  : total_booking_amount,
         //                            'max_price'  : total_booking_amount,
         //                            'onsale'     : 1,
         //                            'date_range' :date_range
         //                  },
         //                  success: function( result ){
         //                    alert(result);
         //                location.href=path+'/cart';
         //                 }
         //                });
         //        }
         
         //         }
         //         });
        
}

      });
 });
             function delete_entry(dead_line,property_id,date_range,tmp_event_id){
              // alert("end"+tmp_event_id);
              gapi.client.load('calendar', 'v3', function() { 
              var request = gapi.client.calendar.events.delete({
                                     'calendarId': "cink7q1aipvdf88789vq9lb3co@group.calendar.google.com",
                                     'eventId'   : tmp_event_id,
                                   });
                   request.execute(function(resp) {
                      // console.log(resp);
                      });

               });
               $.ajax({
                       type : 'POST',
                       url  : misha_loadmore_params.ajaxurl,
                       data : { 
                              'action'       :'delete_tmp_booking_ajax',
                              'property_id'  : property_id,
                              'dead_line'    : dead_line,
                              'date_range'   : date_range
                        },
                       success: function(result)
                       {
                        // console.log(result);
                        document.getElementById("tmp_booking").value = 0;
                        document.getElementById("checkout_status").value = 0;
                        return false;      
                       }  
                    });        
              }

// event insertion in callender Api 
  // Make sure the client is loaded and sign-in is complete before calling this method.
  function eventStore() {
      var CLIENT_ID = '358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com';
      var API_KEY = 'AIzaSyCgn3AhryzKA4Vgp5M_ucyZi6lzuaEPSho';
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
      var SCOPES = "https://www.googleapis.com/auth/calendar.readonly";
      gapi.load('client:auth2', initClient);
       let date_range    = $('#daterange').val();
       var res = date_range.split(" - ");
       var start_date=res[0];
       var end_date=res[1];
       var start_date = new Date(start_date); 
       var end_date = new Date(end_date); 
  }
      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
   function initClient() {
    let date_range    = $('#daterange').val();
    var res = date_range.split(" - ");
    var start_date=res[0];
    var end_date=res[1];
    var start_date = new Date(start_date); 
    alert(start_date);
    var end_date = new Date(end_date); 
    end_date.setDate(end_date.getDate() + 1);
    // alert(start_date);
    // alert(end_date);
        gapi.client.init({
          apiKey: 'AIzaSyCgn3AhryzKA4Vgp5M_ucyZi6lzuaEPSho',
          clientId: '358393949584-7f9h84lp6blm6rf4bvp8glgee970ioee.apps.googleusercontent.com',
          discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
          scope: "https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events"
        }).then(function () {
          // Listen for sign-in state changes
          gapi.auth2.getAuthInstance().signIn()
          .then(function(responce) {
            gapi.client.calendar.events.insert({
              "calendarId": "cink7q1aipvdf88789vq9lb3co@group.calendar.google.com",
              "resource": {
              
              "end"         : {
                            'dateTime': end_date,
                            'timeZone': 'Asia/Kolkata'
                              },
              "start"       : {
                            'dateTime': start_date,
                            'timeZone': 'Asia/Kolkata'  
                              },
	     		   "summary" :"booking done",
               }
              }).then(function(responce){
                // console.log(JSON.stringify(responce));
               if(responce.result.id != undefined)
               {
                document.getElementById('tmp_event_id').value=responce.result.id;         
                 alert('events properly stored');       
               }
               else 
               {
                 alert('events not stored properly');
                 return false;
               } 
                
              } );

          });
        }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
        });
      }

