/*
Fonto.pl .... v0.1.6
copyright by Piotr Kubiak

For more information, visit:
http://fonto.pl/fontogallery.html


*/



// called when the language-code select box changes
function reloadAfterLangChange() {
  var newlanguage = $("#whichlang").val();
  //alert("newlang update: " + newlanguage);
  $.linguaLoadAutoUpdate(newlanguage); // this will also update controls by ID
  updateLangInPage();
}

// set translations
function updateLangInPage() {
  //alert("new translation: " + $.lingua("mytext1"));
  $("#lang").html($("#whichlang").val());

  $("#shorthelp").html($.lingua("shorthelp"));

  $("#lang_photographed").html($.lingua("lang_photographed"));
  $("#lang_created").html($.lingua("lang_created"));
  $("#lang_youhavesel").html($.lingua("lang_youhavesel"));
  $("#lang_photos").html($.lingua("lang_photos"));
  $("#lang_image").html($.lingua("lang_image"));
  $("#lang_of").html($.lingua("lang_of"));
  $("#lang_filename").html($.lingua("lang_filename"));

  $("#showshow").html($.lingua("showshow"));
  $("#showselected").html($.lingua("showselected"));
  $("#showall").html($.lingua("showall"));

  $("#form_name").val($.lingua("form_name"));
  $("#form_email").val($.lingua("form_email"));
  $("#form_phone").val($.lingua("form_phone"));
  $("#form_comments").val($.lingua("form_comments"));
  $("#form_submit").val($.lingua("form_submit"));

}


$(document).ready(function() {








  // lingua setup
  $.linguaInit('./resources/lang/', 'fonto');

  // try to change our drop-down to the browser language
  $("#whichlang").val($.linguaGetLanguage());

  // display current lang
  $("#lang").text($.linguaGetLanguage());

  // try loading the default browser language
  $.linguaLoad($.linguaGetLanguage());
  updateLangInPage();
  $.linguaUpdateElements(); // manual updating of controls by ID



  // get CSS conf
  $.ajax({
    type: 'POST',
    url: './resources/ajax_get_cssconf.php',
    data: {},
    success: function(response){
      if (response.status == 'ok') {
        // reload stylesheed
        $('head').append('<link rel="stylesheet" type="text/css" href="./resources/custom.css?'+ (new Date).getTime() +'" />');
      } else {
        alert("problem importing css conf: " + response.text);
        //console.log("data saving error: " + response.text);
      }
    },
    dataType: "json"
  });
  
  // get SMTP conf
  $.ajax({
    type: 'POST',
    url: './resources/ajax_get_smtpconf.php',
    data: {},
    success: function(response){
      if (response.status == 'ok') {
        $("#form_galleryhash").val(response.galleryhash);
        ;
      } else {
        alert("error smtp conf: " + response.text);
        //console.log("data saving error: " + response.text);
      }
    },
    dataType: "json"
  });

  //alert(hex_md5("pkubiak@gmail.com"));




























































  // right click on photo
  $(".photo_thumb_select").mousedown(function(e){
    $(".photo_thumb_select").bind("contextmenu", function(e) {
      e.preventDefault();
    });

    if (e.button == 2) {
      var clickedPhotoId = $(this).attr("id").substring(2);
      var now = new Date();

      // for faster response check ahead and then rollback in case of an error
      var checkid = ".options #s_" + clickedPhotoId + " img";
      var photoid = "#p_" + clickedPhotoId + " img";
      $(checkid).toggleClass('selected');
      $(photoid).toggleClass('selected');



























      if ($(photoid).hasClass('selected') == true) {
        $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())+1);
      } else {
        $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())-1);
      }



    }
  });

  $(".photo_zoomed_select").on("click", function() {
    var clickedPhotoId = $(this).attr("id").substring(2);
    var now = new Date();

    // for faster response check ahead and then rollback in case of an error
    var checkid = ".options #s_" + clickedPhotoId + " img";
    var photoid = "#p_" + clickedPhotoId + " img";
    var lboxchkid = "#z_" + clickedPhotoId + " img";
    $(checkid).toggleClass('selected');
    $(photoid).toggleClass('selected');
    $(lboxchkid).toggleClass('selected');





























    if ($(photoid).hasClass('selected') == true) {
      $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())+1);
    } else {
      $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())-1);
    }




  });


  // left click on check icon
  $(".photo_select").on("click", function() {
    var clickedPhotoId = $(this).attr("id").substring(2);
    var now = new Date();

    // for faster response check ahead and then rollback in case of an error
    var checkid = ".options #s_" + clickedPhotoId + " img";
    var photoid = "#p_" + clickedPhotoId + " img";
    $(checkid).toggleClass('selected');
    $(photoid).toggleClass('selected');


























    if ($(photoid).hasClass('selected') == true) {
      $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())+1);
    } else {
      $("#infobox #infoselcount").text(parseInt($("#infobox #infoselcount").text())-1);


    }

  });

  $('#form_comments').click(function() {
    $('#form_comments').val('');
    $('#form_comments').unbind('click');
  });

  $('#form_name').click(function() {
    $('#form_name').val('');
    $('#form_name').unbind('click');
  });

  $('#form_email').click(function() {
    $('#form_email').val('');
    $('#form_email').unbind('click');
  });

  $('#form_phone').click(function() {
    $('#form_phone').val('');
    $('#form_phone').unbind('click');
  });

  $('#showselected').click(function() {
    var items = document.getElementsByClassName("photo");
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      if (item.children[0].children[0].children[0].className != 'photo_thumb selected') {
        item.style.display = 'none';
      }
    }
    $('#showselected').addClass('linkSelected');
    $('#showall').removeClass('linkSelected');
    /*$(".photo").each(function() {
      if (!$(this).find('.photo_select img').hasClass('selected')) {
        $(this).hide(200, "linear", function() {
          // Animation complete.
          $('#showselected').addClass('linkSelected');
          $('#showall').removeClass('linkSelected');
        });
      }
    });*/
  });

  $('#showall').click(function() {
    var items = document.getElementsByClassName("photo");
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      item.style.display = 'block';
    }
    $('#showselected').removeClass('linkSelected');
    $('#showall').addClass('linkSelected');
    /*$(".photo").each(function() {
      if (!$(this).find('.photo_select img').hasClass('selected')) {
        $(this).show(200, "linear", function() {
          // Animation complete.
          $('#showselected').removeClass('linkSelected');
          $('#showall').addClass('linkSelected');
        });
      }
    });*/
  });

  $('#form_submit').click(function() {
    /*if ($('#form_step').val() == 1) {
      $('#shorthelp').html('To jest <b>krok drugi</b> selekcji.<br />Przejrzyj wybrane plik, podejmij ostateczną decyzję i prześlij zamówienie.<br />Po przesłąniu zamówienia dokonany wybór zostanie zablokowany.');
      $('#form_submit').val('Zamów wybrane zdjęcia');
      $('#form_step').val('2');
      $('#form_comments').css('display', 'inline');

      $(".photo").each(function() {
        if (!$(this).find('.photo_select img').hasClass('selected')) {
          $(this).hide();
        }
        //if ($(this).addClass( "foo" );
      });
    } else if ($('#form_step').val() == 2) {*/
      $('#form_submit').val('please wait...');
      $('#loader').show(); 
      var photos_selected = '';











































      $("#content .photo_thumb_select").each(function(index, element) {
        //console.log(index + ": " + $(this).attr(id));
        var wrapperId = "#" + $(element).attr("id");
        //console.log(index + ": " + $(element).attr("id") + " / " + $(wrapperId + " img").hasClass("selected"));//$(element + " img").hasClass("selected"));
        if ($(wrapperId + " img").hasClass("selected")) {
          photos_selected += $(element).attr("id").substring(2) + ",";
        }
      });
      //console.log(photos_selected);
      //return;

      $.ajax({
        type: 'POST',
        url: './resources/ajax_submit_form.php',
        data: { form_comments: $('#form_comments').val(),
                form_name: $('#form_name').val(),
                form_email: $('#form_email').val(),
                form_from_email: $('#form_from_email').val(),
                form_from_name: $('#form_from_name').val(),
                form_phone: $('#form_phone').val(),
                form_gallerytitle: $('#form_gallerytitle').val(),
                form_app_mode: 2,
                form_photos_selected: photos_selected
              },
        success: function(response){
          if (response.status == 'ok') {
            //$("#infobox #infoselcount").text(response.count);
            //alert("ok: " + response.text);

            //alert(response.text + " / " + response.lang + " / " + response.id);
            if (response.lang == 'lang_sendsuccess') { response.text = $.lingua("lang_sendsuccess") + ": " + response.id + "."; }
            $('#form').html(response.text);

            $('#form_submit').val($.lingua("form_submit"));
            $('#loader').hide(); 
          } else {
            // rollback
            //$(checkid).toggleClass('selected');
            //$(photoid).toggleClass('selected');

            //
            if (response.lang == 'lang_nothingsel') { response.text = $.lingua("lang_nothingsel"); }
            if (response.lang == 'lang_errmailto') { response.text = $.lingua("lang_errmailto"); }

            alert($.lingua("lang_errorsending") + ".\n\n" + response.text);
            $('#form_submit').val($.lingua("form_submit"));
            //console.log("data saving error: " + response.text);
            $('#loader').hide(); 
          }
        },
        dataType: "json"
      });
  });

  function getPromptEmail() {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    var user_email = prompt("Enter your email as a login.\n\nThis way your selections can will be saved & loaded so you could take a break anytime and return to the process whenever you want, event on a different device: ", "your email here");

    if (!pattern.test(user_email)) {
      alert("Please enter a valid email address.");
      user_email = getPromptEmail();
    }

    return user_email;
  }

  $(window).load(function() {
    //console.log("document loaded");
    $('#loader').hide();

    /*
      multiuser login & save

      if (!$('#form_logged_mail').val().length) {
      var user_email = '';
      var user_pass = '';

      user_email = getPromptEmail();
      $('#form_logged_mail').val(user_email);

      user_pass = prompt("Now enter you pass and we're done: ", "your password here")
      $('#form_logged_pass').val(hex_md5(user_pass));

      alert(loginOrSave);

      alert(user_email + " / " + user_pass);
      //var user_passwd = prompt("Enter Password : ", "your password here");
    }


    */
  });


  /*$(window).bind("beforeunload", function() {
    return confirm("Do you really want to close?") ;
  });*/

});